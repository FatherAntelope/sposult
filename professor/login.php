<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";



/**Регистрация преподавателя**/
if(false) {
    $table = R::dispense('professors');
    $table->professorName = "Имя";
    $table->professorMiddlename = "Отчество";
    $table->professorLogin = "login";
    $password = password_hash("password", PASSWORD_DEFAULT);
    $table->professorPassword = $password;
    R::store($table);
} else {
    $user = R::findOne('professors', 'professor_login = ?', array($_POST['professorLogin']));
    if($user)
    {
        if(password_verify($_POST['professorPassword'], $user->professorPassword))
        {
            setcookie("professorLogin", true, time()+(60*60*24*30));
            setcookie("professorName", $user->professorName, time()+(60*60*24*30));
            setcookie("professorMiddlename", $user->professorMiddlename, time()+(60*60*24*30));
        }
        else
            setcookie("errorAuth", true, time()+6);
    }
    else
        setcookie("errorAuth", true, time()+6);
}


R::close();
?>
