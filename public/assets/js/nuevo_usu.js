// console.log("nuevo_usu.js cargado correctamente");

const form = document.getElementById("formNuevoUsuario");
const tipoSelect = document.getElementById("tipo");
const contratistaField = document.getElementById("contratistaField");
const proveedorField = document.getElementById("proveedorField");
const idContSelect = document.getElementById("id_cont");
const idProvSelect = document.getElementById("id_prov");

// Evento para mostrar/ocultar campos según el tipo
tipoSelect.addEventListener("change", () => {
  contratistaField.style.display = "none";
  proveedorField.style.display = "none";

  if (tipoSelect.value === "contratista") {
    cargarContratistas();
    contratistaField.style.display = "block";
  } else if (tipoSelect.value === "proveedor") {
    cargarProveedores();
    proveedorField.style.display = "block";
  }
});

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

//validamos los campos
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  document.querySelectorAll('.error').forEach(el => el.textContent = '');

  const usuario = document.getElementById("usuario").value.trim();
  const clave = document.getElementById("clave").value.trim();
  const nombre = document.getElementById("nombre").value.trim();
  const tipo = document.getElementById("tipo").value;
  const id_cont = document.getElementById("id_cont").value;
  const id_prov = document.getElementById("id_prov").value;

 let hayErrores = false;

        if (!usuario) {
            document.getElementById('errorUsuario').textContent = "El usuario es obligatorio.";
            hayErrores = true;
        }

        const claveRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!claveRegex.test(clave)) {
            document.getElementById('errorClave').textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.";
            hayErrores = true;
        }

        if (!nombre) {
            document.getElementById('errorNombre').textContent = "El nombre es obligatorio.";
            hayErrores = true;
        }

        if (!tipo) {
            document.getElementById('errorTipo').textContent = "Debes seleccionar un tipo.";
            hayErrores = true;
        }

        if (tipo === 'contratista' && !id_cont) {
            document.getElementById('errorCont').textContent = "Selecciona un contratista.";
            hayErrores = true;
        }

        if (tipo === 'proveedor' && !id_prov) {
            document.getElementById('errorProv').textContent = "Selecciona un proveedor.";
            hayErrores = true;
        }

        if (hayErrores) return; // 🚫 No continuar si hay errores

        // Verificar si el usuario ya existe
        const existe = await fetch(`/controllers/UserController.php?action=existeUsuario&usuario=${usuario}`)
            .then(r => r.json())
            .then(data => data.existe)
            .catch(() => {
                document.getElementById('errorUsuario').textContent = "Error al verificar disponibilidad.";
                return true;
            });

        if (existe) {
            document.getElementById('errorUsuario').textContent = "Ese nombre de usuario ya está registrado.";
            return;
        }

        // Enviar datos
        const formData = new FormData(form);

        fetch('/controllers/nusuario.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.mensaje) {
                alert(data.mensaje); 
                window.location.href = "/views/app_admin.php?opcion=1";
            } else if (data.error) {              
                const generalError = document.getElementById('errorGeneral');
                if (generalError) {
                    generalError.textContent = data.error;
                } else {
                    alert(data.error);
                }
            }
        })
        .catch(() => {
            alert("Error inesperado al crear el usuario.");
        });
    });