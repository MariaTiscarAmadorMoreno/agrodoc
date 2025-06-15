document
  .getElementById("formEditarTrabajador")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    document
      .querySelectorAll(".error")
      .forEach((div) => (div.textContent = ""));

    const id = document.getElementById("id").value;
    const nombre = document.getElementById("nombre").value.trim();
    const apellidos = document.getElementById("apellidos").value.trim();
    const dni = document.getElementById("dni").value.trim();
    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const direccion = document.getElementById("direccion").value.trim();
    const docInput = document.getElementById("documentos");
    let documentos = docInput.value;
    //cuando es proveedor
    if (docInput.hasAttribute("readonly")) {
      documentos = documentos.trim().toLowerCase() === "apto" ? 1 : 0;
    }

    let hayErrores = false;
    const dniRegex = /^([A-Za-z]\d{8}|\d{8}[A-Za-z])$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const telefonoRegex = /^[0-9]{9}$/;

    if (!nombre) {
      document.getElementById("errorNombre").textContent =
        "El nombre es obligatorio.";
      hayErrores = true;
    }

    if (!apellidos) {
      document.getElementById("errorApellidos").textContent =
        "Los apellidos son obligatorios.";
      hayErrores = true;
    }

    if (!dniRegex.test(dni)) {
      document.getElementById("errorDni").textContent =
        "El DNI es obligatorio.";
      hayErrores = true;
    }

    if (!emailRegex.test(email)) {
      document.getElementById("errorEmail").textContent = "Correo no válido.";
      hayErrores = true;
    }

    if (!telefonoRegex.test(telefono)) {
      document.getElementById("errorTelefono").textContent =
        "El teléfono debe tener 9 cifras.";
      hayErrores = true;
    }

    if (!direccion) {
      document.getElementById("errorDireccion").textContent =
        "La dirección es obligatoria.";
      hayErrores = true;
    }

    if (hayErrores) return;

    const datos = {
      id,
      nombre,
      apellidos,
      dni,
      email,
      telefono,
      direccion,
      documentos,
    };

    fetch("/controllers/TrabController.php?action=modificarTrabajador", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ datos: datos }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.mensaje) {
          alert(data.mensaje);
          cargar("#portada", "/views/vertrabajadores.php");
        } else {
          document.getElementById("errorGeneral").textContent =
            data.error || "Error al modificar.";
        }
      })
      .catch((error) => {
        console.error("Error al modificar trabajador:", error);
        alert("Error en el servidor.");
      });
  });
