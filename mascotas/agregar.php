<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../conexion.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = intval($_POST['cliente_id']);
    $nombre = trim($_POST['nombre']);
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);
    $edad = intval($_POST['edad']);
    $peso = floatval($_POST['peso']);

    if ($cliente_id > 0 && !empty($nombre) && !empty($especie)) {
        $stmt = $conexion->prepare("INSERT INTO mascotas (cliente_id, nombre, especie, raza, edad, peso) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdi", $cliente_id, $nombre, $especie, $raza, $edad, $peso);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $msg = "Error al registrar la mascota: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $msg = "Por favor complete todos los campos requeridos.";
    }
}

// Obtener lista de clientes para el selector
$clientes = $conexion->query("SELECT id, nombre FROM clientes");

require_once '../includes/header.php';
?>

<h2>Registrar Nueva Mascota</h2>

<?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Dueño (Cliente) *</label>
                <select name="cliente_id" class="form-select" required
                oninvalid="this.setCustomValidity('Por favor, elegir la cuenta cliente encargada de esta mascota.')"
                oninput="this.setCustomValidity('')">
                    <option value="">Seleccione un cliente...</option>
                    <?php while ($c = $clientes->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de la Mascota *</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Firulais" required
                oninvalid="this.setCustomValidity('Por favor, dar el nombre de la mascota a agregar.')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="mb-3">
                <label class="form-label">Especie *</label>
                <input type="text" name="especie" class="form-control" placeholder="Ej: Perro, Gato..." required
                oninvalid="this.setCustomValidity('Por favor, dar la especia de la mascota.')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="mb-3">
                <label class="form-label">Raza</label>
                <input type="text" name="raza" class="form-control" placeholder="Ej: Golden Retriever, Mestizo..." required
                oninvalid="this.setCustomValidity('Por favor, dar la raza de el animal.')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="mb-3">
                <label class="form-label">Edad (años)</label>
                <input type="number" name="edad" class="form-control" min="0" placeholder="Ej: 3" required
                oninvalid="this.setCustomValidity('Por favor, dar la edad de el animal.')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="mb-3">
                <label class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" name="peso" class="form-control" min="0" placeholder="Ej: 12.5" required
                oninvalid="this.setCustomValidity('Por favor, dar el peso de el animal, en kilogramos.')"
                oninput="this.setCustomValidity('')">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Mascota</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>