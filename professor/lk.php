<?php

require $_SERVER['DOCUMENT_ROOT']."/db/db.php";
require $_SERVER['DOCUMENT_ROOT']."/standards.php";
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
    <link rel="shortcut icon" href="logo.png" type="image/png">
    <title>Document</title>
</head>
<body style="background-image: url('/background.png');">
<br>
<? if(isset($_COOKIE['userData']) && $_COOKIE['userRole'] == 'professor') {
    $userData = json_decode($_COOKIE['userData'], true);

    print_r($userData);

    $resultsAllDataStudents = R::findAll('students');
    $countResultsAllDataStudents = count($resultsAllDataStudents);
    $countUncheckedDataStudents = R::count('students', 'checked = ?', [false]);
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
            <a href="/" class="ui floated small blue labeled icon button">
                <i class="edit icon"></i>
                Посещения
            </a>
        </div>
        <div class="ui header center aligned">
            <div class="ui big label blue"><? echo $userData['professor_name'].' '.$userData['professor_middlename']; ?> </div>
        </div>

        <h3 class="ui top attached header center aligned orange inverted">
            Таблица данных
        </h3>
        <div class="ui segment attached">
            <? if ($countUncheckedDataStudents > 0) { ?>
            <form class="resultCheckData">
                <input type="hidden" name="checkAllDataStudents">
                <button class="ui floated small olive labeled icon button">
                    <i class="check circle icon"></i>
                    Проверить все
                </button>
            </form>
            <? } ?>

        </div>
        <table class="ui sortable celled table attached">
            <thead class="center aligned">
            <tr>
                <th rowspan="2" class="sorted descending">Дата</th>
                <th rowspan="2">ФИО</th>
                <th rowspan="2">Километраж (м.)</th>
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
            <? foreach ( $resultsAllDataStudents AS $result ) {
                echo "<tr>";
                //Дата
                echo "<td>" . date("d.m.Y", strtotime($result['date_training'])) . "</td>";
                echo "<td>" . $result['user_surname']." ".mb_substr($result['user_name'],0,1,'UTF-8'). ".</td>";

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
                        <form class="resultCheckData">
                            <input type="hidden" name="buttonID"  value="<? echo $result['id']; ?>">
                            <button type="submit" class="ui icon button mini olive">
                                <i class="check circle icon"></i>
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

                    <div class="ui orange label">
                        <i class="edit icon"></i>
                        <? echo $countResultsAllDataStudents; ?>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="7">
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
    </div>

<? } else { ?>
    <div class="ui container">
        <div class="field">
            <a href="/" class="ui floated small green labeled icon button">
                <i class="home icon"></i>
                На главную
            </a>
        </div>
        <? if(isset($_COOKIE['errorAuth'])) {?>
            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">Ошибка авторизации</div>
                <ul>
                    <li><p>Логин или пароль неверны. Повторите авторизацию</p></li>
                    <li><p>Возможно, пользователь не зарегистрирован. Обратитесь к разработчику сайта</p></li>
                </ul>
            </div>
        <? } ?>
        <h3 class="ui top attached header center aligned red inverted">Необходимо авторизоваться!</h3>
        <form class="ui attached active tab segment form" id="resultCookie">
            <h4 class="ui dividing header">Личные данные</h4>
            <div class="fields">
                <div class="eight wide field">
                    <label>Логин</label>
                    <input type="text" placeholder="Ваш логин" name="professorLogin" required>
                </div>
                <div class="eight wide field">
                    <label>Пароль</label>
                    <input type="password" placeholder="Ваша пароль" name="professorPassword" required>
                </div>
            </div>
            <br>
            <button type="submit" class="fluid ui blue basic button">Войти</button>
        </form>
    </div>
<? } ?>
</body>

<script>
    $('.message .close')
        .on('click', function() {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        })
    ;

    function callDeleteCookies() {
        delete_cookie("userRole");
        delete_cookie("userData");
        $(location).attr('href', '/');
    }

    function delete_cookie (cookie_name)
    {
        var cookie_date = new Date();  // Текущая дата и время
        cookie_date.setTime (cookie_date.getTime() - 1);
        document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
    }

    $(document).ready(function () {

        $("#resultCookie").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/professor/login.php",
                data: $(this).serialize()
            }).done(function() {
                $(location).attr('href', '/professor/lk.php');
            });
            return false;
        });

        $(".resultCheckData").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/professor/checkData.php",
                data: $(this).serialize()
            }).done(function() {
                $(location).attr('href', '/professor/lk.php');
            });
            return false;
        });
    });
</script>
</html>