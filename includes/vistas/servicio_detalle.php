<?php
$service_id = $_GET['id'] ?? null;
if (!$service_id) {
    echo "<h1>Error</h1><p>ID de servicio no especificado.</p>";
    return;
}
?>

<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="index.php?view=servicios"
                            class="text-decoration-none">Servicios</a></li>
                    <li class="breadcrumb-item active" id="breadcrumbServiceName">Detalle</li>
                </ol>
            </nav>
            <h1 id="serviceTitle">Cargando...</h1>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary px-3" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Información del Servicio -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-muted small text-uppercase">Información General</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.65rem;">Tipo de Servicio</label>
                        <div class="fw-bold" id="infoType">-</div>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.65rem;">Cliente</label>
                        <div class="fw-bold text-dark" id="infoClient">-</div>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.65rem;">Servidor</label>
                        <div class="text-dark" id="infoServer">-</div>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                            style="font-size: 0.65rem;">Ubicación</label>
                        <div class="font-monospace text-primary small" id="infoPath">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles Técnicos / Credenciales -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Accesos y Credenciales</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetalle">
                    <i class="fas fa-plus me-1"></i> Añadir Acceso
                </button>
            </div>
            <div class="card-body p-3">
                <div class="row g-3" id="listaDetalles">
                    <!-- Dinámico -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Técnico -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="formDetalle">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Detalle de Acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="detalleId">
                <input type="hidden" name="service_id" value="<?= $service_id ?>">

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Rol / Tipo de Usuario</label>
                    <input type="text" class="form-control" name="role_name" id="detalleRoleName" required
                        placeholder="ej. Administrador, Cliente, Desarrollador">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">URL de Acceso</label>
                    <input type="url" class="form-control" name="url" id="detalleUrl"
                        placeholder="https://ejemplo.com/admin">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold text-muted">Usuario</label>
                        <input type="text" class="form-control font-monospace" name="username" id="detalleUsername">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold text-muted">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control font-monospace" name="password"
                                id="detallePassword">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Observaciones / Instrucciones</label>
                    <textarea class="form-control" name="observations" id="detalleObservations" rows="5"
                        placeholder="Instrucciones para acceder, notas importantes, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .detail-card {
        border-left: 4px solid #1a73e8;
        background: #f8f9fa;
        transition: all 0.2s;
    }

    .detail-card:hover {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .credential-field {
        background: #ffffff;
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 0.5rem 0.75rem;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
    }
</style>

<script>
    $(document).ready(function () {
        const serviceId = <?= $service_id ?>;

        function loadServiceData() {
            $.post('includes/endpoints/servicios.php', { action: 'fetch', id: serviceId }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#serviceTitle, #breadcrumbServiceName').text(s.name);

                    const typeColors = {
                        'Página Web': 'primary',
                        'Aplicación': 'success',
                        'API': 'info',
                        'Base de Datos': 'warning',
                        'Otro': 'secondary'
                    };
                    const badgeColor = typeColors[s.type] || 'secondary';
                    $('#infoType').html(`<span class="badge bg-${badgeColor}">${s.type}</span>`);
                    $('#infoClient').text(s.client_name || '-');
                    $('#infoServer').text(s.server_name || 'Sin servidor');
                    $('#infoPath').text(s.path || '-');
                }
            }, 'json');
        }

        function loadDetails() {
            $.post('includes/endpoints/servicios.php', { action: 'list_details', service_id: serviceId }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    if (res.data.length === 0) {
                        html = '<div class="col-12 p-5 text-center text-muted">No hay accesos registrados</div>';
                    } else {
                        res.data.forEach(d => {
                            html += `
                            <div class="col-md-6">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <h6 class="mb-0 fw-bold">${d.role_name}</h6>
                                        <div>
                                            <button class="btn btn-sm btn-light edit-detail" data-detail='${JSON.stringify(d)}'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light text-danger delete-detail" data-id="${d.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body py-3">
                                        ${d.url ? `
                                        <div class="mb-3">
                                            <label class="small text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">URL de Acceso</label>
                                            <a href="${d.url}" target="_blank" class="small text-primary text-break"><i class="fas fa-external-link-alt me-1"></i>${d.url}</a>
                                        </div>
                                        ` : ''}
                                        <div class="row g-2">
                                            ${d.username ? `
                                            <div class="col-6">
                                                <label class="small text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Usuario</label>
                                                <div class="credential-field d-flex justify-content-between align-items-center">
                                                    <span class="text-truncate">${d.username}</span>
                                                    <button class="btn btn-sm btn-link p-0 copy-btn" data-copy="${d.username}">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            ` : ''}
                                            ${d.password ? `
                                            <div class="col-6">
                                                <label class="small text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Contraseña</label>
                                                <div class="credential-field d-flex justify-content-between align-items-center">
                                                    <span>••••••••</span>
                                                    <button class="btn btn-sm btn-link p-0 copy-btn" data-copy="${d.password}">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            ` : ''}
                                        </div>
                                        ${d.observations ? `
                                        <div class="mt-3">
                                            <label class="small text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Observaciones</label>
                                            <div class="bg-light p-2 rounded small" style="white-space: pre-wrap; max-height: 100px; overflow-y: auto;">${d.observations}</div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                    }
                    $('#listaDetalles').html(html);
                }
            }, 'json');
        }

        $('#togglePassword').click(function () {
            const input = $('#detallePassword');
            const icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $(document).on('click', '.copy-btn', function () {
            const text = $(this).data('copy');
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copiado',
                    text: 'Texto copiado al portapapeles',
                    timer: 1000,
                    showConfirmButton: false
                });
            });
        });

        $('#formDetalle').submit(function (e) {
            e.preventDefault();
            const action = $('#detalleId').val() ? 'update_detail' : 'create_detail';
            $.post('includes/endpoints/servicios.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#modalDetalle').modal('hide');
                    $('#formDetalle')[0].reset();
                    $('#detalleId').val('');
                    loadDetails();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.edit-detail', function () {
            const d = $(this).data('detail');
            $('#detalleId').val(d.id);
            $('#detalleRoleName').val(d.role_name);
            $('#detalleUrl').val(d.url);
            $('#detalleUsername').val(d.username);
            $('#detallePassword').val(d.password);
            $('#detalleObservations').val(d.observations);
            $('#modalDetalle').modal('show');
        });

        $(document).on('click', '.delete-detail', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar acceso?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/servicios.php', { action: 'delete_detail', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadDetails();
                        }
                    }, 'json');
                }
            });
        });

        loadServiceData();
        loadDetails();
    });
</script>