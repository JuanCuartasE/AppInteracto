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
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-muted small text-uppercase letter-spacing-05">Resumen de Configuración</h5>
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

    <!-- Software Instalado (Abajo) -->
    <div class="col-12">
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
                    let options = '<option value="">Sin propietario</option>';
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

        loadClients();
        loadServerData();
        loadSoftware();
    });
</script>