<? if((isset($_COOKIE['userData']) && $_COOKIE['userRole'] == 'professor') == false) {
    header('Location: /professor/lk.php');
}
require $_SERVER['DOCUMENT_ROOT']."/db/db.php";

$studentsTable = R::findAll('students');
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
    <link rel="shortcut icon" href="/logo.png" type="image/png">
    <title>Посещения</title>
</head>
<body style="background-image: url('/background.png');">
<br>
<div style="margin-left: 10px; margin-right: 10px">
    <div class="field">
        <a href="lk.php" class="ui floated small green labeled icon button">
            <i class="user icon"></i>
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
                <th class="">10.10.2020</th>
                <th class="">11.10.2020</th>
                <th class="">11.10.2020</th>
                <th class="">11.10.2020</th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($studentsTable as $studentData) {
                echo "<tr>";
                echo "<td>". $studentData['user_surname']." ".mb_substr($studentData['user_name'],0,1,'UTF-8').". (".$studentData['user_group'].")</td>";
                echo "<td>". "+". "</td>";
                echo "<td>". "+". "</td>";
                echo "<td>". "+". "</td>";
                echo "<td>". "+". "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>

            <tfoot>
            <tr>
                <th colspan="5">3 человека</th>
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
                        <input type="hidden" name="visitedId<? echo $studentData['id']; ?>" required>
                        <i class="dropdown icon"></i>
                        <div class="default text">Не выбрано</div>
                        <div class="menu">
                            <div class="active item" data-value="0"><i class="minus circle red icon"></i> </div>
                            <div class="item" data-value="1"><i class="plus circle green red icon"></i> </div>
                        </div>
                    </div>
                </td>


                <?
                echo "</tr>";
            }
            ?>
            </tbody>

        </table>

    </div>
    <div class="actions">
        <button class="ui red button" onclick="hideModal()">Отменить</button>
        <button type="submit" class="ui green button">Записать</button>
    </div>
</form>


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
                url: "/login.php",
                data: $(this).serialize()
            }).done(function () {
                location.reload();
            });
            return false;
        });
    });

</script>
</body>
</html>
