<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
$rol = $_SESSION['rol'] ?? '';

require_once '../conexion.php';
require_once '../includes/header.php';

$sql = "SELECT c.id, m.nombre AS mascota, cl.nombre AS cliente, u.nombre AS veterinario, 
               c.fecha_hora, c.motivo, c.estado 
        FROM citas c
        JOIN mascotas m ON c.mascota_id = m.id
        JOIN clientes cl ON m.cliente_id = cl.id
        JOIN usuarios u ON c.veterinario_id = u.id
        ORDER BY c.fecha_hora DESC";

$result = $conexion->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Agenda de Citas Médicas</h2>
    <?php if ($rol !== 'Veterinario'): ?>
        <a href="agendar.php" class="btn btn-success">Agendar Nueva Cita</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha y Hora</th>
                    <th>Mascota (Cliente)</th>
                    <th>Veterinario</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($c = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['fecha_hora']) ?></td>
                        <td><?= htmlspecialchars($c['mascota']) ?> (<?= htmlspecialchars($c['cliente']) ?>)</td>
                        <td><?= htmlspecialchars($c['veterinario']) ?></td>
                        <td><?= htmlspecialchars($c['motivo']) ?></td>
                        <td>
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if ($c['estado'] === 'Pendiente') $badgeClass = 'bg-warning text-dark';
                                if ($c['estado'] === 'Completada') $badgeClass = 'bg-success';
                                if ($c['estado'] === 'Cancelada') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $c['estado'] ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay citas registradas en la agenda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>