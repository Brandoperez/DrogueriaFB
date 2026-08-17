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
const filas = document.querySelectorAll('.tabla__fila--usuarios');

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
                            const pvp = parseFloat(producto.pvp);
                            const precioFinal = parseFloat(producto.precio);
                            const descuento = parseFloat(producto.descuento) || 0;
                            const tieneDescuento = descuento > 0;

                            resultado.innerHTML = `
                                <span class="pedidos__resultado--nombre">${producto.description}${sinStock ? ' (Sin stock)' : ''}</span>
                                <span class="pedidos__resultado--pvp${tieneDescuento ? ' pedidos__resultado--tachado' : ''}">PVP $${pvp.toFixed(2)}</span>
                                <span class="pedidos__resultado--stock">Stock: ${producto.stock}</span>
                                ${tieneDescuento ? `
                                    <span class="pedidos__resultado--descuento">${descuento}% OFF:</span>
                                    <span class="pedidos__resultado--final">$${precioFinal.toFixed(2)}</span>
                                ` : ''}
                            `;

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
let indexEditando = null;

    function actualizarTotalPedido(){
        const totalPedido = document.querySelector('#totalPedido');
        if(!totalPedido) return;

        const total = productosPedidos.reduce((acumulado, item) => acumulado + (item.precio * item.cantidad), 0);
        totalPedido.textContent = `$${total.toFixed(2)}`;
    }

    function renderizarTablaPedidos(){
        tablaProductos.querySelectorAll('.tabla__fila--pedidos').forEach(fila => fila.remove());
        actualizarTotalPedido();
            if(productosPedidos.length === 0){
                tablaProductos.appendChild(filaVacia);
                return;
            }

            filaVacia.remove();

            productosPedidos.forEach((item, index) => {
                const fila = document.createElement('DIV');
                      fila.className = 'tabla__fila--pedidos';

                      const enEdicion = index === indexEditando;
                      const celdaCantidad = enEdicion
                        ? `<input type="number" class="tabla__cantidad--input" data-index="${index}" min="1" value="${item.cantidad}">`
                        : `<span>${item.cantidad}</span>`;

                        const botonAccion = enEdicion
                        ? `<button type="button" class="tabla__guardar" data-index="${index}"><i class="fa-solid fa-check"></i></button>`
                        : `<button type="button" class="tabla__editar" data-index="${index}"><i class="fa-solid fa-pen"></i></button>`;

                      fila.innerHTML = `
                            <span>${item.description}</span>
                            ${celdaCantidad}
                            <span>$${item.precio.toFixed(2)}</span>
                            <span>$${(item.precio * item.cantidad).toFixed(2)}</span>
                            <div class="tabla__acciones">
                                ${botonAccion}
                                <button type="button" class="tabla__eliminar" data-index="${index}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                            <input type="text" class="tabla__observacion" data-index="${index}" placeholder="Nota del producto..." value="${item.observaciones}">
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
            cantidad: cantidad,
            stock: productoSeleccionado.stock,
            observaciones: ''
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
//GUARDAR OBSERVACION DEL PRODUCTO
document.addEventListener('input', (e) => {
    const inputObservacion = e.target.closest('.tabla__observacion');
    if(!inputObservacion) return;

    const index = parseInt(inputObservacion.dataset.index);
    productosPedidos[index].observaciones = inputObservacion.value;
});

//ACTIVAR EDICIÓN DE CANTIDAD
document.addEventListener('click', (e) => {
    const btnEditar = e.target.closest('.tabla__editar');
    if(!btnEditar) return;

    indexEditando = parseInt(btnEditar.dataset.index);
    renderizarTablaPedidos();
});

//GUARDAR NUEVA CANTIDAD
document.addEventListener('click', (e) => {
    const btnGuardar = e.target.closest('.tabla__guardar');
    if(!btnGuardar) return;

    const index = parseInt(btnGuardar.dataset.index);
    const inputCantidadEdit = document.querySelector(`.tabla__cantidad--input[data-index="${index}"]`);
    const nuevaCantidad = parseInt(inputCantidadEdit.value);

    if(!nuevaCantidad || nuevaCantidad <= 0){
        alert('Debes ingresar una cantidad válida');
        return;
    }
    if(nuevaCantidad > productosPedidos[index].stock){
        alert(`Solo hay ${productosPedidos[index].stock} unidades disponibles de este producto`);
        return;
    }

    productosPedidos[index].cantidad = nuevaCantidad;
    indexEditando = null;
    renderizarTablaPedidos();
});

//GUARDAR CANTIDAD CON ENTER
document.addEventListener('keydown', (e) => {
    if(e.key !== 'Enter') return;
    const inputCantidadEdit = e.target.closest('.tabla__cantidad--input');
    if(!inputCantidadEdit) return;

    e.preventDefault();
    document.querySelector(`.tabla__guardar[data-index="${inputCantidadEdit.dataset.index}"]`)?.click();
});

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
//CONTADOR
const contadorTiempo = document.querySelector('#contadorTiempo');
if(contadorTiempo){
    function actualizarContador(){
        const ahora = new Date();
        const limite = new Date();
        limite.setHours(16, 0, 0, 0);

        if(ahora >= limite){
            limite.setDate(limite.getDate() + 1);
        }

        const diferencia = limite - ahora;
        const horas = String(Math.floor(diferencia / (1000 * 60 * 60))).padStart(2, '0');
        const minutos = String(Math.floor((diferencia / (1000 * 60)) % 60)).padStart(2, '0');
        const segundos = String(Math.floor((diferencia / 1000) % 60)).padStart(2, '0');

        contadorTiempo.textContent = `${horas}:${minutos}:${segundos}`;
    }

    actualizarContador();
    setInterval(actualizarContador, 1000);
}