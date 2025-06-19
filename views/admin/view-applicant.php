<?php
session_start();
// Verificar autenticación y permisos de admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: access-denied.php');
    exit;
}

require_once("../../php/conexion.php");
$db_usuarios = getDatabaseConnection('usuarios_db');
$db_academico = getDatabaseConnection('academico_db');

// Obtener datos del postulante
$id = $_GET['id'] ?? 0;
$postulante = $db_usuarios->query("SELECT * FROM candidatos WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

// Obtener documentos académicos
$documentos = $db_academico->query("SELECT * FROM documentos_academicos WHERE candidato_id = $id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Postulante</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="mb-6">
                <a href="dashboard.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                </a>
            </div>

            <!-- Información del Postulante -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Información Personal</h2>
<!--                <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-print mr-2"></i>Imprimir
                        </button>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-download mr-2"></i>Descargar PDF
                        </button>
                    </div> -->
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nombre Completo</label>
                            <p class="text-lg"><?= htmlspecialchars($postulante['nombres']) . ' ' . htmlspecialchars($postulante['apellidos']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Documento de Identidad</label>
                            <p><?= htmlspecialchars($postulante['tipo_documento']) ?>: <?= htmlspecialchars($postulante['numero_documento']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Nacimiento</label>
                            <p><?= htmlspecialchars($postulante['dia_nacimiento']) ?>/<?= htmlspecialchars($postulante['mes_nacimiento']) ?>/<?= htmlspecialchars($postulante['ano_nacimiento']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nacionalidad</label>
                            <p><?= htmlspecialchars($postulante['nacionalidad']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Estado Civil</label>
                            <p><?= htmlspecialchars($postulante['estado_civil']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Género</label>
                            <p><?= htmlspecialchars($postulante['genero']) ?></p>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Contacto</label>
                            <p class="mb-1"><i class="fas fa-envelope mr-2 text-blue-500"></i> <?= htmlspecialchars($postulante['email']) ?></p>
                            <p class="mb-1"><i class="fas fa-phone mr-2 text-blue-500"></i> <?= htmlspecialchars($postulante['telefono_celular']) ?></p>
                            <?php if (!empty($postulante['otro_telefono'])): ?>
                            <p><i class="fas fa-phone-alt mr-2 text-blue-500"></i> <?= htmlspecialchars($postulante['otro_telefono']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dirección</label>
                            <p><?= htmlspecialchars($postulante['direccion']) ?>, <?= htmlspecialchars($postulante['distrito']) ?>, <?= htmlspecialchars($postulante['provincia']) ?>, <?= htmlspecialchars($postulante['pais']) ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Movilidad Propia</label>
                            <p><?= $postulante['movilidad_propia'] ? 'Sí' : 'No' ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Licencias</label>
                            <p><?= $postulante['tiene_licencia'] ? htmlspecialchars(implode(', ', array_map('trim', explode(',', $postulante['tipos_licencia'])))) : 'No tiene licencias' ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Registro</label>
                            <p><?= date('d/m/Y H:i', strtotime($postulante['fecha_registro'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos Académicos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Documentos Académicos</h2>

                <?php if (empty($documentos)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-2"></i>
                        <p>El postulante no ha subido documentos académicos</p>
                    </div>
                <?php else: ?>

                    <?php
                    // Agrupar los documentos por tipo_documento
                    $documentosPorTipo = [];
                    foreach ($documentos as $doc) {
                        $tipo = ucfirst($doc['tipo_documento']) . 's';
                        $documentosPorTipo[$tipo][] = $doc;
                    }
                    ?>

                    <?php foreach ($documentosPorTipo as $tipo => $docs): ?>
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-blue-700 border-b pb-1 mb-4"><?= htmlspecialchars($tipo) ?></h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($docs as $doc): ?>
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div class="pr-2">
                                                <h4 class="font-medium text-md"><?= htmlspecialchars($doc['nombre_documento']) ?></h4>
                                                <p class="text-sm text-gray-500">
                                                    Subido el <?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?><br>
                                                    <?= round($doc['tamano_archivo'] / 1024, 2) ?> KB
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <a href="/Applicant-System/uploads/<?= $postulante['id'] ?>/<?= urlencode($doc['nombre_archivo']) ?>" 
                                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                                                download>
                                                    <i class="fas fa-download mr-1"></i>Descargar
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Vista previa del PDF -->
                                        <div class="mt-3">
                                            <iframe 
                                                src="/Applicant-System/uploads/<?= $postulante['id'] ?>/<?= urlencode($doc['nombre_archivo']) ?>" 
                                                width="100%" 
                                                height="250px"
                                                class="border rounded-md"
                                            >
                                                Este navegador no soporta la visualización de PDFs.
                                            </iframe>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>