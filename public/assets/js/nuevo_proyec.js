const form = document.getElementById("formNuevoProyecto");
form.addEventListener("submit", function (e) {
  e.preventDefault();

  document.querySelectorAll(".error").forEach((div) => (div.textContent = ""));

  //campos de localización  
  const id_finca = document.getElementById("id_finca").value.trim();
  const id_prov = document.getElementById("id_prov").value.trim();
  const trabajo = document.getElementById("trabajo").value.trim();
  const fecha_inicio = document.getElementById("fecha_inicio").value.trim();
  const fecha_fin = document.getElementById("fecha_fin").value.trim();

  //manejar el valor de id_cont segun venga de admin o de contratista
  const idContInput = document.querySelector('[name="id_cont"]');
  const id_cont = idContInput ? idContInput.value.trim() : null;

  let hayErrores = false;

  if (!id_cont) {
    document.getElementById("errorContratista").textContent =
      "Elegir contratista es obligatorio.";
    hayErrores = true;
  }

  if (!id_finca) {
    document.getElementById("errorFinca").textContent =
      "Elegir finca es obligatorio.";
    hayErrores = true;
  }

  if (!id_prov) {
    document.getElementById("errorProveedor").textContent =
      "Elegir proveedor es obligatorio.";
    hayErrores = true;
  }

  if (!trabajo) {
    document.getElementById("errorTrabajo").textContent =
      "Poner el tipo de trabajo es obligatorio.";
    hayErrores = true;
  }


  if (hayErrores) return;


  const formData = {id_cont, id_finca, id_prov, trabajo, fecha_inicio, fecha_fin};
  console.log(formData);

  fetch("/controllers/ProyecController.php?action=crearProyecto", {
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
        cargar("#portada", "/views/verproyectos.php");
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
const idProvSelect = document.getElementById("id_prov");
//Función para cargar contratistas
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

// //Función para cargar proveedores
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

const idContInput = document.querySelector('[name="id_cont"]');
const idCont = idContInput ? idContInput.value : null;

if (idCont) {
  cargarFincas(idCont);
}

if (idContSelect) {
  // Si estamos en modo admin, al cambiar el contratista se actualizan fincas y proveedores
  idContSelect.addEventListener("change", () => {
    const selectedIdCont = idContSelect.value;
    cargarProveedores(selectedIdCont);
    cargarFincas(selectedIdCont);
  });
}

function cargarFincas(idCont) {
  fetch(
    `/controllers/FincasController.php?action=listarFincasPorContratista&id_cont=${idCont}`
  )
    .then((response) => response.json())
    .then((data) => {
      const select = document.getElementById("id_finca");
      select.innerHTML = '<option value="">-- Seleccionar Finca --</option>';
      data.forEach((f) => {
        select.innerHTML += `<option value="${f.id_finca}">${f.localizacion}</option>`;
      });
    })
    .catch((err) => console.error("Error cargando fincas:", err));
}

cargarContratistas();
cargarProveedores();
