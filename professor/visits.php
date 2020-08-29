<? if((isset($_COOKIE['userData']) && $_COOKIE['userRole'] == 'professor') == false) {
    header('Location: /professor/lk.php');
}
require $_SERVER['DOCUMENT_ROOT']."/db/db.php";

$studentsTable = R::getAll("SELECT id, user_name, user_surname, user_group FROM students ORDER BY user_surname ASC");
$dates = R::getAll("SELECT DISTINCT date_training FROM visits ORDER BY date_training ASC");
$countStudents = count($studentsTable);
$countDates = count($dates);
$visitArray = array(array(), array());

function array_sum_rows($arr) {
    $out = array();
    for ($col = 0; $col < 5; $col++ )
    {
        $out[$col] = 0;

        for ($row = 0; $row < 4; $row++ )
        {
            $out[$col] += $arr[$row][$col];
        }
    }
    return $out;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/path/semantic.min.css"/>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css'>
    <script src="/path/jquery.min.js"></script>
    <script src="/path/semantic.min.js"></script>
    <script src="/path/tablesort.js"></script>
    <link rel="shortcut icon" href="/logo.png" type="image/png">
    <title>Посещения</title>
</head>
<body style="background-image: url('/background.png');">
<br>
<div style="margin-left: 10px; margin-right: 10px">
    <div class="field">
        <a href="lk.php" class="ui floated small green labeled icon button">
            <i class="address book icon"></i>
            В личный кабинет
        </a>
        <button class="ui floated small blue labeled icon button" onclick="showModal()" style="margin-top: 5px">
            <i class="edit icon"></i>
            Внести присутствующих
        </button>
    </div>
    <div style="overflow-x: scroll; margin-top: 15px">
        <table class="ui sortable  celled table scrolling" >
            <thead>
            <tr>
                <th class="sorted ascending">Студент</th>
                <?
                foreach ($dates as $date) {
                    echo "<th>". date("d.m.Y", strtotime($date['date_training'])) ."</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>

            <?
            $i = 0;
            foreach ($studentsTable as $studentData) {
                echo "<tr>";
                echo "<td>". $studentData['user_surname']." ".mb_substr($studentData['user_name'],0,1,'UTF-8').". (".$studentData['user_group'].")</td>";
                foreach ($dates as $date) {
                    $visit = R::findOne('visits', 'date_training = :date_training AND student_id = :student_id', array(
                        ':date_training' => $date['date_training'],
                        ':student_id' => $studentData['id']
                    ));



                    if($visit != null) {
                        if($visit['visited'] == 1) {
                            $visitArray[$i][] = 1;
                            echo "<td><i class=\"green plus circle icon\"></i></td>";
                        } else if ($visit['visited'] == 0) {
                            $visitArray[$i][] = 0;
                            echo "<td><i class=\"red minus circle icon\"></i></td>";
                        }

                    }
                    else {
                        $visitArray[$i][] = 0;
                        echo "<td><i class=\"brown question circle icon\"></i></td>";

                    }
                }
                $i++;
                echo "</tr>";
            }
            unset($i);


            $sumVisit = array_sum_rows($visitArray);



            ?>
            </tbody>

            <tfoot>
            <tr>
                <th>
                    <div class="ui orange label">
                        <i class="users icon"></i>
                        <? echo $countStudents; ?>
                    </div>
                </th>
                <?
                for($i = 0; $i < $countDates; $i++) { ?>
                    <th>
                        <div class="ui brown label">
                            <i class="calendar check icon"></i>
                            <? echo $sumVisit[$i]; ?>
                        </div>
                    </th>
                <? } ?>
            </tr>
            </tfoot>
        </table>
    </div>
</div>



<form class="ui long modal tiny" id="sendVisitedStudents">
    <div class="header" style="color: #f2711c">
        Внос присутствующих
    </div>
    <div class="content">

        <div class="ui form">
            <div class="field">
                <label>Дата занятия</label>
                <input type="date" name="DateTraining"value="<? echo date("Y-m-d"); ?>">
            </div>
        </div>

        <table class="ui sortable  celled table scrolling" >
            <thead>
            <tr>
                <th class="sorted ascending">Студент</th>
                <th class="">Присутствие</th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($studentsTable as $studentData) {
                echo "<tr>";
                echo "<td>". $studentData['user_surname']." ".mb_substr($studentData['user_name'],0,1,'UTF-8').". (".$studentData['user_group'].")</td>";
                ?>
                <td>
                    <div class="ui dropdown selection fluid">
                        <input type="hidden" name="VisitID<? echo $studentData['id']; ?>" required>
                        <i class="dropdown icon"></i>
                        <div class="default text">Не выбрано</div>
                        <div class="menu">
                            <div class="active item" data-value="0"><i class="minus circle red icon"></i> </div>
                            <div class="item" data-value="1"><i class="plus circle green red icon"></i> </div>
                        </div>
                    </div>
                </td>
                <input type="hidden" name="StudentID<? echo $studentData['id']; ?>" value="<? echo $studentData['id']; ?>">

                <?
                echo "</tr>";
            }
            ?>
            </tbody>

        </table>

    </div>
    <div class="actions">
        <a class="ui red button" onclick="hideModal()">Отменить</a>
        <input type="hidden" name="CountStudents" value="<? echo $countStudents; ?>">
        <button type="submit" class="ui green button">Записать</button>
    </div>
</form>



</body>

<script>
    $('table').tablesort();

    $('.ui.dropdown')
        .dropdown()
    ;

    function hideModal() {
        $('.long.modal').modal('hide');
    }
    function showModal() {
        $('.long.modal').modal('setting', 'closable', false).modal('show');
    }

    $(document).ready(function () {

        $("#sendVisitedStudents").submit(function () {
            $.ajax({
                type: 'POST',
                url: "/professor/sendVisitedUsers.php",
                data: $(this).serialize()
            }).done(function () {
                location.reload();
            });
            return false;
        });
    });

</script>
</html>
