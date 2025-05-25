const idContSelect = document.getElementById("id_cont");
const idProvSelect = document.getElementById("id_prov");
//Función para cargar contratistas
function cargarContratistas() {
    fetch('/controllers/ContController.php?action=listarContratistas')
        .then(response => response.json())
        .then(data => {
            idContSelect.innerHTML = '<option value="">-- Seleccionar Contratista --</option>';
            data.forEach(contratista => {
                idContSelect.innerHTML += `<option value="${contratista.id_cont}">${contratista.nombre}</option>`;
            });
        })
        .catch(error => console.error("Error al cargar contratistas:", error));
}

// //Función para cargar proveedores
function cargarProveedores() {
    fetch('/controllers/ProvController.php?action=listarProveedores')
        .then(response => response.json())
        .then(data => {
            idProvSelect.innerHTML = '<option value="">-- Seleccionar Proveedor --</option>';
            data.forEach(proveedor => {
                idProvSelect.innerHTML += `<option value="${proveedor.id_prov}">${proveedor.nombre}</option>`;
            });
        })
        .catch(error => console.error("Error al cargar proveedores:", error));
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