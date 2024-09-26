<?php
// Conectar a la base de datos SQLite
try {
    $db = new PDO('sqlite:' .realpath( __DIR__ . '/data/db.sqlite'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

?>
