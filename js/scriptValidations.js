// Validación para nombres, apellidos y distrito (solo letras)
function soloLetras(event) {
    const tecla = event.key;
    const permitidas = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']+$/;
    
    if (!permitidas.test(tecla)) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Validación para teléfonos (solo números y máximo 1 guión)
function validarTelefono(event) {
    const input = event.target;
    const tecla = event.key;
    const valorActual = input.value;
    const cantidadGuiones = (valorActual.match(/-/g) || []).length;
    
    // Permitir solo números o un único guión
    if (!/^[0-9]$/.test(tecla) && !(tecla === '-' && cantidadGuiones === 0)) {
        event.preventDefault();
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Campos de nombres, apellidos y distrito
    document.getElementById('nombre').addEventListener('keypress', soloLetras);
    document.getElementById('apellido').addEventListener('keypress', soloLetras);
    document.getElementById('distrito').addEventListener('keypress', soloLetras);

    // Validación al pegar en nombres y apellidos
    document.getElementById('nombre').addEventListener('paste', function(e) {
        setTimeout(() => {
            e.target.value = e.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g, '');
        }, 0);
    });
    
    document.getElementById('apellido').addEventListener('paste', function(e) {
        setTimeout(() => {
            e.target.value = e.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g, '');
        }, 0);
    });
    
    // Campos de teléfono
    const telefonoCelular = document.getElementById('telefono_celular');
    const otroTelefono = document.getElementById('otro_telefono');
    
    // Función común para validar pegado en teléfonos
    function validarPegadoTelefono(e) {
        setTimeout(() => {
            const input = e.target;
            const valor = input.value;
            const cantidadGuiones = (valor.match(/-/g) || []).length;
            
            // Eliminar caracteres no permitidos y limitar a un guión
            let nuevoValor = valor.replace(/[^0-9-]/g, '');
            if (cantidadGuiones > 1) {
                // Mantener solo el primer guión
                const partes = nuevoValor.split('-');
                nuevoValor = partes[0] + '-' + partes.slice(1).join('');
            }
            input.value = nuevoValor;
        }, 0);
    }
    
    // Función común para validar blur en teléfonos
    function formatearTelefono(e) {
        const input = e.target;
        let valor = input.value;
        const cantidadGuiones = (valor.match(/-/g) || []).length;
        
        // Eliminar caracteres no permitidos
        valor = valor.replace(/[^0-9-]/g, '');
        
        // Limitar a un guión si hay más
        if (cantidadGuiones > 1) {
            const partes = valor.split('-');
            valor = partes[0] + '-' + partes.slice(1).join('');
        }
        
        // Eliminar guiones al inicio/final
        valor = valor.replace(/^-+/, '').replace(/-+$/, '');
        
        input.value = valor;
    }
    
    if (telefonoCelular) {
        telefonoCelular.addEventListener('keypress', validarTelefono);
        telefonoCelular.addEventListener('paste', validarPegadoTelefono);
        telefonoCelular.addEventListener('blur', formatearTelefono);
        telefonoCelular.placeholder = 'Ej: 6XXX-XXXX';
    }
    
    if (otroTelefono) {
        otroTelefono.addEventListener('keypress', validarTelefono);
        otroTelefono.addEventListener('paste', validarPegadoTelefono);
        otroTelefono.addEventListener('blur', formatearTelefono);
        otroTelefono.placeholder = 'Ej: XXX-XXXX';
    }
    
    // Validación para tipo de documento
    const tipoDocumento = document.getElementById('tipo_documento');
    const numeroDocumento = document.getElementById('numero_documento');
    
    if (tipoDocumento && numeroDocumento) {
        // Función para validar estructura de cédula en tiempo real (versión flexible)
        function validarEstructuraCedula(input, tecla) {
            const valor = input.value;
            const partes = valor.split('-');
            
            // Permitir teclas de control (backspace, delete, arrows, etc.)
            if (event.ctrlKey || event.altKey || event.metaKey || 
                tecla.length > 1 || 
                tecla === 'Backspace' || tecla === 'Delete' || 
                tecla === 'ArrowLeft' || tecla === 'ArrowRight' || 
                tecla === 'ArrowUp' || tecla === 'ArrowDown' || 
                tecla === 'Tab') {
                return true;
            }
            
            // Validar según la posición del cursor
            const posicionCursor = input.selectionStart;
            const textoHastaCursor = valor.substring(0, posicionCursor);
            const partesHastaCursor = textoHastaCursor.split('-');
            
            // Determinar en qué segmento está el cursor
            const segmentoActual = partesHastaCursor.length - 1;
            
            // Validar primer segmento (1-13)
            if (segmentoActual === 0) {
                // No permitir guión si no hay dígitos
                if (tecla === '-' && partesHastaCursor[0].length === 0) return false;
                
                // Validar que sea número y el valor no exceda 13
                if (/^[0-9]$/.test(tecla)) {
                    const nuevoValor = partesHastaCursor[0] + tecla;
                    if (parseInt(nuevoValor) > 13) return false;
                } else if (tecla !== '-') {
                    return false;
                }
            }
            // Validar segundo segmento (1-5 dígitos)
            else if (segmentoActual === 1) {
                // No permitir más de 5 dígitos
                if (partesHastaCursor[1] && partesHastaCursor[1].length >= 5 && tecla !== '-') return false;
                if (!/^[0-9]$/.test(tecla) && !(tecla === '-' && partesHastaCursor[1] && partesHastaCursor[1].length > 0)) return false;
            }
            // Validar tercer segmento (1-4 dígitos)
            else if (segmentoActual === 2) {
                // No permitir más de 4 dígitos
                if (partesHastaCursor[2] && partesHastaCursor[2].length >= 4) return false;
                if (!/^[0-9]$/.test(tecla)) return false;
            }
            // No permitir más de 2 guiones
            else if (segmentoActual > 2) {
                return false;
            }
            
            return true;
        }

        // Función para validar pegado de cédula (versión flexible)
        function validarPegadoCedula(valor) {
            // Eliminar caracteres no permitidos
            valor = valor.replace(/[^0-9-]/g, '');
            
            // Dividir en partes
            const partes = valor.split('-').filter(p => p !== '');
            
            // Validar y ajustar cada parte
            if (partes.length > 0) {
                // Primer segmento (1-13)
                partes[0] = partes[0].substring(0, 2);
                if (parseInt(partes[0]) > 13) {
                    partes[0] = '13';
                }
                
                // Segundo segmento (1-5 dígitos)
                if (partes.length > 1) {
                    partes[1] = partes[1].substring(0, 5);
                }
                
                // Tercer segmento (1-4 dígitos)
                if (partes.length > 2) {
                    partes[2] = partes[2].substring(0, 4);
                }
            }
            
            // Reconstruir el valor
            return partes.slice(0, 3).join('-');
        }

        // Función para formatear cédula al perder foco (versión flexible)
        function formatearCedula(valor) {
            // Eliminar caracteres no permitidos
            valor = valor.replace(/[^0-9-]/g, '');
            
            // Dividir en partes
            const partes = valor.split('-').filter(p => p !== '');
            
            // Validar estructura completa
            if (partes.length >= 1) {
                // Validar primer segmento (1-13)
                if (partes[0]) {
                    const primerSegmento = Math.min(parseInt(partes[0]) || 0, 13);
                    partes[0] = primerSegmento.toString();
                }
                
                // Validar segundo segmento (1-5 dígitos, no rellenamos)
                if (partes.length >= 2) {
                    partes[1] = partes[1].substring(0, 5);
                }
                
                // Validar tercer segmento (1-4 dígitos, no rellenamos)
                if (partes.length >= 3) {
                    partes[2] = partes[2].substring(0, 4);
                }
                
                // Reconstruir el valor
                valor = partes.slice(0, 3).join('-');
            }
            
            return valor;
        }
        
        tipoDocumento.addEventListener('change', function() {
            numeroDocumento.value = '';
            if (this.value === 'cedula') {
                numeroDocumento.placeholder = 'Ej: 00-0000-0000 (1-13, 1-5 dígitos, 1-4 dígitos)';
            } else if (this.value === 'pasaporte') {
                numeroDocumento.placeholder = 'Ej: AB000000';
            }
        });
        
        numeroDocumento.addEventListener('keypress', function(e) {
            const tipo = tipoDocumento.value;
            const tecla = e.key;
            let valido = true;
            
            if (tipo === 'cedula') {
                valido = validarEstructuraCedula(this, tecla);
            } else if (tipo === 'pasaporte') {
                valido = validarPasaporte(this, tecla);
            }
            
            if (!valido) {
                e.preventDefault();
            }
        });
        
        numeroDocumento.addEventListener('paste', function(e) {
            const tipo = tipoDocumento.value;
            setTimeout(() => {
                if (tipo === 'cedula') {
                    e.target.value = validarPegadoCedula(e.target.value);
                } else if (tipo === 'pasaporte') {
                    let valor = e.target.value;
                    valor = valor.replace(/[^a-zA-Z0-9-]/g, '');
                    const guiones = (valor.match(/-/g) || []).length;
                    if (guiones > 1) {
                        const partes = valor.split('-');
                        valor = partes[0] + '-' + partes.slice(1).join('');
                    }
                    e.target.value = valor.toUpperCase();
                }
            }, 0);
        });
        
        numeroDocumento.addEventListener('blur', function() {
            const tipo = tipoDocumento.value;
            
            if (tipo === 'cedula') {
                this.value = formatearCedula(this.value);
            } else if (tipo === 'pasaporte') {
                let valor = this.value;
                valor = valor.replace(/[^a-zA-Z0-9-]/g, '');
                const guiones = (valor.match(/-/g) || []).length;
                if (guiones > 1) {
                    const partes = valor.split('-');
                    valor = partes[0] + '-' + partes.slice(1).join('');
                }
                this.value = valor.replace(/^-+/, '').replace(/-+$/, '').toUpperCase();
            }
        });
    }
});