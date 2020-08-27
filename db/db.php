<?php
require $_SERVER['DOCUMENT_ROOT']."/db/rb.php";
$username = 'root';
$password = '';
$dbname = 'sposult';
$hostname = 'localhost';
R::setup( "mysql:host={$hostname};dbname={$dbname}", $username, $password);
if ( !R::testConnection() )
{
    exit ('No connection to the DB');
}
?>
