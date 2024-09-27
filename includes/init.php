<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$basedir="http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

function checkUserAccess()
{
    session_start();
    if(!isset($_SESSION['usuario']))
    {        
        header('Location: login.php');
        exit;
    }
}
?>
