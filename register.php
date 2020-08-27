<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

if(false) {
    $table = R::dispense('professors');
    $table->professorName = "Имя";
    $table->professorMiddlename = "Отчество";
    $table->professorLogin = "login";
    $password = password_hash("password", PASSWORD_DEFAULT);
    $table->professorPassword = $password;
    R::store($table);
} else {
    if (R::count('students', "user_login = ?", array($_POST['UserLogin'])) == 0) {
        $table = R::dispense('students');

        $table->userLogin = $_POST['UserLogin'];
        $table->userPassword = password_hash($_POST['UserPassword'], PASSWORD_DEFAULT);
        $table->userName = $_POST['UserName'];
        $table->userSurname = $_POST['UserSurname'];
        $table->userGroup = $_POST['UserGroup'];
        $table->userHeight = $_POST['UserHeight'];
        $table->userWeight = $_POST['UserWeight'];
        R::store($table);

    } else {
        setcookie("errorRegistration", true, time() + 6);
    }
}




?>