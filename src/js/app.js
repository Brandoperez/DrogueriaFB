const alertas = document.querySelectorAll('.alerta--exito');

if(alertas.length > 0){
    setTimeout(() => {
        alertas.forEach(alerta => {
            alerta.style.transition = 'opacity 500ms ease';
            alerta.style.opacity = '0';
            setTimeout(() => { alerta.remove(); }, 500);
        });
    }, 3000);
}

// BOTONES DEL SIDEBAR
const toggleSidebar = document.querySelectorAll('.sidebar__toggle');
if(toggleSidebar.length > 0){
    toggleSidebar.forEach(toggle => {
        toggle.addEventListener('click', function(e){
            e.preventDefault();
            const grupo = this.closest('.sidebar__grupo');
            grupo.classList.toggle('sidebar__grupo--abierto');
        });          
    });
}

// BUSCADOR DE USUARIOS
const buscador = document.querySelector('#buscarUsuarios');
const filas = document.querySelectorAll('.usuario-fila');

if(buscador){
    buscador.addEventListener('input', e => {
        const texto = e.target.value.toLowerCase();
        filas.forEach(fila => {
            const contenido = fila.textContent.toLowerCase();
            fila.style.display = contenido.includes(texto) ? 'grid' : 'none';
        });
    });
}

// BUSCADOR DE CLIENTES
const btnBuscar  = document.getElementById('btnBuscarClientes'); // ← sin #
const btnLimpiar = document.getElementById('btnLimpiarFiltros'); // ← sin #

if(btnBuscar && btnLimpiar){ // ← verificar que existan antes de usarlos
    btnBuscar.addEventListener('click', buscarClientes);
    btnLimpiar.addEventListener('click', limpiarFiltros); // ← L minúscula

    async function buscarClientes() {
        const datos = {
            buscar:    document.getElementById('buscar').value.trim(),
            vendedor:  document.getElementById('vendedor').value,
            estado:    document.getElementById('estado').value,
            localidad: document.getElementById('localidad').value
        };

        try {
            const respuesta = await fetch('/api/clientes/buscar', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(datos)
            });
            const clientes = await respuesta.json();
            renderizarClientes(clientes);
        } catch (error) {
            console.error('Error al buscar clientes:', error);
        }
    }

    function limpiarFiltros() {
        document.getElementById('buscar').value    = '';
        document.getElementById('vendedor').value  = '';
        document.getElementById('estado').value    = '';
        document.getElementById('localidad').value = '';
        buscarClientes();
    }

    function renderizarClientes(clientes) {
        const tabla = document.querySelector('.clientes__tabla');
        tabla.querySelectorAll('.fila').forEach(f => f.remove());
        tabla.querySelector('.tabla__vacia')?.remove();

        if(clientes.length === 0){
            const vacia = document.createElement('p');
            vacia.className = 'tabla__vacia';
            vacia.textContent = 'No se encontraron clientes';
            tabla.appendChild(vacia);
            return;
        }

        clientes.forEach(c => {
            const fila = document.createElement('div');
            fila.className = 'fila grid-7';
            fila.innerHTML = `
                <span class="clientes__nombre">${c.name}</span>
                <span>${c.cuit}</span>
                <span>${c.province ?? '-'}</span>
                <span>${c.seller_name ?? 'Sin vendedor'}</span>
                <span>${c.price_list_name ?? 'Sin lista'}</span>
                <a href="#" class="estado js-cambiar-estado ${c.active ? 'estado--completado' : 'estado--cancelado'}"
                   data-id="${c.id}" data-estado="${c.active}">
                   ${c.active ? 'Activo' : 'Inactivo'}
                </a>
                <div class="acciones--tabla">
                    <a href="/admin/clientes/ver?id=${c.id}" class="acciones__editar"><i class="fa-solid fa-eye"></i></a>
                    <a href="/admin/clientes/editar?id=${c.id}" class="acciones__editar"><i class="fa-solid fa-pen"></i></a>
                    <a href="/admin/clientes/eliminar?id=${c.id}" class="acciones__eliminar js-eliminar"><i class="fa-solid fa-trash"></i></a>
                </div>
            `;
            tabla.appendChild(fila);
        });
    }
}

// ESTADOS — delegación de eventos para filas estáticas Y dinámicas
document.addEventListener('click', async function(e) {
    const el = e.target.closest('.js-cambiar-estado');
    if(!el) return;

    e.preventDefault();
    const id  = el.dataset.id;
    const url = `/api/clientes/estado?id=${id}`;

    try {
        const respuesta = await fetch(url);
        const data = await respuesta.json();

        if(data.resultado){
            const activo = data.nuevo_estado;
            el.textContent = activo ? 'Activo' : 'Inactivo';
            el.classList.toggle('estado--completado', activo);
            el.classList.toggle('estado--cancelado', !activo);
            el.dataset.estado = activo;
        }
    } catch(error) {
        console.error('Error:', error);
    }
});

//AGREGAR ARCHIVO A LA VISTA
const inputArchivo = document.querySelector('#archivo');
const textoArchivo = document.querySelector('.excel__archivo--seleccionado');

    inputArchivo.addEventListener('change', () => {
        if(inputArchivo.files.length > 0){
            textoArchivo.textContent = inputArchivo.files[0].name;
        }else{
            textoArchivo.textContent = '';
        }
    })