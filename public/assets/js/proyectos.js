console.log("proyectos.js cargado correctamente");

//Función para actualizar los datos después de eliminar o modificar
function cargarProyectos() {
  console.log("Intentando cargar proyectos...");

  fetch("/controllers/ProyecController.php?action=listarProyectos")
    .then((response) => {
      console.log("Respuesta recibida:", response);
      return response.json();
    })
    .then((data) => {
      console.log("Datos recibidos para actualizar tabla:", data);

      let tabla = document
        .getElementById("proyectosTabla")
        .querySelector("tbody");
      if (!tabla) {
        console.error("La tabla no está definida");
        return;
      }

      tabla.innerHTML = "";

      data.forEach((proyecto) => {
        let row = `
                    <tr data-id="${proyecto.id_proyec}">
                        <td class="ocultar-sm">${proyecto.id_proyec}</td>
                        <td class='editable'>${proyecto.trabajo}</td>
                        <td >${proyecto.localizacion_finca}</td>
                        <td>${proyecto.localizacion_finca}</td>
                        <td>${proyecto.tipo_cultivo}</td>
                        <td>${proyecto.nombre_contratista}</td>
                        <td>${proyecto.nombre_proveedor}</td>
                        <td class='editable'>${proyecto.fecha_inicio}</td>
                        <td class='editable'>${proyecto.fecha_fin}</td>
                        <td>
                            <button onclick="editarProyecto(${proyecto.id_proyec})">Modificar</button>
                            <button style="display:none;" onclick="guardarProyecto(${proyecto.id_proyec})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarProyecto(${proyecto.id_proyec})">Eliminar</button>
                        </td>
                    </tr>
                `;

        tabla.innerHTML += row;
      });

      console.log("Tabla actualizada correctamente");
    })
    .catch((error) => console.error("Error al cargar proyectos:", error));
}

// Eliminar proyecto
function eliminarProyecto(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este proyecto?")) {
    fetch(
      `/controllers/ProyecController.php?action=eliminarProyecto&id=${id}`,
      {
        method: "GET",
      }
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error en la solicitud: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        alert(data.mensaje || data.error);

        // Obtener tipo de usuario y id del HTML
        const datosUsuario = document.getElementById("datosUsuario");
        const tipo = datosUsuario.dataset.tipo;
        const idCont = datosUsuario.dataset.idCont;
        if (tipo === "contratista") {
          cargar("#portada", `/views/verproyectos.php?id=${idCont}`);
        } else {
          cargar("#portada", "/views/verproyectos.php");
        }
      })
      .catch((error) => console.error("Error al eliminar proyecto:", error));
  }
}

function asignarTrabajador(e, idProyecto) {
  e.preventDefault();
  const select = e.target.querySelector('select');
  const idTrab = select.value;

  fetch(`/controllers/ProyecController.php?action=asociarTrabajador&id_proy=${idProyecto}&id_trab=${idTrab}`)
      .then(res => res.json())
      .then(data => {
          alert(data.mensaje || data.error);
          cargar('#portada', '/views/vertrabajadores_proyecto.php');
      });
}


  document.body.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar-trabajador")) {
      const idTrab = e.target.dataset.idTrab;
      const idProyec = e.target.dataset.idProyec;

      if (confirm("¿Eliminar este trabajador de la campaña?")) {
        fetch(`/controllers/ProyecController.php?action=eliminarTrabajador&id_proyec=${idProyec}&id_trab=${idTrab}`)
          .then(res => res.json())
          .then(data => {
            alert(data.mensaje || data.error);
            cargar('#portada', '/views/vertrabajadores_proyecto.php');
          })
          .catch(err => console.error("Error al eliminar trabajador:", err));
      }
    }
  });

