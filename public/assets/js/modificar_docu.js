
  // const tablas = document.querySelectorAll(".tabla-documentos");

  // tablas.forEach((tabla) => {
  //   tabla.addEventListener("click", (event) => {
  //     const botonEditar = event.target.closest(".editar");
  //     const botonGuardar = event.target.closest(".guardar");

  //     if (botonEditar) {
  //       const fila = botonEditar.closest("tr");
  //       const celdaFecha = fila.querySelector(".fecha");
  //       const fechaOriginal = celdaFecha.textContent.trim();

  //       // Guardamos valores originales
  //       fila.dataset.fechaOriginal = fechaOriginal;

  //       // Convertimos en campo editable
  //       celdaFecha.innerHTML = `<input type="date" value="${fechaOriginal}">`;

  //       // Mostrar botón guardar, ocultar editar
  //       botonEditar.style.display = "none";
  //       fila.querySelector(".guardar").style.display = "inline-block";
  //     }

  //     if (botonGuardar) {
  //       const fila = botonGuardar.closest("tr");
  //       const idDoc = fila.dataset.idDoc;
  //       const inputFecha = fila.querySelector(".fecha input");
  //       const nuevaFecha = inputFecha.value;

  //       if (!nuevaFecha || nuevaFecha === fila.dataset.fechaOriginal) {
  //         alert("No hay cambios que guardar.");
  //         fila.querySelector(".fecha").textContent = fila.dataset.fechaOriginal;
  //         fila.querySelector(".guardar").style.display = "none";
  //         fila.querySelector(".editar").style.display = "inline-block";
  //         return;
  //       }

  //       const datos = {
  //         id_doc: idDoc,
  //         nueva_fecha: nuevaFecha
  //       };

  //       fetch("/controllers/DocuController.php?action=modificarDocumento", {
  //         method: "POST",
  //         headers: {
  //           "Content-Type": "application/json"
  //         },
  //         body: JSON.stringify(datos)
  //       })
  //         .then(res => res.json())
  //         .then(data => {
  //           if (data.mensaje) {
  //             alert(data.mensaje);
  //             fila.querySelector(".fecha").textContent = nuevaFecha;
  //           } else {
  //             alert(data.error || "Error al modificar documento.");
  //           }
  //           fila.querySelector(".guardar").style.display = "none";
  //           fila.querySelector(".editar").style.display = "inline-block";
  //         })
  //         .catch(err => {
  //           console.error(err);
  //           alert("Error del servidor.");
  //         });
  //     }
  //   });
  // });



console.log("se carga modificar_docu.php")

 document.getElementById("formModificarDocumento").addEventListener("submit", function (e) {
    e.preventDefault();

    // Limpiar errores anteriores
    document.querySelectorAll(".error").forEach(el => el.textContent = "");

    const id_doc = document.getElementById('id_doc').value;
    const fecha_caducidad = document.getElementById('fecha_caducidad').value;

    if (!fecha_caducidad) {
      document.getElementById("errorFecha").textContent = "La fecha es obligatoria.";
      return;
    }

    const datos = { id_doc, fecha_caducidad };

    fetch("/controllers/DocuController.php?action=modificarDocumento", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(data => {
      if (data.mensaje) {
        alert(data.mensaje);
        cargar('#portada', '/views/verdocumentos.php');
      } else {
        document.getElementById("errorGeneral").textContent = data.error || "Error al modificar.";
      }
    })
    .catch(() => {
      document.getElementById("errorGeneral").textContent = "Error de comunicación con el servidor.";
    });
  });

