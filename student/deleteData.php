<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";
$data = R::load('visits', $_POST['buttonID']);
R::trash($data);
R::close();
?>
