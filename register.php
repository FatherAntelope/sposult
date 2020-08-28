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
        $table->user_login = $_POST['UserLogin'];
        $table->user_password = password_hash($_POST['UserPassword'], PASSWORD_DEFAULT);
        $table->user_name = $_POST['UserName'];
        $table->user_surname = $_POST['UserSurname'];
        $table->user_group = $_POST['UserGroup'];
        $table->user_height = $_POST['UserHeight'];
        $table->user_weight = $_POST['UserWeight'];
        R::store($table);

    } else {
        setcookie("errorRegistration", true, time() + 6);
        die(header("HTTP/1.0 400 Bad Request"));
    }
}




?>