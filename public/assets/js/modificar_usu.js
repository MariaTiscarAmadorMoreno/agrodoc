// const tablaUsuarios = document.getElementById("usuariosTabla");

// tablaUsuarios.addEventListener("click", (event) => {
//   if (event.target.classList.contains("editar")) {
//     let row = event.target.closest("tr");

//     //Guardamos los valores originales para comparar después
//     row.dataset.originalNombre = row.querySelector("td:nth-child(2)").innerText;
//     row.dataset.originalUsuario =
//       row.querySelector("td:nth-child(3)").innerText;
//     row.dataset.originalClave = row.querySelector("td:nth-child(4)").innerText;

//     row.querySelectorAll(".editable").forEach((cell) => {
//       let valor = cell.innerText;
//       cell.innerHTML = `<input type="text" value="${valor}">`;
//     });

//     row.querySelector(".editar").style.display = "none";
//     row.querySelector(".guardar").style.display = "inline-block";
//   }
// });

// tablaUsuarios.addEventListener("click", (event) => {
//   if (event.target.classList.contains("guardar")) {
//     let row = event.target.closest("tr");
//     let id = row.dataset.id;
//     let nombre = row.querySelector("td:nth-child(2) input").value;
//     let usuario = row.querySelector("td:nth-child(3) input").value;
//     let clave = row.querySelector("td:nth-child(4) input").value;

//     let tipo = row.dataset.tipo || "admin";
//     let idCont = tipo === "contratista" ? row.dataset.idCont || null : null;
//     let idProv = tipo === "proveedor" ? row.dataset.idProv || null : null;

//     //Comprobamos si los valores son iguales a los originales y si son iguales, no realizamos ningún cambio
//     if (
//       nombre === row.dataset.originalNombre &&
//       usuario === row.dataset.originalUsuario &&
//       clave === row.dataset.originalClave
//     ) {
//       alert("No se realizaron cambios, los datos son los mismos.");

//       //Volvermos a mostrar los valores originales si no hay cambios
//       row.querySelector("td:nth-child(2)").innerText =
//         row.dataset.originalNombre;
//       row.querySelector("td:nth-child(3)").innerText =
//         row.dataset.originalUsuario;
//       row.querySelector("td:nth-child(4)").innerText =
//         row.dataset.originalClave;

//       row.querySelector(".editar").style.display = "inline-block";
//       row.querySelector(".guardar").style.display = "none";
//       return;
//     }

//     if (!nombre || !usuario) {
//       alert("El nombre y el usuario no pueden estar vacíos.");
//       return;
//     }

//     let datos = {
//       id: id,
//       usuario: usuario,
//       clave: clave,
//       nombre: nombre,
//       tipo: tipo,
//       id_cont: idCont,
//       id_prov: idProv,
//     };

//     console.log("Datos enviados para modificar:", datos);

//     fetch("/controllers/UserController.php?action=modificarUsuario", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify({ datos: datos }),
//     })
//       .then((response) => response.json())
//       .then((data) => {
//         if (data.mensaje) {
//           alert(data.mensaje);

//           //Actualizamos los valores directamente en la tabla
//           row.querySelector("td:nth-child(2)").innerText = nombre;
//           row.querySelector("td:nth-child(3)").innerText = usuario;
//           row.querySelector("td:nth-child(4)").innerText = clave;

//           row.querySelector(".editar").style.display = "inline-block";
//           row.querySelector(".guardar").style.display = "none";
//         } else {
//           alert(data.error);
//         }
//       })
//       .catch((error) => {
//         console.error("Error al modificar usuario:", error);
//         alert("Error en el servidor.");
//       });
//   }
// });



    const form = document.getElementById('formEditarUsuario');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log("se carga nuevo archivo modificar_usu,js")

        document.querySelectorAll('.error').forEach(div => div.textContent = '');

        const id = document.getElementById('id').value;
        const tipo= document.getElementById('tipo').value;
        const nombre = document.getElementById('nombre').value.trim();
        const usuario = document.getElementById('usuario').value.trim();
        const clave = document.getElementById('clave').value.trim();

        let hayErrores = false;

        if (!nombre) {
            document.getElementById('errorNombre').textContent = "El nombre es obligatorio.";
            hayErrores = true;
        }

        if (!usuario) {
            document.getElementById('errorUsuario').textContent = "El usuario es obligatorio.";
            hayErrores = true;
        }

        const claveRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!claveRegex.test(clave)) {
            document.getElementById('errorClave').textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.";
            hayErrores = true;
        }

        if (hayErrores) return;

        // Comprobar si el nombre de usuario ya existe en otro usuario
        const existe = await fetch(`/controllers/UserController.php?action=existeUsuario&usuario=${usuario}`)
            .then(res => res.json())
            .then(data => data.existe && usuario !== document.getElementById('usuario').defaultValue)
            .catch(() => true);

        if (existe) {
            document.getElementById('errorUsuario').textContent = "Ese usuario ya está registrado.";
            return;
        }

        const datos = {
            id,
            nombre,
            usuario,
            clave,
            tipo
        };

        fetch('/controllers/UserController.php?action=modificarUsuario', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ datos })
        })
        .then(r => r.json())
        .then(data => {
          console.log("Respuesta del servidor:", data);
            if (data.mensaje) {
                alert(data.mensaje);
               cargar('#portada', '/views/verusuarios.php');
            } else {
                document.getElementById('errorGeneral').textContent = data.error || "Error desconocido";
            }
        })
        .catch(() => {
            document.getElementById('errorGeneral').textContent = "Error en el servidor.";
        });
    });

