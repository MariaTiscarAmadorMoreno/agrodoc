console.log("fincas.js cargado correctamente");

//Función para actualizar los datos después de eliminar o modificar
function cargarFincas() {
    console.log("Intentando cargar fincas...");

    fetch('/controllers/FincasController.php?action=listarFincas')
        .then(response => {
            console.log("Respuesta recibida:", response);
            return response.json();  
        })
        .then(data => {
            console.log("Datos recibidos para actualizar tabla:", data);

            let tabla = document.getElementById("fincasTabla").querySelector('tbody');
            if (!tabla) {
                console.error("La tabla no está definida");
                return;
            }
           
            tabla.innerHTML = ""; 

            data.forEach(finca => {
               
                let row = `
                    <tr data-id="${finca.id_finca}">
                        <td class="ocultar-sm">${finca.id_finca}</td>
                        <td class='editable'>${finca.nombre_contratista}</td>
                        <td class='editable'>${finca.cultivo}</td>
                        <td class='editable'>${finca.hectarea}</td>
                        <td class='editable' id="localizacion">${finca.localizacion}</td>
                        <td><a href="javascript:void(0);"class="enlace_mapa">Ver en mapa</a></td>
                        <td>
                            <button onclick="editarFinca(${finca.id_finca})">Modificar</button>
                            <button style="display:none;" onclick="guardarFinca(${finca.id_finca})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarFinca(${finca.id_finca})">Eliminar</button>
                        </td>
                    </tr>
                `;

                tabla.innerHTML += row;
            });

            console.log("Tabla actualizada correctamente");
        })
        .catch(error => console.error("Error al cargar fincas:", error));
}

// Eliminar finca 
// function eliminarFinca(id) {
//     if (confirm("¿Estás seguro de que deseas eliminar este finca?")) {
//         fetch(`/controllers/FincasController.php?action=eliminarFinca&id=${id}`, {
//             method: 'GET',
//         })
//         .then(response =>             
//             response.json())           
//         .then(data => {
//             if (data.mensaje) {
//                 alert(data.mensaje);
//                 console.log("Finca eliminada correctamente. Actualizando la lista...");
//                 cargarFincas();
//             } else {
//                 alert("Error al eliminar finca: " + data.error);
//             }
//         })
//         .catch(error => console.error("Error al eliminar finca:", error));
//     }
// }

function eliminarFinca(idFinca) {
    if (confirm("¿Estás seguro de que deseas eliminar esta finca?")) {
        fetch(`/controllers/FincasController.php?action=eliminarFinca&id=${idFinca}`)
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje || data.error);

                // Obtener tipo de usuario y id del HTML
                const datosUsuario = document.getElementById('datosUsuario');
                const tipo = datosUsuario.dataset.tipo;
                const idCont = datosUsuario.dataset.idCont;

                if (tipo === 'contratista') {
                    cargar('#portada', `/views/verfincas.php?id=${idCont}`);
                } else {
                    cargar('#portada', '/views/verfincas.php');
                }
            })
            .catch(error => console.error("Error al eliminar:", error));
    }
}
