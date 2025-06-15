console.log("nuevo_cont.js cargado correctamente");
const form = document.getElementById('formNuevoContratista');
form.addEventListener('submit', function (e) {
    e.preventDefault();

    // Para limpiar los errores
    document.querySelectorAll('.error').forEach(div => div.textContent = '');

   
    const nombre = document.getElementById('nombre').value.trim();
    const cif = document.getElementById('cif').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefono = document.getElementById('telefono').value.trim();  
    
    //campos de direccion
    const calle = document.getElementById('calle').value.trim();
    const numero = document.getElementById('numero').value.trim();
    const cp = document.getElementById('cp').value.trim();
    const poblacion = document.getElementById('poblacion').value.trim();
    const provincia = document.getElementById('provincia').value.trim();

    let hayErrores = false;

    if (!nombre) {
        document.getElementById('errorNombre').textContent = "El nombre es obligatorio.";
        hayErrores = true;
    }

    const cifRegex = /^([A-Za-z]\d{8}|\d{8}[A-Za-z])$/;

    if (!cifRegex.test(cif))  {
        document.getElementById('errorCIF').textContent = "CIF inválido. Debe comenzar con una letra seguida de 8 dígitos.";
        hayErrores = true;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
        document.getElementById('errorEmail').textContent = "Correo electrónico no válido.";
        hayErrores = true;
    }

    const telefonoRegex = /^[0-9]{9}$/;
    
    if (!telefonoRegex.test(telefono)) {
        document.getElementById('errorTelefono').textContent = "El teléfono debe tener 9 dígitos.";
        hayErrores = true;
    }
 

    if (hayErrores) return;

    // Concatenamos la dirección completa
    const direccion = `${calle}, Nº ${numero}, ${cp} ${poblacion}, ${provincia}`;

    //enviar los datos
    const formData = { nombre, cif, email, telefono, direccion };
    console.log (formData);

    fetch('/controllers/ContController.php?action=crearContratista', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify( formData )
    })
    .then(res => res.json())
    .then(data => {
        if (data.mensaje) {
            alert(data.mensaje);
            cargar('#portada', '/views/vercontratistas.php');
        } else {
            document.getElementById('errorGeneral').textContent = data.error || "Error desconocido al crear.";
        }
    })
    .catch(() => {
        document.getElementById('errorGeneral').textContent = "Error de comunicación con el servidor.";
    });
});
