<?php
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

if(isset($_POST['checkAllDataStudents'])) {
    $dataUncheckedAll = R::find('visits', 'checked = :checked', array(
        ':checked' => false
    ));

    foreach ($dataUncheckedAll as $dataUnchecked) {
        $dataUnchecked['checked'] = true;
        R::store($dataUnchecked);
    }
} elseif (isset($_POST['buttonID'])) {
    $dataUnchecked = R::load('visits', $_POST['buttonID']);
    $dataUnchecked['checked'] = true;
    R::store($dataUnchecked);
}


R::close();


?>
