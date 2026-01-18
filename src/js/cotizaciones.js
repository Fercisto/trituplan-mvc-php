document.addEventListener('DOMContentLoaded', () => {

    let items = [];

    const descripcion = document.querySelector('#descripcion');
    const cantidad = document.querySelector('#cantidad');
    const precioUnitario = document.querySelector('#precio_unitario');
    const precioTotal = document.querySelector('#precio_total');

    // totales
    const subtotalInput = document.querySelector('#subtotal');
    const ivaInput = document.querySelector('#iva');
    const totalInput = document.querySelector('#total');
    const totalEnLetraInput = document.querySelector('#total_letra');

    const btnAgregarItem = document.querySelector('#btn-agregar-item');
    let modoEdicion = false;
    let itemEditandoId = null;

    const alerta = document.querySelector('#formulario__alerta');

    const itemsTabla = document.querySelector('#items-tabla');

    // Variables del wizard
    const wizardSteps = document.querySelectorAll('.wizard__step');
    const wizardContents = document.querySelectorAll('.wizard__content');
    const btnSiguiente = document.querySelector('#btn-siguiente');
    const btnAnterior = document.querySelector('#btn-anterior');
    const formularioSubmit = document.querySelector('.formulario__submit');

    let pasoActual = 1;
    const totalPasos = wizardSteps.length;

    // Cargar items: primero desde PHP (modo edición), luego desde localStorage
    cargarItemsIniciales();

    // Renderizar tabla al inicio
    renderItemsTabla(items);

    cantidad.addEventListener('change', calcularTotal);
    precioUnitario.addEventListener('change', calcularTotal);
    btnAgregarItem.addEventListener('click', manejarFormulario);

    function obtenerNumero(valor) {
        // Convertir a string y limpiar
        const valorStr = String(valor).trim();

        // Si está vacío, retornar 0
        if (valorStr === '') return 0;

        // Intentar parsear directamente
        const numero = parseFloat(valorStr);

        // Si es un número válido, retornarlo, sino 0
        return !isNaN(numero) ? numero : 0;
    }

    function numeroALetras(numero) {
        const unidades = ['', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        const decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        const especiales = ['diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'];
        const centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        function convertirGrupo(n) {
            if (n === 0) return '';
            if (n === 100) return 'cien';

            let texto = '';
            const c = Math.floor(n / 100);
            const d = Math.floor((n % 100) / 10);
            const u = n % 10;

            if (c > 0) texto += centenas[c] + ' ';

            if (d === 1) {
                texto += especiales[u] + ' ';
            } else {
                if (d > 0) {
                    if (d === 2 && u > 0) {
                        texto += 'veinti' + unidades[u] + ' ';
                    } else {
                        texto += decenas[d];
                        if (u > 0) texto += ' y ' + unidades[u] + ' ';
                        else texto += ' ';
                    }
                } else if (u > 0) {
                    texto += unidades[u] + ' ';
                }
            }

            return texto.trim();
        }

        function convertirNumero(n) {
            if (n === 0) return 'cero';
            if (n === 1) return 'un';

            let texto = '';

            const millones = Math.floor(n / 1000000);
            const miles = Math.floor((n % 1000000) / 1000);
            const cientos = n % 1000;

            if (millones > 0) {
                if (millones === 1) {
                    texto += 'un millón ';
                } else {
                    texto += convertirGrupo(millones) + ' millones ';
                }
            }

            if (miles > 0) {
                if (miles === 1) {
                    texto += 'mil ';
                } else {
                    texto += convertirGrupo(miles) + ' mil ';
                }
            }

            if (cientos > 0) {
                texto += convertirGrupo(cientos);
            }

            return texto.trim();
        }

        // Separar parte entera y decimal
        const partes = numero.toFixed(2).split('.');
        const parteEntera = parseInt(partes[0]);
        const parteDecimal = partes[1];

        let resultado = convertirNumero(parteEntera);

        // Capitalizar primera letra
        resultado = resultado.charAt(0).toUpperCase() + resultado.slice(1);

        // Agregar "pesos" en singular o plural
        if (parteEntera === 1) {
            resultado += ' peso';
        } else {
            resultado += ' pesos';
        }

        // Agregar centavos
        resultado += ' ' + parteDecimal + '/100 M.N.';

        return resultado;
    }

    function calcularTotal() {
        const cantidadNum = obtenerNumero(cantidad.value);
        const precioNum = obtenerNumero(precioUnitario.value);
        precioTotal.value = '$' + (cantidadNum * precioNum).toFixed(2);
    }

    function manejarFormulario() {
        if (modoEdicion) {
            actualizarItem();
        } else {
            agregarItem();
        }
    }

    function agregarItem() {
        if (!validarFormulario('Agregar')) {
            return;
        }

        const cantidadNum = obtenerNumero(cantidad.value);
        const precioUnitarioNum = obtenerNumero(precioUnitario.value);

        const item = {
            id: crypto.randomUUID(),
            descripcion: descripcion.value,
            cantidad: cantidad.value.trim(), // Guardar como texto con unidad
            cantidadNumerica: cantidadNum, // Guardar número para cálculos
            precioUnitario: precioUnitarioNum,
            precio: cantidadNum * precioUnitarioNum
        };

        items = [...items, item];

        guardarItemsLocalStorage();
        calcularPrecios(items);
        mostrarAlerta('exito', 'Producto Agregado');
        renderItemsTabla(items);
        limpiarFormulario();
    }

    function iniciarEdicion(itemSeleccionado) {
        modoEdicion = true;
        itemEditandoId = itemSeleccionado.id;

        descripcion.value = itemSeleccionado.descripcion;
        // Mostrar la cantidad como texto (con unidad si la tiene)
        cantidad.value = itemSeleccionado.cantidad;
        precioUnitario.value = itemSeleccionado.precioUnitario;
        // Calcular el precio total basado en cantidadNumerica si existe, sino extraer el número
        const cantidadNum = itemSeleccionado.cantidadNumerica || obtenerNumero(itemSeleccionado.cantidad);
        precioTotal.value = '$' + (cantidadNum * itemSeleccionado.precioUnitario).toFixed(2);

        btnAgregarItem.textContent = 'Actualizar Producto';

        // Deshabilitar botones de la tabla durante edición
        deshabilitarBotonesTabla(true);
    }

    function actualizarItem() {
        if (!validarFormulario('Actualizar')) {
            return;
        }

        const cantidadNum = obtenerNumero(cantidad.value);
        const precioUnitarioNum = obtenerNumero(precioUnitario.value);

        items = items.map(itemActual => {
            if (itemActual.id === itemEditandoId) {
                return {
                    ...itemActual,
                    descripcion: descripcion.value,
                    cantidad: cantidad.value.trim(), // Guardar como texto con unidad
                    cantidadNumerica: cantidadNum, // Guardar número para cálculos
                    precioUnitario: precioUnitarioNum,
                    precio: cantidadNum * precioUnitarioNum
                };
            }
            return itemActual;
        });

        guardarItemsLocalStorage();
        renderItemsTabla(items);
        calcularPrecios(items);
        mostrarAlerta('exito', 'Actualizado Correctamente');
        cancelarEdicion();
    }

    function cancelarEdicion() {
        modoEdicion = false;
        itemEditandoId = null;
        btnAgregarItem.textContent = 'Agregar Producto';
        limpiarFormulario();
        deshabilitarBotonesTabla(false);
    }

    function eliminarItem(id) {
        items = items.filter(item => item.id !== id);
        guardarItemsLocalStorage();
        renderItemsTabla(items);
        calcularPrecios(items);
        mostrarAlerta('exito', 'Producto Eliminado');
    }

    function calcularPrecios(items) {
        const subtotal = items.reduce((suma, producto) => {
            return suma + producto.precio;
        }, 0);

        const iva = subtotal * 0.16;
        const total = subtotal + iva;

        subtotalInput.value = '$' + subtotal.toFixed(2);
        ivaInput.value = '$' + iva.toFixed(2);
        totalInput.value = '$' + total.toFixed(2);

        // Actualizar total en letra si existe el campo
        if (totalEnLetraInput) {
            totalEnLetraInput.value = numeroALetras(total);
        }
    }

    function validarFormulario(modo) {
        // Validar campos vacíos
        if (descripcion.value.trim() === '' || cantidad.value.trim() === '' || precioUnitario.value.trim() === '') {
            mostrarAlerta('error', `Completa Todos los Campos para ${modo} un Producto`);
            return false;
        }

        // Validar que sean números válidos
        const cantidadNum = obtenerNumero(cantidad.value);
        const precioNum = obtenerNumero(precioUnitario.value);

        if (cantidadNum <= 0) {
            mostrarAlerta('error', 'La cantidad debe ser mayor a 0');
            return false;
        }

        if (precioNum <= 0) {
            mostrarAlerta('error', 'El precio debe ser mayor a 0');
            return false;
        }

        return true;
    }

    function renderItemsTabla(items) {
        limpiarHTML();

        if (items.length === 0) {
            const tableRow = document.createElement('TR');
            const tableData = document.createElement('TD');
            tableData.setAttribute('colspan', '6');
            tableData.textContent = 'No hay productos agregados';
            tableData.style.paddingTop = '20px';
            tableRow.appendChild(tableData);
            itemsTabla.appendChild(tableRow);
            calcularPrecios([]); // Resetear totales
            return;
        }

        items.forEach(item => {
            const tableRow = document.createElement('TR');

            const descripcionTableDate = document.createElement('TD');
            descripcionTableDate.textContent = item.descripcion;

            const cantidadTableData = document.createElement('TD');
            cantidadTableData.textContent = item.cantidad;

            const precioUnitarioTableData = document.createElement('TD');
            precioUnitarioTableData.textContent = '$' + item.precioUnitario.toFixed(2);

            const precioTotalTableData = document.createElement('TD');
            precioTotalTableData.textContent = '$' + item.precio.toFixed(2);

            const btnActualizar = document.createElement('BUTTON');
            btnActualizar.type = 'button';
            btnActualizar.classList.add('admin__tabla--enlace', 'admin__tabla--enlace-actualizar');
            const iconoEditar = document.createElement('I');
            iconoEditar.classList.add('fa-solid', 'fa-pen-to-square');
            btnActualizar.appendChild(iconoEditar);
            btnActualizar.appendChild(document.createTextNode(' Editar'));
            btnActualizar.onclick = () => iniciarEdicion(item);

            const btnEliminar = document.createElement('BUTTON');
            btnEliminar.type = 'button';
            btnEliminar.classList.add('admin__tabla--enlace', 'admin__tabla--enlace-eliminar');
            const iconoEliminar = document.createElement('I');
            iconoEliminar.classList.add('fas', 'fa-trash-alt');
            btnEliminar.appendChild(iconoEliminar);
            btnEliminar.appendChild(document.createTextNode(' Eliminar'));
            btnEliminar.onclick = () => {
                eliminarItem(item.id);
            };

            const accionesTableData = document.createElement('TD');
            accionesTableData.classList.add('admin__tabla--acciones');
            accionesTableData.append(btnActualizar, btnEliminar);

            tableRow.append(descripcionTableDate, cantidadTableData, precioUnitarioTableData, precioTotalTableData, accionesTableData);
            itemsTabla.appendChild(tableRow);
        });
    }

    function deshabilitarBotonesTabla(deshabilitar) {
        const botones = itemsTabla.querySelectorAll('button');
        botones.forEach(boton => {
            boton.disabled = deshabilitar;
        });
    }

    function mostrarAlerta(tipo, mensaje) {
        while (alerta.firstElementChild) {
            alerta.removeChild(alerta.firstElementChild);
        }

        const alertaMensaje = document.createElement('P');
        alertaMensaje.classList.add('formulario__alerta-mensaje', 'alerta');

        if (tipo === 'error') {
            alertaMensaje.classList.add('alerta__error');
        } else {
            alertaMensaje.classList.add('alerta__exito');
        }

        alertaMensaje.textContent = mensaje;
        alerta.appendChild(alertaMensaje);

        // Desplazar hacia la alerta
        alerta.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        setTimeout(() => {
            alertaMensaje.remove();
        }, 3000);
    }

    function limpiarFormulario() {
        descripcion.value = '';
        cantidad.value = '';
        precioUnitario.value = '';
        precioTotal.value = '';
    }

    function limpiarHTML() {
        while (itemsTabla.firstElementChild) {
            itemsTabla.removeChild(itemsTabla.firstElementChild);
        }
    }

    function guardarItemsLocalStorage() {
        localStorage.setItem('items', JSON.stringify(items));
    }

    function cargarItemsIniciales() {
        // Items desde PHP (modo edición)
        if (window.itemsIniciales && window.itemsIniciales.length > 0) {
            items = window.itemsIniciales.map(item => {
                // Agregar ID único y cantidadNumerica si no existen
                return {
                    id: item.id || crypto.randomUUID(),
                    descripcion: item.descripcion,
                    cantidad: item.cantidad,
                    cantidadNumerica: item.cantidadNumerica || obtenerNumero(item.cantidad),
                    precioUnitario: parseFloat(item.precioUnitario),
                    precio: parseFloat(item.precio)
                };
            });
            calcularPrecios(items);
            return;
        }

        // Items desde localStorage (navegación entre páginas en modo crear)
        const itemsGuardados = localStorage.getItem('items');
        if (itemsGuardados) {
            items = JSON.parse(itemsGuardados);
            calcularPrecios(items);
        }
    }

    // Ocultar botón submit inicialmente
    formularioSubmit.style.display = 'none';

    // Navegación del wizard
    btnSiguiente.addEventListener('click', () => {
        if (validarPasoActual()) {
            if (pasoActual < totalPasos) {
                pasoActual++;
                mostrarPaso(pasoActual);
            }
        }
    });

    btnAnterior.addEventListener('click', () => {
        if (pasoActual > 1) {
            pasoActual--;
            mostrarPaso(pasoActual);
        }
    });

    function mostrarPaso(paso) {
        // Actualizar barra de progreso
        const wizardStepsContainer = document.querySelector('.wizard__steps');
        wizardStepsContainer.setAttribute('data-progress', paso);

        // Actualizar indicadores de paso
        wizardSteps.forEach((stepElement, index) => {
            if (index + 1 === paso) {
                stepElement.classList.add('wizard__step--active');
            } else if (index + 1 < paso) {
                stepElement.classList.add('wizard__step--completed');
                stepElement.classList.remove('wizard__step--active');
            } else {
                stepElement.classList.remove('wizard__step--active', 'wizard__step--completed');
            }
        });

        // Mostrar contenido correspondiente
        wizardContents.forEach((content, index) => {
            if (index + 1 === paso) {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        });

        // Controlar visibilidad de botones
        if (paso === 1) {
            btnAnterior.style.display = 'none';
        } else {
            btnAnterior.style.display = 'inline-flex';
        }

        if (paso === totalPasos) {
            btnSiguiente.style.display = 'none';
            formularioSubmit.style.display = 'inline-block';
        } else {
            btnSiguiente.style.display = 'inline-flex';
            formularioSubmit.style.display = 'none';
        }

        // Scroll al inicio del formulario
        document.querySelector('.admin-crud').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function validarPasoActual() {
        switch (pasoActual) {
            case 1:
                return validarPaso1();
            case 2:
                return validarPaso2();
            case 3:
                return true; // El paso 3 es solo visualización
            default:
                return true;
        }
    }

    function validarPaso1() {
        const fecha = document.querySelector('#fecha');
        const destinatario = document.querySelector('#destinatario');

        if (!fecha.value.trim()) {
            mostrarAlerta('error', 'La fecha es obligatoria');
            fecha.focus();
            return false;
        }

        if (!destinatario.value.trim()) {
            mostrarAlerta('error', 'El destinatario es obligatorio');
            destinatario.focus();
            return false;
        }

        return true;
    }

    function validarPaso2() {
        if (items.length === 0) {
            mostrarAlerta('error', 'Debes agregar al menos un producto o servicio');
            return false;
        }
        return true;
    }

    // Preparar datos antes de enviar el formulario
    document.querySelector('#formulario-cotizacion').addEventListener('submit', (e) => {
        // Validar que hay items
        if (items.length === 0) {
            e.preventDefault();
            mostrarAlerta('error', 'Debes agregar al menos un producto o servicio');
            return false;
        }

        // Limpiar items: remover campos innecesarios (id y cantidadNumerica)
        const itemsLimpios = items.map(item => {
            return {
                descripcion: item.descripcion,
                cantidad: item.cantidad,
                precioUnitario: item.precioUnitario,
                precio: item.precio
            };
        });

        // Guardar items limpios en el campo oculto
        document.querySelector('#items-json').value = JSON.stringify(itemsLimpios);

        // Recopilar las condiciones generales dinámicamente
        const condicionesInputs = document.querySelectorAll('.condicion-input');
        const condiciones = [];

        condicionesInputs.forEach(input => {
            if (input.value.trim()) {
                condiciones.push(input.value.trim());
            }
        });

        // Guardar condiciones como JSON en campo oculto
        const condicionesInput = document.querySelector('#condiciones-json');
        if (condicionesInput) {
            condicionesInput.value = JSON.stringify(condiciones);
        }

        // Limpiar los valores monetarios (remover el símbolo $) antes de enviar
        const subtotalValor = subtotalInput.value.replace('$', '');
        const ivaValor = ivaInput.value.replace('$', '');
        const totalValor = totalInput.value.replace('$', '');

        // Actualizar los valores sin el símbolo $
        subtotalInput.value = subtotalValor;
        ivaInput.value = ivaValor;
        totalInput.value = totalValor;

        // Limpiar localStorage antes de enviar el formulario
        localStorage.removeItem('items');
    });

    // Inicializar wizard
    mostrarPaso(1);

    // Manejo de Condiciones Dinámicas
    const condicionesContainer = document.querySelector('#condiciones-container');
    const btnAgregarCondicion = document.querySelector('#btn-agregar-condicion');

    if (btnAgregarCondicion && condicionesContainer) {
        // Cargar condiciones iniciales (modo edición)
        cargarCondicionesIniciales();

        // Agregar nueva condición
        btnAgregarCondicion.addEventListener('click', agregarCondicion);

        // Delegación de eventos para eliminar condiciones
        condicionesContainer.addEventListener('click', (e) => {
            if (e.target.closest('.btn-eliminar-condicion')) {
                const campo = e.target.closest('.formulario__campo--condicion');
                if (campo) {
                    eliminarCondicion(campo);
                }
            }
        });
    }

    function cargarCondicionesIniciales() {
        if (window.condicionesIniciales && window.condicionesIniciales.length > 0) {
            // Limpiar condiciones por defecto
            condicionesContainer.innerHTML = '';

            // Crear condiciones desde los datos guardados
            window.condicionesIniciales.forEach((condicion, index) => {
                crearCampoCondicion(index + 1, condicion);
            });
        }
        actualizarNumerosCondiciones();
    }

    function agregarCondicion() {
        const condicionesActuales = condicionesContainer.querySelectorAll('.formulario__campo--condicion');
        const nuevoNumero = condicionesActuales.length + 1;

        crearCampoCondicion(nuevoNumero, '');

        // Hacer scroll al nuevo campo
        const nuevoCampo = condicionesContainer.lastElementChild;
        nuevoCampo.querySelector('input').focus();
    }

    function crearCampoCondicion(numero, valor) {
        const campo = document.createElement('div');
        campo.classList.add('formulario__campo', 'formulario__campo--condicion');
        campo.setAttribute('data-condicion', numero);

        campo.innerHTML = `
            <label class="formulario__label">Condición ${numero}</label>
            <div class="formulario__campo--con-boton">
                <input
                    type="text"
                    class="formulario__input condicion-input"
                    placeholder="Escribe una condición..."
                    value="${escapeHtml(valor)}"
                >
                <button type="button" class="btn-eliminar-condicion" title="Eliminar condición">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;

        condicionesContainer.appendChild(campo);
    }

    function eliminarCondicion(campo) {
        const condicionesActuales = condicionesContainer.querySelectorAll('.formulario__campo--condicion');

        // No permitir eliminar si solo queda una condición
        if (condicionesActuales.length <= 1) {
            mostrarAlerta('error', 'Debe haber al menos una condición');
            return;
        }

        campo.remove();
        actualizarNumerosCondiciones();
    }

    function actualizarNumerosCondiciones() {
        const condiciones = condicionesContainer.querySelectorAll('.formulario__campo--condicion');
        condiciones.forEach((campo, index) => {
            const numero = index + 1;
            campo.setAttribute('data-condicion', numero);
            campo.querySelector('.formulario__label').textContent = `Condición ${numero}`;
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

});