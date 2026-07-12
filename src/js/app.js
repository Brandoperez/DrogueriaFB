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
    tabla.querySelectorAll('.tabla__fila--clientes').forEach(f => f.remove());  // ✅ selector correcto
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
        fila.className = 'tabla tabla__fila--clientes';  // ✅ mismas clases que usa el PHP
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
//BUSCAR CLIENTES PARA PEDIDOS
const inputCliente = document.querySelector('#cliente');
const contenedorInputCliente = document.querySelector('#resultado-clientes');
let clienteSeleccionado = null;

if(window.clienteActual){
    clienteSeleccionado = window.clienteActual;
}

if(inputCliente && contenedorInputCliente){
    inputCliente.addEventListener('input', (e) =>{
        const termino = e.target.value;

        if(termino.length < 3){
            contenedorInputCliente.innerHTML = '';
            return;
        }

        fetch(`/api/pedidos/clientes?q=${encodeURIComponent(termino)}`)
            .then(respuesta => respuesta.json())
            .then(clientes => {
                contenedorInputCliente.innerHTML = '';

                clientes.forEach(cliente => {
                    const resultado = document.createElement('DIV');
                    resultado.classList.add('pedidos__resultado');
                    resultado.textContent = `${cliente.name} - ${cliente.cuit}`;

                    resultado.addEventListener('click', () => {
                        inputCliente.value = cliente.name;
                        document.querySelector('#cliente_id').value = cliente.id;
                        clienteSeleccionado = cliente;
                        contenedorInputCliente.innerHTML = '';
                    });

                    contenedorInputCliente.appendChild(resultado);
                });
            });
    });
}

//BUSCAR PRODUCTOS PEDIDOS
const inputProducto = document.querySelector('#producto');
const contenedorProductos = document.querySelector('#resultado-productos');
let productoSeleccionado = null;
const inputCantidad = document.querySelector('#cantidad');
const btnAgregarProductos = document.querySelector('.pedidos__agregar');

      if (inputProducto && contenedorProductos) {
        inputProducto.addEventListener('input', (e) => {
            const termino = e.target.value;

            if (termino.length < 2) {
                contenedorProductos.innerHTML = '';
                return;
            }
            if(!clienteSeleccionado){
                contenedorProductos.innerHTML = '<div class="pedidos__resultado">Primero seleccioná un cliente</div>';
                return;
            }

            const rutaProductos = window.RUTA_BUSCAR_PRODUCTOS || '/api/pedidos/productos';

            fetch(`${rutaProductos}?q=${encodeURIComponent(termino)}&price_list_id=${clienteSeleccionado.price_list_id ?? ''}`)
                .then(respuesta => respuesta.json())
                .then(productos => {
                    console.log(productos);
                    contenedorProductos.innerHTML = '';

                        productos.forEach(producto => {
                            const resultado = document.createElement('DIV');
                            resultado.classList.add('pedidos__resultado');

                            const sinStock = producto.stock <= 0;

                            resultado.textContent = sinStock
                                ? `${producto.code} - ${producto.description} (Sin stock)`
                                : `${producto.code} - ${producto.description}`;

                                if(sinStock){
                                    resultado.classList.add('pedidos__resultado--disabled');
                                }else{
                                    resultado.addEventListener('click', () => {
                                    inputProducto.value = producto.description;
                                    document.querySelector('#producto_id').value = producto.id;
                                    productoSeleccionado = producto;
                                    contenedorProductos.innerHTML = '';
                                });
                                }

                            contenedorProductos.appendChild(resultado);
                    });
                });
        });
    }

//TABLA DE PRODUCTOS
const tablaProductos = document.querySelector('.tabla__productos');
const filaVacia = document.querySelector('.tabla__vacia');
let productosPedidos = [];

    function renderizarTablaPedidos(){
        tablaProductos.querySelectorAll('.tabla__fila--pedidos').forEach(fila => fila.remove());
            if(productosPedidos.length === 0){
                tablaProductos.appendChild(filaVacia);
                return;
            }

            filaVacia.remove();

            productosPedidos.forEach((item, index) => {
                const fila = document.createElement('DIV');
                      fila.className = 'tabla__fila--pedidos';
                      fila.innerHTML = `
                            <span>${item.description}</span>
                            <span>${item.cantidad}</span>
                            <span>$${item.precio.toFixed(2)}</span>
                            <span>$${(item.precio * item.cantidad).toFixed(2)}</span>
                            <button type="button" class="tabla__eliminar" data-index="${index}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                        tablaProductos.appendChild(fila);
            });
    }

//AGREGAR PRODUCTOS A LA TABLA
function agregarProducto(){
    if(!productoSeleccionado){
        alert('Debes Seleccionar un producto');
        return;
    }

    const cantidad = parseInt(inputCantidad.value);
    if(!cantidad || cantidad <= 0){
        alert('Debes Ingresar una cantidad validad');
        return;
    }

    const existente = productosPedidos.find(p => p.producto_id === productoSeleccionado.id);
    const cantidadAcumulada = (existente ? existente.cantidad : 0) + cantidad;

    if(cantidadAcumulada > productoSeleccionado.stock){
        alert(`Solo hay ${productoSeleccionado.stock} unidades disponibles de este producto`);
        return;
    }

    if(existente){
        existente.cantidad += cantidad;
    }else{
        productosPedidos.push({
            producto_id: productoSeleccionado.id,
            description: productoSeleccionado.description,
            precio: parseFloat(productoSeleccionado.precio),
            cantidad: cantidad
        });
    }
    renderizarTablaPedidos();

    inputProducto.value = '';
    document.querySelector('#producto_id').value = '';
    inputCantidad.value = '';
    productoSeleccionado = null;
}

if(btnAgregarProductos){
    btnAgregarProductos.addEventListener('click', agregarProducto);
}

if(inputCantidad){
    inputCantidad.addEventListener('keydown', (e) => {
        if(e.key === 'Enter'){
            e.preventDefault(); // evita que el Enter mande el formulario completo
            agregarProducto();
        }
    });
}
    
//ELIMINAR PRODUCTO TABLA
document.addEventListener('click', (e) =>{
    const btnEliminar = e.target.closest('.tabla__eliminar');
        if(!btnEliminar) return;

        const index = parseInt(btnEliminar.dataset.index);
        productosPedidos.splice(index, 1);
        renderizarTablaPedidos();
})

//ENVIAR FORMULARIO DEL PEDIDO
const formularioPedido = document.querySelector('.formulario');
    if(formularioPedido){
        formularioPedido.addEventListener('submit', (e) => {
            if(!document.querySelector('#cliente_id').value){
            e.preventDefault();
            alert('Debes seleccionar un cliente');
            return;
            }

            if(productosPedidos.length === 0){
            e.preventDefault();
            alert('Debes agregar al menos un producto');
            return;
            }

            document.querySelector('#productos_json').value = JSON.stringify(productosPedidos);
        })
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

    if(inputArchivo && textoArchivo){
        inputArchivo.addEventListener('change', () => {
        if(inputArchivo.files.length > 0){
            textoArchivo.textContent = inputArchivo.files[0].name;
        }else{
            textoArchivo.textContent = '';
        }
    });}