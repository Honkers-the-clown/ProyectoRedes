<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] === 'Veterinario') {
    header("Location: listar.php");
    exit();
}

require_once '../conexion.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);

    if (!empty($nombre) && !empty($cedula)) {
        $stmt = $conexion->prepare("INSERT INTO clientes (nombre, cedula, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $cedula, $telefono, $correo, $direccion);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al registrar cliente (Es posible que la cédula ya esté registrada): " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "Por favor complete los campos obligatorios.";
    }
}

require_once '../includes/header.php';
?>

<h2>Registrar Nuevo Cliente</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre Completo *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cédula *</label>
                <input type="text" name="cedula" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <textarea name="direccion" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>