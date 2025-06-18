<?php
session_start();
$es_admin = isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true;
$admin_nombre = $_SESSION['admin_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        danger: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="bg-danger-600 py-4 px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-white text-2xl mr-3"></i>
                        <h1 class="text-xl font-bold text-white">Acceso Restringido</h1>
                    </div>
                    <?php if ($es_admin): ?>
                    <span class="bg-white text-danger-600 text-xs font-semibold px-2 py-1 rounded-full">
                        Admin
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Contenido -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-danger-100 text-danger-600 mb-4">
                        <i class="fas fa-ban text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Acceso Denegado</h2>
                    <p class="text-gray-600">
                        No tienes permiso para acceder a esta página o recurso.
                    </p>
                </div>
                
                <div class="bg-danger-50 border-l-4 border-danger-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-danger-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-danger-700">
                                <?php if ($es_admin): ?>
                                Tu cuenta no tiene los privilegios necesarios para esta sección.
                                <?php else: ?>
                                Debes iniciar sesión como administrador para acceder.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <?php if ($es_admin): ?>
                        <a href="admin/dashboard.php" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Volver al Panel de Control
                        </a>
                    <?php else: ?>
                        <a href="login.php" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-danger-600 text-white rounded-lg hover:bg-danger-700 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión como Administrador
                        </a>
                    <?php endif; ?>
                    
                    <a href="/Applicant-System/index.php"
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-home mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
            
            <!-- Pie de página -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <i class="fas fa-lock mr-1"></i>
                        Sistema Seguro
                    </div>
                    <div>
                        <?= date('Y') ?> &copy; Todos los derechos reservados
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($admin_nombre): ?>
        <div class="mt-6 text-center text-sm text-gray-500">
            Sesión iniciada como: <span class="font-medium"><?= htmlspecialchars($admin_nombre) ?></span>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>