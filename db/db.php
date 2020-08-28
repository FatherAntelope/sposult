<?php
require $_SERVER['DOCUMENT_ROOT']."/db/rb.php";
$username = 'root';
$password = 'admin';
$dbname = 'sposult';
$hostname = '192.168.0.103';
R::setup( "mysql:host={$hostname};dbname={$dbname}", $username, $password);
if ( !R::testConnection() )
{
    exit ('No connection to the DB');
}
?>
