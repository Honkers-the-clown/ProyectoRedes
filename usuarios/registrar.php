<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../login.php");
    exit();
}

require_once '../conexion.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $rol = $_POST['rol'];

    if (!empty($nombre) && !empty($usuario) && !empty($password)) {
        // Encriptar contraseña de forma segura
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $usuario, $passwordHash, $rol);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al registrar usuario (El nombre de usuario ya podria existir): " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "Por favor complete todos los campos obligatorios.";
    }
}

require_once '../includes/header.php';
?>

<h2>Registrar Nuevo Usuario</h2>

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
                <label class="form-label">Nombre de Usuario *</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol del Sistema *</label>
                <select name="rol" class="form-select" required>
                    <option value="Recepcionista">Recepcionista</option>
                    <option value="Veterinario">Veterinario</option>
                    <option value="Administrador">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>