<?php
session_start();
require_once("../../php/conexion.php");
require_once("../../php/utils.php");

// Redirigir si ya está autenticado
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $db = getDatabaseConnection('admin_db');
        
        // Buscar administrador por email
        $stmt = $db->prepare("SELECT * FROM administradores WHERE email = ? AND activo = 1 LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Autenticación exitosa
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            $_SESSION['admin_email'] = $admin['email'];
            
            // Actualizar último acceso
            $db->prepare("UPDATE administradores SET ultimo_acceso = NOW() WHERE id = ?")
               ->execute([$admin['id']]);
            
            // Registrar log
            registrarLog($admin['id'], 'Inicio de sesión exitoso');
            
            header('Location: dashboard.php');
            exit;
        } else {
            // Autenticación fallida
            $error = "Credenciales incorrectas o cuenta inactiva";
            registrarLog(0, "Intento de inicio de sesión fallido para: $email");
        }
    } catch (PDOException $e) {
        $error = "Error en el sistema. Por favor intente más tarde.";
        error_log("Error en login: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                    <i class="fas fa-lock text-blue-600 text-2xl"></i>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Acceso Administrativo</h2>
                <p class="mt-2 text-gray-600">Ingrese sus credenciales para continuar</p>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Recordar sesión
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="/Applicant-System/index.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Regresar
                        </a>
                    </div>
                </div>
                
                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>