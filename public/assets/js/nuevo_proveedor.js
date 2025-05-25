// console.log("nuevo_usu.js cargado correctamente");

const form = document.getElementById('formNuevoUsuario');
const tipoSelect = document.getElementById('tipo');
const contratistaField = document.getElementById('contratistaField');
const proveedorField = document.getElementById('proveedorField');
const idContSelect = document.getElementById('id_cont');
const idProvSelect = document.getElementById('id_prov');

// Evento para mostrar/ocultar campos según el tipo
tipoSelect.addEventListener('change', () => {
    contratistaField.style.display = 'none';
    proveedorField.style.display = 'none';

    if (tipoSelect.value === 'contratista') {
        cargarContratistas();
        contratistaField.style.display = 'block';
    } else if (tipoSelect.value === 'proveedor') {
        cargarProveedores();
        proveedorField.style.display = 'block';
    }
});

// Función para cargar contratistas
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

// Función para cargar proveedores
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

