<div class="page-title-section animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-0">Gestión de Dominios</h1>
            <p class="text-muted small mb-0">Administración de nombres de dominio y vencimientos</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary px-3" onclick="loadDomains()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-primary px-4 fw-bold" onclick="openDomainModal()">
                <i class="fas fa-plus me-2"></i> Nuevo Dominio
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small text-uppercase text-muted">
                            <tr>
                                <th class="px-4">Dominio</th>
                                <th>Propietario</th>
                                <th>Proveedor</th>
                                <th>Registro</th>
                                <th>Precio Venta</th>
                                <th>Vencimiento</th>
                                <th class="text-end px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="domainsList">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dominio -->
<div class="modal fade" id="modalDominio" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formDominio">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Guardar Dominio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="dominioId">

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nombre del Dominio</label>
                    <input type="text" class="form-control" name="name" id="dominioName" required
                        placeholder="ej. ejemplo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Propietario</label>
                    <select class="form-select" name="client_id" id="dominioClientId">
                        <option value="">Cargando...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Proveedor</label>
                    <select class="form-select" name="provider_id" id="dominioProviderId">
                        <option value="">Selección de proveedor...</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha Registro</label>
                        <input type="date" class="form-control" name="registration_date" id="dominioRegDate">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha Caducidad</label>
                        <input type="date" class="form-control" name="expiration_date" id="dominioExpDate">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Valor de Venta (COP)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control" name="sale_price" id="dominioPrice"
                            placeholder="0.00">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function loadDomains() {
        $.post('includes/endpoints/dominios.php', { action: 'list' }, function (res) {
            if (res.status === 'success') {
                let html = '';
                if (res.data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center py-5 text-muted">No hay dominios registrados</td></tr>';
                } else {
                    res.data.forEach(d => {
                        // Check expiry status
                        let expiryBadge = '';
                        if (d.expiration_date) {
                            const today = new Date();
                            const exp = new Date(d.expiration_date);
                            const diffTime = exp - today;
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                            if (diffDays < 0) {
                                expiryBadge = '<span class="badge bg-danger ms-2">Expirado</span>';
                            } else if (diffDays < 30) {
                                expiryBadge = `<span class="badge bg-warning text-dark ms-2">${diffDays} días</span>`;
                            }
                        }

                        const priceFormatter = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
                        const priceDisplay = d.sale_price > 0 ? priceFormatter.format(d.sale_price) : '-';

                        const providerUrl = d.provider_url ? `<a href="${d.provider_url}" target="_blank" class="ms-1 text-muted" title="Ir al sitio"><i class="fas fa-external-link-alt fa-xs"></i></a><button class="btn btn-link btn-sm p-0 ms-1 text-muted js-copy-url" data-url="${d.provider_url}" title="Copiar URL"><i class="fas fa-copy fa-xs"></i></button>` : '';
                        html += `
                    <tr>
                        <td class="px-4 fw-bold text-primary">${d.name}</td>
                        <td>${d.owner_display}</td>
                        <td class="small">${d.provider_name || 'Interno'} ${providerUrl}</td>
                        <td class="small text-muted">${d.registration_date || '-'}</td>
                        <td>${priceDisplay}</td>
                        <td class="small fw-bold">${d.expiration_date || '-'} ${expiryBadge}</td>
                        <td class="text-end px-4">
                            <button class="btn btn-sm btn-icon btn-light me-2 js-edit-domain" data-id="${d.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-light text-danger js-delete-domain" data-id="${d.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                    });
                }
                $('#domainsList').html(html);
            }
        }, 'json');
    }

    function loadClientsForModal() {
        $.post('includes/endpoints/servidores.php', { action: 'list_clients' }, function (res) {
            if (res.status === 'success') {
                // Using the same list_clients endpoint which adds Interacto SA as first option
                let options = '';
                res.data.forEach(c => {
                    options += `<option value="${c.id}">${c.name}</option>`;
                });
                $('#dominioClientId').html(options);
            }
        }, 'json');
    }

    function loadProvidersForModal() {
        $.post('includes/endpoints/maestros.php', { action: 'list_records', table_name: 'mst_proveedores' }, function (res) {
            if (res.status === 'success') {
                let options = '<option value="">Sin proveedor / Interno</option>';
                res.data.forEach(p => {
                    options += `<option value="${p.id}">${p.name}</option>`;
                });
                $('#dominioProviderId').html(options);
            }
        }, 'json');
    }

    function openDomainModal(id = null) {
        $('#formDominio')[0].reset();
        $('#dominioId').val('');
        loadClientsForModal();
        loadProvidersForModal();

        if (id) {
            $.post('includes/endpoints/dominios.php', { action: 'fetch', id: id }, function (res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#dominioId').val(d.id);
                    $('#dominioName').val(d.name);
                    $('#dominioClientId').val(d.client_id || ''); // '' is Interacto SA in dropdown
                    $('#dominioRegDate').val(d.registration_date);
                    $('#dominioExpDate').val(d.expiration_date);
                    $('#dominioPrice').val(d.sale_price);
                    $('#dominioProviderId').val(d.provider_id || '');
                    $('#modalDominio').modal('show');
                }
            }, 'json');
        } else {
            $('#modalDominio').modal('show');
        }
    }

    $(document).ready(function () {
        loadDomains();

        $('#formDominio').submit(function (e) {
            e.preventDefault();
            const action = $('#dominioId').val() ? 'update' : 'create';
            $.post('includes/endpoints/dominios.php', $(this).serialize() + '&action=' + action, function (res) {
                if (res.status === 'success') {
                    $('#modalDominio').modal('hide');
                    Swal.fire('Guardado', res.message, 'success');
                    loadDomains();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
        });

        $(document).on('click', ('.js-edit-domain'), function () {
            openDomainModal($(this).data('id'));
        });

        $(document).on('click', '.js-delete-domain', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Confirmar?',
                text: "No podrás deshacer esto",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.post('includes/endpoints/dominios.php', { action: 'delete', id: id }, function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Eliminado', res.message, 'success');
                            loadDomains();
                        }
                    }, 'json');
                }
            });
        });

        $(document).on('click', '.js-copy-url', function () {
            const url = $(this).data('url');
            if (!url) return;
            navigator.clipboard.writeText(url).then(() => {
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.html('<i class="fas fa-check text-success"></i>');
                setTimeout(() => { $btn.html(originalHtml); }, 2000);
            });
        });
    });
</script>