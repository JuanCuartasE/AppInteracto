<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Gestión de Clientes</h2>
        <p class="text-muted">Administra la base de datos de personas y empresas.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCliente">
            <i class="fas fa-plus me-2"></i> Nuevo Cliente
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4">Nombre</th>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaClientes">
                    <!-- Dinámico -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formCliente">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="clienteId">
                <div class="mb-3">
                    <label class="form-label">Nombre / Razón Social</label>
                    <input type="text" class="form-control" name="name" id="clienteName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Cliente</label>
                    <select class="form-select" name="type" id="clienteType" required>
                        <option value="Cliente">Cliente (Persona)</option>
                        <option value="Empresa">Empresa</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo Documento</label>
                        <select class="form-select" name="doc_type" id="clienteDocType">
                            <option value="CC">CC</option>
                            <option value="CE">CE</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="NIT">NIT</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nro Documento</label>
                        <input type="text" class="form-control" name="doc_number" id="clienteDocNumber">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="clienteEmail">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="phone" id="clientePhone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="address" id="clienteAddress">
                </div>
                <div class="mb-3">
                    <label class="form-label">Municipio</label>
                    <input type="text" class="form-control" name="municipality" id="clienteMunicipality">
                </div>
                <div class="mb-3">
                    <label class="form-label">Página Web</label>
                    <input type="url" class="form-control" name="website" id="clienteWebsite" placeholder="https://...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="description" id="clienteDescription" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        function loadClients() {
            $.post('includes/endpoints/clientes.php', { action: 'list' }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(c => {
                        html += `
                        <tr class="clickable-row" data-id="${c.id}" style="cursor:pointer">
                            <td class="px-4 fw-medium">${c.name}</td>
                            <td><span class="badge ${c.type === 'Empresa' ? 'bg-info' : 'bg-success'}">${c.type}</span></td>
                            <td>${c.doc_type || '-'} ${c.doc_number || ''}</td>
                            <td>${c.email || '-'}</td>
                            <td>${c.phone || '-'}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 edit-client" data-client='${JSON.stringify(c)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-client" data-id="${c.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaClientes').html(html || '<tr><td colspan="6" class="text-center py-4 text-muted">No hay clientes registrados</td></tr>');
                }
            }, 'json');
        }

        $(document).on('click', '.clickable-row', function (e) {
            if ($(e.target).closest('button').length) return;
            const id = $(this).data('id');
            window.location.href = `index.php?view=cliente_detalle&id=${id}`;
        });

        $(document).on('click', '.edit-client', function () {
            const c = $(this).data('client');
            $('#clienteId').val(c.id);
            $('#clienteName').val(c.name);
            $('#clienteType').val(c.type);
            $('#clienteDocType').val(c.doc_type);
            $('#clienteDocNumber').val(c.doc_number);
            $('#clienteEmail').val(c.email);
            $('#clientePhone').val(c.phone);
            $('#clienteAddress').val(c.address);
            $('#clienteMunicipality').val(c.municipality);
            $('#clienteWebsite').val(c.website);
            $('#clienteDescription').val(c.description);
            $('#modalCliente').modal('show');
        });

        $('#formCliente').submit(function (e) {
            e.preventDefault();
            const data = $(this).serialize();
            const action = $('#clienteId').val() ? 'update' : 'create';

            $.post('includes/endpoints/clientes.php', data + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success');
                    $('#modalCliente').modal('hide');
                    $('#formCliente')[0].reset();
                    $('#clienteId').val('');
                    loadClients();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.delete-client', function () {
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
                    $.post('includes/endpoints/clientes.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadClients();
                        }
                    }, 'json');
                }
            });
        });

        loadClients();
    });
</script>