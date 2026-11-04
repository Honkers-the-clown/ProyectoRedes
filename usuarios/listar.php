<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../login.php");
    exit();
}

require_once '../conexion.php';
require_once '../includes/header.php';

$result = $conexion->query("SELECT id, nombre, usuario, rol, creado_en FROM usuarios ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gestión de Usuarios</h2>
    <a href="registrar.php" class="btn btn-success">Nuevo Usuario</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($u = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['usuario']) ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($u['rol']) ?></span>
                        </td>
                        <td><?= $u['creado_en'] ?></td>
                        <td>
                            <a href="modificar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                <a href="eliminar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Confirma que desea eliminar este usuario?')">Eliminar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>