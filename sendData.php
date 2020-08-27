<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

    $table = R::dispense('students');

    if(!isset($_POST['UserName'])) {
        $table->userName = $_COOKIE['userName'];
    } else {
        $table->userName = $_POST['UserName'];
    }

    if(!isset($_POST['UserSurname'])) {
        $table->userSurname = $_COOKIE['userSurname'];
    } else {
        $table->userSurname = $_POST['UserSurname'];
    }

    if(!isset($_POST['UserGroup'])) {
        $table->userGroup = $_COOKIE['userGroup'];
    } else {
        $table->userGroup = $_POST['UserGroup'];
    }

    $table->dateTraining = $_POST['DateTraining'];
    $table->dateFilling = date("Y-m-d");
    $table->pulseFirst = $_POST['PulseFirst'];
    $table->pulseSecond = $_POST['PulseSecond'];
    $table->pulseThird = $_POST['PulseThird'];
    $table->distance = $_POST['Distance'];
    $table->checked = false;

    R::store($table);

R::close();
?>