<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Gestión de Servidores</h1>
            <p class="text-muted">Administra tus VPS y servidores remotos en una infraestructura centralizada.</p>
        </div>
        <button class="btn btn-primary px-4 py-2" id="btnNuevoServidor"
            style="background-color: #1a73e8; border-color: #1a73e8;">
            <i class="fas fa-plus me-2"></i> Nuevo Servidor
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm animate__animated animate__fadeIn mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4">Propietario / Servidor</th>
                        <th>IPv4</th>
                        <th>IPv6</th>
                        <th>Sistema Operativo</th>
                        <th>Fecha Creación</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaServidores">
                    <!-- Dinámico -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Servidor -->
<div class="modal fade" id="modalServidor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content border-0 shadow-lg" id="formServidor">
            <div class="modal-header border-bottom-0 pt-4 px-4 bg-light">
                <div>
                    <h5 class="fw-bold mb-0" id="modalTitle">Nuevo Servidor</h5>
                    <p class="text-muted small mb-0">Configura los detalles técnicos de tu nueva instancia.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="servidorId">

                <!-- Sección 1: Identificación -->
                <div class="form-section mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">1</div>
                        <h6 class="fw-bold mb-0">Detalles de Identificación</h6>
                    </div>
                    <div class="ps-5">
                        <p class="text-muted small mb-3">Asigna un nombre descriptivo y selecciona el cliente
                            propietario de este servidor para su correcta facturación y seguimiento.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre del
                                    Servidor</label>
                                <input type="text" class="form-control" name="name" id="servidorName" required
                                    placeholder="ej. Web Server Producción 01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Propietario
                                    (Cliente)</label>
                                <select class="form-select" name="client_id" id="servidorClientId">
                                    <option value="">-- Seleccionar Cliente --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <!-- Sección 2: Red -->
                <div class="form-section mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">2</div>
                        <h6 class="fw-bold mb-0">Configuración de Red</h6>
                    </div>
                    <div class="ps-5">
                        <p class="text-muted small mb-3">Ingresa las direcciones IP públicas asignadas. Esto permitirá
                            la conectividad y el monitoreo de los servicios instalados.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección IPv4</label>
                                <input type="text" class="form-control" name="ipv4" id="servidorIpv4"
                                    placeholder="ej. 172.67.143.12">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección IPv6</label>
                                <input type="text" class="form-control" name="ipv6" id="servidorIpv6"
                                    placeholder="ej. 2606:4700:3033::ac43:8f0c">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <!-- Sección 3: Sistema -->
                <div class="form-section mb-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">3</div>
                        <h6 class="fw-bold mb-0">Sistema Operativo</h6>
                    </div>
                    <div class="ps-5">
                        <p class="text-muted small mb-3">Especifica el entorno base del servidor. Esto es útil para
                            conocer la compatibilidad del software que se instalará más tarde.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Sistema
                                    Operativo</label>
                                <input type="text" class="form-control" name="os" id="servidorOs"
                                    placeholder="ej. Ubuntu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Versión OS</label>
                                <input type="text" class="form-control" name="os_version" id="servidorOsVersion"
                                    placeholder="ej. 24.04 LTS">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Creación de la
                                VPS</label>
                            <input type="date" class="form-control" name="created_at" id="servidorCreatedAt">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary px-5 fw-bold"
                    style="background-color: #1a73e8; border-color: #1a73e8;">Siguiente</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        function formatDate(dateStr) {
            if (!dateStr || dateStr === '0000-00-00') return 'Sin fecha';
            try {
                const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                // Clean input from optional time part
                const cleanDate = dateStr.split(' ')[0];
                const parts = cleanDate.split('-');
                if (parts.length !== 3) return dateStr;

                // Construct date using components (Year, MonthIndex, Day)
                const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));

                if (isNaN(d.getTime())) return dateStr;
                return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
            } catch (e) {
                return dateStr;
            }
        }

        function loadServers() {
            $.post('includes/endpoints/servidores.php', { action: 'list' }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(s => {
                        html += `
                        <tr class="clickable-row" data-id="${s.id}" style="cursor:pointer">
                            <td class="px-4" data-label="Propietario / Servidor">
                                <span class="fw-bold d-block text-truncate" style="max-width: 200px;" title="${s.name}">${s.name}</span>
                                <span class="text-muted small d-block text-truncate" style="max-width: 200px;">${s.client_name || 'Sin propietario'}</span>
                            </td>
                            <td data-label="IPv4"><code class="small text-dark">${s.ipv4 || '<span class="text-muted small">-</span>'}</code></td>
                            <td data-label="IPv6"><code class="small text-dark">${s.ipv6 || '<span class="text-muted small">-</span>'}</code></td>
                            <td data-label="Sistema Operativo">
                                <span class="small">${s.os || '-'} ${s.os_version || ''}</span>
                            </td>
                            <td data-label="Fecha Creación" class="small">${formatDate(s.created_at)}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light text-muted me-2 edit-server" data-server='${JSON.stringify(s)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-server" data-id="${s.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaServidores').html(html || '<tr><td colspan="6" class="text-center py-5 text-muted">No se encontraron servidores</td></tr>');
                }
            }, 'json');
        }

        function loadClients() {
            $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
                if (res.status === 'success') {
                    let options = '<option value="">-- Seleccionar Cliente --</option>';
                    res.data.forEach(c => {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#servidorClientId').html(options);
                }
            }, 'json');
        }

        $('#btnNuevoServidor').click(function () {
            $('#formServidor')[0].reset();
            $('#servidorId').val('');
            $('#modalTitle').text('Nuevo Servidor');
            loadClients();
            $('#modalServidor').modal('show');
        });

        $(document).on('click', '.clickable-row', function (e) {
            if ($(e.target).closest('button').length) return;
            const id = $(this).data('id');
            window.location.href = `index.php?view=servidor_detalle&id=${id}`;
        });

        $(document).on('click', '.edit-server', function (e) {
            e.stopPropagation();
            const s = $(this).data('server');
            $('#servidorId').val(s.id);
            $('#servidorName').val(s.name);
            $('#servidorIpv4').val(s.ipv4);
            $('#servidorIpv6').val(s.ipv6);
            $('#servidorOs').val(s.os);
            $('#servidorOsVersion').val(s.os_version);
            $('#servidorCreatedAt').val(s.created_at);

            loadClients();
            setTimeout(() => {
                $('#servidorClientId').val(s.client_id);
            }, 100);

            $('#modalTitle').text('Editar Servidor');
            $('#modalServidor').modal('show');
        });

        $('#formServidor').submit(function (e) {
            e.preventDefault();
            const data = $(this).serialize();
            const action = $('#servidorId').val() ? 'update' : 'create';

            $.post('includes/endpoints/servidores.php', data + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success');
                    $('#modalServidor').modal('hide');
                    loadServers();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.delete-server', function (e) {
            e.stopPropagation();
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminarán también los registros de software asociados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/servidores.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadServers();
                        }
                    }, 'json');
                }
            });
        });

        loadServers();
    });
</script>