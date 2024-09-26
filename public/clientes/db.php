<?php
// Conectar a la base de datos SQLite
try {
    $db = new PDO('sqlite:' .realpath( __DIR__ . '/../../data/clientes.sqlite'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Función para insertar un cliente
function agregarCliente($db, $nombre, $apellidos, $email, $telefono, $direccion, $notas) {
    $stmt = $db->prepare("INSERT INTO clientes (nombre, apellidos, email, telefono, direccion, notas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellidos, $email, $telefono, $direccion, $notas]);
}

// Función para obtener todos los clientes
function obtenerClientes($db) {
    return $db->query("SELECT * FROM clientes")->fetchAll();
}

// Función para obtener un cliente por su ID
function obtenerClientePorId($db, $id) {
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Función para actualizar un cliente
function actualizarCliente($db, $id, $nombre, $apellidos, $email, $telefono, $direccion, $notas) {
    $stmt = $db->prepare("UPDATE clientes SET nombre = ?, apellidos = ?, email = ?, telefono = ?, direccion = ?, notas = ? WHERE id = ?");
    $stmt->execute([$nombre, $apellidos, $email, $telefono, $direccion, $notas, $id]);
}

// Función para eliminar un cliente
function eliminarCliente($db, $id) {
    $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
}
?>
