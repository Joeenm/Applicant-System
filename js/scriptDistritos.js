document.addEventListener('DOMContentLoaded', function() {
    const provinciaSelect = document.getElementById('provincia');
    const distritoSelect = document.getElementById('distrito');
    
    // Cargar datos de distritos
    fetch('/Applicant-System/json/distritos-panama.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los distritos');
            }
            return response.json();
        })
        .then(data => {
            // Evento cuando cambia la provincia
            provinciaSelect.addEventListener('change', function() {
                const provincia = this.value;
                distritoSelect.innerHTML = '<option value="">Seleccione distrito</option>';
                
                if (provincia && data[provincia]) {
                    data[provincia].forEach(distrito => {
                        const option = document.createElement('option');
                        option.value = distrito;
                        option.textContent = distrito;
                        distritoSelect.appendChild(option);
                    });
                }
                
                distritoSelect.disabled = !provincia;
            });
            
            // Disable distrito select inicialmente
            distritoSelect.disabled = true;
        })
        .catch(error => {
            console.error('Error cargando distritos:', error);
            // Mostrar mensaje de error al usuario si lo deseas
            distritoSelect.innerHTML = '<option value="">Error cargando distritos</option>';
        });
});