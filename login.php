<?php
session_start();
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    if (!empty($usuario) && !empty($password)) {
       $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE usuario = '$usuario'";
       $result = $conexion->query($sql);

        if ($user = $result->fetch_assoc()) {
            if ($password === $user['password']) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario no existe.";
        }
        $result = $stmt->get_result();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>VetCare Web - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="container-fluid login-container d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card shadow-lg">
                <div class="login-header">
                    <div class="mb-2">
                    </div>
                    <h3 class="fw-bold mb-0">VetCare Web</h3>
                    <p class="text-white-50 mb-0 small">Sistema de Gestión Veterinaria</p>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2 text-center small"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold small">Usuario</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted fw-semibold small">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">Ingresar al Sistema</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>