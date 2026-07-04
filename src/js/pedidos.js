//LISTADO DE PEDIDOS

const btnBuscarPedidos = document.querySelector('#btnBuscarPedidos');
const btnLimpiarFiltros = document.querySelector('#btnLimpiarFiltros');

const filtrosFecha = document.querySelector('#fecha');
const filtrosVendedor = document.querySelector('#vendedor');
const filtrosCliente = document.querySelector('#cliente');
const filtrosEstado = document.querySelector('#estado');
const filtrosBuscar = document.querySelector('#buscar');

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

            console.log(pedidos);

        } catch(error) {
            console.error('Error al buscar pedidos:', error);
        }
    }

    function limpiarFiltros(){
        console.log('Limpiando filtros');
    }