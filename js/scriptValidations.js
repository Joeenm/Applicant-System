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
    // Campos de nombres y apellidos
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
        // Función para validar cédula (números y hasta 2 guiones)
        function validarCedula(input, tecla) {
            const valorActual = input.value;
            const cantidadGuiones = (valorActual.match(/-/g) || []).length;
            
            // Permitir solo números o hasta 2 guiones
            if (!/^[0-9]$/.test(tecla) && !(tecla === '-' && cantidadGuiones < 2)) {
                return false;
            }
            return true;
        }
        
        // Función para validar pasaporte (letras, números y 1 guión)
        function validarPasaporte(input, tecla) {
            const valorActual = input.value;
            const cantidadGuiones = (valorActual.match(/-/g) || []).length;
            
            // Permitir letras, números o un único guión
            if (!/^[a-zA-Z0-9]$/.test(tecla) && !(tecla === '-' && cantidadGuiones === 0)) {
                return false;
            }
            return true;
        }
        
        tipoDocumento.addEventListener('change', function() {
            numeroDocumento.value = '';
            if (this.value === 'cedula') {
                numeroDocumento.placeholder = 'Ej: 00-0000-0000';
            } else if (this.value === 'pasaporte') {
                numeroDocumento.placeholder = 'Ej: AB123456';
            }
        });
        
        numeroDocumento.addEventListener('keypress', function(e) {
            const tipo = tipoDocumento.value;
            const tecla = e.key;
            let valido = true;
            
            if (tipo === 'cedula') {
                valido = validarCedula(this, tecla);
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
                let valor = e.target.value;
                if (tipo === 'cedula') {
                    // Para cédula: solo números y máximo 2 guiones
                    valor = valor.replace(/[^0-9-]/g, '');
                    const guiones = (valor.match(/-/g) || []).length;
                    if (guiones > 2) {
                        const partes = valor.split('-');
                        valor = partes[0] + '-' + partes[1] + '-' + partes.slice(2).join('');
                    }
                } else if (tipo === 'pasaporte') {
                    // Para pasaporte: letras, números y máximo 1 guión
                    valor = valor.replace(/[^a-zA-Z0-9-]/g, '');
                    const guiones = (valor.match(/-/g) || []).length;
                    if (guiones > 1) {
                        const partes = valor.split('-');
                        valor = partes[0] + '-' + partes.slice(1).join('');
                    }
                    valor = valor.toUpperCase();
                }
                e.target.value = valor;
            }, 0);
        });
        
        numeroDocumento.addEventListener('blur', function() {
            const tipo = tipoDocumento.value;
            let valor = this.value;
            
            if (tipo === 'cedula') {
                // Eliminar caracteres no permitidos y limitar a 2 guiones
                valor = valor.replace(/[^0-9-]/g, '');
                const guiones = (valor.match(/-/g) || []).length;
                if (guiones > 2) {
                    const partes = valor.split('-');
                    valor = partes[0] + '-' + partes[1] + '-' + partes.slice(2).join('');
                }
                // Eliminar guiones al inicio/final
                valor = valor.replace(/^-+/, '').replace(/-+$/, '');
            } else if (tipo === 'pasaporte') {
                // Eliminar caracteres no permitidos y limitar a 1 guión
                valor = valor.replace(/[^a-zA-Z0-9-]/g, '');
                const guiones = (valor.match(/-/g) || []).length;
                if (guiones > 1) {
                    const partes = valor.split('-');
                    valor = partes[0] + '-' + partes.slice(1).join('');
                }
                // Eliminar guiones al inicio/final y convertir a mayúsculas
                valor = valor.replace(/^-+/, '').replace(/-+$/, '').toUpperCase();
            }
            
            this.value = valor;
        });
    }
});