<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

$table = R::findOne('visits', 'student_id = :student_id AND date_training = :date_training', array(
    ':student_id' => $_POST['UserID'],
    ':date_training' => $_POST['DateTraining']
));


$table->date_filling = date("Y-m-d");
$table->pulse_first = $_POST['PulseFirst'];
$table->pulse_second = $_POST['PulseSecond'];
$table->pulse_third = $_POST['PulseThird'];
$table->distance = $_POST['Distance'];

R::store($table);

R::close();
?>