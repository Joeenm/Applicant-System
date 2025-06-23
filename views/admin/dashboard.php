<?php
session_start();
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: access-denied.php');
    exit;
}

require_once("../../php/conexion.php");
$db = getDatabaseConnection('usuarios_db');

// Parámetros de paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10; // Número de registros por página
$pagina = max(1, $pagina); // Asegurar que la página no sea menor que 1

// Calcular el offset
$offset = ($pagina - 1) * $porPagina;

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Consulta base con o sin búsqueda
$sql = "SELECT * FROM candidatos";
$sqlTotal = "SELECT COUNT(*) AS total FROM candidatos";

$params = [];
$where = [];

if (!empty($buscar)) {
    $buscarParam = "%$buscar%";
    $where[] = "(nombres LIKE :b1 OR apellidos LIKE :b2 OR tipo_documento LIKE :b3 OR numero_documento LIKE :b4 OR email LIKE :b5)";
    $params[':b1'] = $buscarParam;
    $params[':b2'] = $buscarParam;
    $params[':b3'] = $buscarParam;
    $params[':b4'] = $buscarParam;
    $params[':b5'] = $buscarParam;
}

// Construir consulta final
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
    $sqlTotal .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY fecha_registro DESC LIMIT :limit OFFSET :offset";
$params[':limit'] = $porPagina;
$params[':offset'] = $offset;

// Ejecutar consulta principal
$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $paramType);
}
$stmt->execute();
$postulantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener total de resultados
$stmtTotal = $db->prepare($sqlTotal);
if (!empty($buscar)) {
    $stmtTotal->bindValue(':b1', $buscarParam, PDO::PARAM_STR);
    $stmtTotal->bindValue(':b2', $buscarParam, PDO::PARAM_STR);
    $stmtTotal->bindValue(':b3', $buscarParam, PDO::PARAM_STR);
    $stmtTotal->bindValue(':b4', $buscarParam, PDO::PARAM_STR);
    $stmtTotal->bindValue(':b5', $buscarParam, PDO::PARAM_STR);
}
$stmtTotal->execute();
$totalResultados = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Calcular el total de páginas
$totalPaginas = ceil($totalResultados / $porPagina);
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
                        <a href="../../views/admin/dashboard.php" class="block py-2 px-4 bg-blue-700 rounded">
                            <i class="fas fa-users mr-2"></i>Postulantes
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../../views/admin/documents.php" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-file-alt mr-2"></i>Documentos
                        </a>
                    </li>
                    <!-- <li class="mb-2">
                        <a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-cog mr-2"></i>Configuración
                        </a>
                    </li> -->
                    <li>
                        <a href="../../php/logout.php" class="block py-2 px-4 hover:bg-blue-700 rounded">
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
                        Mostrando <span class="font-medium"><?= ($offset + 1) ?></span> a 
                        <span class="font-medium"><?= min($offset + $porPagina, $totalResultados) ?></span> de 
                        <span class="font-medium"><?= $totalResultados ?></span> resultados
                    </div>
                    <div class="flex space-x-2">
                        <!-- Botón "Anterior" -->
                        <button 
                            onclick="window.location.href = '?pagina=<?= max(1, $pagina - 1) ?><?= !empty($buscar) ? '&buscar=' . urlencode($buscar) : '' ?>'"
                            class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 <?= $pagina == 1 ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            <?= $pagina == 1 ? 'disabled' : '' ?>
                        >
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <!-- Números de página -->
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <button 
                                onclick="window.location.href = '?pagina=<?= $i ?><?= !empty($buscar) ? '&buscar=' . urlencode($buscar) : '' ?>'"
                                class="px-3 py-1 border rounded-md <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?>"
                            >
                                <?= $i ?>
                            </button>
                        <?php endfor; ?>

                        <!-- Botón "Siguiente" -->
                        <button 
                            onclick="window.location.href = '?pagina=<?= min($totalPaginas, $pagina + 1) ?><?= !empty($buscar) ? '&buscar=' . urlencode($buscar) : '' ?>'"
                            class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 <?= $pagina == $totalPaginas ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            <?= $pagina == $totalPaginas ? 'disabled' : '' ?>
                        >
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>