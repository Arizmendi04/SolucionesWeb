// scripts.js

// Función para filtrar productos en tiempo real
function filtrarProductos(valor) {
    const cards = document.querySelectorAll('.product-card');
    valor = valor.toLowerCase();

    cards.forEach(card => {
        const nombre = card.querySelector('h5').textContent.toLowerCase();
        if (nombre.includes(valor)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}
