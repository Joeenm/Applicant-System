<?php
require_once 'conexion.php';

// Obtener conexiones usando la clase Database
$usuarios_db = getDatabaseConnection('usuarios_db');
$academico_db = getDatabaseConnection('academico_db');

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Procesar datos personales y guardar en usuarios_db
        $datos_personales = procesarDatosPersonales($_POST);
        $usuario_id = guardarDatosPersonales($usuarios_db, $datos_personales);
        
        // 2. Procesar archivos académicos y guardar en academico_db
        $archivos_academicos = procesarArchivosAcademicos($_FILES, $_POST, $usuario_id);
        guardarArchivosAcademicos($academico_db, $archivos_academicos);
        
        // Redirigir a página de éxito
        header('Location: /Applicant-System/views/user/confirm.php');
        exit;
    } catch (Exception $e) {
        // Manejar errores
        die("Error al procesar el formulario: " . $e->getMessage());
    }
}

// Funciones de procesamiento (actualizadas)
function procesarDatosPersonales($post) {
    return [
        'nombres' => $post['nombre'] ?? '',
        'apellidos' => $post['apellido'] ?? '',
        'nacionalidad' => $post['nacionalidad'] ?? '',
        'dia_nacimiento' => isset($post['dia_nacimiento']) ? (int)$post['dia_nacimiento'] : 0,
        'mes_nacimiento' => isset($post['mes_nacimiento']) ? (int)$post['mes_nacimiento'] : 0,
        'ano_nacimiento' => isset($post['ano_nacimiento']) ? (int)$post['ano_nacimiento'] : 0,
        'estado_civil' => $post['estado_civil'] ?? '',
        'tipo_documento' => $post['tipo_documento'] ?? '',
        'numero_documento' => $post['numero_documento'] ?? '',
        'genero' => $post['genero'] ?? '',
        'movilidad_propia' => isset($post['movilidad_propia']) ? 1 : 0,
        'tiene_licencia' => isset($post['tiene_licencia']) ? 1 : 0,
        'licencias' => isset($post['licencia']) ? implode(',', $post['licencia']) : '',
        'telefono_celular' => $post['telefono_celular'] ?? '',
        'otro_telefono' => $post['otro_telefono'] ?? null,
        'pais' => $post['pais'] ?? 'Panamá',
        'provincia' => $post['provincia'] ?? '',
        'distrito' => $post['distrito'] ?? '',
        'direccion' => $post['direccion'] ?? '',
        'email' => $post['email'] ?? ''
    ];
}

function guardarDatosPersonales($db, $datos) {
    $stmt = $db->prepare("INSERT INTO candidatos (
        nombres, apellidos, email, nacionalidad, 
        dia_nacimiento, mes_nacimiento, ano_nacimiento, 
        estado_civil, tipo_documento, numero_documento, genero, 
        movilidad_propia, tiene_licencia, tipos_licencia, 
        telefono_celular, otro_telefono, pais, provincia, distrito, direccion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $datos['nombres'], $datos['apellidos'], $datos['email'], $datos['nacionalidad'],
        $datos['dia_nacimiento'], $datos['mes_nacimiento'], $datos['ano_nacimiento'],
        $datos['estado_civil'], $datos['tipo_documento'], $datos['numero_documento'], $datos['genero'],
        $datos['movilidad_propia'], $datos['tiene_licencia'], $datos['licencias'],
        $datos['telefono_celular'], $datos['otro_telefono'], $datos['pais'], $datos['provincia'],
        $datos['distrito'], $datos['direccion']
    ]);

    $usuario_id = $db->lastInsertId();

    return $usuario_id;
}

function procesarArchivosAcademicos($files, $post, $usuario_id) {
    $categorias = [
        'doctorados' => 'doctorado',
        'maestrias' => 'maestria',
        'postgrados' => 'postgrado',
        'licenciaturas' => 'licenciatura',
        'tecnicos' => 'tecnico',
        'certificados' => 'certificado',
        'diplomados' => 'diplomado',
        'seminarios' => 'seminario',
        'cursos' => 'curso'
    ];
    
    $archivos_procesados = [];
    
    foreach ($categorias as $categoria_post => $categoria_db) {
        if (!isset($files[$categoria_post])) {
            continue;
        }
        
        // Reestructurar archivos
        $archivos = reestructurarArchivos($files[$categoria_post]);
        $nombres_documentos = $post[$categoria_post]['nombre'] ?? [];
        
        foreach ($archivos as $index => $archivo) {
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                error_log("Error en archivo {$archivo['name']}: Código {$archivo['error']}");
                continue;
            }
            
            try {
                if (validarArchivo($archivo)) {
                    $archivo_guardado = guardarArchivoEnDisco($archivo, $usuario_id, $categoria_post);
                    $nombre_doc = $nombres_documentos[$index] ?? 'Documento sin nombre';
                    
                    $archivos_procesados[] = [
                        'usuario_id' => $usuario_id,
                        'tipo_documento' => $categoria_db,
                        'nombre_documento' => $nombre_doc,
                        'nombre_archivo' => $archivo_guardado['nombre_archivo'],
                        'ruta_archivo' => $archivo_guardado['ruta_archivo'],
                        'fecha_subida' => date('Y-m-d H:i:s'),
                        'tamano_archivo' => $archivo['size']
                    ];
                }
            } catch (Exception $e) {
                error_log("Error procesando archivo {$archivo['name']}: " . $e->getMessage());
                continue;
            }
        }
    }
    
    return $archivos_procesados;
}

function reestructurarArchivos($archivos_categoria) {
    $resultado = [];
    
    // Verificar si es la estructura anidada especial
    if (isset($archivos_categoria['name']['archivo']) && is_array($archivos_categoria['name']['archivo'])) {
        foreach ($archivos_categoria['name']['archivo'] as $index => $name) {
            $resultado[] = [
                'name' => $archivos_categoria['name']['archivo'][$index],
                'type' => $archivos_categoria['type']['archivo'][$index],
                'tmp_name' => $archivos_categoria['tmp_name']['archivo'][$index],
                'error' => $archivos_categoria['error']['archivo'][$index],
                'size' => $archivos_categoria['size']['archivo'][$index]
            ];
        }
    } 
    // Estructura tradicional
    elseif (is_array($archivos_categoria['name'])) {
        foreach ($archivos_categoria['name'] as $index => $name) {
            $resultado[] = [
                'name' => $archivos_categoria['name'][$index],
                'type' => $archivos_categoria['type'][$index],
                'tmp_name' => $archivos_categoria['tmp_name'][$index],
                'error' => $archivos_categoria['error'][$index],
                'size' => $archivos_categoria['size'][$index]
            ];
        }
    }
    // Un solo archivo
    else {
        $resultado[] = $archivos_categoria;
    }
    
    return $resultado;
}

function validarArchivo($archivo) {
    // Tamaño máximo: 5MB (en bytes)
    $max_size = 5 * 1024 * 1024;
    
    // Extensiones permitidas
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    
    // Obtener extensión del archivo
    $file_extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    // Validar tamaño
    if ($archivo['size'] > $max_size) {
        throw new Exception("El archivo {$archivo['name']} excede el tamaño máximo permitido de 5MB");
    }
    
    // Validar extensión
    if (!in_array($file_extension, $allowed_extensions)) {
        throw new Exception("El archivo {$archivo['name']} tiene una extensión no permitida. Solo se aceptan PDF, DOC y DOCX");
    }
    
    return true;
}

function guardarArchivoEnDisco($archivo, $usuario_id, $categoria) {
    $directorio = "../uploads/{$usuario_id}/";
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    // Generar nombre único para el archivo
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_base = sanitizeFileName(pathinfo($archivo['name'], PATHINFO_FILENAME));
    $nombre_archivo = "{$categoria}_" . substr(md5(uniqid()), 0, 8) . "_{$nombre_base}.{$extension}";
    $ruta_completa = $directorio . $nombre_archivo;
    
    if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        return [
            'nombre_archivo' => $nombre_archivo,
            'ruta_archivo' => $ruta_completa
        ];
    }
    
    throw new Exception("No se pudo guardar el archivo {$archivo['name']}");
}

function sanitizeFileName($filename) {
    // Reemplazar caracteres especiales y espacios
    $filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $filename);
    // Eliminar múltiples guiones bajos
    $filename = preg_replace('/_+/', '_', $filename);
    return $filename;
}

function guardarArchivosAcademicos($db, $archivos) {
    $stmt = $db->prepare("INSERT INTO documentos_academicos (
        candidato_id, tipo_documento, nombre_documento, nombre_archivo, ruta_archivo, fecha_subida, tamano_archivo
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($archivos as $archivo) {
        if (!$stmt->execute([
            $archivo['usuario_id'],
            $archivo['tipo_documento'],
            $archivo['nombre_documento'],
            $archivo['nombre_archivo'],
            $archivo['ruta_archivo'],
            $archivo['fecha_subida'],
            $archivo['tamano_archivo']
        ])) {
            throw new Exception("Error al guardar documento académico en la base de datos");
        }
    }

    $stmt = null;
}
?>