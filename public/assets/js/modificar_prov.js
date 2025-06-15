document.getElementById('formEditarProveedor').addEventListener('submit', function (e) {
    e.preventDefault();

    document.querySelectorAll('.error').forEach(div => div.textContent = '');

    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const cif = document.getElementById('cif').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const direccion = document.getElementById('direccion').value.trim();

    let hayErrores = false;

    const cifRegex = /^([A-Za-z]\d{8}|\d{8}[A-Za-z])$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const telefonoRegex = /^[0-9]{9}$/;

    if (!nombre) {
        document.getElementById('errorNombre').textContent = "El nombre es obligatorio.";
        hayErrores = true;
    }

        if (!apellidos) {
        document.getElementById('errorApellidos').textContent = "Los apellidos son obligatorios.";
        hayErrores = true;
    }

    if (!cifRegex.test(cif)) {
        document.getElementById('errorCIF').textContent = "El CIF es obligatorio.";
        hayErrores = true;
    }

    if (!emailRegex.test(email)) {
        document.getElementById('errorEmail').textContent = "Correo no válido.";
        hayErrores = true;
    }

    if (!telefonoRegex.test(telefono)) {
        document.getElementById('errorTelefono').textContent = "El teléfono debe tener 9 cifras.";
        hayErrores = true;
    }

    if (!direccion) {
        document.getElementById('errorDireccion').textContent = "La dirección es obligatoria.";
        hayErrores = true;
    }

    if (hayErrores) return;

    const datos = { id, nombre, apellidos, cif, email, telefono, direccion };

    fetch('/controllers/ProvController.php?action=modificarProveedor', {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ datos })
    })
    .then(r => r.json())
    .then(data => {
        if (data.mensaje) {
            alert(data.mensaje);
            cargar('#portada', '/views/verproveedores.php');
        } else {
            document.getElementById('errorGeneral').textContent = data.error || "Error al modificar.";
        }
    })
    .catch(() => {
        document.getElementById('errorGeneral').textContent = "Error en el servidor.";
    });
});