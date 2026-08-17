//LISTADO DE PEDIDOS - PORTAL CLIENTE
(function () {
    // Si existe #vendedor, estamos en el listado de admin (lo maneja pedidos.js)
    if (document.querySelector('#vendedor')) return;

    const btnBuscarPedidos = document.querySelector('#btnBuscarPedidos');
    const btnLimpiarFiltros = document.querySelector('#btnLimpiarFiltros');

    const filtrosFecha = document.querySelector('#fecha');
    const filtrosEstado = document.querySelector('#estado');
    const filtrosBuscar = document.querySelector('#buscar');

    const tablaBody = document.querySelector('.pedidos__tabla-body');

    if (!btnBuscarPedidos || !btnLimpiarFiltros || !tablaBody) return;

    btnBuscarPedidos.addEventListener('click', buscarPedidosCliente);
    btnLimpiarFiltros.addEventListener('click', limpiarFiltrosCliente);

    async function buscarPedidosCliente() {
        const filtros = {
            fecha: filtrosFecha.value,
            estado: filtrosEstado.value,
            buscar: filtrosBuscar.value.trim()
        };
        const params = new URLSearchParams(filtros);

        try {
            const respuesta = await fetch(`/api/cliente/pedidos/buscar?${params.toString()}`);
            const pedidos = await respuesta.json();

            renderizarPedidosCliente(pedidos);
        } catch (error) {
            console.error('Error al buscar pedidos:', error);
        }
    }

    function limpiarFiltrosCliente() {
        filtrosFecha.value = '';
        filtrosEstado.value = '';
        filtrosBuscar.value = '';

        buscarPedidosCliente();
    }

    function renderizarPedidosCliente(pedidos) {
        if (!pedidos || pedidos.length === 0) {
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

            const claseEstado =
                pedido.status === 'procesado' ? 'estado--proceso' :
                pedido.status === 'completado' ? 'estado--completado' :
                pedido.status === 'cancelado' ? 'estado--cancelado' :
                'estado--nuevo';

            return `
            <div class="tabla tabla__fila--listado-pe pedidos__fila">
                <span class="pedidos__pedido-id">#${numero}</span>
                <span>${fecha}</span>
                <span>${pedido.seller_name ?? 'Sin vendedor'}</span>
                <div class="estado ${claseEstado}">
                    <span>${pedido.status}</span>
                </div>
                <span>$${total}</span>
                <div class="tabla__acciones">
                    <a href="/cliente/pedidos/detalle?id=${pedido.id}"><i class="fa-solid fa-eye"></i></a>
                </div>
            </div>
        `;
        }).join('');
    }
})();