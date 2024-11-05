// Recuperar el carrito desde localStorage al cargar la página
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];  // Si no hay carrito, inicializamos uno vacío

// Función para actualizar la vista del carrito (en el carrito flotante)
function actualizarCarrito() {
    const carritoItems = document.getElementById("carrito-items");
    const carritoTotal = document.getElementById("carrito-total");

    carritoItems.innerHTML = ""; // Limpiamos el contenido actual del carrito

    // Agregar cada producto al carrito visual
    carrito.forEach((producto, index) => {
        const itemCarrito = document.createElement("div");
        itemCarrito.classList.add("carrito-item");
        itemCarrito.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}" />
            <p>${producto.nombre}</p>
            <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
        `;
        carritoItems.appendChild(itemCarrito);
    });

    // Mostrar el total
    const total = carrito.reduce((acc, producto) => acc + producto.precio, 0);
    carritoTotal.innerHTML = `Total: $${total.toFixed(2)}`;

    // Guardar el carrito actualizado en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// Función para agregar un producto al carrito
function agregarAlCarrito(producto) {
    carrito.push(producto);
    actualizarCarrito();  // Actualizamos la vista del carrito después de agregar el producto
}

// Función para eliminar un producto del carrito
function eliminarDelCarrito(index) {
    carrito.splice(index, 1);  // Eliminar el producto del carrito en el índice especificado
    actualizarCarrito();  // Actualizamos la vista del carrito después de eliminar el producto
}

// Función para vaciar el carrito
function vaciarCarrito() {
    carrito = [];  // Limpiar el carrito
    actualizarCarrito();  // Actualizamos la vista del carrito
}

// Función para finalizar la compra
function finalizarCompra() {
    if (carrito.length > 0) {
        // Crear el objeto de productos con el carrito
        const productos = carrito.map(producto => ({
            nombre: producto.nombre,
            precio: producto.precio,
            imagen: producto.imagen
        }));

        // Enviar los datos del carrito al servidor para procesar la compra
        fetch('procesar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ carrito: productos, carritoId: carritoId })  // Enviamos el carrito y el ID único
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compra realizada con éxito!');
                // Limpiar carrito después de finalizar compra
                localStorage.removeItem('carrito');
                window.location.href = 'indexpedido.html';  // Redirigir a la página de finalización
            } else {
                alert('Hubo un problema al procesar tu compra.');
            }
        })
        .catch(error => {
            console.error('Error al procesar la compra:', error);
            alert('Error en la solicitud.');
        });
    } else {
        alert('Tu carrito está vacío.');
    }
}


// Mostrar el carrito flotante al hacer clic en "Comprar"
function mostrarCarrito() {
    const carritoFlotante = document.getElementById("carrito-flotante");
    carritoFlotante.style.display = "block";  // Mostrar el carrito flotante
}

// Función para cerrar el carrito flotante
function cerrarCarrito() {
    const carritoFlotante = document.getElementById("carrito-flotante");
    carritoFlotante.style.display = "none";  // Ocultar el carrito flotante
}

// Hacer que los botones de "Comprar" agreguen productos al carrito y muestren el carrito flotante
const botonesComprar = document.querySelectorAll(".comprar-btn");
botonesComprar.forEach((boton) => {
    boton.addEventListener("click", function (e) {
        const item = e.target.closest(".menu-item");
        const nombre = item.querySelector("h3").textContent;
        const precio = item.querySelector("span").textContent.replace('$', '').trim();
        const imagen = item.querySelector("img").src;

        // Crear un objeto de producto
        const producto = {
            nombre: nombre,
            precio: parseFloat(precio),
            imagen: imagen
        };

        // Agregar el producto al carrito
        agregarAlCarrito(producto);
        mostrarCarrito();  // Mostrar el carrito flotante
    });
});

// Inicializar la vista del carrito cuando se carga la página
actualizarCarrito();  // Cargar el carrito y actualizar la vista con los productos guardados

// Asignar eventos a botones de cierre y vaciar carrito
document.getElementById('cerrar-carrito').addEventListener('click', cerrarCarrito);
document.getElementById('vaciar-carrito').addEventListener('click', vaciarCarrito);
