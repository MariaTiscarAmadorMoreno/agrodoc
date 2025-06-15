document.getElementById("formEditarProyecto").addEventListener("submit", function (e) {
    e.preventDefault();
    document.querySelectorAll(".error").forEach((div) => (div.textContent = ""));

    const trabajo = document.getElementById("trabajo").value.trim();
    const fecha_inicio = document.getElementById("fecha_inicio").value.trim();
    const fecha_fin = document.getElementById("fecha_fin").value;
    const id = document.getElementById("id").value;

   

    let hayErrores = false;

 
    if (!trabajo) {
      document.getElementById("errorTrabajo").textContent =
        "El tipo de trabajo es obligatorio.";
      hayErrores = true;
    }
     if (!fecha_inicio) {
      document.getElementById("errorFechaInicio").textContent =
        "La fecha de inicio es obligatoria.";
      hayErrores = true;
    }
      if (!fecha_fin) {
      document.getElementById("errorFechaFin").textContent =
        "La fecha de fin es obligatoria.";
      hayErrores = true;
    }

    if (hayErrores) return;

    const datos = [id, trabajo, fecha_inicio, fecha_fin];

    fetch("/controllers/ProyecController.php?action=modificarProyecto", {
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
            cargar('#portada', '/views/verproyectos.php');
        } else {
            document.getElementById('errorGeneral').textContent = data.error || "Error al modificar.";
        }
      })
      .catch((error) => {
        console.error("Error al modificar proyecto:", error);
        alert("Error en el servidor.");
      });
  });
