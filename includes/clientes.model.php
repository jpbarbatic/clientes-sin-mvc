<?php
// Función para insertar un cliente
function agregarCliente($db, $nombre, $apellidos, $email, $telefono, $direccion, $notas) {
    require_once('db.php');
    $stmt = $db->prepare("INSERT INTO clientes (nombre, apellidos, email, telefono, direccion, notas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellidos, $email, $telefono, $direccion, $notas]);
}

// Función para obtener todos los clientes
function obtenerClientes() {
    require_once('db.php');
    return $db->query("SELECT * FROM clientes")->fetchAll();
}

// Función para obtener un cliente por su ID
function obtenerClientePorId($id) {
    require_once('db.php');
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Función para actualizar un cliente
function actualizarCliente($id, $nombre, $apellidos, $email, $telefono, $direccion, $notas) {
    require_once('db.php');
    $stmt = $db->prepare("UPDATE clientes SET nombre = ?, apellidos = ?, email = ?, telefono = ?, direccion = ?, notas = ? WHERE id = ?");
    $stmt->execute([$nombre, $apellidos, $email, $telefono, $direccion, $notas, $id]);
}

// Función para eliminar un cliente
function eliminarCliente($id) {
    require_once('db.php');
    $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
}
?>
