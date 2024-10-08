<?php
require("../includes/init.php");
require('../includes/clientes.model.php');
$page="clientes";
checkUserAccess();

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
        actualizarCliente($id, $nombre, $apellidos, $email, $telefono, $direccion, $notas);
    } else {
        // Si estamos agregando un nuevo cliente
        agregarCliente($db, $nombre, $apellidos, $email, $telefono, $direccion, $notas);
    }

    header("Location: clientes.php");
    exit;
}

if (isset($_GET['qr'])) {
    require('../libs/phpqrcode/qrlib.php');
    $id = $_GET['qr'];
    $cliente = obtenerClientePorId($id);

    // we building raw data
    $codeContents  = 'BEGIN:VCARD'."\n";
    $codeContents .= 'VERSION:3.0'."\n";
    $codeContents .= 'FN:'.$cliente['nombre'].' '.($cliente['apellidos'])."\n";
    $codeContents .= 'TEL;WORK;VOICE:'.$cliente['telefono']."\n";
    $codeContents .= 'END:VCARD';
    
    // generating

    QRcode::png($codeContents); 

    exit;
}


// Manejo de eliminación de cliente
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    eliminarCliente($id);

    header("Location: clientes.php");
    exit;
}

// Manejo de edición de cliente (cuando se carga el formulario de edición)
$clienteAEditar = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $clienteAEditar = obtenerClientePorId($id);
}

// Obtener todos los clientes para mostrarlos en la tabla
$clientes = obtenerClientes();

require("../includes/clientes.html.php");
?>


