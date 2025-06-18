<?php
session_start();
// Verificar autenticación y permisos de admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: access-denied.php');
    exit;
}

require_once("../../php/conexion.php");
$db = getDatabaseConnection('usuarios_db');

$buscar = $_GET['buscar'] ?? '';

if (!empty($buscar)) {
    $buscarParam = "%$buscar%";
    $sql = "SELECT * FROM candidatos WHERE
        nombres LIKE :b1 OR
        apellidos LIKE :b2 OR
        tipo_documento LIKE :b3 OR
        numero_documento LIKE :b4 OR
        email LIKE :b5
        ORDER BY fecha_registro DESC";
    
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':b1', $buscarParam, PDO::PARAM_STR);
    $stmt->bindParam(':b2', $buscarParam, PDO::PARAM_STR);
    $stmt->bindParam(':b3', $buscarParam, PDO::PARAM_STR);
    $stmt->bindParam(':b4', $buscarParam, PDO::PARAM_STR);
    $stmt->bindParam(':b5', $buscarParam, PDO::PARAM_STR);

    $stmt->execute();
    $postulantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $postulantes = $db->query("SELECT * FROM candidatos ORDER BY fecha_registro ASC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/scriptDashboard.js"></script>
    <script src="../../js/scriptDelete.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 fixed h-full">
            <div class="p-4 border-b border-blue-700">
                <h1 class="text-2xl font-bold">Panel de Control</h1>
                <p class="text-blue-200 text-sm">Administración de Postulantes</p>
            </div>
            <nav class="p-4">
                <ul>
                    <li class="mb-2">
                        <a href="#" class="block py-2 px-4 bg-blue-700 rounded">
                            <i class="fas fa-users mr-2"></i>Postulantes
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-file-alt mr-2"></i>Documentos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-cog mr-2"></i>Configuración
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="ml-64 p-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Lista de Postulantes</h2>
                    <div class="relative">
                        <form method="GET" class="relative">
                            <input type="text" name="buscar" placeholder="Buscar postulante..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>

                            <button type="button" onclick="window.location.href = window.location.pathname;" class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Postulantes -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Identidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($postulantes as $postulante): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($postulante['nombres'] . ' ' . $postulante['apellidos']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($postulante['pais']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($postulante['tipo_documento']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($postulante['numero_documento']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($postulante['email']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($postulante['telefono_celular']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($postulante['fecha_registro'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    <a href="#" onclick="toggleFavorito(<?= $postulante['id'] ?>, this)" class="favorito mr-3" title="Marcar como favorito">
                                        <i class="fas fa-star"></i>
                                    </a>
                                    <a href="view-applicant.php?id=<?= $postulante['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></a>
                                    <a href="#" onclick="confirmarEliminacion(<?= $postulante['id'] ?>)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-500">
                        Mostrando <span class="font-medium">1</span> a <span class="font-medium">10</span> de <span class="font-medium"><?= count($postulantes) ?></span> resultados
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 border rounded-md bg-blue-600 text-white">1</button>
                        <button class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50">2</button>
                        <button class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>