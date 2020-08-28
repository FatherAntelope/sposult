<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

$table = R::dispense('visits');

$table->student_id ='';
$table->date_training = $_POST['DateTraining'];
$table->date_filling = date("Y-m-d");
$table->pulse_first = $_POST['PulseFirst'];
$table->pulse_second = $_POST['PulseSecond'];
$table->pulse_third = $_POST['PulseThird'];
$table->distance = $_POST['Distance'];
$table->checked = false;
$table->visited = false;

R::store($table);

R::close();
?>