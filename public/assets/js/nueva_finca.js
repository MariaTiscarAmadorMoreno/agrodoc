console.log("nueva_finca.js cargado correctamente");
const form = document.getElementById("formNuevaFinca");
form.addEventListener("submit", function (e) {
  e.preventDefault();

  // Para limpiar los errores
  document.querySelectorAll(".error").forEach((div) => (div.textContent = ""));

  //campos de localización
  const calle = document.getElementById("calle").value.trim();
  const numero = document.getElementById("numero").value.trim();
  const cp = document.getElementById("cp").value.trim();
  const poblacion = document.getElementById("poblacion").value.trim();
  const provincia = document.getElementById("provincia").value.trim();

  const cultivo = document.getElementById("cultivo").value.trim();
  const hectarea = document.getElementById("hectarea").value.trim();
  //manejar el valor de id_cont segun venga de admin o de contratista
  const idContInput = document.querySelector('[name="id_cont"]');
  const id_cont = idContInput ? idContInput.value.trim() : null;

  let hayErrores = false;

  if (!cultivo) {
    document.getElementById("errorCultivo").textContent =
      "El tipo de cultivo es obligatorio.";
    hayErrores = true;
  }

  if (!hectarea) {
    document.getElementById("errorHectarea").textContent =
      "El número de hectáreas es obligatorio.";
    hayErrores = true;
  }
    if (!id_cont) {
    document.getElementById("errorContratista").textContent =
      "El contratista es obligatorio.";
    hayErrores = true;
  }

  if (hayErrores) return;

  // Concatenamos la dirección completa
  const localizacion = `${calle}, Nº ${numero}, ${cp} ${poblacion}, ${provincia}`;

  //enviar los datos
  const formData = { localizacion, cultivo, hectarea, id_cont };
  console.log(formData);

  fetch("/controllers/FincasController.php?action=crearFinca", {
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
        cargar("#portada", "/views/verfincas.php");
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

const idContSelect = document.getElementById("id_cont");

// Función para cargar contratistas
function cargarContratistas() {
  fetch("/controllers/ContController.php?action=listarContratistas")
    .then((response) => response.json())
    .then((data) => {
      idContSelect.innerHTML =
        '<option value="">-- Seleccionar Contratista --</option>';
      data.forEach((contratista) => {
        idContSelect.innerHTML += `<option value="${contratista.id_cont}">${contratista.nombre}</option>`;
      });
    })
    .catch((error) => console.error("Error al cargar contratistas:", error));
}
cargarContratistas();