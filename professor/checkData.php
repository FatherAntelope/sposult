<?php
echo "<pre>";
require $_SERVER['DOCUMENT_ROOT'] . "/db/db.php";

if(isset($_POST['checkAllDataStudents'])) {
    $dataUncheckedAll = R::find('students', 'checked = :checked', array(
        ':checked' => false
    ));
    //print_r($dataUnchecked);

    foreach ($dataUncheckedAll as $dataUnchecked) {
        $dataUnchecked['checked'] = true;
        R::store($dataUnchecked);
    }
} elseif (isset($_POST['buttonID'])) {
    $dataUnchecked = R::load('students', $_POST['buttonID']);
    $dataUnchecked['checked'] = true;
    R::store($dataUnchecked);
}


R::close();


?>
