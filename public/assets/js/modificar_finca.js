document.getElementById('formEditarFinca').addEventListener('submit', function (e) {
    e.preventDefault();

    document.querySelectorAll('.error').forEach(div => div.textContent = '');

    const id = document.getElementById('id').value;
    const localizacion = document.getElementById('localizacion').value.trim();
    const cultivo = document.getElementById('cultivo').value.trim();
    const hectarea = document.getElementById('hectarea').value.trim();
    const id_cont = document.getElementById('id_cont').value;

    let hayErrores = false;

    if (!localizacion) {
        document.getElementById('errorLocalizacion').textContent = "La localizacion es obligatorio.";
        hayErrores = true;
    } 

    if (!cultivo) {
        document.getElementById('errorCultivo').textContent = "El tipo de cultivo es obligatoria.";
        hayErrores = true;
    }

        if (!hectarea) {
        document.getElementById('errorHectarea').textContent = "El número de hectáreas es obligatoria.";
        hayErrores = true;
    }

    if (hayErrores) return;

    const datos = { id, localizacion, cultivo, hectarea, id_cont};

    fetch('/controllers/FincasController.php?action=modificarFinca', {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ datos })
    })
    .then(r => r.json())
    .then(data => {
        if (data.mensaje) {
            alert(data.mensaje);
            cargar('#portada', '/views/verfincas.php');
        } else {
            document.getElementById('errorGeneral').textContent = data.error || "Error al modificar.";
        }
    })
    .catch(() => {
        document.getElementById('errorGeneral').textContent = "Error en el servidor.";
    });
});

