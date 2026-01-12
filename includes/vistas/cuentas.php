<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-0">Gestión de Cuentas</h1>
            <p class="text-muted small mb-0">Accesos a proveedores y servicios externos</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary px-3" onclick="loadAccounts()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-primary px-4 fw-bold" onclick="openAccountModal()">
                <i class="fas fa-plus me-2"></i> Nueva Cuenta
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small text-uppercase text-muted">
                            <tr>
                                <th class="px-4">Cuenta / Nombre</th>
                                <th>Proveedor</th>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                                <th>URL / Acceso</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="accountsList">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cuenta -->
<div class="modal fade" id="modalAccount" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formAccount">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Guardar Cuenta de Acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="accountId">

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nombre de la Cuenta</label>
                    <input type="text" class="form-control" name="name" id="accountName" required
                        placeholder="ej. GoDaddy Principal">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Proveedor</label>
                    <select class="form-select" name="provider_id" id="accountProviderId">
                        <option value="">Seleccione un proveedor...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Usuario / Email</label>
                    <input type="text" class="form-control" name="username" id="accountUser"
                        placeholder="admin@ejemplo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="accountPass"
                            placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('accountPass')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">URL de Acceso</label>
                    <input type="url" class="form-control" name="url" id="accountUrl"
                        placeholder="https://sso.godaddy.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Notas / Descripción</label>
                    <textarea class="form-control" name="description" id="accountDesc" rows="2"
                        placeholder="Información adicional..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function loadAccounts() {
        $.post('includes/endpoints/cuentas.php', { action: 'list' }, function (res) {
            if (res.status === 'success') {
                let html = '';
                if (res.data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center py-5 text-muted">No hay cuentas registradas</td></tr>';
                } else {
                    res.data.forEach(a => {
                        const urlBtn = a.url ? `
                            <a href="${a.url}" target="_blank" class="btn btn-sm btn-light" title="Ir al sitio"><i class="fas fa-external-link-alt"></i></a>
                            <button class="btn btn-sm btn-light js-copy-val" data-val="${a.url}" title="Copiar URL"><i class="fas fa-copy"></i></button>
                        ` : '-';

                        const userBtn = a.username ? `<button class="btn btn-sm btn-link p-0 ms-2 js-copy-val" data-val="${a.username}" title="Copiar Usuario"><i class="fas fa-copy text-muted fa-xs"></i></button>` : '';

                        const passHtml = a.password ? `
                            <div class="d-flex align-items-center">
                                <span class="password-masked" id="passText_${a.id}">••••••••</span>
                                <button class="btn btn-sm btn-link p-0 ms-2 js-toggle-row-pass" data-id="${a.id}" data-pass="${a.password}"><i class="fas fa-eye fa-xs text-muted"></i></button>
                                <button class="btn btn-sm btn-link p-0 ms-1 js-copy-val" data-val="${a.password}" title="Copiar Contraseña"><i class="fas fa-copy fa-xs text-muted"></i></button>
                            </div>
                        ` : '-';

                        html += `
                        <tr>
                            <td class="px-4 fw-bold text-primary">${a.name}</td>
                            <td class="small text-muted">${a.provider_name || 'Sin asignar'}</td>
                            <td class="small">${a.username || '-'} ${userBtn}</td>
                            <td>${passHtml}</td>
                            <td>${urlBtn}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 js-edit-account" data-id="${a.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger js-delete-account" data-id="${a.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                }
                $('#accountsList').html(html);
            }
        }, 'json');
    }

    function loadProvidersForAccount() {
        $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: 'mst_proveedores' }, function (res) {
            if (res.status === 'success') {
                let options = '<option value="">Seleccione un proveedor...</option>';
                res.data.forEach(p => { options += `<option value="${p.id}">${p.name}</option>`; });
                $('#accountProviderId').html(options);
            }
        }, 'json');
    }

    function openAccountModal(id = null) {
        $('#formAccount')[0].reset();
        $('#accountId').val('');
        loadProvidersForAccount();

        if (id) {
            $.post('includes/endpoints/cuentas.php', { action: 'fetch', id: id }, function (res) {
                if (res.status === 'success') {
                    const a = res.data;
                    $('#accountId').val(a.id);
                    $('#accountName').val(a.name);
                    $('#accountProviderId').val(a.provider_id || '');
                    $('#accountUser').val(a.username);
                    $('#accountPass').val(a.password);
                    $('#accountUrl').val(a.url);
                    $('#accountDesc').val(a.description);
                    $('#modalAccount').modal('show');
                }
            }, 'json');
        } else {
            $('#modalAccount').modal('show');
        }
    }

    function togglePass(id) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }

    $(document).ready(function () {
        loadAccounts();
        $('#formAccount').submit(function (e) {
            e.preventDefault();
            const action = $('#accountId').val() ? 'update' : 'create';
            $.post('includes/endpoints/cuentas.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    $('#modalAccount').modal('hide');
                    Swal.fire('Guardado', res.message, 'success');
                    loadAccounts();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.js-edit-account', function () { openAccountModal($(this).data('id')); });

        $(document).on('click', '.js-delete-account', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar cuenta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.post('includes/endpoints/cuentas.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadAccounts();
                        }
                    }, 'json');
                }
            });
        });

        $(document).on('click', '.js-copy-val', function () {
            const val = $(this).data('val');
            if (!val) return;
            navigator.clipboard.writeText(val).then(() => {
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.html('<i class="fas fa-check text-success fa-xs"></i>');
                setTimeout(() => { $btn.html(originalHtml); }, 1500);
            });
        });

        $(document).on('click', '.js-toggle-row-pass', function () {
            const id = $(this).data('id');
            const pass = $(this).data('pass');
            const $text = $(`#passText_${id}`);
            const $btn = $(this);

            if ($text.text() === '••••••••') {
                $text.text(pass).addClass('fw-bold text-dark');
                $btn.html('<i class="fas fa-eye-slash fa-xs text-muted"></i>');
            } else {
                $text.text('••••••••').removeClass('fw-bold text-dark');
                $btn.html('<i class="fas fa-eye fa-xs text-muted"></i>');
            }
        });
    });
</script>