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
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);

    if (!empty($nombre)) {
        $stmt = $conexion->prepare("UPDATE clientes SET nombre = ?, telefono = ?, correo = ?, direccion = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $telefono, $correo, $direccion, $id);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al actualizar el cliente: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "El nombre del cliente es obligatorio.";
    }
}

// Obtener datos actuales del cliente
$stmt_c = $conexion->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt_c->bind_param("i", $id);
$stmt_c->execute();
$cliente = $stmt_c->get_result()->fetch_assoc();
$stmt_c->close();

if (!$cliente) {
    header("Location: listar.php");
    exit();
}

require_once '../includes/header.php';
?>

<h2>Modificar Cliente</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre del Cliente *</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <textarea name="direccion" class="form-control" rows="2"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning fw-bold">Guardar Cambios</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>