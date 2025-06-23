<?php
require_once("conexion.php");

function registrarLog($adminId, $accion) {
    try {
        $db = getDatabaseConnection('admin_db');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $stmt = $db->prepare("INSERT INTO logs_acceso (administrador_id, accion, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$adminId, $accion, $ip]);
    } catch (PDOException $e) {
        error_log("Error al registrar log: " . $e->getMessage());
    }
}

// Obtener nombre de administrador por ID
function obtenerNombreAdministrador($adminId) {
    try {
        $db = getDatabaseConnection('admin_db');
        $stmt = $db->prepare("SELECT nombre FROM administradores WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        return $admin['nombre'] ?? null;
    } catch (PDOException $e) {
        error_log("Error al obtener nombre del administrador: " . $e->getMessage());
        return null;
    }
}

// Verificar si hay sesión activa de administrador
function verificarSesionAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}
