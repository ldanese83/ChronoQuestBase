(function () {
    function showAlert(type, message) {
        const alertBox = $('#messages-page-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type)
            .text(message);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined' || !$.fn.DataTable || !$('#messagesTable').length) {
            return;
        }

        const table = $('#messagesTable').DataTable({
            pageLength: parseInt($('#messagesTable').data('page-length'), 10) || 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
            select: {
                style: 'multi',
                selector: 'td:not(:last-child)'
            },
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false
                }
            ],
            order: [[2, 'desc']],
            language: {
                search: window.cqT('js.datatable.search', 'Cerca:'),
                lengthMenu: window.cqT('js.messages.length_menu', 'Mostra _MENU_ messaggi'),
                info: window.cqT('js.messages.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ messaggi'),
                infoEmpty: window.cqT('js.messages.none_available', 'Nessun messaggio disponibile'),
                infoFiltered: window.cqT('js.messages.info_filtered', '(filtrati da _MAX_ messaggi totali)'),
                zeroRecords: window.cqT('js.messages.none_found', 'Nessun messaggio trovato'),
                paginate: {
                    first: window.cqT('js.datatable.first', 'Prima'),
                    last: window.cqT('js.datatable.last', 'Ultima'),
                    next: window.cqT('js.datatable.next', 'Successiva'),
                    previous: window.cqT('js.datatable.previous', 'Precedente')
                }
            }
        });

        function aggiornaSelezione() {
            const count = table.rows({ selected: true }).count();
            $('#numSelezionatiMessaggi').text(count);

            if (count > 0) {
                $('#azioniSelezioneMessaggi').removeClass('d-none').addClass('d-flex');
            } else {
                $('#azioniSelezioneMessaggi').addClass('d-none').removeClass('d-flex');
            }
        }

        table.on('select deselect', aggiornaSelezione);
        aggiornaSelezione();

        $('#btnEliminaMessaggiSelezionati').on('click', function () {
            const ids = [];
            table.rows({ selected: true }).every(function () {
                ids.push(this.data()[0]);
            });

            if (ids.length === 0) {
                showAlert('warning', window.cqT('js.messages.none_selected', 'Nessun messaggio selezionato.'));
                return;
            }

            if (!window.confirm(window.cqT('js.messages.delete_selected.confirm', 'Vuoi eliminare i messaggi selezionati?'))) {
                return;
            }

            const formData = new URLSearchParams();
            ids.forEach(function (id) {
                formData.append('ids[]', id);
            });

            fetch('/studenti/messages/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('js.delete.failed', 'Eliminazione non riuscita.'));
                        return;
                    }

                    showAlert('success', payload.message || window.cqT('student.communications.message.delete.success', 'Messaggi eliminati con successo.'));
                    table.rows({ selected: true }).remove().draw(false);
                    aggiornaSelezione();
                })
                .catch(function () {
                    showAlert('danger', window.cqT('js.server.communication_error', 'Errore di comunicazione con il server.'));
                });
        });
    });
})();
