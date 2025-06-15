console.log("nuevo_trab.js cargado correctamente");
const form = document.getElementById("formNuevoTrabajador");
form.addEventListener("submit", function (e) {
  e.preventDefault();

  // Para limpiar los errores
  document.querySelectorAll(".error").forEach((div) => (div.textContent = ""));

  const nombre = document.getElementById("nombre").value.trim();
  const apellidos = document.getElementById("apellidos").value.trim();
  const dni = document.getElementById("dni").value.trim();
  const email = document.getElementById("email").value.trim();
  const telefono = document.getElementById("telefono").value.trim();
  //campos de localización
  const calle = document.getElementById("calle").value.trim();
  const numero = document.getElementById("numero").value.trim();
  const cp = document.getElementById("cp").value.trim();
  const poblacion = document.getElementById("poblacion").value.trim();
  const provincia = document.getElementById("provincia").value.trim();

  const documentos = 0;
  //manejar el valor de id_cont segun venga de admin o de contratista
  const idProvInput = document.querySelector('[name="id_prov"]');
  const id_prov = idProvInput ? idProvInput.value.trim() : null;

  let hayErrores = false;

  if (!nombre) {
    document.getElementById("errorNombre").textContent =
      "El nombre es obligatorio.";
    hayErrores = true;
  }

  if (!apellidos) {
    document.getElementById("errorApellidos").textContent =
      "Los apellidos son abligatorios.";
    hayErrores = true;
  }

  const dniRegex = /^([A-Za-z]\d{8}|\d{8}[A-Za-z])$/;
  if (!dniRegex.test(dni)) {
    document.getElementById("errorDni").textContent =
      "DNI inválido. Debe comenzar con una letra seguida de 8 dígitos.";
    hayErrores = true;
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    document.getElementById("errorEmail").textContent =
      "Correo electrónico no válido.";
    hayErrores = true;
  }

  const telefonoRegex = /^[0-9]{9}$/;
  if (!telefonoRegex.test(telefono)) {
    document.getElementById("errorTelefono").textContent =
      "El teléfono debe tener 9 dígitos.";
    hayErrores = true;
  }

  if (hayErrores) return;

  // Concatenamos la dirección completa
  const direccion = `${calle}, Nº ${numero}, ${cp} ${poblacion}, ${provincia}`;

  //enviar los datos
  const formData = {
    nombre,
    apellidos,
    dni,
    email,
    telefono,
    direccion,
    documentos,
    id_prov,
  };
  console.log(formData);

  fetch("/controllers/TrabController.php?action=crearTrabajador", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formData),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.mensaje) {
        alert(data.mensaje);
        cargar("#portada", "/views/vertrabajadores.php");
      } else {
        document.getElementById("errorGeneral").textContent =
          data.error || "Error desconocido al crear.";
      }
    })
    .catch(() => {
      document.getElementById("errorGeneral").textContent =
        "Error de comunicación con el servidor.";
    });
});

const idProvSelect = document.getElementById("id_prov");

// Función para cargar proveedores
function cargarProveedores() {
  fetch("/controllers/ProvController.php?action=listarProveedores")
    .then((response) => response.json())
    .then((data) => {
      idProvSelect.innerHTML =
        '<option value="">-- Seleccionar Proveedor --</option>';
      data.forEach((proveedor) => {
        idProvSelect.innerHTML += `<option value="${proveedor.id_prov}">${proveedor.nombre}</option>`;
      });
    })
    .catch((error) => console.error("Error al cargar proveedores:", error));
}

cargarProveedores();
