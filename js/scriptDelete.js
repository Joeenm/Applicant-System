function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción! Se eliminarán todos los datos y documentos del postulante.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarPostulante(id);
        }
    });
}

function eliminarPostulante(id) {
    // Mostrar carga
    Swal.fire({
        title: 'Eliminando...',
        html: 'Por favor espera mientras eliminamos los datos.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/Applicant-System/php/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${encodeURIComponent(id)}`
    })
    .then(async response => {
        const text = await response.text();
        
        try {
            // Intenta parsear como JSON
            const data = JSON.parse(text);
            
            if (data.success) {
                Swal.fire(
                    '¡Eliminado!',
                    'El postulante y sus documentos han sido eliminados.',
                    'success'
                ).then(() => {
                    window.location.href = '../admin/dashboard.php';
                });
            } else {
                Swal.fire(
                    'Error',
                    data.message || 'Ocurrió un error al eliminar el postulante.',
                    'error'
                );
            }
        } catch (e) {
            // Si no es JSON válido, muestra el error del servidor
            console.error('Respuesta del servidor:', text);
            throw new Error('El servidor respondió con: ' + text.substring(0, 100));
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        Swal.fire(
            'Error',
            'Error del servidor: ' + error.message,
            'error'
        );
    });
}