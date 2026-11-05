<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
$rol = $_SESSION['rol'] ?? '';

require_once '../conexion.php';
require_once '../includes/header.php';

$result = $conexion->query("SELECT id, nombre, cedula, telefono, correo, direccion FROM clientes ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gestión de Clientes</h2>
    <?php if ($rol !== 'Veterinario'): ?>
        <a href="registrar.php" class="btn btn-success">Nuevo Cliente</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($c = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['cedula']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= htmlspecialchars($c['correo']) ?></td>
                        <td><?= htmlspecialchars($c['direccion']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>