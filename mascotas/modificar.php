<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: listar.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = intval($_POST['cliente_id']);
    $nombre = trim($_POST['nombre']);
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);
    $edad = intval($_POST['edad']);
    $peso = floatval($_POST['peso']);

    if ($cliente_id > 0 && !empty($nombre) && !empty($especie)) {
        $stmt = $conexion->prepare("UPDATE mascotas SET cliente_id = ?, nombre = ?, especie = ?, raza = ?, edad = ?, peso = ? WHERE id = ?");
        $stmt->bind_param("isssdii", $cliente_id, $nombre, $especie, $raza, $edad, $peso, $id);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al actualizar la mascota: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "Por favor complete todos los campos requeridos.";
    }
}

// Obtener datos actuales de la mascota
$stmt_m = $conexion->prepare("SELECT * FROM mascotas WHERE id = ?");
$stmt_m->bind_param("i", $id);
$stmt_m->execute();
$mascota = $stmt_m->get_result()->fetch_assoc();
$stmt_m->close();

if (!$mascota) {
    header("Location: listar.php");
    exit();
}

// Obtener lista de clientes para el selector
$clientes = $conexion->query("SELECT id, nombre FROM clientes");

require_once '../includes/header.php';
?>

<h2>Editar Mascota</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Dueño (Cliente) *</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente...</option>
                    <?php while ($c = $clientes->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= ($c['id'] == $mascota['cliente_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de la Mascota *</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($mascota['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Especie *</label>
                <input type="text" name="especie" class="form-control" value="<?= htmlspecialchars($mascota['especie']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Raza</label>
                <input type="text" name="raza" class="form-control" value="<?= htmlspecialchars($mascota['raza']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Edad (años)</label>
                <input type="number" name="edad" class="form-control" min="0" value="<?= $mascota['edad'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" name="peso" class="form-control" min="0" value="<?= $mascota['peso'] ?>">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>