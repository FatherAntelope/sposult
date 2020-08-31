<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

$table = R::findOne('students', 'id = :id', array(
    ':id' => $_POST['UserID']
));


$table->user_height = $_POST['UserHeight'];
$table->user_weight = $_POST['UserWeight'];
unset($_COOKIE['userData']);
setcookie("userData", $table, time()+(60*60*24*30));
R::store($table);

R::close();
?>
