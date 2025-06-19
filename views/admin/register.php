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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                    <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Registro de Administrador</h2>
                <p class="mt-2 text-gray-600">Complete el formulario para registrar un nuevo administrador</p>
            </div>
            
            <?php if ($mensaje): ?>
            <div class="<?= strpos($mensaje, 'exitosamente') !== false ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500' ?> p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="<?= strpos($mensaje, 'exitosamente') !== false ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500' ?>"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm <?= strpos($mensaje, 'exitosamente') !== false ? 'text-green-700' : 'text-red-700' ?>"><?= htmlspecialchars($mensaje) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="nombre" name="nombre" required
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ingrese su nombre completo">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md"
                            placeholder="ejemplo@dominio.com">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md"
                            placeholder="••••••••">
                    </div>
                </div>
                
                <div class="flex items-center justify-end">
                    <div class="text-sm">
                        <a href="/Applicant-System/index.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Regresar al inicio
                        </a>
                    </div>
                </div>
                
                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-user-plus mr-2"></i> Registrar Administrador
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../js/scriptValidations.js"></script>
</body>
</html>