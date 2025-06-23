document.addEventListener('DOMContentLoaded', function() {
    // Configuración de los selects a cargar
    const selectsConfig = [
        {
            selectId: 'provincia',
            targetSelectId: 'distrito',
            jsonPath: '/Applicant-System/json/distritos-panama.json',
            defaultOption: 'Seleccione distrito',
            isDependent: true,
            processor: function(data, parentValue) {
                if (parentValue && data[parentValue]) {
                    return data[parentValue].map(distrito => ({
                        value: distrito,
                        text: distrito
                    }));
                }
                return [];
            }
        },
        {
            selectId: 'nacionalidad',
            jsonPath: '/Applicant-System/json/paises.json',
            defaultOption: 'Seleccione su nacionalidad',
            processor: function(data) {
                return data.sort((a, b) => a.localeCompare(b))
                          .map(nacionalidad => ({
                              value: nacionalidad,
                              text: nacionalidad
                          }));
            }
        }
    ];

    // Cargar cada configuración
    selectsConfig.forEach(config => {
        const mainSelect = document.getElementById(config.selectId);
        const targetSelect = config.targetSelectId ? document.getElementById(config.targetSelectId) : null;

        if (!mainSelect) return;

        // Cargar datos JSON
        fetch(config.jsonPath)
            .then(response => {
                if (!response.ok) throw new Error(`Error al cargar ${config.selectId}`);
                return response.json();
            })
            .then(data => {
                // Procesar datos para select principal
                if (!config.isDependent) {
                    const options = config.processor(data);
                    mainSelect.innerHTML = `<option value="">${config.defaultOption}</option>`;
                    
                    options.forEach(option => {
                        const optElement = document.createElement('option');
                        optElement.value = option.value;
                        optElement.textContent = option.text;
                        mainSelect.appendChild(optElement);
                    });
                }

                // Configurar evento change para selects dependientes
                if (config.isDependent && targetSelect) {
                    targetSelect.disabled = true;
                    targetSelect.innerHTML = `<option value="">${config.defaultOption}</option>`;

                    mainSelect.addEventListener('change', function() {
                        const parentValue = this.value;
                        const options = config.processor(data, parentValue);
                        
                        targetSelect.innerHTML = `<option value="">${config.defaultOption}</option>`;
                        targetSelect.disabled = !parentValue;

                        options.forEach(option => {
                            const optElement = document.createElement('option');
                            optElement.value = option.value;
                            optElement.textContent = option.text;
                            targetSelect.appendChild(optElement);
                        });
                    });
                }
            })
            .catch(error => {
                console.error(`Error cargando ${config.selectId}:`, error);
                mainSelect.innerHTML = `<option value="">Error cargando datos</option>`;
                if (targetSelect) {
                    targetSelect.innerHTML = `<option value="">Error cargando datos</option>`;
                }
            });
    });
});