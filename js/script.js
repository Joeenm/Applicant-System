// Mostrar/ocultar tipos de licencia según checkbox
document.getElementById('tiene_licencia').addEventListener('change', function() {
    document.getElementById('tipos_licencia').classList.toggle('hidden', !this.checked);
});
        
// Función para agregar campos de documentos dinámicamente
function addDocumentField(category) {
    const container = document.getElementById(`${category}-container`);
    const newField = document.createElement('div');
    newField.className = 'document-field group relative';
    newField.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nombre del documento*</label>
                <input type="text" name="${category}[nombre][]" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="Ej: ${getPlaceholder(category)}">
            </div>
            <div class="flex items-end">
                <input type="file" name="${category}[archivo][]" accept=".pdf,.doc,.docx" required
                    class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>
        <button type="button" onclick="removeDocumentField(this)" class="hidden group-hover:block absolute -right-5 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </button>
    `;
    container.appendChild(newField);

    // Validar tamaño del archivo al seleccionarlo
    const fileInput = newField.querySelector('input[type="file"]');
    fileInput.addEventListener('change', function(e) {
        const maxSize = 5 * 1024 * 1024; // 5MB en bytes
        const file = e.target.files[0];
        
        if (file && file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Archivo demasiado grande',
                text: `El archivo "${file.name}" excede el límite de 5MB. Por favor, elige un archivo más pequeño.`,
                confirmButtonColor: '#3085d6',
            });
            e.target.value = ''; // Limpiar el input
        }
    });
}

// Función para obtener texto de placeholder según la categoría
function getPlaceholder(category) {
    const placeholders = {
        'doctorados': 'Doctorado en...',
        'maestrias': 'Maestría en...',
        'postgrados': 'Postgrado en...',
        'licenciaturas': 'Licenciatura en...',
        'tecnicos': 'Técnico en...',
        'certificados': 'Certificado de...',
        'diplomados': 'Diplomado en...',
        'seminarios': 'Seminario sobre...',
        'cursos': 'Curso de...'
    };
    return placeholders[category] || 'Nombre del documento';
}

// Función para eliminar campos de documentos
function removeDocumentField(button) {
    const field = button.closest('.document-field');
    
    field.remove();
}

const nacionalidades = [
    "Afganistán", "Albania", "Alemania", "Argentina", "Australia",
    "Brasil", "Canadá", "Chile", "China", "Colombia", "Costa Rica",
    "Cuba", "Ecuador", "Egipto", "El Salvador", "España",
    "Estados Unidos", "Francia", "Guatemala", "Honduras", "India",
    "Italia", "Japón", "México", "Nicaragua", "Panamá", "Paraguay",
    "Perú", "Portugal", "Reino Unido", "República Dominicana",
    "Uruguay", "Venezuela", "Zimbabue"
];

const select = document.getElementById("nacionalidad");
        
nacionalidades.forEach(pais => {
    const option = document.createElement("option");
    option.value = pais;
    option.textContent = pais;
    select.appendChild(option);
});

const diaSelect = document.getElementById('dia_nacimiento');
const mesSelect = document.getElementById('mes_nacimiento');
const anoSelect = document.getElementById('ano_nacimiento');

function ajustarDias() {
    const year = parseInt(anoSelect.value);
    const month = parseInt(mesSelect.value);
    const diasActuales = diaSelect.options.length - 1;
    let diasEnMes = 31;

    if (year && month) {
        diasEnMes = new Date(year, month, 0).getDate();
    } else if (month) {

        diasEnMes = new Date(2020, month, 0).getDate();
    }

    if (diasEnMes > diasActuales) {
        for (let i = diasActuales + 1; i <= diasEnMes; i++) {
            let day = i < 10 ? '0' + i : i;
            let option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            diaSelect.appendChild(option);
        }
    } else if (diasEnMes < diasActuales) {
        for (let i = diasActuales; i > diasEnMes; i--) {
            diaSelect.remove(i);
        }
    }

    if (diaSelect.value) {
        let diaValor = parseInt(diaSelect.value);
        if (diaValor > diasEnMes) {
            diaSelect.value = diasEnMes < 10 ? '0' + diasEnMes : diasEnMes;
        }
    }
}

mesSelect.addEventListener('change', ajustarDias);
anoSelect.addEventListener('change', ajustarDias)

ajustarDias();

document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Validar si hay archivos cargados
    const archivos = form.querySelectorAll('input[type="file"]');
    let archivosVacios = true;

    archivos.forEach(input => {
        if (input.files.length > 0) {
            archivosVacios = false;
        }
    });

    // Si no hay archivos, preguntar con SweetAlert
    if (archivosVacios) {
        const confirmacion = await Swal.fire({
            title: '¿No subiste archivos?',
            text: 'No se detectaron archivos válidos. ¿Deseas continuar de todas formas?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, cancelar'
        });

        if (!confirmacion.isConfirmed) {
            Swal.fire('Cancelado', 'Podrás subir los archivos antes de enviar el formulario nuevamente.', 'info');
            return;
        }
    }

    // Mostrar mensaje de carga mientras se verifica duplicados
    Swal.fire({
        title: 'Validando datos...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const response = await fetch('/Applicant-System/php/duplication.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.valido) {
            // Si no hay duplicados, enviar el formulario
            form.submit();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Datos duplicados',
                html: result.errores.join('<br>'),
                confirmButtonColor: '#3085d6'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar al servidor. Intenta nuevamente.',
            confirmButtonColor: '#3085d6'
        });
    }
});