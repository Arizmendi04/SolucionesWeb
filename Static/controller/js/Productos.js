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

document.getElementById("fotoProducto").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("previewImg");
    const previewContainer = document.getElementById("previewContainer");
    const removePreview = document.getElementById("removePreview");

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            removePreview.style.display = "flex"; // Muestra la "X"
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "/SolucionesWeb/Static/Img/imagengenerica.png"; // Imagen predeterminada
        removePreview.style.display = "none"; // Oculta la "X"
    }
});

// Funcionalidad para la "X" de eliminación
document.getElementById("removePreview").addEventListener("click", function() {
    const preview = document.getElementById("previewImg");
    const previewContainer = document.getElementById("previewContainer");
    const removePreview = document.getElementById("removePreview");

    preview.src = "/SolucionesWeb/Static/Img/imagengenerica.png"; // Imagen predeterminada
    document.getElementById("fotoProducto").value = ""; // Limpia el campo de archivo
    removePreview.style.display = "none"; // Oculta la "X"
});
