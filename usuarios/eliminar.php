<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../login.php");
    exit();
}

require_once '../conexion.php';

$id = intval($_GET['id'] ?? 0);

// Evitar que el administrador borre su propia cuenta activa por accidente
if ($id > 0 && $id !== intval($_SESSION['usuario_id'])) {
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: listar.php");
exit();
?>