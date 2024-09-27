<?php 
session_start();
// Borramos todas las variables
session_unset();
// Borramos la sesión
session_destroy(); 

// Nos vamos a la pantalla de login;
header('Location: login.php');
exit;
?>
