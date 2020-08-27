<?php
require $_SERVER['DOCUMENT_ROOT']."/db/db.php";
$data = R::load('students', $_POST['buttonID']);
R::trash($data);
R::close();
?>
