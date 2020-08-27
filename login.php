<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";


/**Регистрация преподавателя**/

    if($_POST['whoAuthorization'] == 0) {
        $user = R::findOne('students', 'user_login = ?', array($_POST['UserLogin']));
        if($user)
        {
            if(password_verify($_POST['UserPassword'], $user->userPassword)) {
                setcookie("userLogin", $_POST['UserLogin'], time()+(60*60*24*30));
                setcookie("userRole", 'student', time()+(60*60*24*30));
            }
            else
                setcookie("errorAuth", true, time()+6);
        }
        else
            setcookie("errorAuth", true, time()+6);
    }
    elseif ($_POST['whoAuthorization'] == 1) {
        $user = R::findOne('professors', 'professor_login = ?', array($_POST['UserLogin']));
        if($user) {
            if(password_verify($_POST['UserPassword'], $user->professorPassword)) {
                setcookie("userLogin", $_POST['UserLogin'], time()+(60*60*24*30));
                setcookie("userRole", 'professor', time()+(60*60*24*30));
            }
            else
                setcookie("errorAuth", true, time()+6);
        }
        else
            setcookie("errorAuth", true, time()+6);
    }

R::close();
?>
