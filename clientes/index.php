<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos SQLite
try {
    $db = new PDO('sqlite:' .realpath( __DIR__ . '/../data/clientes.sqlite'));
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <style>
        body{
            font-family: arial;
        }
        #listado, #listado td, #listado th {
            border: 1px solid black;
            border-collapse: collapse;
        }
        
        #listado td, #listado th {
            padding: 5px;
        }

        .centrado{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Gestión de Clientes</h1>

    <!-- Formulario para añadir o editar clientes -->
    <h2><?= $clienteAEditar ? 'Editar Cliente' : 'Añadir Cliente' ?></h2>

    <form method="POST" action="index.php">
        <?php if ($clienteAEditar): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($clienteAEditar['id']) ?>">
        <?php endif; ?>
        <table>
            <tr>
                <td><label>Nombre: </label></td>
                <td><input type="text" name="nombre" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['nombre']) : '' ?>" required></td>
            </tr>
            <tr>
                <td><label>Apellidos: </label></td>
                <td><input type="text" name="apellidos" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['apellidos']) : '' ?>" required></td>
            </tr>
            <tr>
                <td><label>Email: </label></td>
                <td><input type="email" name="email" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['email']) : '' ?>" required></td>
            </tr>
            <tr>
                <td><label>Teléfono: </label></td>
                <td><input type="text" name="telefono" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['telefono']) : '' ?>" required></td>
            </tr>
            <tr>
                <td><label>Dirección: </label></td>
                <td><input type="text" name="direccion" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['direccion']) : '' ?>"></td>
            </tr>
            <tr>
                <td><label>Notas: </label></td>
                <td><textarea name="notas" style="width: 400px; height: 150px;"><?= $clienteAEditar ? htmlspecialchars($clienteAEditar['notas']) : '' ?></textarea></td>
            </tr>
        </table>
        <button type="submit"><?= $clienteAEditar ? 'Actualizar Cliente' : 'Añadir Cliente' ?></button>
    </form>

    <!-- Listado de clientes -->
    <h2>Listado de Clientes</h2>
    <table id="listado">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td class="centrado"><?=htmlspecialchars($cliente['id']) ?></td>
                <td><?php echo isset($cliente['nombre']) ? htmlspecialchars($cliente['nombre']) : "" ?></td>
                <td><?php echo isset($cliente['apellidos']) ? htmlspecialchars($cliente['apellidos']) : "" ?></td>
                <td><?php echo isset($cliente['email']) ? htmlspecialchars($cliente['email']) : "" ?></td>
                <td><?php echo isset($cliente['telefono']) ? htmlspecialchars($cliente['telefono']) : "" ?></td>
                <td><?php echo isset($cliente['direccion']) ? htmlspecialchars($cliente['direccion']) : "" ?></td>
                <td>
                    <a href="index.php?edit=<?= $cliente['id'] ?>">Editar</a>  
                    <a href="index.php?delete=<?= $cliente['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

