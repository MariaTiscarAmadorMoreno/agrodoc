document.getElementById('formSubirDocumento').addEventListener('submit', function (e) {
    e.preventDefault();

    // Limpiar errores anteriores
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    const archivo = document.getElementById('archivo');
    const fechaCaducidad = document.getElementById('fecha_caducidad');

    let valido = true;
  
    if (!archivo.files.length) {
        document.getElementById('errorArchivo').textContent = 'Este campo es obligatorio.';
        valido = false;
    }
    if (!fechaCaducidad.value.trim()) {
        document.getElementById('errorFecha').textContent = 'Este campo es obligatorio.';
        valido = false;
    }

    if (!valido) {
        document.getElementById('errorGeneral').textContent = 'Por favor, completa todos los campos obligatorios.';
        return;
    }

    // Si pasa validación
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
