<?php
session_start();

// Si el usuario NO ha iniciado sesión, redirigir inmediatamente a login.php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/header.php';
?>

<!-- Contenido principal -->
<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold">Bienvenido a VetCare Web</h1>
        <p class="col-md-8 fs-4">Sistema de Gestión de Citas, Pacientes y Expedientes Clínicos.</p>
    </div>
</div>

<div class="row text-center">
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Gestión de Clientes</h5>
                <p class="card-text">Registre y consulte información de dueños de mascotas.</p>
                <a href="clientes/listar.php" class="btn btn-outline-primary">Ir a Clientes</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Agenda de Citas</h5>
                <p class="card-text">Agende y revise citas con los veterinarios disponibles.</p>
                <a href="citas/listar.php" class="btn btn-outline-primary">Ver Citas</a>
            </div>
        </div>
    </div>

    <!-- Validamos con isset() para que no de error si 'rol' no está definido -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Control de Usuarios</h5>
                <p class="card-text">Administre cuentas y permisos para el personal.</p>
                <a href="usuarios/listar.php" class="btn btn-outline-primary">Gestionar Usuarios</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>