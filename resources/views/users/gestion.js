document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
    cargarTodosLosRoles();
});

let listaRolesGlobal = [];

// Obtiene todos los roles disponibles en el sistema para armar los checkboxes
function cargarTodosLosRoles() {
    fetch('api_gestion.php?accion=listar_roles')
        .then(res => res.json())
        .then(roles => { listaRolesGlobal = roles; });
}

function cargarUsuarios() {
    fetch('api_gestion.php?accion=listar_usuarios')
        .then(res => res.json())
        .then(usuarios => {
            const tbody = document.getElementById('tabla-usuarios');
            tbody.innerHTML = '';
            
            usuarios.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.roles ? user.roles : '<em>Ninguno</em>'}</td>
                    <td><button class="btn" onclick="abrirModal(${user.id}, '${user.username}', '${user.id_roles || ''}')">Editar Roles</button></td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function abrirModal(id, username, idRolesString) {
    document.getElementById('modal-user-id').value = id;
    document.getElementById('modal-username').textContent = username;
    
    // Convertir la lista de IDs de roles actuales en un array numérico
    const rolesActuales = idRolesString ? idRolesString.split(',').map(Number) : [];
    const contenedor = document.getElementById('contenedor-checkboxes');
    contenedor.innerHTML = '';

    // Renderizar un checkbox por cada rol existente en el sistema
    listaRolesGlobal.forEach(rol => {
        const marcar = rolesActuales.includes(rol.id) ? 'checked' : '';
        contenedor.innerHTML += `
            <label>
                <input type="checkbox" name="roles[]" value="${rol.id}" ${marcar}> ${rol.nombre}
            </label>
        `;
    });

    document.getElementById('modal-roles').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modal-roles').style.display = 'none';
}

// Procesar el envío del formulario mediante AJAX
document.getElementById('form-roles').addEventListener('submit', (e) => {
    e.preventDefault();
    
    const idUsuario = document.getElementById('modal-user-id').value;
    const formData = new FormData(e.target);
    formData.append('id_usuario', idUsuario);
    formData.append('accion', 'actualizar_roles');

    fetch('api_gestion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Roles actualizados correctamente');
            cerrarModal();
            cargarUsuarios();
        } else {
            alert('Error: ' + data.message);
        }
    });
});
