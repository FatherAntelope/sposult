<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

$table = R::findOne('visits', 'student_id = :student_id AND date_training = :date_training', array(
    ':student_id' => $_POST['UserID'],
    ':date_training' => $_POST['DateTraining']
));
if($table != null) {
    $table->checked = false;
    $table->visited = true;
} else {
    R::freeze(true);
    $table = R::dispense('visits');
    $table->student_id = $_POST['UserID'];
    $table->date_training = $_POST['DateTraining'];
    $table->checked = false;
    $table->visited = true;
}



R::store($table);

R::close();
?>
