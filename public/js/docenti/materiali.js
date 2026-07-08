(function () {
    function translateMessage(message, fallback) {
        if (message) {
            return window.cqT(message, message);
        }

        return fallback;
    }

    function showAlert(type, message) {
        const alertBox = $('#material-management-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type)
            .text(message || window.cqT('js.operation.completed', 'Operazione completata.'));

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function handleJsonResponse(response) {
        return response.json().catch(function () {
            return { success: false, message: window.cqT('js.response.invalid', 'Risposta non valida dal server.') };
        });
    }

    function setCreateMode() {
        $('#materialFormModalLabel').text(window.cqT('teacher.materials.modal.add_title', 'Carica materiale'));
        $('#materialId').val('0');
        $('#materialName').val('');
        $('#materialDescription').val('');
        $('#materialTopic').val('');
        $('#materialFile').val('');
    }

    function setEditMode(button) {
        $('#materialFormModalLabel').text(window.cqT('teacher.materials.modal.edit_title', 'Modifica materiale'));
        $('#materialId').val(button.data('material-id'));
        $('#materialName').val(button.data('material-name') || '');
        $('#materialDescription').val(button.data('material-description') || '');
        $('#materialTopic').val(String(button.data('material-topic-id') || ''));
        $('#materialFile').val('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#materialTable').length) {
            $('#materialTable').DataTable({
                pageLength: parseInt($('#materialTable').data('page-length'), 10) || 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[2, 'asc'], [3, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.materials.table.length_menu', 'Mostra _MENU_ materiali'),
                    info: window.cqT('teacher.materials.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ materiali'),
                    infoEmpty: window.cqT('teacher.materials.table.info_empty', 'Nessun materiale disponibile'),
                    infoFiltered: window.cqT('teacher.materials.table.info_filtered', '(filtrati da _MAX_ materiali totali)'),
                    zeroRecords: window.cqT('teacher.materials.table.zero_records', 'Nessun materiale trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        $('#openCreateMaterialModal').on('click', function () {
            setCreateMode();
            $('#materialFormModal').modal('show');
        });

        $(document).on('click', '.js-edit-material', function () {
            setEditMode($(this));
            $('#materialFormModal').modal('show');
        });

        $('#materialForm').on('submit', function (event) {
            event.preventDefault();

            const materialId = parseInt($('#materialId').val(), 10) || 0;
            if (materialId === 0) {
                const fileInput = $('#materialFile').get(0);
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    showAlert('warning', window.cqT('teacher.materials.file.required', 'Per creare un materiale devi selezionare un file.'));
                    return;
                }
            }

            const formData = new FormData(this);
            $('#saveMaterialButton').prop('disabled', true);

            fetch('/docenti/materiali/save', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', translateMessage(payload.message, window.cqT('js.save.failed', 'Salvataggio non riuscito.')));
                        return;
                    }

                    $('#materialFormModal').modal('hide');
                    showAlert('success', translateMessage(payload.message, window.cqT('teacher.materials.saved.success', 'Materiale salvato correttamente.')));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.materials.save.unexpected_error', 'Errore imprevisto durante il salvataggio del materiale.'));
                })
                .finally(function () {
                    $('#saveMaterialButton').prop('disabled', false);
                });
        });

        $(document).on('click', '.js-delete-material', function () {
            const materialId = parseInt($(this).data('material-id'), 10) || 0;
            const materialName = $(this).data('material-name') || window.cqT('teacher.materials.this_material', 'questo materiale');

            if (!window.confirm(window.cqT('teacher.materials.delete.confirm', 'Confermi l\'eliminazione di "{name}"?').replace('{name}', materialName))) {
                return;
            }

            fetch('/docenti/materiali/' + materialId + '/delete', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', translateMessage(payload.message, window.cqT('js.delete.failed', 'Eliminazione non riuscita.')));
                        return;
                    }

                    showAlert('success', translateMessage(payload.message, window.cqT('teacher.materials.deleted.success', 'Materiale eliminato correttamente.')));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.materials.delete.unexpected_error', 'Errore imprevisto durante l\'eliminazione del materiale.'));
                });
        });
    });
})();
