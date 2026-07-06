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
            return `
            <div class="tabla tabla__fila--listado-pe pedidos__fila">
                <span class="pedidos__pedido-id">#${numero}</span>
                <span>${fecha}</span>
                <span>${pedido.client_name ?? 'Sin cliente'}</span>
                <span>${pedido.seller_name ?? 'Sin vendedor'}</span>
                <div class="estado estado--proceso">
                <span>${pedido.status}</span>
                </div>
                <span>$${total}</span>
                <div class="tabla__acciones-tabla">
                    <a href="/admin/pedidos/detalle?id=<?php echo $pedido['id']; ?>" class="tabla__editar"><i class="fa-solid fa-eye"></i></a>
                </div>
            </div>
        `;
        }).join('');
    }

//CAMBIO DE ESTADO
const botonesEstado = document.querySelectorAll('.js-cambiar-estado-pedido');
const botonesCancelar = document.querySelectorAll('.js-cancelar-pedido');

if(botonesEstado.length > 0){
    botonesEstado.forEach(boton => {
        boton.addEventListener('click', async function(e) {
            e.preventDefault();

            const id = this.dataset.id;
            const estado = this.dataset.estado;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);
            
            const respuesta = await fetch('/api/pedidos/estado', {
                method: 'POST',
                body: formData
            });

            const resultado = await respuesta.json();
            console.log(resultado)
            if(resultado.resultado){

    const fila = this.closest('.pedidos__fila');
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

                    this.dataset.estado = 'completed';
                    this.title = 'Completar pedido';
                    this.innerHTML = '<i class="fa-solid fa-check"></i>';

                }else if(resultado.estado === 'completed'){
                    estadoTexto.textContent = 'Completado';
                    estadoDiv.classList.add('estado--completado');

                    this.remove();
                }

            }
        })
    })
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