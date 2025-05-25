console.log("contratista.js cargado correctamente");

//Función para actualizar los datos después de eliminar o modificar
function cargarContratistas() {
    console.log("Intentando cargar contratistas...");

    fetch('/controllers/ContController.php?action=listarContratistas')
        .then(response => {
            console.log("Respuesta recibida:", response);
            return response.json();  
        })
        .then(data => {
            console.log("Datos recibidos para actualizar tabla:", data);

            let tabla = document.getElementById("contratistasTabla").querySelector('tbody');
            if (!tabla) {
                console.error("La tabla no está definida");
                return;
            }

            tabla.innerHTML = ""; 

            data.forEach(contratista => {
               
                let row = `
                    <tr data-id="${contratista.id_cont}">
                        <td class="ocultar-sm">${contratista.id_cont}</td>
                        <td class='editable'>${contratista.nombre}</td>
                        <td class='editable'>${contratista.cif}</td>
                        <td class='editable'>${contratista.email}</td>
                        <td class='editable'>${contratista.telefono}</td>
                        <td class='editable'>${contratista.direccion}</td>
                        <td>
                            <button onclick="editarContratista(${contratista.id_cont})">Modificar</button>
                            <button style="display:none;" onclick="guardarContratista(${contratista.id_cont})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarContratista(${contratista.id_cont})">Eliminar</button>
                        </td>
                    </tr>
                `;

                tabla.innerHTML += row;
            });

            console.log("Tabla actualizada correctamente");
        })
        .catch(error => console.error("Error al cargar contratistas:", error));
}

// Eliminar contratista 
function eliminarContratista(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este contratista?")) {
        fetch(`/controllers/ContController.php?action=eliminarContratista&id=${id}`, {
            method: 'GET',
        })
        .then(response =>             
            response.json())           
        .then(data => {
            if (data.mensaje) {
                alert(data.mensaje);
                console.log("Contratista eliminado correctamente. Actualizando la lista...");
                cargarContratistas(); 
            } else {
                alert("Error al eliminar contratista: " + data.error);
            }
        })
        .catch(error => console.error("Error al eliminar contratista:", error));
    }
}
