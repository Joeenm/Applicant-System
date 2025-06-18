<?php
require_once("../../php/conexion.php");
require_once("../../php/utils.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nombre && $email && $password) {
        try {
            $db = getDatabaseConnection('admin_db');

            // Verificar si ya existe ese correo
            $stmt = $db->prepare("SELECT id FROM administradores WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $mensaje = "El correo ya está registrado.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("INSERT INTO administradores (nombre, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $email, $passwordHash]);

                registrarLog($db->lastInsertId(), "Registro de nuevo administrador");
                $mensaje = "Administrador registrado exitosamente.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al registrar: " . $e->getMessage();
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Administrador</title>
</head>
<body>
    <h2>Registrar Nuevo Administrador</h2>
    <?php if ($mensaje): ?>
        <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
