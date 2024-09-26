<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$basedir="http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

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
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css"/>
    <style>
        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        }

        @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
        }

        .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
        }

        .bi {
        vertical-align: -.125em;
        fill: currentColor;
        }

        .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
        }

        .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
        z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
        }
        .container{
            padding-top: 60px;
        }
    </style>
</head>
<body>
   
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestión2000</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $basedir ?>">Clientes</a>
        </li>
    </div>
  </div>
</nav>
<main class="container">
    <h2>Gestión de Clientes</h2>
    <!-- Formulario para añadir o editar clientes -->
    <h4><?= $clienteAEditar ? 'Editar Cliente' : 'Añadir Cliente' ?></h4>

    <form method="POST" action="index.php">
        <?php if ($clienteAEditar): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($clienteAEditar['id']) ?>">
        <?php endif; ?>
            <div class="">
            <div class="row">
                <div class="form-group col-md-2">
                    <label>Nombre: </label>
                    <input class="form-control" type="text" name="nombre" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['nombre']) : '' ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Apellidos: </label>
                    <input class="form-control" type="text" name="apellidos" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['apellidos']) : '' ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Email: </label>
                    <input class="form-control" type="email" name="email" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['email']) : '' ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Teléfono: </label>
                    <input class="form-control" type="text" name="telefono" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['telefono']) : '' ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">                
                    <label>Dirección: </label>
                    <input class="form-control" type="text" name="direccion" value="<?= $clienteAEditar ? htmlspecialchars($clienteAEditar['direccion']) : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Notas: </label>
                    <textarea class="form-control" name="notas"><?= $clienteAEditar ? htmlspecialchars($clienteAEditar['notas']) : '' ?></textarea>
                </div>
            </div>
            </div>

            <button class="btn btn-success" type="submit"><?= $clienteAEditar ? 'Actualizar' : 'Guardar' ?></button>
    </form>
<hr class="my-4">
    <!-- Listado de clientes -->
    <h4>Listado de Clientes</h4>
    <table class="table">
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
</main>

</body>
</html>

