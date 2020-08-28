<?php

require $_SERVER['DOCUMENT_ROOT']."/db/db.php";
$settings = array(
    'normalDistance' => 1200, 'niceDistance' => 2000,
    'pulsePeaceMin' => 60, 'pulsePeaceMax' => 90,
    'pulseWarmupMin' => 90, 'pulseWarmupMax' => 120,
    'pulseTrainingMin' => 120, 'pulseTrainingMax' => 160
);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/path/semantic.min.css"/>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>
    <script src="/path/jquery.min.js"></script>
    <script src="/path/semantic.min.js"></script>
    <script src="/path/tablesort.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="shortcut icon" href="/logo.png" type="image/png">
    <title>Личный кабинет</title>
</head>
<body style="background-image: url('/background.png');">
<br>
<? if(isset($_COOKIE['userData']) && $_COOKIE['userRole'] == 'student') {

    $dataUser = json_decode($_COOKIE['userData'], true);

    $resultsDataStudent = R::find('students', 'user_name = :userLogin ORDER BY date_training DESC', array(
        ':userLogin' => $dataUser['user_login'],
    ));


    $countResultsData = count($resultsDataStudent);

    if($countResultsData != 0)
    {
        $headerHorizonDistance = array('Дата'); $hearerVerticalDistance = array('Метры');
        $headerHorizonPulse = array('Дата'); $hearerVerticalOnePulse = array('До разминки'); $hearerVerticalTwoPulse = array('После разминки'); $hearerVerticalThreePulse = array('После бега');

        $sumDistance = 0;
        $sumPulseFirst = 0;
        $sumPulseSecond = 0;
        $sumPulseThird = 0;

        foreach ( $resultsDataStudent AS $result ) {
            $headerHorizonDistance[] = date("d.m.Y", strtotime($result['date_training']));
            $hearerVerticalDistance[] = (Integer)($result['distance']);
            $sumDistance += (Integer)($result['distance']);
        }

        $averageDistance = $sumDistance / $countResultsData;

        $dataDistanceForGraphic = array_map(
            function ($headerHorizonDistance, $hearerVerticalDistance) {
                return [$headerHorizonDistance,$hearerVerticalDistance];
            }, $headerHorizonDistance, $hearerVerticalDistance
        );
        unset($headerHorizonDistance); unset($hearerVerticalDistance);



        foreach ( $resultsDataStudent AS $result ) {
            $headerHorizonPulse[] = date("d.m.Y", strtotime($result['date_training']));
            $hearerVerticalOnePulse[] = (Integer)($result['pulse_first']);
            $hearerVerticalTwoPulse[] = (Integer)($result['pulse_second']);
            $hearerVerticalThreePulse[] = (Integer)($result['pulse_third']);
            $sumPulseFirst += (Integer)($result['pulse_first']);
            $sumPulseSecond += (Integer)($result['pulse_second']);
            $sumPulseThird += (Integer)($result['pulse_third']);
        }

        $averagePulseFirst = $sumPulseFirst / $countResultsData;
        $averagePulseSecond = $sumPulseSecond/ $countResultsData;
        $averagePulseThird = $sumPulseThird/ $countResultsData;

        $dataPulseForGraphic = array_map(
            function ($headerHorizonPulse , $hearerVerticalOnePulse, $hearerVerticalTwoPulse, $hearerVerticalThreePulse) {
                return [$headerHorizonPulse ,$hearerVerticalOnePulse, $hearerVerticalTwoPulse, $hearerVerticalThreePulse];
            }, $headerHorizonPulse , $hearerVerticalOnePulse, $hearerVerticalTwoPulse, $hearerVerticalThreePulse
        );
    }



    ?>
    <div class="ui container">
        <div class="field">
            <button class="ui floated small red labeled icon button" onclick="callDeleteCookies()">
                <i class="power off icon"></i>
                Выйти
            </button>
            <a href="/" class="ui floated small green labeled icon button">
                <i class="home icon"></i>
                На главную
            </a>
        </div>
        <div class="ui header center aligned">
            <div class="ui big label blue"><? echo $dataUser['user_name'].' '.$dataUser['user_surname'].' ('.$dataUser['user_group'].')'; ?> </div>
        </div>
        <? if ($countResultsData == 0) {?>
            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">Данные отсутствуют</div>
                <ul>
                    <li><p>Проверьте корректность авторизации</p></li>
                    <li><p>Заполните данные</p></li>
                </ul>
            </div>
        <? } ?>
        <h3 class="ui top attached header center aligned orange inverted">
            Таблица данных
        </h3>
        <table class="ui sortable celled table attached">
            <thead class="center aligned">
            <tr>
                <th rowspan="2" class="sorted descending">Дата</th>
                <th rowspan="2" >Километраж (м.)</th>
                <th colspan="3">Пульс (уд. в мин.)</th>
                <th rowspan="2">Действие</th>
            </tr>
            <tr>
                <th>До разминки</th>
                <th>После разминки</th>
                <th>После бега</th>
            </tr>
            </thead>
            <tbody class="center aligned">
            <? foreach ( $resultsDataStudent AS $result ) {
                echo "<tr>";
                //Дата
                echo "<td>" . date("d.m.Y", strtotime($result['date_training'])) . "</td>";

                //Дистанция
                if ($result['distance'] >= $settings['normalDistance'] && $result['distance'] < $settings['niceDistance']) {
                    echo "<td class='warning'>" . $result['distance'] . "</td>";
                } elseif ($result['distance'] < $settings['normalDistance']) {
                    echo "<td class='negative'>" . $result['distance'] . "</td>";
                } elseif ($result['distance'] >= $settings['niceDistance']) {
                    echo "<td class='positive'>" . $result['distance'] . "</td>";
                }

                //Пульс построение
                if ($result['pulse_first'] >= $settings['pulsePeaceMin'] && $result['pulse_first'] <= $settings['pulsePeaceMax']) {
                    echo "<td class='positive'>" . $result['pulse_first'] . "</td>";
                } else {
                    echo "<td class='negative'>" . $result['pulse_first'] . "</td>";
                }

                if ($result['pulse_second'] >= $settings['pulseWarmupMin'] && $result['pulse_second'] <= $settings['pulseWarmupMax']) {
                    echo "<td class='positive'>" . $result['pulse_second'] . "</td>";
                } else {
                    echo "<td class='negative'>" . $result['pulse_second'] . "</td>";
                }

                if ($result['pulse_third'] >= $settings['pulseTrainingMin'] && $result['pulse_third'] <= $settings['pulseTrainingMax']) {
                    echo "<td class='positive'>" . $result['pulse_third'] . "</td>";
                } else {
                    echo "<td class='negative'>" . $result['pulse_third'] . "</td>";
                }
                //echo "</tr>";
                if ($result['checked']) {
                    echo "<td><i class='large check circle green icon'></i></td>";
                } else { ?>
                    <td>
                        <form class="resultDeleteData">
                            <input type="hidden" name="buttonID"  value="<? echo $result['id']; ?>">
                            <button type="submit" class="ui icon button blue">
                                <i class="trash icon"></i>
                            </button>
                        </form>
                    </td>
                <? }
                echo "</tr>";
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="7">
                    <button class="ui right floated small orange labeled icon button" onclick="openModalAddData()">
                        <i class="plus circle icon"></i>
                        Добавить
                    </button>
                    <div class="ui orange label">
                        <i class="edit icon"></i>
                        <? echo $countResultsData; ?>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="6">
                    <div class="ui info message mini">
                        <div class="header"> <h3>Нормативы:</h3></div>
                        <ul>
                            <li><h4 style="font-weight:normal">Пульс в состоянии покоя (уд. в мин.): <? echo $settings['pulsePeaceMin']." - ". $settings['pulsePeaceMax']; ?></h4></li>
                            <li><h4 style="font-weight:normal">Пульс после разминки (уд. в мин.): <? echo $settings['pulseWarmupMin']." - ". $settings['pulseWarmupMax']; ?></h4></li>
                            <li><h4 style="font-weight:normal">Пульс после пробежки (уд. в мин.): <? echo $settings['pulseTrainingMin']." - ". $settings['pulseTrainingMax']; ?></h4></li>
                            <li><h4 style="font-weight:normal">Дистанция (м): <? echo (($settings['normalDistance'] + $settings['niceDistance'])/2)." < "; ?></h4></li>
                        </ul>
                    </div>
                </th>
            </tr>
            </tfoot>
        </table>

        <h3 class="ui top attached header center aligned orange inverted">
            Графики
        </h3>
        <div class="ui segment attached">
            <? if ($countResultsData != 0) {?>
                <div id="chart_distance"></div>
                <div id="chart_pulseFirst"></div>
            <? } ?>
        </div>

    </div>


<? } else { ?>

    <div class="ui container">
        <div class="field">
            <a href="/" class="ui floated small green labeled icon button">
                <i class="home icon"></i>
                На главную
            </a>
        </div>
        <h3 class="ui top attached header center aligned red inverted">Необходимо авторизоваться!</h3>
        <form class="ui attached active tab segment form" id="loginUser">
            <h4 class="ui dividing header">Личные данные</h4>
            <div class=" fields">
                <div class="eight wide field">
                    <label>Логин</label>
                    <input type="text" placeholder="Ваше имя" name="UserLogin" required>
                </div>
                <div class="eight wide field">
                    <label>Пароль</label>
                    <input type="password" placeholder="Ваша фамилия" name="UserPassword" required>
                </div>
            </div>
            <input type="hidden" name="whoAuthorization" value="0">
            <? if(isset($_COOKIE['errorAuth'])) {?>
                <div class="ui negative message">
                    <i class="close icon"></i>
                    <div class="header">Ошибка авторизации</div>
                    <ul>
                        <li><p>Логин или пароль неверны</p></li>
                        <li><p>Повторите авторизацию</p></li>
                    </ul>
                </div>
            <? } ?>
            <button type="submit" class="fluid ui blue basic button">Войти</button>
        </form>
    </div>
<? } ?>

<div class="ui modal">
    <div class="header" style="color: #f2711c">Добавление данных</div>
    <div class="content">
        <form class="ui form" id="resultForm">
            <div class=" fields">
                <div class="four wide field">
                    <label>Дата занятия</label>
                    <input type="date" value="<?php echo date("Y-m-d");?>" name="DateTraining" required>
                </div>
                <div class="three wide field">
                    <label>Пульс до разминки</label>
                    <input type="number" name="PulseFirst" min="0" max="999" required>
                </div>
                <div class="three wide field">
                    <label>Пульс после разминки</label>
                    <input type="number" name="PulseSecond" min="0" max="999" required>
                </div>
                <div class="three wide field">
                    <label>Пульс после бега</label>
                    <input type="number" name="PulseThird" min="0" max="999" required>
                </div>
                <div class="three wide field">
                    <label>Дистанция</label>
                    <input type="number" placeholder="В метрах" min="0" max="9999" name="Distance" required>
                </div>
            </div>
            <br>
            <button type="submit" class="fluid ui blue basic button">Отправить данные</button>
        </form>
    </div>
</div>

</body>

<script>
    function callDeleteCookies() {
        delete_cookie("userData");
        delete_cookie("userRole");
        $(location).attr('href', '/');
    }

    function delete_cookie (cookie_name)
    {
        document.cookie = cookie_name += '=; Max-Age=0; path=/; domain=' + location.host;
    }
</script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawDistanceGraph);
    google.charts.setOnLoadCallback(drawPulseFirstGraph);


    var dataDistanceForGraphic = <?php echo json_encode($dataDistanceForGraphic) ?>;
    var dataPulseForGraphic = <?php echo json_encode($dataPulseForGraphic) ?>;
    function drawDistanceGraph() {
        var data = google.visualization.arrayToDataTable(dataDistanceForGraphic);

        let options = {
            hAxis: {
                titleTextStyle: {
                    bold: true,
                    fontSize : 14
                },
            },
            vAxis: {
                title: 'Метры',
                titleTextStyle: {
                    bold: true,
                    fontSize : 14
                },
            },
            title: "Пройденная дистанция",
            height: 400,
            legend: { position: 'none' }
        };

        var chart = new google.charts.Line(document.getElementById('chart_distance'));

        chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawPulseFirstGraph() {
        var data = google.visualization.arrayToDataTable(dataPulseForGraphic);

        let options = {
            hAxis: {
                titleTextStyle: {
                    bold: true,
                    fontSize : 14
                },
            },
            vAxis: {
                title: 'Уд. в сек.',
                titleTextStyle: {
                    bold: true,
                    fontSize : 14
                },
            },
            title: "Пульс",
            height: 400,
            legend: { position: 'none' }
        };

        var chart = new google.charts.Line(document.getElementById('chart_pulseFirst'));

        chart.draw(data, google.charts.Line.convertOptions(options));
    }
</script>


<script>
    $('.message .close')
        .on('click', function() {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        })
    ;

    $('table').tablesort();

    $('.ui.dropdown')
        .dropdown()
    ;

    function openModalAddData() {
        $('.ui.modal').modal('show');
    }


    $(document).ready(function () {
        $("#resultForm").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/sendData.php",
                data: $(this).serialize()
            }).done(function() {
                $(location).attr('href', '/data.php');
            });
            return false;
        });


        $("#loginUser").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/login.php",
                data: $(this).serialize()
            }).done(function() {
                location.reload();
            });
            return false;
        });

        $(".resultDeleteData").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/deleteData.php",
                data: $(this).serialize()
            }).done(function() {
                $(location).attr('href', '/data.php');
            });
            return false;
        });
    });
</script>
</html>