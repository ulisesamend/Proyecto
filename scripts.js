document.addEventListener('DOMContentLoaded', () => {
    const carrito = [];
    const carritoItems = document.getElementById('carrito-items');
    const carritoTotal = document.getElementById('carrito-total');
    const vaciarCarritoBtn = document.getElementById('vaciar-carrito');
    const carritoFlotante = document.getElementById('carrito-flotante');
    const abrirCarritoBtn = document.getElementById('abrir-carrito');
    const cerrarCarritoBtn = document.getElementById('cerrar-carrito');

    function actualizarCarrito() {
        carritoItems.innerHTML = '';
        let total = 0;
        carrito.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('carrito-item');
            itemDiv.innerHTML = `
                <span>${item.nombre}</span>
                <span>$${item.precio.toFixed(2)}</span>
            `;
            carritoItems.appendChild(itemDiv);
            total += item.precio;
        });
        carritoTotal.textContent = `Total: $${total.toFixed(2)}`;
    }

    document.querySelectorAll('.comprar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const itemDiv = e.target.closest('.menu-item');
            const nombre = itemDiv.querySelector('h3').textContent;
            const precio = parseFloat(itemDiv.querySelector('span').textContent.replace('$', '').replace(',', ''));

            carrito.push({ nombre, precio });
            actualizarCarrito();
            carritoFlotante.classList.add('show'); // Mostrar el carrito al agregar un item
        });
    });

    vaciarCarritoBtn.addEventListener('click', () => {
        carrito.length = 0; // Limpiar el array del carrito
        actualizarCarrito();
    });

    abrirCarritoBtn.addEventListener('click', () => {
        carritoFlotante.classList.add('show');
    });

    cerrarCarritoBtn.addEventListener('click', () => {
        carritoFlotante.classList.remove('show');
    });
});
