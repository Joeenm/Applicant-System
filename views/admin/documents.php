<?php
session_start();
// Verificar autenticación y permisos de admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: access-denied.php');
    exit;
}

// Incluir el archivo de conexión
require_once('../../php/conexion.php');

// Consultar estadísticas mejoradas
try {
    $db_academico = getDatabaseConnection('academico_db');
    
    // 1. Estadísticas por tipo (actual)
    $query = "SELECT tipo_documento, COUNT(*) as total FROM documentos_academicos GROUP BY tipo_documento";
    $documentos_por_tipo = $db_academico->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Documentos recientes (nueva consulta)
    $query_recientes = "SELECT tipo_documento, nombre_documento, fecha_subida 
                       FROM documentos_academicos 
                       ORDER BY fecha_subida DESC LIMIT 5";
    $documentos_recientes = $db_academico->query($query_recientes)->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. Tendencias mensuales (nueva consulta)
    $query_tendencias = "SELECT 
                            DATE_FORMAT(fecha_subida, '%Y-%m') as mes,
                            COUNT(*) as total
                         FROM documentos_academicos
                         GROUP BY mes
                         ORDER BY mes DESC
                         LIMIT 6";
    $tendencias_mensuales = $db_academico->query($query_tendencias)->fetchAll(PDO::FETCH_ASSOC);
    
    // 4. Tamaño total por tipo (nueva consulta)
    $query_tamanos = "SELECT 
                         tipo_documento, 
                         SUM(tamano_archivo) as total_bytes,
                         COUNT(*) as cantidad
                      FROM documentos_academicos
                      GROUP BY tipo_documento";
    $tamanos_por_tipo = $db_academico->query($query_tamanos)->fetchAll(PDO::FETCH_ASSOC);
    
    $total_documentos = array_reduce($documentos_por_tipo, function($carry, $item) {
        return $carry + $item['total'];
    }, 0);
    
    // Colores para gráficos
    $colores = [
        'doctorado' => 'bg-purple-500',
        'maestria' => 'bg-blue-500',
        'postgrado' => 'bg-indigo-500',
        'licenciatura' => 'bg-green-500',
        'tecnico' => 'bg-yellow-500',
        'certificado' => 'bg-red-500',
        'diplomado' => 'bg-pink-500',
        'seminario' => 'bg-orange-500',
        'curso' => 'bg-teal-500'
    ];
    
} catch (Exception $e) {
    error_log("Error al obtener estadísticas de documentos: " . $e->getMessage());
    $documentos_por_tipo = [];
    $documentos_recientes = [];
    $tendencias_mensuales = [];
    $tamanos_por_tipo = [];
    $total_documentos = 0;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a href="../../views/admin/dashboard.php" class="block py-2 px-4 hover:bg-blue-700 rounded">
                            <i class="fas fa-users mr-2"></i>Postulantes
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../../views/admin/documents.php" class="block py-2 px-4 bg-blue-700 rounded">
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
            <!-- Sección de Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Tarjeta Total Documentos -->
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500">Documentos totales</p>
                        <h3 class="text-2xl font-bold"><?= number_format($total_documentos) ?></h3>
                    </div>
                </div>
                
                <!-- Tarjeta Tipos de Documentos -->
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-tags text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500">Tipos de documentos</p>
                        <h3 class="text-2xl font-bold"><?= count($documentos_por_tipo) ?></h3>
                    </div>
                </div>
                
                <!-- Tarjeta Tamaño Total -->
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-database text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500">Almacenamiento usado</p>
                        <h3 class="text-2xl font-bold">
                            <?php
                            $total_bytes = array_reduce($tamanos_por_tipo, function($carry, $item) {
                                return $carry + $item['total_bytes'];
                            }, 0);
                            echo formatBytes($total_bytes);
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Distribución -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Distribución por Tipo de Documento</h2>
                <div class="h-80">
                    <canvas id="chartTipos"></canvas>
                </div>
            </div>

            <!-- Sección de Documentos Recientes -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Documentos Recientes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($documentos_recientes as $doc): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full <?= $colores[$doc['tipo_documento']] ?> text-white">
                                        <?= ucfirst($doc['tipo_documento']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $doc['nombre_documento'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gráfico de Tendencias -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tendencias Mensuales</h2>
                    <div class="h-64">
                        <canvas id="chartTendencias"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Uso de Almacenamiento</h2>
                    <div class="h-64">
                        <canvas id="chartAlmacenamiento"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Gráfico de distribución por tipos
    const ctxTipos = document.getElementById('chartTipos').getContext('2d');
    new Chart(ctxTipos, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($documentos_por_tipo, 'tipo_documento')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($documentos_por_tipo, 'total')) ?>,
                backgroundColor: [
                    '#9F7AEA', '#4299E1', '#667EEA', '#48BB78', 
                    '#ECC94B', '#F56565', '#ED64A6', '#ED8936', '#38B2AC'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Gráfico de tendencias mensuales
    const ctxTendencias = document.getElementById('chartTendencias').getContext('2d');
    new Chart(ctxTendencias, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($tendencias_mensuales, 'mes')) ?>,
            datasets: [{
                label: 'Documentos subidos',
                data: <?= json_encode(array_column($tendencias_mensuales, 'total')) ?>,
                backgroundColor: 'rgba(66, 153, 225, 0.2)',
                borderColor: 'rgba(66, 153, 225, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de almacenamiento por tipo
    const ctxAlmacenamiento = document.getElementById('chartAlmacenamiento').getContext('2d');
    new Chart(ctxAlmacenamiento, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($tamanos_por_tipo, 'tipo_documento')) ?>,
            datasets: [{
                label: 'Almacenamiento (MB)',
                data: <?= json_encode(array_map(function($item) {
                    return round($item['total_bytes'] / (1024 * 1024), 2);
                }, $tamanos_por_tipo)) ?>,
                backgroundColor: '#9F7AEA',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>
</html>

<?php
// Función para formatear bytes a unidades legibles
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>