<?php
require $_SERVER['DOCUMENT_ROOT']."/db/db.php";

R::freeze(true);

for ($i = 1; $i <= $_POST['CountStudents']; $i++) {
    $table = R::dispense('visits');

    $studentID = "StudentID".$i;
    $table->student_id = $_POST[$studentID];
    $table->date_training = $_POST['DateTraining'];
    $table->checked = false;

    $visitID = "VisitID".$i;
    if($_POST[$visitID] == 0)
        $table->visited = false;
    elseif ($_POST[$visitID] == 1)
        $table->visited = true;

    R::store($table);
}


R::close();
?>
