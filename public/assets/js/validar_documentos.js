document.getElementById('formSubirDocumento').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/api/upload_documento.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.mensaje) {
            alert(data.mensaje);
            cargar('#portada', '/views/verdocumentos.php');
        } else {
            document.getElementById('errorGeneral').textContent = data.error || 'Error desconocido al subir.';
        }
    })
    .catch(err => {     

        console.error("Error en la subida:", err);
        document.getElementById('errorGeneral').textContent = "Error de comunicación con el servidor.";
    });
});