//Función para actualizar los datos después de eliminar o modificar
function cargarUsuarios() {
    console.log("Intentando cargar usuarios...");

    fetch('/controllers/UserController.php?action=listarUsuarios')
        .then(response => {
            console.log("Respuesta recibida:", response);
            return response.json();  
        })
        .then(data => {
            console.log("Datos recibidos para actualizar tabla:", data);

            let tabla = document.getElementById("usuariosTabla").querySelector('tbody');
            if (!tabla) {
                console.error("La tabla no está definida");
                return;
            }

            tabla.innerHTML = ""; 

            data.forEach(usuario => {
                let tipoUsuario = '';

                if (usuario.tipo === 'contratista') {
                    tipoUsuario = `Contratista: ${usuario.nombre_contratista ?? 'No disponible'}`;
                } else if (usuario.tipo === 'proveedor') {
                    tipoUsuario = `Proveedor: ${usuario.nombre_proveedor ?? 'No disponible'} ${usuario.apellidos_proveedor ?? 'No disponible'}`;
                } else {
                    tipoUsuario = 'Administrador';
                }
                

                let row = `
                    <tr data-id="${usuario.id_usu}" data-tipo="${usuario.tipo}">
                        <td class="ocultar-sm">${usuario.id_usu}</td>
                        <td class='editable'>${usuario.nombre}</td>
                        <td class='editable'>${usuario.usuario}</td>
                        <td class='editable'>${usuario.clave}</td>
                        <td>${tipoUsuario}</td>
                        <td>
                            <button onclick="editarUsuario(${usuario.id_usu})">Modificar</button>
                            <button style="display:none;" onclick="guardarUsuario(${usuario.id_usu})">Guardar</button>
                        </td>
                        <td>
                            <button onclick="eliminarUsuario(${usuario.id_usu})">Eliminar</button>
                        </td>
                    </tr>
                `;

                tabla.innerHTML += row;
            });

            console.log("Tabla actualizada correctamente");
        })
        .catch(error => console.error("Error al cargar usuarios:", error));
}

// Eliminar usuario 
function eliminarUsuario(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
        fetch(`/controllers/UserController.php?action=eliminarUsuario&id=${id}`, {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            if (data.mensaje) {
                alert(data.mensaje);
                console.log("Usuario eliminado correctamente. Actualizando la lista...");
                cargarUsuarios(); 
            } else {
                alert("Error al eliminar usuario: " + data.error);
            }
        })
        .catch(error => console.error("Error al eliminar usuario:", error));
    }
}

