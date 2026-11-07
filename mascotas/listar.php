<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../conexion.php';
require_once '../includes/header.php';

// Consulta para obtener las mascotas unidas con el nombre del cliente dueño
$sql = "SELECT mascotas.*, clientes.nombre AS cliente_nombre 
        FROM mascotas 
        INNER JOIN clientes ON mascotas.cliente_id = clientes.id";
$result = $conexion->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gestión de Mascotas</h2>
    <a href="agregar.php" class="btn btn-success">Registrar Nueva Mascota</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Dueño (Cliente)</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Edad</th>
                    <th>Peso (kg)</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['cliente_nombre']) ?></td>
                            <td><strong><?= htmlspecialchars($row['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($row['especie']) ?></td>
                            <td><?= htmlspecialchars($row['raza']) ?></td>
                            <td><?= $row['edad'] ?> años</td>
                            <td><?= $row['peso'] ?> kg</td>
                            <td class="text-center">
                                <a href="modificar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm fw-semibold">Editar</a>
                                <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm fw-semibold" onclick="return confirm('¿Está seguro de eliminar esta mascota?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No hay mascotas registradas en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>