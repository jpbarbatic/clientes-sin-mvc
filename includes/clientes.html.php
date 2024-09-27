<?php include "pre.html.php";?>
    <h2>Gestión de Clientes</h2>
    <!-- Formulario para añadir o editar clientes -->
    <h4><?= $clienteAEditar ? 'Editar Cliente' : 'Añadir Cliente' ?></h4>

    <form method="POST" action="clientes.php">
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
                <?php if($clienteAEditar): ?>
                <div class="form-group col-md-4">
                    <label>QR: </label>                    
                    <img src="clientes.php?qr=<?php echo $clienteAEditar['id'] ?>">
                </div>
                <?php endif; ?>
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
                    <a class="btn btn-primary" href="clientes.php?edit=<?= $cliente['id'] ?>">Editar</a>  
                    <a class="btn btn-danger" href="clientes.php?delete=<?= $cliente['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php include "post.html.php";?>

