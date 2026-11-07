<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../conexion.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $conexion->query("DELETE FROM mascotas WHERE id = $id");
}
header("Location: listar.php");
exit();
?>