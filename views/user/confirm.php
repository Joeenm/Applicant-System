<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación Exitosa | Proceso de Reclutamiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Encabezado con fondo azul -->
            <div class="bg-blue-600 py-6 px-8 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <i class="fas fa-check text-blue-600 text-xl"></i>
                </div>
                <h2 class="mt-3 text-2xl font-bold text-white">¡Postulación Exitosa!</h2>
                <p class="mt-1 text-blue-100">Hemos recibido tu información correctamente</p>
            </div>
            
            <!-- Contenido principal -->
            <div class="py-8 px-8">
                <div class="text-center">
                    <p class="text-gray-600 mb-6">
                        Gracias por completar el formulario de postulación. Hemos recibido tus datos y documentos académicos. 
                        Nos pondremos en contacto contigo si tu perfil coincide con nuestras vacantes disponibles.
                    </p>
                    
                    <div class="mt-6 bg-blue-50 rounded-lg p-4 text-left mb-6">
                        <h3 class="text-sm font-medium text-blue-800">¿Qué sigue?</h3>
                        <ul class="mt-2 text-sm text-blue-700 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-blue-500 flex-shrink-0"></i>
                                <span>Revisaremos tu información en los próximos días</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-blue-500 flex-shrink-0"></i>
                                <span>Te contactaremos al correo o teléfono proporcionado</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-blue-500 flex-shrink-0"></i>
                                <span>Puedes actualizar tu información en cualquier momento</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Resumen de envío (opcional) -->
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-500">Número de solicitud</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            <?php echo 'SOL-' . strtoupper(uniqid()); ?>
                        </p>
                    </div>
                </div>
                
                <!-- Acciones -->
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="/Applicant-System/index.php" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Volver al inicio
                    </a>
                </div>
            </div>
            
            <!-- Pie de página -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">
                    Si tienes alguna pregunta, contáctanos a <a href="mailto:reclutamiento@empresa.com" class="text-blue-600 hover:text-blue-500">reclutamiento@empresa.com</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>