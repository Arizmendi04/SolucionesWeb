function showAlert(message) {
    document.getElementById('alertMessage').innerText = message;
    document.getElementById('alertOverlay').style.display = 'block';
    document.getElementById('alertBox').style.display = 'block';
}

function closeAlert() {
    document.getElementById('alertOverlay').style.display = 'none';
    document.getElementById('alertBox').style.display = 'none';
}

function confirmLogout() {
    window.location.href = '../../Controller/Logout.php'; 
}

// Agrega el evento de clic para el botón de cerrar sesión
document.getElementById('logoutButton').onclick = function(event) {
    event.preventDefault(); // Previene el comportamiento por defecto del botón
    showAlert('¿Estás seguro de que deseas cerrar sesión?');
};
