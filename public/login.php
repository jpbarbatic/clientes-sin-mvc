<?php
require("../includes/init.php");
require('../includes/login.model.php');

session_start();

if(isset($_SESSION['usuario']))
{        
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario=$_POST['usuario'];
    $password=$_POST['password'];
    if(checkUserPassword($usuario, $password)){
        $_SESSION['usuario']=$usuario;
        header('Location: dashboard.php');
        exit;
    }else{

    }
}
else{
    require("../includes/login.html.php");
}
?>


