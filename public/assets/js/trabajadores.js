//Función para actualizar los datos después de eliminar o modificar
function cargarTrabajadores() {
    const datosUsuario = document.getElementById('datosUsuario');
    const tipo = datosUsuario.dataset.tipo;
    const idProv = datosUsuario.dataset.idProv;
    console.log("Datos de proveedor:",tipo, idProv);

    let url = '/controllers/TrabController.php?action=listarTrabajadores';

    // Si es proveedor, pedimos solo sus trabajadores
    if (tipo === 'proveedor') {
        url = `/controllers/TrabController.php?action=listarTrabajadoresPorProveedor&id_prov=${idProv}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById("trabajadoresTabla").querySelector('tbody');
            tabla.innerHTML = "";

            data.forEach(trabajador => {
                const row = `
                    <tr data-id="${trabajador.id_trab}">
                        <td class="ocultar-sm">${trabajador.id_trab}</td>
                        <td class='editable'>${trabajador.nombre}</td>
                        <td class='editable'>${trabajador.apellidos}</td>
                        <td class='editable'>${trabajador.dni}</td>
                        <td class='editable'>${trabajador.email}</td>
                        <td class='editable'>${trabajador.telefono}</td>
                        <td class='editable'>${trabajador.direccion}</td>
                        <td>${trabajador.documentos ? 'Sí' : 'No'}</td>
                        <td>${trabajador.nombre_proveedor ?? ''} ${trabajador.apellidos_proveedor ?? ''}</td>
                        <td>
                            <button onclick="editarTrabajador(${trabajador.id_trab})">Modificar</button>
                            <button style="display:none;" onclick="guardarTrabajador(${trabajador.id_trab})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarTrabajador(${trabajador.id_trab})">Eliminar</button>
                        </td>
                    </tr>
                `;
                tabla.innerHTML += row;
            });
        })
        .catch(error => console.error("Error al cargar trabajadores:", error));
}


// Eliminar trabajador
function eliminarTrabajador(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este trabajador?")) {
        fetch(`/controllers/TrabController.php?action=eliminarTrabajador&id=${id}`)
        .then(response =>             
            response.json())           
        .then(data => {          
                alert(data.mensaje || data.error);              
               
                //Obtener tipo de usuario y id del HTML
                const datosUsuario = document.getElementById('datosUsuario');
                const tipo = datosUsuario.dataset.tipo;
                const idProv = datosUsuario.dataset.idProv; 

                if (tipo === 'proveedor') {
                    cargar('#portada', `/views/vertrabajadores.php?id=${idProv}`);
                } else {
                    cargar('#portada', '/views/vertrabajadores.php');
                }
            
        })
        .catch(error => console.error("Error al eliminar trabajador:", error));
    }
}
