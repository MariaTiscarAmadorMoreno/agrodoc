console.log("proveedores.js cargado correctamente");

//Función para actualizar los datos después de eliminar o modificar
function cargarProveedores() {
    console.log("Intentando cargar proveedores...");

    fetch('/controllers/ProvController.php?action=listarProveedores')
        .then(response => {
            console.log("Respuesta recibida:", response);
            return response.json();  
        })
        .then(data => {
            console.log("Datos recibidos para actualizar tabla:", data);

            let tabla = document.getElementById("proveedoresTabla").querySelector('tbody');
            if (!tabla) {
                console.error("La tabla no está definida");
                return;
            }

            tabla.innerHTML = ""; 

            data.forEach(proveedor => {
               
                let row = `
                    <tr data-id="${proveedor.id_prov}">
                        <td class="ocultar-sm">${proveedor.id_prov}</td>
                        <td class='editable'>${proveedor.nombre}</td>
                        <td class='editable'>${proveedor.apellidos}</td>
                        <td class='editable'>${proveedor.cif}</td>
                        <td class='editable'>${proveedor.email}</td>
                        <td class='editable'>${proveedor.telefono}</td>
                        <td class='editable'>${proveedor.direccion}</td>
                        <td>
                            <button onclick="editarProveedor(${proveedor.id_prov})">Modificar</button>
                            <button style="display:none;" onclick="guardarProveedor(${proveedor.id_prov})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarProveedor(${proveedor.id_prov})">Eliminar</button>
                        </td>
                    </tr>
                `;

                tabla.innerHTML += row;
            });

            console.log("Tabla actualizada correctamente");
        })
        .catch(error => console.error("Error al cargar proveedores:", error));
}

// Eliminar proveedor 
function eliminarProveedor(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este proveedor?")) {
        fetch(`/controllers/ProvController.php?action=eliminarProveedor&id=${id}`, {
            method: 'GET',
        })
        .then(response =>             
            response.json())           
        .then(data => {
            if (data.mensaje) {
                alert(data.mensaje);
                console.log("Proveedor eliminado correctamente. Actualizando la lista...");
                cargarProveedores(); 
            } else {
                alert("Error al eliminar proveedor: " + data.error);
            }
        })
        .catch(error => console.error("Error al eliminar proveedor:", error));
    }
}
