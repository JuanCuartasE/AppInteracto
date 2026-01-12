<?php
$server_id = $_GET['id'] ?? null;
if (!$server_id) {
    echo "<h1>Error</h1><p>ID de servidor no especificado.</p>";
    return;
}
?>

<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="index.php?view=servidores"
                            class="text-decoration-none">Servidores</a></li>
                    <li class="breadcrumb-item active" id="breadcrumbServerName">Detalle</li>
                </ol>
            </nav>
            <h1 id="serverTitle">Cargando...</h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white border rounded px-3 py-2 small shadow-sm">
                <span class="text-muted d-block small text-uppercase fw-bold" style="font-size: 0.65rem;">ID
                    Instancia</span>
                <span class="fw-bold text-dark">#<?= $server_id ?></span>
            </div>
            <button class="btn btn-outline-secondary px-3" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-outline-primary px-4" id="btnEditServer" style="display: none;">
                <i class="fas fa-edit me-2"></i> Editar
            </button>
            <button class="btn btn-primary px-4 fw-bold" id="btnSaveServer"
                style="display: none; background-color: #1a73e8; border-color: #1a73e8;">
                <i class="fas fa-save me-2"></i> Guardar
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Información del Servidor (Summary Header) -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold text-muted small text-uppercase">Resumen de Configuración</span>
            </div>
            <div class="card-body p-4">
                <form id="formEditServer">
                    <input type="hidden" name="id" value="<?= $server_id ?>">
                    <div class="row g-3">
                        <div class="col-md-4 col-lg-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.65rem;">Nombre</label>
                            <input type="text" class="form-control form-control-sm" name="name" id="infoName" readonly>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.65rem;">Propietario</label>
                            <select class="form-select form-select-sm" name="client_id" id="infoClientId" disabled>
                                <option value="">Sin propietario</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.65rem;">IPv4 Address</label>
                            <input type="text" class="form-control form-control-sm font-monospace" name="ipv4"
                                id="infoIpv4" readonly>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.65rem;">IPv6 Address</label>
                            <input type="text" class="form-control form-control-sm font-monospace" name="ipv6"
                                id="infoIpv6" readonly style="font-size: 0.75rem;">
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.65rem;">Sistema Ops</label>
                            <input type="text" class="form-control form-control-sm" name="os" id="infoOs" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Servicios Vinculados -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold text-muted small text-uppercase">Servicios Vinculados</span>
                <button class="btn btn-xs btn-primary" id="btnAddService"
                    style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                    <i class="fas fa-plus me-1"></i> Añadir
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-mobile-cards">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4">Servicio</th>
                                <th>Tipo</th>
                                <th>Cliente</th>
                                <th>Ubicación</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="listaServiciosVinculados">
                            <!-- Dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Software Instalado (Abajo) -->
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold text-muted small text-uppercase">Software Instalado</span>
                <button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#modalSoftware"
                    style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                    <i class="fas fa-plus me-1"></i> Añadir
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-mobile-cards">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4">Programa</th>
                                <th>Versión</th>
                                <th>Descripción</th>
                                <th>Instalación</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="listaSoftware">
                            <!-- Dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
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
                <!-- Server ID fixed to current server -->
                <input type="hidden" name="server_id" value="<?= $server_id ?>">

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
                                <!-- Dinámico desde mst_tipo_servicio -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">2. Ubicación Técnica</h6>
                    <label class="form-label small fw-bold text-muted">Path del Proyecto</label>
                    <input type="text" class="form-control font-monospace bg-light" name="path" id="servicioPath"
                        placeholder="/var/www/html/proyecto">
                    <small class="text-muted">Ruta en el servidor donde se encuentra el proyecto</small>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">3. Descripción</h6>
                    <label class="form-label small fw-bold text-muted">Detalles Adicionales</label>
                    <textarea class="form-control" name="description" id="servicioDescription" rows="3"
                        placeholder="Información relevante sobre el servicio..."></textarea>
                </div>

                <!-- Hidden inputs for association -->
                <input type="hidden" name="client_id" id="servicioClientId">
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Software -->
<div class="modal fade" id="modalSoftware" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formSoftware">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Software</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="softwareId">
                <input type="hidden" name="server_id" value="<?= $server_id ?>">
                <div class="mb-3">
                    <label class="form-label">Nombre del Programa</label>
                    <input type="text" class="form-control" name="name" id="softwareName" required
                        placeholder="ej. PHP, MySQL, Nginx">
                </div>
                <div class="mb-3">
                    <label class="form-label">Versión</label>
                    <input type="text" class="form-control" name="version" id="softwareVersion" placeholder="ej. 8.2">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="description" id="softwareDescription" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comando de Instalación</label>
                    <textarea class="form-control font-monospace bg-light" name="install_command"
                        id="softwareInstallCommand" rows="3" placeholder="sudo apt install..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Software</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        const serverId = <?= $server_id ?>;
        let editMode = false;

        function toggleEditMode(enable) {
            editMode = enable;
            const fields = $('#formEditServer input, #formEditServer select');

            if (enable) {
                fields.prop('readonly', false).prop('disabled', false);
                $('#btnEditServer').hide();
                $('#btnSaveServer').show();
            } else {
                fields.prop('readonly', true).prop('disabled', true);
                $('#btnEditServer').show();
                $('#btnSaveServer').hide();
            }
        }

        function formatDate(dateStr) {
            if (!dateStr || dateStr === '0000-00-00') return 'Sin fecha';
            try {
                const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                const cleanDate = dateStr.split(' ')[0];
                const parts = cleanDate.split('-');
                if (parts.length !== 3) return dateStr;
                const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                if (isNaN(d.getTime())) return dateStr;
                return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
            } catch (e) {
                return dateStr;
            }
        }

        function loadClients() {
            $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
                if (res.status === 'success') {
                    let options = '';
                    res.data.forEach(c => {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#infoClientId').html(options);
                }
            }, 'json');
        }

        function loadServerData() {
            $.post('includes/endpoints/servidores.php', { action: 'fetch', id: serverId }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#serverTitle, #breadcrumbServerName').text(s.name);

                    // Populate form fields
                    $('#infoName').val(s.name);
                    $('#infoClientId').val(s.client_id || '');
                    $('#infoIpv4').val(s.ipv4 || '');
                    $('#infoIpv6').val(s.ipv6 || '');
                    $('#infoOs').val(s.os || '');

                    $('#btnEditServer').show();
                }
            }, 'json');
        }

        function loadSoftware() {
            $.post('includes/endpoints/server_software.php', { action: 'list', server_id: serverId }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(sw => {
                        html += `
                        <tr>
                            <td class="px-4 fw-bold" data-label="Programa">${sw.name}</td>
                            <td data-label="Versión">${sw.version || '-'}</td>
                            <td data-label="Descripción"><span class="text-muted small">${sw.description || '-'}</span></td>
                            <td data-label="Instalación">
                                <code class="bg-light px-2 py-1 rounded small text-dark">${sw.install_command || '-'}</code>
                            </td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light text-muted me-2 edit-software" data-sw='${JSON.stringify(sw)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-software" data-id="${sw.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaSoftware').html(html || '<tr><td colspan="5" class="text-center py-5 text-muted">No se ha registrado software en este servidor</td></tr>');
                }
            }, 'json');
        }

        $('#btnEditServer').click(function () {
            toggleEditMode(true);
        });

        $('#btnSaveServer').click(function () {
            $.post('includes/endpoints/servidores.php', $('#formEditServer').serialize() + '&action=update', function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', 'Servidor actualizado correctamente', 'success');
                    toggleEditMode(false);
                    loadServerData();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $('#formSoftware').submit(function (e) {
            e.preventDefault();
            const action = $('#softwareId').val() ? 'update' : 'create';
            $.post('includes/endpoints/server_software.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Hecho!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#modalSoftware').modal('hide');
                    $('#formSoftware')[0].reset();
                    $('#softwareId').val('');
                    loadSoftware();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.edit-software', function () {
            const sw = $(this).data('sw');
            $('#softwareId').val(sw.id);
            $('#softwareName').val(sw.name);
            $('#softwareVersion').val(sw.version);
            $('#softwareDescription').val(sw.description);
            $('#softwareInstallCommand').val(sw.install_command);
            $('#modalSoftware').modal('show');
        });

        $(document).on('click', '.delete-software', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/server_software.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            loadSoftware();
                            Swal.fire('Eliminado', res.message, 'success');
                        }
                    }, 'json');
                }
            });
        });

        function loadLinkedServices() {
            $.post('includes/endpoints/servicios.php', { action: 'list', server_id: serverId }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(s => {
                        html += `
                        <tr class="clickable-row" data-href="index.php?view=servicio_detalle&id=${s.id}" style="cursor:pointer">
                            <td class="px-4" data-label="Servicio">
                                <a href="index.php?view=servicio_detalle&id=${s.id}" class="fw-bold text-primary text-decoration-none">${s.name}</a>
                            </td>
                            <td data-label="Tipo"><span class="badge bg-light text-dark border">${s.type}</span></td>
                            <td data-label="Cliente">${s.client_name || '-'}</td>
                            <td data-label="Ubicación"><code class="small text-muted">${s.path || '-'}</code></td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 edit-service" data-id="${s.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-service" data-id="${s.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                    $('#listaServiciosVinculados').html(html || '<tr><td colspan="5" class="text-center py-5 text-muted">No hay servicios vinculados a este servidor</td></tr>');
                }
            }, 'json');
        }

        // Navigate on row click (delegated)
        $(document).on('click', '.clickable-row', function (e) {
            // Prevent if clicked on action buttons
            if ($(e.target).closest('button').length || $(e.target).closest('a').length) return;
            window.location = $(this).data('href');
        });

        $('#btnAddService').click(function () {
            $('#formServicio')[0].reset();
            $('#servicioId').val('');

            // 1. Load Service Types from Maestros
            $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: 'mst_tipo_servicio' }, function (res) {
                if (res.status === 'success') {
                    let opts = '';
                    res.data.forEach(t => {
                        opts += `<option value="${t.name}">${t.name}</option>`;
                    });
                    $('#servicioType').html(opts);
                }
            }, 'json');

            // 2. Load Client (Restricted to Server Owner)
            const serverOwnerId = $('#infoClientId').val();

            // We need the list to show the name, but just selecting the one is enough.
            // Reusing list_clients for consistency to show the name properly.
            $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
                if (res.status === 'success') {
                    let options = '<option value="">Seleccionar cliente...</option>';
                    res.data.forEach(c => {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#servicioClientId').html(options);

                    // Auto-select and Lock
                    if (serverOwnerId) {
                        $('#servicioClientId').val(serverOwnerId);
                        $('#servicioClientIdHidden').val(serverOwnerId);
                    } else {
                        // Case: Server owner is "Interacto SA" (id='') or Unassigned.
                        // Logic: if serverOwner is empty, we assume Interacto SA internal?
                        // The dropdown value for Interacto SA is '' (empty string).
                        $('#servicioClientId').val('');
                        $('#servicioClientIdHidden').val('');
                    }
                }
            }, 'json');

            $('#modalServicio').modal('show');
        });

        $('#formServicio').submit(function (e) {
            e.preventDefault();

            // Append the hidden client ID to the serialized data because disabled select isn't sent
            // Alternatively, just enable it before send, but appending is safer visually.
            // Actually, serialize() misses disabled fields. Let's fix client_id param manually if needed or rely on hidden.
            // However, the backend expects 'client_id'.
            let formData = $(this).serialize();

            // Replace client_id_hidden with client_id if present (or just add client_id manually)
            const realClientId = $('#servicioClientIdHidden').val();
            if (realClientId !== undefined) {
                formData += '&client_id=' + realClientId;
            } else {
                // Fallback if hidden is empty (maybe Interacto SA)
                formData += '&client_id=';
            }

            const action = $('#servicioId').val() ? 'update' : 'create';
            $.post('includes/endpoints/servicios.php', formData + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#modalServicio').modal('hide');
                    loadLinkedServices();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.edit-service', function (e) {
            e.stopPropagation();
            const id = $(this).data('id');
            $.post('includes/endpoints/servicios.php', { action: 'fetch', id: id }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#servicioId').val(s.id);
                    $('#servicioName').val(s.name);
                    $('#servicioPath').val(s.path);
                    $('#servicioDescription').val(s.description || '');

                    // 1. Load Types
                    $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: 'mst_tipo_servicio' }, function (tRes) {
                        if (tRes.status === 'success') {
                            let opts = '';
                            tRes.data.forEach(t => {
                                opts += `<option value="${t.name}">${t.name}</option>`;
                            });
                            $('#servicioType').html(opts);
                            $('#servicioType').val(s.type);
                        }
                    }, 'json');

                    // 2. Load Clients (Locked)
                    $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (cRes) {
                        if (cRes.status === 'success') {
                            let options = '<option value="">Seleccionar cliente...</option>';
                            cRes.data.forEach(c => {
                                options += `<option value="${c.id}">${c.name}</option>`;
                            });
                            $('#servicioClientId').html(options);
                            $('#servicioClientId').val(s.client_id);
                            $('#servicioClientIdHidden').val(s.client_id);

                            // Re-lock just in case (though static HTML handles it, dynamic loading might reset props if we weren't careful)
                            $('#servicioClientId').prop('disabled', true);
                        }
                    }, 'json');

                    $('#modalServicio').modal('show');
                }
            }, 'json');
        });

        $(document).on('click', '.delete-service', function (e) {
            e.stopPropagation();
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
                            loadLinkedServices();
                        }
                    }, 'json');
                }
            });
        });

        loadClients();
        loadServerData();
        loadSoftware();
        loadLinkedServices();
    });
</script>