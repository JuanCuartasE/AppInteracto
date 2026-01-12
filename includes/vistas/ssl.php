<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-0">Gestión de Certificados SSL</h1>
            <p class="text-muted small mb-0">Control de certificados, servidores y proveedores</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary px-3" onclick="loadSSL()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-primary px-4 fw-bold" onclick="openSSLModal()">
                <i class="fas fa-plus me-2"></i> Nuevo Certificado
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
                                <th class="px-4">Servidor</th>
                                <th>Cliente / Propietario</th>
                                <th>Proveedor</th>
                                <th>Email Responsable</th>
                                <th>Vencimiento</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="sslList">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal SSL -->
<div class="modal fade" id="modalSSL" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formSSL">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Guardar Certificado SSL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="sslId">

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Servidor / VPS</label>
                    <select class="form-select" name="server_id" id="sslServerId" required>
                        <option value="">Seleccione un servidor...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Propietario / Cliente</label>
                    <select class="form-select" name="client_id" id="sslClientId">
                        <option value="">Cargando...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Proveedor</label>
                    <select class="form-select" name="provider_id" id="sslProviderId">
                        <option value="">Seleccione proveedor...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email del Responsable</label>
                    <input type="email" class="form-control" name="responsible_email" id="sslEmail"
                        placeholder="correo@ejemplo.com">
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha Creación</label>
                        <input type="date" class="form-control" name="creation_date" id="sslCreDay">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha Vencimiento</label>
                        <input type="date" class="form-control" name="expiration_date" id="sslExpDay">
                    </div>
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
    function loadSSL() {
        $.post('includes/endpoints/ssl.php', { action: 'list' }, function (res) {
            if (res.status === 'success') {
                let html = '';
                if (res.data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center py-5 text-muted">No hay certificados registrados</td></tr>';
                } else {
                    res.data.forEach(s => {
                        let expiryBadge = '';
                        if (s.expiration_date) {
                            const today = new Date();
                            const exp = new Date(s.expiration_date);
                            const diffDays = Math.ceil((exp - today) / (1000 * 60 * 60 * 24));
                            if (diffDays < 0) expiryBadge = '<span class="badge bg-danger ms-2">Expirado</span>';
                            else if (diffDays < 30) expiryBadge = `<span class="badge bg-warning text-dark ms-2">${diffDays} días</span>`;
                        }

                        const providerUrl = s.provider_url ? `<a href="${s.provider_url}" target="_blank" class="ms-1 text-muted" title="Ir al sitio"><i class="fas fa-external-link-alt fa-xs"></i></a><button class="btn btn-link btn-sm p-0 ms-1 text-muted js-copy-url" data-url="${s.provider_url}" title="Copiar URL"><i class="fas fa-copy fa-xs"></i></button>` : '';
                        html += `
                        <tr>
                            <td class="px-4 fw-bold text-primary">${s.server_name}</td>
                            <td>${s.owner_display}</td>
                            <td class="small">${s.provider_name || 'Interno'} ${providerUrl}</td>
                            <td class="small text-muted">${s.responsible_email || '-'}</td>
                            <td class="small fw-bold">${s.expiration_date || '-'} ${expiryBadge}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 js-edit-ssl" data-id="${s.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger js-delete-ssl" data-id="${s.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                }
                $('#sslList').html(html);
            }
        }, 'json');
    }

    function loadSSLData() {
        // Clients
        $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
            if (res.status === 'success') {
                let options = '';
                res.data.forEach(c => { options += `<option value="${c.id}">${c.name}</option>`; });
                $('#sslClientId').html(options);
            }
        }, 'json');

        // Servers
        $.post('includes/endpoints/servidores.php', { action: 'list' }, function (res) {
            if (res.status === 'success') {
                let options = '<option value="">Seleccione un servidor...</option>';
                res.data.forEach(srv => { options += `<option value="${srv.id}">${srv.name} (${srv.ipv4})</option>`; });
                $('#sslServerId').html(options);
            }
        }, 'json');

        // Providers
        $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: 'mst_proveedores' }, function (res) {
            if (res.status === 'success') {
                let options = '<option value="">Sin proveedor / Interno</option>';
                res.data.forEach(p => { options += `<option value="${p.id}">${p.name}</option>`; });
                $('#sslProviderId').html(options);
            }
        }, 'json');
    }

    function openSSLModal(id = null) {
        $('#formSSL')[0].reset();
        $('#sslId').val('');
        loadSSLData();

        if (id) {
            $.post('includes/endpoints/ssl.php', { action: 'fetch', id: id }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#sslId').val(s.id);
                    $('#sslServerId').val(s.server_id);
                    $('#sslClientId').val(s.client_id || '');
                    $('#sslProviderId').val(s.provider_id || '');
                    $('#sslEmail').val(s.responsible_email);
                    $('#sslCreDay').val(s.creation_date);
                    $('#sslExpDay').val(s.expiration_date);
                    $('#modalSSL').modal('show');
                }
            }, 'json');
        } else {
            $('#modalSSL').modal('show');
        }
    }

    $(document).ready(function () {
        loadSSL();
        $('#formSSL').submit(function (e) {
            e.preventDefault();
            const action = $('#sslId').val() ? 'update' : 'create';
            $.post('includes/endpoints/ssl.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    $('#modalSSL').modal('hide');
                    Swal.fire('Guardado', res.message, 'success');
                    loadSSL();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.js-edit-ssl', function () { openSSLModal($(this).data('id')); });
        $(document).on('click', '.js-delete-ssl', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar certificado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.post('includes/endpoints/ssl.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadSSL();
                        }
                    }, 'json');
                }
            });
        });

        $(document).on('click', '.js-copy-url', function () {
            const url = $(this).data('url');
            if (!url) return;
            navigator.clipboard.writeText(url).then(() => {
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.html('<i class="fas fa-check text-success"></i>');
                setTimeout(() => { $btn.html(originalHtml); }, 2000);
            });
        });
    });
</script>