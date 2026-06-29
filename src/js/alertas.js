document.addEventListener('DOMContentLoaded', () => {
    //ELIMINAR
    const enlacesEliminar = document.querySelectorAll('.js-eliminar');
    enlacesEliminar.forEach(enlace => {
        enlace.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.getAttribute('href');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este registro será eliminado permanentemente",
                icon: 'warning',
                with: '700px',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });

        });
    });

    //EDITAR
    const enlaceEditar = document.querySelectorAll('.js-editar');
    enlaceEditar.forEach(enlace => {
        enlace.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.getAttribute('href');

            Swal.fire({
                title: '¡Actualizado!',
                text: 'Los cambios fueron guardados correctamente.',
                icon: 'success',
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });

        });
    });

     // ALERTA GLOBAL (IMPORT, CRUD, LOGIN, ETC)
    // =========================
    if (window.ALERTA) {

        const { tipo, mensaje } = window.ALERTA;

        let titulo = '';

        if (tipo === 'success') {
            titulo = 'Éxito';
        } else if (tipo === 'warning') {
            titulo = 'Atención';
        } else if (tipo === 'error') {
            titulo = 'Error';
        } else {
            titulo = 'Información';
        }

        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
            confirmButtonText: 'OK'
        });
    }
});