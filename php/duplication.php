<?php
require_once("conexion.php");
$db = getDatabaseConnection('usuarios_db');

header('Content-Type: application/json');

$tipo_documento = $_POST['tipo_documento'] ?? '';
$numero_documento = $_POST['numero_documento'] ?? '';
$email = $_POST['email'] ?? '';

$errores = [];

// Validar documento
if (!empty($tipo_documento) && !empty($numero_documento)) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM candidatos WHERE tipo_documento = ? AND numero_documento = ?");
    $stmt->execute([$tipo_documento, $numero_documento]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El {$tipo_documento} {$numero_documento} ya está registrado.";
    }
}

// Validar email
if (!empty($email)) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM candidatos WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El correo electrónico ya está registrado.";
    }
}

echo json_encode([
    'valido' => empty($errores),
    'errores' => $errores
]);