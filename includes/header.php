<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ProyectoRedes/login.php");
    exit();
}
$rol = $_SESSION['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetCare Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProyectoRedes/css/estilos.css">
</head>
<body>
<!-- Se removió "bg-primary" para permitir el control de color por estilos -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/ProyectoRedes/index.php">VetCare Web</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <!-- Clientes: Admin y Recepcionista pueden modificar, Veterinario solo ver -->
        <li class="nav-item"><a class="nav-link" href="/ProyectoRedes/clientes/listar.php">Clientes</a></li>
        
        <!-- Citas: Admin y Recepcionista pueden modificar, Veterinario solo ver -->
        <li class="nav-item"><a class="nav-link" href="/ProyectoRedes/citas/listar.php">Citas</a></li>
        
        <!-- Usuarios: Únicamente visible para el Administrador -->
        <?php if ($rol === 'Administrador'): ?>
        <li class="nav-item"><a class="nav-link" href="/ProyectoRedes/usuarios/listar.php">Usuarios</a></li>
        <?php endif; ?>
      </ul>
      <span class="navbar-text me-3 text-white">
        <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong> (<?= htmlspecialchars($rol) ?>)
      </span>
    <a href="/ProyectoRedes/logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
    </div>
  </div>
</nav>
<div class="container my-4">