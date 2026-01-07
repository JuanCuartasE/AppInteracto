<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Gestión de Clientes</h1>
            <p class="text-muted">Administra la base de datos de personas y empresas.</p>
        </div>
        <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCliente"
            style="background-color: #1a73e8; border-color: #1a73e8;">
            <i class="fas fa-plus me-2"></i> Nuevo Cliente
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm animate__animated animate__fadeIn">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
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
    <div class="modal-dialog modal-lg">
        <form class="modal-content border-0 shadow-lg" id="formCliente">
            <div class="modal-header border-bottom-0 pt-4 px-4 bg-light">
                <div>
                    <h5 class="fw-bold mb-0" id="modalTitle">Nuevo Cliente</h5>
                    <p class="text-muted small mb-0">Registra un nuevo contacto para tu base de datos comercial.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="clienteId">

                <!-- Sección 1: Datos Básicos -->
                <div class="form-section mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">1</div>
                        <h6 class="fw-bold mb-0">Información de Identidad</h6>
                    </div>
                    <div class="ps-5">
                        <p class="text-muted small mb-3">Define si el registro corresponde a una persona natural o a una
                            organización.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre / Razón
                                    Social</label>
                                <input type="text" class="form-control" name="name" id="clienteName" required
                                    placeholder="ej. Juan Pérez o Interacto S.A.S">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tipo de
                                    Cliente</label>
                                <select class="form-select" name="type" id="clienteType" required>
                                    <option value="Cliente">Cliente (Persona)</option>
                                    <option value="Empresa">Empresa</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tipo Documento</label>
                                <select class="form-select" name="doc_type" id="clienteDocType">
                                    <option value="CC">CC</option>
                                    <option value="CE">CE</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nro Documento</label>
                                <input type="text" class="form-control" name="doc_number" id="clienteDocNumber"
                                    placeholder="ej. 10203040">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <!-- Sección 2: Contacto -->
                <div class="form-section mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">2</div>
                        <h6 class="fw-bold mb-0">Datos de Contacto y Ubicación</h6>
                    </div>
                    <div class="ps-5">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                                <input type="email" class="form-control" name="email" id="clienteEmail"
                                    placeholder="correo@ejemplo.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                                <input type="text" class="form-control" name="phone" id="clientePhone"
                                    placeholder="ej. +57 300 000 0000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Municipio</label>
                                <input type="text" class="form-control" name="municipality" id="clienteMunicipality"
                                    placeholder="ej. Medellín">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección</label>
                                <input type="text" class="form-control" name="address" id="clienteAddress"
                                    placeholder="Carrera 10 # 20-30">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Página Web</label>
                            <input type="url" class="form-control" name="website" id="clienteWebsite"
                                placeholder="https://...">
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <!-- Sección 3: Información Adicional -->
                <div class="form-section mb-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 28px; height: 28px; font-size: 0.8rem;">3</div>
                        <h6 class="fw-bold mb-0">Información Adicional</h6>
                    </div>
                    <div class="ps-5">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Notas o
                                Descripción</label>
                            <textarea class="form-control" name="description" id="clienteDescription" rows="3"
                                placeholder="Detalles relevantes sobre el cliente..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 fw-bold"
                    style="background-color: #1a73e8; border-color: #1a73e8;">Guardar Cliente</button>
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
                            <td class="px-4 fw-medium" data-label="Nombre">${c.name}</td>
                            <td data-label="Tipo"><span class="badge ${c.type === 'Empresa' ? 'bg-info' : 'bg-success'}">${c.type}</span></td>
                            <td data-label="Documento">${c.doc_type || '-'} ${c.doc_number || ''}</td>
                            <td data-label="Email">${c.email || '-'}</td>
                            <td data-label="Teléfono">${c.phone || '-'}</td>
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