(function () {
    function showAlert(type, message) {
        const alertBox = $('#power-management-alert');
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
        $('#powerFormModalLabel').text(window.cqT('teacher.powers.modal.add_title', 'Aggiungi potere'));
        $('#powerId').val('0');
        $('#powerName').val('');
        $('#powerDescription').val('');
        $('#powerLevel').val('0');
        $('#powerMana').val('1');
        $('#powerImage').val('');
    }

    function setEditMode(button) {
        $('#powerFormModalLabel').text(window.cqT('teacher.powers.modal.edit_title', 'Modifica potere'));
        $('#powerId').val(button.data('power-id'));
        $('#powerName').val(button.data('power-name'));
        $('#powerDescription').val(button.data('power-description'));
        $('#powerLevel').val(button.data('power-level'));
        $('#powerMana').val(button.data('power-mana'));
        $('#powerImage').val('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#powerTable').length) {
            $('#powerTable').DataTable({
                pageLength: parseInt($('#powerTable').data('page-length'), 10) || 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[3, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.powers.table.length_menu', 'Mostra _MENU_ poteri'),
                    info: window.cqT('teacher.powers.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ poteri'),
                    infoEmpty: window.cqT('teacher.powers.table.info_empty', 'Nessun potere disponibile'),
                    infoFiltered: window.cqT('js.table.info_filtered', '(filtrati da _MAX_ elementi totali)'),
                    zeroRecords: window.cqT('teacher.powers.table.zero_records', 'Nessun potere trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        if ($.fn.DataTable && $('#powerImportTable').length) {
            $('#powerImportTable').DataTable({
                pageLength: parseInt($('#powerImportTable').data('page-length'), 10) || 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[2, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.powers.import.table.length_menu', 'Mostra _MENU_ poteri importabili'),
                    info: window.cqT('teacher.powers.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ poteri'),
                    infoEmpty: window.cqT('teacher.powers.import.table.info_empty', 'Nessun potere importabile'),
                    infoFiltered: window.cqT('js.table.info_filtered', '(filtrati da _MAX_ elementi totali)'),
                    zeroRecords: window.cqT('teacher.powers.table.zero_records', 'Nessun potere trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        $('#openCreatePowerModal').on('click', function () {
            setCreateMode();
            $('#powerFormModal').modal('show');
        });

        $('#openImportPowerModal').on('click', function () {
            $('#importPowerModal').modal('show');
        });

        $(document).on('click', '.js-edit-power', function () {
            setEditMode($(this));
            $('#powerFormModal').modal('show');
        });

        $('#powerForm').on('submit', function (event) {
            event.preventDefault();

            const powerId = parseInt($('#powerId').val(), 10) || 0;
            if (powerId === 0) {
                const fileInput = $('#powerImage').get(0);
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    showAlert('warning', window.cqT('teacher.powers.image.required', 'Carica un\'immagine per il nuovo potere.'));
                    return;
                }
            }

            const formData = new FormData(this);
            $('#savePowerButton').prop('disabled', true);

            fetch('/docenti/poteri/save', {
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

                    $('#powerFormModal').modal('hide');
                    showAlert('success', payload.message || window.cqT('teacher.powers.saved.success', 'Potere salvato correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.powers.save.unexpected_error', 'Errore imprevisto durante il salvataggio del potere.'));
                })
                .finally(function () {
                    $('#savePowerButton').prop('disabled', false);
                });
        });

        $(document).on('click', '.js-delete-power', function () {
            const powerId = parseInt($(this).data('power-id'), 10) || 0;
            const powerName = $(this).data('power-name') || window.cqT('teacher.powers.this_power', 'questo potere');

            if (!window.confirm(window.cqT('teacher.powers.delete.confirm', 'Confermi l\'eliminazione di "{name}"?').replace('{name}', powerName))) {
                return;
            }

            fetch('/docenti/poteri/' + powerId + '/delete', {
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

                    showAlert('success', payload.message || window.cqT('teacher.powers.deleted.success', 'Potere eliminato correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.powers.delete.unexpected_error', 'Errore imprevisto durante l\'eliminazione del potere.'));
                });
        });

        $(document).on('click', '.js-import-power', function () {
            const sourcePowerId = parseInt($(this).data('source-power-id'), 10) || 0;
            const sourcePowerName = $(this).data('source-power-name') || window.cqT('teacher.powers.this_power', 'questo potere');

            if (!sourcePowerId) {
                showAlert('danger', window.cqT('teacher.powers.import.source_invalid', 'Potere di origine non valido.'));
                return;
            }

            if (!window.confirm(window.cqT('teacher.powers.import.confirm', 'Confermi l\'importazione di "{name}"?').replace('{name}', sourcePowerName))) {
                return;
            }

            const formData = new URLSearchParams();
            formData.append('source_power_id', String(sourcePowerId));

            fetch('/docenti/poteri/import', {
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

                    $('#importPowerModal').modal('hide');
                    showAlert('success', payload.message || window.cqT('teacher.powers.imported.success', 'Potere importato correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.powers.import.unexpected_error', 'Errore imprevisto durante l\'importazione del potere.'));
                });
        });
    });
})();
