<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-0">Gestión de Maestros</h1>
            <p class="text-muted small mb-0">Administración de tablas auxiliares y listas desplegables</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Sidebar de Tablas -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small text-uppercase text-muted">Tablas Maestras</div>
            <div class="list-group list-group-flush" id="maestrosList">
                <!-- Dinámico -->
            </div>
        </div>
    </div>

    <!-- Contenido CRUD -->
    <div class="col-md-9">
        <div class="card border-0 shadow-sm" id="crudContainer" style="display:none;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold" id="currentTableName">Tabla</span>
                <button class="btn btn-sm btn-primary" id="btnNewRecord">
                    <i class="fas fa-plus me-1"></i> Nuevo Registro
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th class="px-4">Nombre</th>
                                <th>Descripción</th>
                                <th id="urlHeader" style="display:none;">URL</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTable">
                            <!-- Dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Estado vacío -->
        <div id="emptyState" class="text-center py-5 text-muted">
            <i class="fas fa-database fa-3x mb-3 text-light-gray" style="opacity: 0.3;"></i>
            <p>Selecciona una tabla maestra para gestionar sus datos.</p>
        </div>
    </div>
</div>

<!-- Modal Maestros -->
<div class="modal fade" id="modalMaestro" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formMaestro">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="recordId">
                <input type="hidden" name="table_name" id="recordTableName">

                <div class="mb-3">
                    <label class="form-label fw-bold small">Nombre / Valor</label>
                    <input type="text" class="form-control" name="name" id="recordName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Descripción</label>
                    <textarea class="form-control" name="description" id="recordDescription" rows="3"></textarea>
                </div>
                <!-- Campo Dinámico: URL (Solo para proveedores) -->
                <div class="mb-3" id="urlFieldContainer" style="display:none;">
                    <label class="form-label fw-bold small">URL del Proveedor</label>
                    <input type="url" class="form-control" name="url" id="recordUrl" placeholder="https://...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        let currentTable = '';

        // Cargar lista de tablas
        function loadTables() {
            $.post('includes/endpoints/maestros.php', { action: 'list_tables' }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(t => {
                        html += `<a href="#" class="list-group-item list-group-item-action table-link" 
                        data-table="${t.table}" data-name="${t.display_name}">
                        <i class="fas fa-table me-2 text-muted"></i> ${t.display_name}
                    </a>`;
                    });
                    $('#maestrosList').html(html);
                }
            }, 'json');
        }

        // Cargar registros de una tabla
        function loadRecords(table) {
            currentTable = table;
            $('#emptyState').hide();
            $('#crudContainer').fadeIn();

            $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: table }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(r => {
                        let urlContent = '';
                        if (currentTable === 'mst_proveedores') {
                            const url = r.url || '';
                            const copyBtn = url ? `<button class="btn btn-sm btn-light ms-2 js-copy-url" data-url="${url}" title="Copiar URL"><i class="fas fa-copy text-muted"></i></button>` : '';
                            urlContent = `<td><span class="small text-truncate d-inline-block" style="max-width: 150px;">${url || '-'}</span>${copyBtn}</td>`;
                        }

                        html += `
                    <tr>
                        <td class="px-4 fw-bold text-primary">${r.name}</td>
                        <td class="text-muted small">${r.description || '-'}</td>
                        ${urlContent}
                        <td class="text-end px-4">
                            <button class="btn btn-sm btn-icon btn-light me-2 edit-record" 
                                data-id="${r.id}" data-name="${r.name}" data-desc="${r.description || ''}" data-url="${r.url || ''}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-light text-danger delete-record" 
                                data-id="${r.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                    });
                    const colspan = currentTable === 'mst_proveedores' ? 4 : 3;
                    $('#recordsTable').html(html || `<tr><td colspan="${colspan}" class="text-center py-4">Sin registros</td></tr>`);
                }
            }, 'json');
        }

    // Click en tabla del sidebar
    $(document).on('click', '.table-link', function (e) {
        e.preventDefault();
        $('.table-link').removeClass('active');
        $(this).addClass('active');
        const tableName = $(this).data('table');
        const displayName = $(this).data('name');

        $('#currentTableName').text(displayName);
        if (tableName === 'mst_proveedores') {
            $('#urlFieldContainer').show();
            $('#urlHeader').show();
        } else {
            $('#urlFieldContainer').hide();
            $('#urlHeader').hide();
        }
        loadRecords(tableName);
    });

    // Nuevo registro
    $('#btnNewRecord').click(function () {
        $('#formMaestro')[0].reset();
        $('#recordId').val('');
        $('#recordTableName').val(currentTable);
        $('#modalMaestro').modal('show');
    });

    // Guardar registro
    $('#formMaestro').submit(function (e) {
        e.preventDefault();
        const action = $('#recordId').val() ? 'update' : 'create';
        $.post('includes/endpoints/maestros.php', $(this).serialize() + '&action=' + action, function (res) {
            if (res.status === 'success') {
                $('#modalMaestro').modal('hide');
                Swal.fire('Guardado', res.message, 'success');
                loadRecords(currentTable);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });

    // Editar registro
    $(document).on('click', '.edit-record', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const desc = $(this).data('desc');

        $('#recordId').val(id);
        $('#recordTableName').val(currentTable);
        $('#recordName').val(name);
        $('#recordDescription').val(desc);

        if (currentTable === 'mst_proveedores') {
            // Get URL from data-url if we add it to the button
            const url = $(this).data('url');
            $('#recordUrl').val(url || '');
        }
        $('#modalMaestro').modal('show');
    });

    // Eliminar registro
    $(document).on('click', '.delete-record', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Confirmar eliminación?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('includes/endpoints/maestros.php', {
                    action: 'delete',
                    id: id,
                    table_name: currentTable
                }, function (res) {
                    if (res.status === 'success') {
                        loadRecords(currentTable);
                        Swal.fire('Eliminado', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });

    // Copiar URL al portapapeles
    $(document).on('click', '.js-copy-url', function () {
        const url = $(this).data('url');
        if (!url) return;

        navigator.clipboard.writeText(url).then(() => {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<i class="fas fa-check text-success"></i>');
            setTimeout(() => {
                $btn.html(originalHtml);
            }, 2000);
        });
    });

    loadTables();
    });
</script>