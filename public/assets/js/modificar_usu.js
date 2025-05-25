const tablaUsuarios = document.getElementById("usuariosTabla");

tablaUsuarios.addEventListener("click", (event) => {
  if (event.target.classList.contains("editar")) {
    let row = event.target.closest("tr");

    //Guardamos los valores originales para comparar después
    row.dataset.originalNombre = row.querySelector("td:nth-child(2)").innerText;
    row.dataset.originalUsuario =
      row.querySelector("td:nth-child(3)").innerText;
    row.dataset.originalClave = row.querySelector("td:nth-child(4)").innerText;

    row.querySelectorAll(".editable").forEach((cell) => {
      let valor = cell.innerText;
      cell.innerHTML = `<input type="text" value="${valor}">`;
    });

    row.querySelector(".editar").style.display = "none";
    row.querySelector(".guardar").style.display = "inline-block";
  }
});

tablaUsuarios.addEventListener("click", (event) => {
  if (event.target.classList.contains("guardar")) {
    let row = event.target.closest("tr");
    let id = row.dataset.id;
    let nombre = row.querySelector("td:nth-child(2) input").value;
    let usuario = row.querySelector("td:nth-child(3) input").value;
    let clave = row.querySelector("td:nth-child(4) input").value;

    let tipo = row.dataset.tipo || "admin";
    let idCont = tipo === "contratista" ? row.dataset.idCont || null : null;
    let idProv = tipo === "proveedor" ? row.dataset.idProv || null : null;

    //Comprobamos si los valores son iguales a los originales y si son iguales, no realizamos ningún cambio
    if (
      nombre === row.dataset.originalNombre &&
      usuario === row.dataset.originalUsuario &&
      clave === row.dataset.originalClave
    ) {
      alert("No se realizaron cambios, los datos son los mismos.");

      //Volvermos a mostrar los valores originales si no hay cambios
      row.querySelector("td:nth-child(2)").innerText =
        row.dataset.originalNombre;
      row.querySelector("td:nth-child(3)").innerText =
        row.dataset.originalUsuario;
      row.querySelector("td:nth-child(4)").innerText =
        row.dataset.originalClave;

      row.querySelector(".editar").style.display = "inline-block";
      row.querySelector(".guardar").style.display = "none";
      return;
    }

    if (!nombre || !usuario) {
      alert("El nombre y el usuario no pueden estar vacíos.");
      return;
    }

    let datos = {
      id: id,
      usuario: usuario,
      clave: clave,
      nombre: nombre,
      tipo: tipo,
      id_cont: idCont,
      id_prov: idProv,
    };

    console.log("Datos enviados para modificar:", datos);

    fetch("/controllers/UserController.php?action=modificarUsuario", {
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

          //Actualizamos los valores directamente en la tabla
          row.querySelector("td:nth-child(2)").innerText = nombre;
          row.querySelector("td:nth-child(3)").innerText = usuario;
          row.querySelector("td:nth-child(4)").innerText = clave;

          row.querySelector(".editar").style.display = "inline-block";
          row.querySelector(".guardar").style.display = "none";
        } else {
          alert(data.error);
        }
      })
      .catch((error) => {
        console.error("Error al modificar usuario:", error);
        alert("Error en el servidor.");
      });
  }
});
