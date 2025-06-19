<?php
// Incluir archivo de conexión
require_once('../../php/conexion.php');

// Procesar formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../php/insert.php';
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación a Vacante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-section {
            scroll-margin-top: 1rem;
        }
        @media (min-width: 1024px) {
            .form-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado del Formulario -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Postulación a Vacante</h1>
            <p class="text-gray-600 mt-2">Complete todos los campos requeridos</p>
        </div>
        
        <!-- Formulario -->
            <form class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6" action="/Applicant-System/php/insert.php" method="POST" enctype="multipart/form-data">            <div class="form-container">
                <!-- Columna Izquierda - Datos Personales -->
                <div class="form-section">
                    <!-- Sección de Información Personal -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Información Personal</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Nombre y Apellido -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombres*</label>
                                    <input type="text" id="nombre" name="nombre" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellidos*</label>
                                    <input type="text" id="apellido" name="apellido" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <!-- Nacionalidad -->
                            <div>
                                <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-1">Nacionalidad*</label>
                                <select id="nacionalidad" name="nacionalidad" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione su nacionalidad</option>
                                </select>
                            </div>
                            
                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Nacimiento*</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <select id="dia_nacimiento" name="dia_nacimiento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Día</option>
                                            <!-- Mostrar inicialmente los 31 días -->
                                            <script>
                                                for(let i = 1; i <= 31; i++) {
                                                    let day = i < 10 ? '0' + i : i;
                                                    document.write(`<option value="${day}">${day}</option>`);
                                                }
                                            </script>
                                        </select>
                                    </div>
                                    <div>
                                        <select id="mes_nacimiento" name="mes_nacimiento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Mes</option>
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select id="ano_nacimiento" name="ano_nacimiento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Año</option>
                                            <!-- Años desde 1920 hasta 2007 -->
                                            <script>
                                                for(let i = 2007; i >= 1920; i--) {
                                                    document.write(`<option value="${i}">${i}</option>`);
                                                }
                                            </script>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estado Civil -->
                            <div>
                                <label for="estado_civil" class="block text-sm font-medium text-gray-700 mb-1">Estado Civil*</label>
                                <select id="estado_civil" name="estado_civil" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione</option>
                                    <option value="Soltero/a">Soltero/a</option>
                                    <option value="Casado/a">Casado/a</option>
                                    <option value="Divorciado/a">Divorciado/a</option>
                                    <option value="Viudo/a">Viudo/a</option>
                                    <option value="Unión Libre">Unión Libre</option>
                                </select>
                            </div>
                            
                            <!-- Tipo de Documento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Documento de Identidad*</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div>
                                        <select id="tipo_documento" name="tipo_documento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Tipo</option>
                                            <option value="cedula">Cédula</option>
                                            <option value="pasaporte">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" id="numero_documento" name="numero_documento" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Número">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Género -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Género*</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="genero" value="femenino" required
                                            class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Femenino</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="genero" value="masculino"
                                            class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Masculino</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Movilidad y Licencia -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Movilidad y Licencia</label>
                                <div class="space-y-2">
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="movilidad_propia" value="1"
                                                class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2">Poseo movilidad propia</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="tiene_licencia" value="1" id="tiene_licencia"
                                                class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2">Poseo Licencia de Conducir</span>
                                        </label>
                                        <div id="tipos_licencia" class="mt-2 ml-6 hidden">
                                            <label class="block text-xs text-gray-500 mb-1">Tipos de licencia:</label>
                                            <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="A"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">A</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="B"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">B</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="C"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">C</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="D"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">D</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="E"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">E</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="E1"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">E1</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="E2"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">E2</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="E3"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">E3</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="F"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">F</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="G"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">G</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="H"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">H</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="licencia[]" value="I"
                                                        class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-1 text-xs">I</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Datos de Contacto -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Datos de Contacto</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Teléfonos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="telefono_celular" class="block text-sm font-medium text-gray-700 mb-1">Teléfono Celular*</label>
                                    <input type="tel" id="telefono_celular" name="telefono_celular" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="otro_telefono" class="block text-sm font-medium text-gray-700 mb-1">Otro Teléfono</label>
                                    <input type="tel" id="otro_telefono" name="otro_telefono"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <!-- País (fijo: Panamá) -->
                            <div>
                                <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">País</label>
                                <input type="text" id="pais" name="pais" value="Panamá" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                            </div>
                            
                            <!-- Provincia y Distrito -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="provincia" class="block text-sm font-medium text-gray-700 mb-1">Provincia*</label>
                                    <select id="provincia" name="provincia" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione provincia</option>
                                        <option value="Bocas del Toro">Bocas del Toro</option>
                                        <option value="Coclé">Coclé</option>
                                        <option value="Colón">Colón</option>
                                        <option value="Chiriquí">Chiriquí</option>
                                        <option value="Darién">Darién</option>
                                        <option value="Herrera">Herrera</option>
                                        <option value="Los Santos">Los Santos</option>
                                        <option value="Panamá">Panamá</option>
                                        <option value="Veraguas">Veraguas</option>
                                        <option value="Panamá Oeste">Panamá Oeste</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="distrito" class="block text-sm font-medium text-gray-700 mb-1">Distrito*</label>
                                    <select id="distrito" name="distrito" required disabled
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione provincia primero</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Dirección -->
                            <div>
                                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección*</label>
                                <input type="text" id="direccion" name="direccion" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico*</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha - Documentos Académicos -->
                <div class="form-section">
                    <!-- Sección de Documentos Académicos -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Documentos Académicos</h2>
                        
                        <!-- Doctorados -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Doctorados</label>
                                <button type="button" onclick="addDocumentField('doctorados')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Doctorado
                                </button>
                            </div>
                            <div id="doctorados-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Maestrías -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Maestrías</label>
                                <button type="button" onclick="addDocumentField('maestrias')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Maestría
                                </button>
                            </div>
                            <div id="maestrias-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Postgrados -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Postgrados</label>
                                <button type="button" onclick="addDocumentField('postgrados')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Postgrado
                                </button>
                            </div>
                            <div id="postgrados-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Licenciaturas/Ingenierías -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Licenciaturas/Ingenierías</label>
                                <button type="button" onclick="addDocumentField('licenciaturas')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Licenciatura
                                </button>
                            </div>
                            <div id="licenciaturas-container" class="space-y-3">
                                <!-- Campo inicial requerido -->
                            </div>
                        </div>
                        
                        <!-- Técnicos -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Técnicos</label>
                                <button type="button" onclick="addDocumentField('tecnicos')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Técnico
                                </button>
                            </div>
                            <div id="tecnicos-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Certificado Escolar -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Certificados Escolares</label>
                                <button type="button" onclick="addDocumentField('certificados')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Certificado
                                </button>
                            </div>
                            <div id="certificados-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Diplomados -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Diplomados</label>
                                <button type="button" onclick="addDocumentField('diplomados')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Diplomado
                                </button>
                            </div>
                            <div id="diplomados-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Seminarios -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Seminarios</label>
                                <button type="button" onclick="addDocumentField('seminarios')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Seminario
                                </button>
                            </div>
                            <div id="seminarios-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                        
                        <!-- Cursos -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Cursos</label>
                                <button type="button" onclick="addDocumentField('cursos')" class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100">
                                    + Añadir Curso
                                </button>
                            </div>
                            <div id="cursos-container" class="space-y-3">
                                <!-- Campos dinámicos se agregarán aquí -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botón de Envío -->
            <div class="text-center mt-8">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Enviar Postulación
                </button>
            </div>
        </form>
    </div>

    <script src="../../js/script.js"></script>
    <script src="../../js/scriptDistritos.js"></script>
    <script src="../../js/scriptValidations.js"></script>
</body>
</html>