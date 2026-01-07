<?php
$client_id = $_GET['id'] ?? null;
if (!$client_id) {
    echo "<h1>Error</h1><p>ID de cliente no especificado.</p>";
    return;
}
?>

<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?view=clientes">Clientes</a></li>
                <li class="breadcrumb-item active" id="breadcrumbClientName">Detalle del Cliente</li>
            </ol>
        </nav>
        <h2 class="fw-bold" id="clientTitle">Cargando...</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <button class="btn btn-outline-primary me-2" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="btn btn-primary" id="btnSaveClient">
            <i class="fas fa-save me-2"></i> Guardar Cambios
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Información del Cliente -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Información General</h5>
            </div>
            <div class="card-body">
                <form id="formEditCliente">
                    <input type="hidden" name="id" value="<?= $client_id ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre / Razón Social</label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Tipo</label>
                            <select class="form-select" name="type" id="editType">
                                <option value="Cliente">Cliente</option>
                                <option value="Empresa">Empresa</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Tipo Doc</label>
                            <select class="form-select" name="doc_type" id="editDocType">
                                <option value="CC">CC</option>
                                <option value="CE">CE</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="NIT">NIT</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nro Documento</label>
                        <input type="text" class="form-control" name="doc_number" id="editDocNumber">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Dirección</label>
                        <input type="text" class="form-control" name="address" id="editAddress">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Municipio</label>
                        <input type="text" class="form-control" name="municipality" id="editMunicipality">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Página Web</label>
                        <input type="url" class="form-control" name="website" id="editWebsite">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Descripción</label>
                        <textarea class="form-control" name="description" id="editDescription" rows="4"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contactos -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Contactos Relacionados</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalContacto">
                    <i class="fas fa-plus me-1"></i> Añadir Contacto
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4">Nombre</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th class="text-center">Principal</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="listaContactos">
                            <!-- Dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Contacto -->
<div class="modal fade" id="modalContacto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="formContacto">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="contactoId">
                <input type="hidden" name="client_id" value="<?= $client_id ?>">

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Trato</label>
                        <input type="text" class="form-control" name="salutation" id="contactoSalutation"
                            placeholder="Sr.">
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" class="form-control" name="first_name" id="contactoFirstName" required>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="last_name" id="contactoLastName" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cargo (Opcional)</label>
                    <input type="text" class="form-control" name="position" id="contactoPosition">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Principal</label>
                        <input type="email" class="form-control" name="email_1" id="contactoEmail1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Alternativo</label>
                        <input type="email" class="form-control" name="email_2" id="contactoEmail2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono 1</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone_1" id="contactoPhone1" required>
                            <div class="input-group-text">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_1" id="contactoWpp1">
                                    <label class="form-check-label ms-1 small" for="contactoWpp1"><i
                                            class="fab fa-whatsapp text-success"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono 2</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone_2" id="contactoPhone2">
                            <div class="input-group-text">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_2" id="contactoWpp2">
                                    <label class="form-check-label ms-1 small" for="contactoWpp2"><i
                                            class="fab fa-whatsapp text-success"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_primary" id="contactoIsPrimary">
                    <label class="form-check-label" for="contactoIsPrimary">
                        Es el contacto principal de este cliente
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Contacto</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        const clientId = <?= $client_id ?>;

        function loadClientData() {
            $.post('includes/endpoints/clientes.php', { action: 'fetch', id: clientId }, function (res) {
                if (res.status === 'success') {
                    const c = res.data;
                    $('#clientTitle, #breadcrumbClientName').text(c.name);
                    $('#editName').val(c.name);
                    $('#editType').val(c.type);
                    $('#editDocType').val(c.doc_type);
                    $('#editDocNumber').val(c.doc_number);
                    $('#editAddress').val(c.address);
                    $('#editMunicipality').val(c.municipality);
                    $('#editWebsite').val(c.website);
                    $('#editDescription').val(c.description);
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        }

        function loadContacts() {
            $.post('includes/endpoints/contactos.php', { action: 'list', client_id: clientId }, function (res) {
                if (res.status === 'success') {
                    let html = '';
                    res.data.forEach(co => {
                        html += `
                        <tr>
                            <td class="px-4 fw-medium">${co.salutation ? co.salutation + ' ' : ''}${co.first_name} ${co.last_name}</td>
                            <td>${co.position || '-'}</td>
                            <td>${co.email_1}</td>
                            <td>${co.phone_1} ${co.whatsapp_1 == 1 ? '<i class="fab fa-whatsapp text-success ms-1"></i>' : ''}</td>
                            <td class="text-center">${co.is_primary == 1 ? '<i class="fas fa-star text-warning"></i>' : '-'}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-icon btn-light me-2 edit-contact" data-contact='${JSON.stringify(co)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light text-danger delete-contact" data-id="${co.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#listaContactos').html(html || '<tr><td colspan="6" class="text-center py-4 text-muted">No hay contactos</td></tr>');
                }
            }, 'json');
        }

        $('#btnSaveClient').click(function () {
            $.post('includes/endpoints/clientes.php', $('#formEditCliente').serialize() + '&action=update', function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', 'Información del cliente actualizada', 'success');
                    loadClientData();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $('#formContacto').submit(function (e) {
            e.preventDefault();
            const action = $('#contactoId').val() ? 'update' : 'create';
            $.post('includes/endpoints/contactos.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.message, 'success');
                    $('#modalContacto').modal('hide');
                    $('#formContacto')[0].reset();
                    $('#contactoId').val('');
                    loadContacts();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.edit-contact', function () {
            const co = $(this).data('contact');
            $('#contactoId').val(co.id);
            $('#contactoSalutation').val(co.salutation);
            $('#contactoFirstName').val(co.first_name);
            $('#contactoLastName').val(co.last_name);
            $('#contactoPosition').val(co.position);
            $('#contactoEmail1').val(co.email_1);
            $('#contactoEmail2').val(co.email_2);
            $('#contactoPhone1').val(co.phone_1);
            $('#contactoPhone2').val(co.phone_2);
            $('#contactoWpp1').prop('checked', co.whatsapp_1 == 1);
            $('#contactoWpp2').prop('checked', co.whatsapp_2 == 1);
            $('#contactoIsPrimary').prop('checked', co.is_primary == 1);
            $('#modalContacto').modal('show');
        });

        $(document).on('click', '.delete-contact', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar contacto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('includes/endpoints/contactos.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadContacts();
                        }
                    }, 'json');
                }
            });
        });

        loadClientData();
        loadContacts();
    });
</script>