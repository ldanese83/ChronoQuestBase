(function () {
    function showAlert(type, message) {
        const alertBox = $('#punishment-management-alert');
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
        $('#punishmentFormModalLabel').text(window.cqT('teacher.punishments.modal.add_title', 'Aggiungi punizione'));
        $('#punishmentId').val('0');
        $('#punishmentDescription').val('');
        $('#punishmentDays').val('1');
        $('#punishmentImage').val('');
    }

    function setEditMode(button) {
        $('#punishmentFormModalLabel').text(window.cqT('teacher.punishments.modal.edit_title', 'Modifica punizione'));
        $('#punishmentId').val(button.data('punishment-id'));
        $('#punishmentDescription').val(button.data('punishment-description'));
        $('#punishmentDays').val(button.data('punishment-days'));
        $('#punishmentImage').val('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#punishmentTable').length) {
            $('#punishmentTable').DataTable({
                pageLength: parseInt($('#punishmentTable').data('page-length'), 10) || 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[2, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.punishments.table.length_menu', 'Mostra _MENU_ punizioni'),
                    info: window.cqT('teacher.punishments.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ punizioni'),
                    infoEmpty: window.cqT('teacher.punishments.table.info_empty', 'Nessuna punizione disponibile'),
                    infoFiltered: window.cqT('js.table.info_filtered', '(filtrati da _MAX_ elementi totali)'),
                    zeroRecords: window.cqT('teacher.punishments.table.zero_records', 'Nessuna punizione trovata'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        if ($.fn.DataTable && $('#punishmentImportTable').length) {
            $('#punishmentImportTable').DataTable({
                pageLength: parseInt($('#punishmentImportTable').data('page-length'), 10) || 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[3, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.punishments.import.table.length_menu', 'Mostra _MENU_ punizioni importabili'),
                    info: window.cqT('teacher.punishments.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ punizioni'),
                    infoEmpty: window.cqT('teacher.punishments.import.table.info_empty', 'Nessuna punizione importabile'),
                    infoFiltered: window.cqT('js.table.info_filtered', '(filtrati da _MAX_ elementi totali)'),
                    zeroRecords: window.cqT('teacher.punishments.table.zero_records', 'Nessuna punizione trovata'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        $('#openCreatePunishmentModal').on('click', function () {
            setCreateMode();
            $('#punishmentFormModal').modal('show');
        });

        $('#openImportPunishmentModal').on('click', function () {
            $('#importPunishmentModal').modal('show');
        });

        $(document).on('click', '.js-edit-punishment', function () {
            setEditMode($(this));
            $('#punishmentFormModal').modal('show');
        });

        $('#punishmentForm').on('submit', function (event) {
            event.preventDefault();

            const punishmentId = parseInt($('#punishmentId').val(), 10) || 0;
            if (punishmentId === 0) {
                const fileInput = $('#punishmentImage').get(0);
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    showAlert('warning', window.cqT('teacher.punishments.image.required', 'Carica un\'immagine per la nuova punizione.'));
                    return;
                }
            }

            const formData = new FormData(this);
            $('#savePunishmentButton').prop('disabled', true);

            fetch('/docenti/punizioni/save', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('js.save.failed', 'Salvataggio non riuscito.'));
                        return;
                    }

                    $('#punishmentFormModal').modal('hide');
                    showAlert('success', payload.message || window.cqT('teacher.punishments.saved.success', 'Punizione salvata correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.punishments.save.unexpected_error', 'Errore imprevisto durante il salvataggio della punizione.'));
                })
                .finally(function () {
                    $('#savePunishmentButton').prop('disabled', false);
                });
        });

        $(document).on('click', '.js-delete-punishment', function () {
            const punishmentId = parseInt($(this).data('punishment-id'), 10) || 0;

            if (!window.confirm(window.cqT('teacher.punishments.delete.confirm', 'Confermi l\'eliminazione della punizione selezionata?'))) {
                return;
            }

            fetch('/docenti/punizioni/' + punishmentId + '/delete', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('js.delete.failed', 'Eliminazione non riuscita.'));
                        return;
                    }

                    showAlert('success', payload.message || window.cqT('teacher.punishments.deleted.success', 'Punizione eliminata correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.punishments.delete.unexpected_error', 'Errore imprevisto durante l\'eliminazione della punizione.'));
                });
        });

        $(document).on('click', '.js-import-punishment', function () {
            const sourcePunishmentId = parseInt($(this).data('source-punishment-id'), 10) || 0;

            if (!sourcePunishmentId) {
                showAlert('danger', window.cqT('teacher.punishments.import.source_invalid', 'Punizione di origine non valida.'));
                return;
            }

            if (!window.confirm(window.cqT('teacher.punishments.import.confirm', 'Confermi l\'importazione della punizione selezionata?'))) {
                return;
            }

            const formData = new URLSearchParams();
            formData.append('source_punishment_id', String(sourcePunishmentId));

            fetch('/docenti/punizioni/import', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('js.import.failed', 'Importazione non riuscita.'));
                        return;
                    }

                    $('#importPunishmentModal').modal('hide');
                    showAlert('success', payload.message || window.cqT('teacher.punishments.imported.success', 'Punizione importata correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.punishments.import.unexpected_error', 'Errore imprevisto durante l\'importazione della punizione.'));
                });
        });
    });
})();
