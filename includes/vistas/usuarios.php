<?php
if (!checkPermission('OWNER')) {
    echo "<h1>Acceso Denegado</h1><p>Esta sección es solo para el rol OWNER.</p>";
    return;
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Gestión de Usuarios</h2>
        <p class="text-muted">Crea y administra las credenciales del sistema.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="fas fa-user-plus me-2"></i> Nuevo Usuario
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4">Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaUsuarios">
                    <!-- Dinámico -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="userId">
                <div class="mb-3">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="name" id="userName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email / Usuario</label>
                    <input type="email" class="form-control" name="email" id="userEmail" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="userPass"
                        placeholder="Dejar vacío para no cambiar">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select class="form-select" name="role" id="userRole" required>
                        <option value="OWNER">OWNER</option>
                        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                        <option value="COLABORADOR">COLABORADOR</option>
                        <option value="MONITOR">MONITOR</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        function loadUsers() {
            $.post('includes/endpoints/usuarios.php', { action: 'list' }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(u => {
                        html += `
                        <tr>
                            <td class="px-4 fw-medium" data-label="Nombre">${u.name}</td>
                            <td data-label="Email">${u.email}</td>
                            <td data-label="Rol"><span class="badge ${u.role === 'OWNER' ? 'bg-danger' : 'bg-primary'}">${u.role}</span></td>
                            <td data-label="Estado"><span class="badge ${u.status === 1 ? 'bg-success' : 'bg-secondary'}">${u.status === 1 ? 'Activo' : 'Inactivo'}</span></td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 edit-user" data-user='${JSON.stringify(u)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-user" data-id="${u.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaUsuarios').html(html);
                }
            }, 'json');
        }

        $(document).on('click', '.edit-user', function () {
            const u = $(this).data('user');
            $('#userId').val(u.id);
            $('#userName').val(u.name);
            $('#userEmail').val(u.email);
            $('#userRole').val(u.role);
            $('#userPass').attr('placeholder', 'Dejar vacío para no cambiar');
            $('#modalUsuario').modal('show');
        });

        $('#formUsuario').submit(function (e) {
            e.preventDefault();
            const data = $(this).serialize();
            const action = $('#userId').val() ? 'update' : 'create';

            $.post('includes/endpoints/usuarios.php', data + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success');
                    $('#modalUsuario').modal('hide');
                    loadUsers();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.delete-user', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/usuarios.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadUsers();
                        }
                    }, 'json');
                }
            });
        });

        loadUsers();
    });
</script>