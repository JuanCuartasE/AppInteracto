<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Gestión de Servicios</h1>
            <p class="text-muted">Administra los servicios web, aplicaciones y APIs de tus clientes.</p>
        </div>
        <button class="btn btn-primary px-4 py-2" id="btnNuevoServicio"
            style="background-color: #1a73e8; border-color: #1a73e8;">
            <i class="fas fa-plus me-2"></i> Nuevo Servicio
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm animate__animated animate__fadeIn mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4">Servicio</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Servidor</th>
                        <th>Ubicación</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaServicios">
                    <!-- Dinámico -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo/Editar Servicio -->
<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="formServicio">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Datos del Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="servicioId">

                <div class="mb-4">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">1. Información Básica</h6>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre del Servicio</label>
                            <input type="text" class="form-control" name="name" id="servicioName" required
                                placeholder="ej. Portal Web Corporativo">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-muted">Tipo</label>
                            <select class="form-select" name="type" id="servicioType" required>
                                <option value="Página Web">Página Web</option>
                                <option value="Aplicación">Aplicación</option>
                                <option value="API">API</option>
                                <option value="Base de Datos">Base de Datos</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">2. Asociación</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Cliente Propietario</label>
                            <select class="form-select" name="client_id" id="servicioClientId" required>
                                <option value="">Seleccionar cliente...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Servidor (Opcional)</label>
                            <select class="form-select" name="server_id" id="servicioServerId">
                                <option value="">Sin servidor asignado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">3. Ubicación Técnica</h6>
                    <label class="form-label small fw-bold text-muted">Path del Proyecto</label>
                    <input type="text" class="form-control font-monospace bg-light" name="path" id="servicioPath"
                        placeholder="/var/www/html/proyecto">
                    <small class="text-muted">Ruta en el servidor donde se encuentra el proyecto</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        function loadServices() {
            $.post('includes/endpoints/servicios.php', { action: 'list' }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(s => {
                        const typeColors = {
                            'Página Web': 'primary',
                            'Aplicación': 'success',
                            'API': 'info',
                            'Base de Datos': 'warning',
                            'Otro': 'secondary'
                        };
                        const badgeColor = typeColors[s.type] || 'secondary';

                        html += `
                        <tr>
                            <td class="px-4 fw-bold" data-label="Servicio">
                                <a href="index.php?view=servicio_detalle&id=${s.id}" class="text-decoration-none text-dark">
                                    ${s.name}
                                </a>
                            </td>
                            <td data-label="Tipo">
                                <span class="badge bg-${badgeColor}">${s.type}</span>
                            </td>
                            <td data-label="Cliente">${s.client_name || '-'}</td>
                            <td data-label="Servidor">${s.server_name || '-'}</td>
                            <td data-label="Ubicación">
                                <code class="small">${s.path || '-'}</code>
                            </td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-light me-2 edit-service" data-id="${s.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger delete-service" data-id="${s.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                    $('#listaServicios').html(html || '<tr><td colspan="6" class="text-center py-5 text-muted">No hay servicios registrados</td></tr>');
                }
            }, 'json');
        }

        function loadClients() {
            $.post('includes/endpoints/servicios.php', { action: 'list_clients' }, function (res) {
                if (res.status === 'success') {
                    let options = '<option value="">Seleccionar cliente...</option>';
                    res.data.forEach(c => {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#servicioClientId').html(options);
                }
            }, 'json');
        }

        function loadServers(clientId = null) {
            const data = { action: 'list_servers' };
            if (clientId) data.client_id = clientId;

            $.post('includes/endpoints/servicios.php', data, function (res) {
                if (res.status === 'success') {
                    let options = '<option value="">Sin servidor asignado</option>';
                    res.data.forEach(s => {
                        options += `<option value="${s.id}">${s.name}</option>`;
                    });
                    $('#servicioServerId').html(options);
                }
            }, 'json');
        }

        $('#servicioClientId').change(function () {
            const clientId = $(this).val();
            if (clientId) {
                loadServers(clientId);
            } else {
                loadServers();
            }
        });

        $('#btnNuevoServicio').click(function () {
            $('#formServicio')[0].reset();
            $('#servicioId').val('');
            loadClients();
            loadServers();
            $('#modalServicio').modal('show');
        });

        $('#formServicio').submit(function (e) {
            e.preventDefault();
            const action = $('#servicioId').val() ? 'update' : 'create';
            $.post('includes/endpoints/servicios.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#modalServicio').modal('hide');
                    loadServices();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.edit-service', function () {
            const id = $(this).data('id');
            $.post('includes/endpoints/servicios.php', { action: 'fetch', id: id }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#servicioId').val(s.id);
                    $('#servicioName').val(s.name);
                    $('#servicioType').val(s.type);
                    $('#servicioClientId').val(s.client_id);
                    $('#servicioServerId').val(s.server_id || '');
                    $('#servicioPath').val(s.path);
                    loadClients();
                    loadServers(s.client_id);
                    setTimeout(() => {
                        $('#servicioClientId').val(s.client_id);
                        $('#servicioServerId').val(s.server_id || '');
                    }, 200);
                    $('#modalServicio').modal('show');
                }
            }, 'json');
        });

        $(document).on('click', '.delete-service', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar servicio?',
                text: 'Se eliminarán también todos los detalles técnicos asociados',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/servicios.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadServices();
                        }
                    }, 'json');
                }
            });
        });

        loadServices();
    });
</script>