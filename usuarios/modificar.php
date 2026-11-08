<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../login.php");
    exit();
}

require_once '../conexion.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listar.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $rol = $_POST['rol'];
    $password = trim($_POST['password']);

    if (!empty($nombre)) {
        if (!empty($password)) {
            // Si ingresó nueva contraseña, se vuelve a hashear
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, password = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nombre, $passwordHash, $rol, $id);
        } else {
            // Si la contraseña está vacía, se mantiene la anterior
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre, $rol, $id);
        }

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al actualizar el usuario: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "El nombre es obligatorio.";
    }
}

// Obtener datos actuales del usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
if (!$usuario) {
    header("Location: listar.php");
    exit();
}

require_once '../includes/header.php';
?>

<h2>Modificar Usuario</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre Completo *</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control" required
                oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de Usuario (No modificable)</label>
                <input type="text" value="<?= htmlspecialchars($usuario['usuario']) ?>" class="form-control" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Nueva Contraseña (Dejar en blanco para conservar la actual)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Rol del Sistema *</label>
                <select name="rol" class="form-select" required
                oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                oninput="this.setCustomValidity('')">
                    <option value="Recepcionista" <?= $usuario['rol'] == 'Recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
                    <option value="Veterinario" <?= $usuario['rol'] == 'Veterinario' ? 'selected' : '' ?>>Veterinario</option>
                    <option value="Administrador" <?= $usuario['rol'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>