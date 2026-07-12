document.addEventListener('DOMContentLoaded', () => {
    const selectProvincia = document.querySelector('#province');
    const selectLocalidad = document.querySelector('#locality');

    if (!selectProvincia || !selectLocalidad) {
        return;
    }

    async function cargarLocalidades(provincia, localidadActual = '') {
        selectLocalidad.innerHTML = '<option value="">Cargando...</option>';

        if (!provincia) {
            selectLocalidad.innerHTML = '<option value="">Seleccioná primero una Provincia</option>';
            return;
        }

        try {
            const respuesta = await fetch(`/api/clientes/localidades?provincia=${encodeURIComponent(provincia)}`);
            const localidades = await respuesta.json();

            selectLocalidad.innerHTML = '<option value="">Seleccioná una Localidad</option>';

            localidades.forEach(localidad => {
                const option = document.createElement('option');
                option.value = localidad;
                option.textContent = localidad;
                if (localidad === localidadActual) {
                    option.selected = true;
                }
                selectLocalidad.appendChild(option);
            });

        } catch (error) {
            console.error('Error al cargar localidades:', error);
            selectLocalidad.innerHTML = '<option value="">Error al cargar localidades</option>';
        }
    }

    // Al cambiar la provincia, recargar localidades (sin conservar la actual)
    selectProvincia.addEventListener('change', function() {
        cargarLocalidades(this.value);
    });

    // Al cargar la página (modo edición), si ya hay provincia elegida, precargar localidades
    const localidadActual = selectLocalidad.getAttribute('data-actual');
    if (selectProvincia.value) {
        cargarLocalidades(selectProvincia.value, localidadActual);
    }
});