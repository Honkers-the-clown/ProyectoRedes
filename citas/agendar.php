<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] === 'Veterinario') {
    header("Location: listar.php");
    exit();
}

require_once '../conexion.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota_id = intval($_POST['mascota_id']);
    $veterinario_id = intval($_POST['veterinario_id']);
    $fecha_hora = trim($_POST['fecha_hora']);
    $motivo = trim($_POST['motivo']);
    $estado = 'Pendiente';

    if ($mascota_id > 0 && $veterinario_id > 0 && !empty($fecha_hora) && !empty($motivo)) {
        $stmt = $conexion->prepare("INSERT INTO citas (mascota_id, veterinario_id, fecha_hora, motivo, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $mascota_id, $veterinario_id, $fecha_hora, $motivo, $estado);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al agendar la cita: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "Por favor complete todos los campos requeridos.";
    }
}

// Obtener lista de mascotas para el select
$mascotas = $conexion->query("SELECT m.id, m.nombre, c.nombre AS cliente FROM mascotas m JOIN clientes c ON m.cliente_id = c.id");

// Obtener lista de usuarios con rol de Veterinario o Administrador
$veterinarios = $conexion->query("SELECT id, nombre, rol FROM usuarios WHERE rol IN ('Veterinario', 'Administrador')");

require_once '../includes/header.php';
?>

<h2>Agendar Nueva Cita</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Mascota (Dueño) *</label>
                <select name="mascota_id" class="form-select" required>
                    <option value="">Seleccione una mascota...</option>
                    <?php while ($m = $mascotas->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?> (Dueño: <?= htmlspecialchars($m['cliente']) ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Veterinario Asignado *</label>
                <select name="veterinario_id" class="form-select" required>
                    <option value="">Seleccione un veterinario...</option>
                    <?php while ($v = $veterinarios->fetch_assoc()): ?>
                        <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nombre']) ?> (<?= $v['rol'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha y Hora de la Cita *</label>
                <input type="datetime-local" name="fecha_hora" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Motivo de la Consulta *</label>
                <textarea name="motivo" class="form-control" rows="3" required placeholder="Ej: Control de rutina, vacunación, urgencia..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cita</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>