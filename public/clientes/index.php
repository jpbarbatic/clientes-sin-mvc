<?php
define('_EXEC', 1);
include "../../includes/init.php";
require('model.php');

// Manejo del formulario para agregar o editar clientes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $notas = $_POST['notas'];
    
    // Si estamos editando (verificamos si hay un id en el formulario)
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        actualizarCliente($db, $id, $nombre, $apellidos, $email, $telefono, $direccion, $notas);
    } else {
        // Si estamos agregando un nuevo cliente
        agregarCliente($db, $nombre, $apellidos, $email, $telefono, $direccion, $notas);
    }

    header("Location: index.php");
    exit;
}

// Manejo de eliminación de cliente
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    eliminarCliente($db, $id);

    header("Location: index.php");
    exit;
}

// Manejo de edición de cliente (cuando se carga el formulario de edición)
$clienteAEditar = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $clienteAEditar = obtenerClientePorId($db, $id);
}

// Obtener todos los clientes para mostrarlos en la tabla
$clientes = obtenerClientes($db);

require("main.php");
?>


