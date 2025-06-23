<?php
// Configuración inicial
set_time_limit(60); // Aumentar tiempo de ejecución
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();
header('Content-Type: application/json');

// Verificar si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta absoluta para conexion.php
require_once(__DIR__.'/conexion.php');

try {
    // Verificar autenticación
    if (!isset($_SESSION['admin_id'])) {
        throw new Exception('Acceso no autorizado', 401);
    }

    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Obtener y validar ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        throw new Exception('ID no válido', 400);
    }

    // Obtener conexiones con tiempo de espera
    $db_usuarios = getDatabaseConnection('usuarios_db');
    $db_academico = getDatabaseConnection('academico_db');
    
    // Configurar tiempo de espera para transacciones (30 segundos)
    $db_usuarios->setAttribute(PDO::ATTR_TIMEOUT, 30);
    $db_academico->setAttribute(PDO::ATTR_TIMEOUT, 30);

    // TRANSACCIÓN PRINCIPAL (usuarios)
    $db_usuarios->beginTransaction();
    
    try {
        // Verificar existencia con bloqueo
        $stmt = $db_usuarios->prepare("SELECT * FROM candidatos WHERE id = ? FOR UPDATE");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            throw new Exception("Postulante no encontrado");
        }

        // TRANSACCIÓN SECUNDARIA (académica)
        $db_academico->beginTransaction();
        try {
            // Eliminar documentos académicos
            $stmt = $db_academico->prepare("DELETE FROM documentos_academicos WHERE candidato_id = ?");
            if (!$stmt->execute([$id])) {
                throw new Exception("Error al eliminar documentos académicos");
            }
            $db_academico->commit();
        } catch (Exception $e) {
            $db_academico->rollBack();
            throw new Exception("Error en transacción académica: ".$e->getMessage());
        }

        // Eliminar candidato
        $stmt = $db_usuarios->prepare("DELETE FROM candidatos WHERE id = ?");
        if (!$stmt->execute([$id])) {
            throw new Exception("Error al eliminar candidato");
        }

        // Eliminar archivos físicos (fuera de transacción)
        $directorio = __DIR__."/../uploads/$id/";
        if (file_exists($directorio)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directorio, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($files as $fileinfo) {
                $fileinfo->isDir() ? rmdir($fileinfo->getRealPath()) : unlink($fileinfo->getRealPath());
            }
            rmdir($directorio);
        }

        $db_usuarios->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Eliminación completada correctamente'
        ]);
        
    } catch (Exception $e) {
        $db_usuarios->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} finally {
    ob_end_flush();
    exit;
}

function eliminarDirectorio($dir) {
    if (!is_dir($dir)) {
        return;
    }
    $archivos = array_diff(scandir($dir), ['.', '..']);
    foreach ($archivos as $archivo) {
        $ruta = "$dir/$archivo";
        is_dir($ruta) ? eliminarDirectorio($ruta) : unlink($ruta);
    }
    rmdir($dir);
}
?>