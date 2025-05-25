const tablaFincas = document.getElementById("fincasTabla");

tablaFincas.addEventListener("click", (event) => {
  if (event.target.classList.contains("editar")) {
    let row = event.target.closest("tr");

    row.dataset.originalCultivo = row.querySelector("td:nth-child(3)").innerText;
    row.dataset.originalHectareas = row.querySelector("td:nth-child(4)").innerText;
    row.dataset.originalLocalizacion = row.querySelector("td:nth-child(5)").innerText;

    row.querySelectorAll(".editable").forEach((cell) => {
      let valor = cell.innerText.trim(); // Excluir el boton
      let enlaceMapa = cell.querySelector('.enlace_mapa');
  
      if (enlaceMapa) {
          enlaceMapa.remove(); 
      }
  
      cell.innerHTML = `<input type="text" value="${valor}">`;
  
      if (enlaceMapa) {
          cell.appendChild(enlaceMapa);
      }
  });

    row.querySelector(".editar").style.display = "none";
    row.querySelector(".guardar").style.display = "inline-block";
  }
});

tablaFincas.addEventListener("click", (event) => {
  if (event.target.classList.contains("guardar")) {
    let row = event.target.closest("tr");
    let id = row.dataset.id;
    let cultivo = row.querySelector("td:nth-child(3) input").value; 
    let hectareas = parseInt(row.querySelector("td:nth-child(4) input").value) || 0;
    let localizacion = row.querySelector("td:nth-child(5) input").value;
    let originalHectareas = parseInt(row.dataset.originalHectareas) || 0;

  
    if (
      cultivo === row.dataset.originalCultivo &&
      hectareas === originalHectareas &&
      localizacion === row.dataset.originalLocalizacion
    ) {
      alert("No se realizaron cambios, los datos son los mismos.");

     
      row.querySelector("td:nth-child(3)").innerText = row.dataset.originalCultivo;
      row.querySelector("td:nth-child(4)").innerText = originalHectareas;
      row.querySelector("td:nth-child(5)").innerText = row.dataset.originalLocalizacion;

      row.querySelector(".editar").style.display = "inline-block";
      row.querySelector(".guardar").style.display = "none";
      return;
    }

    let datos = [id, cultivo, hectareas, localizacion];

    console.log("Datos enviados para modificar:", datos);

   
    fetch("/controllers/FincasController.php?action=modificarFinca", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ datos: datos })
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.mensaje) {
          alert(data.mensaje);

          
          row.querySelector("td:nth-child(3)").innerText = cultivo;
          row.querySelector("td:nth-child(4)").innerText = hectareas;
          row.querySelector("td:nth-child(5)").innerText = localizacion;

          row.querySelector(".editar").style.display = "inline-block";
          row.querySelector(".guardar").style.display = "none";
        } else {
          alert(data.error);
        }
      })
      .catch((error) => {
        console.error("Error al modificar finca:", error);
        alert("Error en el servidor.");
      });
  }
});
