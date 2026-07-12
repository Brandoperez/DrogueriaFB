//LISTADO DE PEDIDOS

const btnBuscarPedidos = document.querySelector('#btnBuscarPedidos');
const btnLimpiarFiltros = document.querySelector('#btnLimpiarFiltros');

const filtrosFecha = document.querySelector('#fecha');
const filtrosVendedor = document.querySelector('#vendedor');
const filtrosCliente = document.querySelector('#cliente');
const filtrosEstado = document.querySelector('#estado');
const filtrosBuscar = document.querySelector('#buscar');

const tablaBody = document.querySelector('.pedidos__tabla-body');

if(btnBuscarPedidos && btnLimpiarFiltros){
    btnBuscarPedidos.addEventListener('click', buscarPedidos);
    btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
}
    async function buscarPedidos(){
        const filtros = {
            fecha: filtrosFecha.value,
            vendedor: filtrosVendedor.value,
            cliente: filtrosCliente.value,
            estado: filtrosEstado.value,
            buscar: filtrosBuscar.value.trim()
        }
        const params = new URLSearchParams(filtros);

        try {
            const respuesta = await fetch(`/api/pedidos/buscar?${params.toString()}`);
            const pedidos = await respuesta.json();

            renderizarPedidos(pedidos);

        } catch(error) {
            console.error('Error al buscar pedidos:', error);
        }
    }

    function limpiarFiltros(){
        filtrosFecha.value = '';
        filtrosCliente.value = '';
        filtrosVendedor.value = '';
        filtrosEstado.value = '';
        filtrosBuscar.value = '';

        buscarPedidos();
    }

    function renderizarPedidos(pedidos){
        if(!pedidos || pedidos.length === 0){
            tablaBody.innerHTML = '<p class="tabla__vacia">No hay pedidos registrados</p>';
            return;
        }

        tablaBody.innerHTML = pedidos.map(pedido => {
            const numero = String(pedido.id).padStart(6, '0');
            const fecha = new Date(pedido.created_at).toLocaleString('es-AR', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
            });

            const total = Number(pedido.total).toLocaleString('es-AR', { minimumFractionDigits: 2 });

                let acciones = `
                    <a href="/admin/pedidos/detalle?id=${pedido.id}">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                `;

                if(pedido.status === 'pending'){
            acciones += `
                        <a href="#" class="tabla__accion js-cambiar-estado-pedido" data-id="${pedido.id}" data-estado="confirmed" title="Confirmar pedido"> <i class="fa-solid fa-circle-arrow-right"></i></a>

                        <a href="#" class="tabla__accion js-cancelar-pedido" data-id="${pedido.id}" data-estado="cancelled" title="Cancelar pedido"> <i class="fa-solid fa-trash"></i></a>
                    `;
                } else if(pedido.status === 'confirmed'){
                    acciones += `
                        <a href="#" class="tabla__accion js-cambiar-estado-pedido" data-id="${pedido.id}" data-estado="completed" title="Completar pedido"> <i class="fa-solid fa-check"></i></a>

                        <a href="#" class="tabla__accion js-cancelar-pedido" data-id="${pedido.id}" data-estado="cancelled" title="Cancelar pedido"> <i class="fa-solid fa-trash"></i></a>`;
                }

                const claseEstado =
                    pedido.status === 'pending' ? 'estado--proceso' :
                    pedido.status === 'completed' ? 'estado--completado' :
                    pedido.status === 'cancelled' ? 'estado--cancelado' :
                    'estado--confirmado';
            return `
            <div class="tabla tabla__fila--listado-pe pedidos__fila">
                <span class="pedidos__pedido-id">#${numero}</span>
                <span>${fecha}</span>
                <span>${pedido.client_name ?? 'Sin cliente'}</span>
                <span>${pedido.seller_name ?? 'Sin vendedor'}</span>
                <div class="estado ${claseEstado}">
                    <span>${pedido.status}</span>
                </div>
                <span>$${total}</span>
                <div class="tabla__acciones">
                    ${acciones}
                </div>
            </div>
        `;
        }).join('');
    }

//CAMBIO DE ESTADO
const botonesEstado = document.querySelectorAll('.js-cambiar-estado-pedido');
const botonesCancelar = document.querySelectorAll('.js-cancelar-pedido');

if(tablaBody){
    tablaBody.addEventListener('click', async function(e){
        const botonEstado = e.target.closest('.js-cambiar-estado-pedido');

        if(!botonEstado){
            return;
        }

        e.preventDefault();

        const id = botonEstado.dataset.id;
        const estado = botonEstado.dataset.estado;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('estado', estado);

        const respuesta = await fetch('/api/pedidos/estado', {
            method: 'POST',
            body: formData
        });

        const resultado = await respuesta.json();

        if(resultado.resultado){
            const fila = botonEstado.closest('.pedidos__fila');
            const estadoDiv = fila.querySelector('.estado');
            const estadoTexto = estadoDiv.querySelector('span');

            estadoDiv.classList.remove(
                'estado--proceso',
                'estado--confirmado',
                'estado--completado'
            );

            if(resultado.estado === 'confirmed'){
                estadoTexto.textContent = 'Confirmado';
                estadoDiv.classList.add('estado--confirmado');

                botonEstado.dataset.estado = 'completed';
                botonEstado.title = 'Completar pedido';
                botonEstado.innerHTML = '<i class="fa-solid fa-check"></i>';

            } else if(resultado.estado === 'completed'){
                estadoTexto.textContent = 'Completado';
                estadoDiv.classList.add('estado--completado');

                botonEstado.remove();
            }
        }
    });
}

if(botonesCancelar.length > 0){
    botonesCancelar.forEach(boton => {
        boton.addEventListener('click', async function(e) {
            e.preventDefault();
            const confirmar = await Swal.fire({
                title: '¿Cancelar pedido?',
                text: 'Esta acción cambiará el pedido a cancelado.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, volver'
            });

            if(!confirmar.isConfirmed){
                return;
            }

            const id = this.dataset.id;
            const estado = this.dataset.estado;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);

            const respuesta = await fetch('/api/pedidos/estado', {
                method: 'POST',
                body: formData
            })

            const resultado = await respuesta.json();

            if(resultado.resultado){
                const fila = this.closest('.pedidos__fila');
                const estadoDiv = fila.querySelector('.estado');
                const estadoTexto = estadoDiv.querySelector('span');

                estadoTexto.textContent = 'Cancelado';

                estadoDiv.classList.remove(
                    'estado--proceso',
                    'estado--confirmado',
                    'estado--completado'
                );

                estadoDiv.classList.add('estado--cancelado');

                const acciones = fila.querySelector('.tabla__acciones');
                const botonesAccion = acciones.querySelectorAll('.js-cambiar-estado-pedido, .js-cancelar-pedido');

                botonesAccion.forEach(boton => boton.remove());

                Swal.fire({
                    title: 'Pedido cancelado',
                    text: 'El pedido fue marcado como cancelado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        })
    })
}