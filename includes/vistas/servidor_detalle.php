<?php
$server_id = $_GET['id'] ?? null;
if (!$server_id) {
    echo "<h1>Error</h1><p>ID de servidor no especificado.</p>";
    return;
}
?>

<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?view=servidores">Servidores</a></li>
                <li class="breadcrumb-item active" id="breadcrumbServerName">Detalle del Servidor</li>
            </ol>
        </nav>
        <h2 class="fw-bold" id="serverTitle">Cargando...</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <button class="btn btn-primary" id="btnSaveServer">
            <i class="fas fa-save me-2"></i> Guardar Cambios
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Información del Servidor -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Configuración VPS</h5>
            </div>
            <div class="card-body">
                <form id="formEditServidor">
                    <input type="hidden" name="id" value="<?= $server_id ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre del Servidor</label>
                        <input type="text" class="form-control" name="name" id="editServerName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Propietario (Cliente)</label>
                        <select class="form-select" name="client_id" id="editServerClientId">
                            <!-- Dinámico -->
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">IPv4</label>
                            <input type="text" class="form-control" name="ipv4" id="editIpv4">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">IPv6</label>
                            <input type="text" class="form-control" name="ipv6" id="editIpv6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">SO</label>
                            <input type="text" class="form-control" name="os" id="editOs">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Versión SO</label>
                            <input type="text" class="form-control" name="os_version" id="editOsVersion">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha Creación VPS</label>
                        <input type="date" class="form-control" name="created_at" id="editCreatedAt">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Software Instalado -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Software Instalado</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalSoftware">
                    <i class="fas fa-plus me-1"></i> Añadir Software
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

        function loadServerData() {
            $.post('includes/endpoints/servidores.php', { action: 'fetch', id: serverId }, function (res) {
                if (res.status === 'success') {
                    const s = res.data;
                    $('#serverTitle, #breadcrumbServerName').text(s.name);
                    $('#editServerName').val(s.name);
                    $('#editIpv4').val(s.ipv4);
                    $('#editIpv6').val(s.ipv6);
                    $('#editOs').val(s.os);
                    $('#editOsVersion').val(s.os_version);
                    $('#editCreatedAt').val(s.created_at);

                    loadClientsSelect(s.client_id);
                }
            }, 'json');
        }

        function loadClientsSelect(selectedId) {
            $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
                if (res.status === 'success') {
                    let options = '<option value="">-- Sin propietario --</option>';
                    res.data.forEach(c => {
                        options += `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${c.name}</option>`;
                    });
                    $('#editServerClientId').html(options);
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
                            <td class="px-4 fw-medium" data-label="Programa">${sw.name}</td>
                            <td data-label="Versión">${sw.version || '-'}</td>
                            <td data-label="Descripción"><small class="text-muted">${sw.description || '-'}</small></td>
                            <td data-label="Instalación">
                                <code class="small">${sw.install_command || '-'}</code>
                            </td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 edit-software" data-sw='${JSON.stringify(sw)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-software" data-id="${sw.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaSoftware').html(html || '<tr><td colspan="5" class="text-center py-4 text-muted">No hay software registrado</td></tr>');
                }
            }, 'json');
        }

        $('#btnSaveServer').click(function () {
            $.post('includes/endpoints/servidores.php', $('#formEditServidor').serialize() + '&action=update', function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', 'Configuración actualizada', 'success');
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
                    Swal.fire('Éxito', res.message, 'success');
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
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/server_software.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadSoftware();
                        }
                    }, 'json');
                }
            });
        });

        loadServerData();
        loadSoftware();
    });
</script>