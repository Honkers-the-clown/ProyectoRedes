<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../conexion.php';
require_once '../includes/header.php';

// Consulta para obtener todos los clientes
$sql = "SELECT * FROM clientes ORDER BY id DESC";
$result = $conexion->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gestión de Clientes</h2>
    <a href="agregar.php" class="btn btn-success">+ Registrar Nuevo Cliente</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['correo']) ?></td>
                            <td><?= htmlspecialchars($row['direccion']) ?></td>
                            <td class="text-center">
                                <a href="modificar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm fw-semibold">Editar</a>
                                <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm fw-semibold" onclick="return confirm('¿Está seguro de eliminar este cliente? Se eliminarán también sus registros vinculados.');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No hay clientes registrados en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>