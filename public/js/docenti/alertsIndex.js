(function () {
    function showAlert(type, message) {
        const alertBox = $('#alerts-page-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type)
            .text(message);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#alertsTable').length) {
            $('#alertsTable').DataTable({
                pageLength: parseInt($('#alertsTable').data('page-length'), 10) || 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'desc']],
                language: {
                    search: window.cqT('js.datatable.search', 'Cerca:'),
                    lengthMenu: window.cqT('js.alerts.length_menu', 'Mostra _MENU_ alert'),
                    info: window.cqT('js.alerts.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ alert'),
                    infoEmpty: window.cqT('js.alerts.none_available', 'Nessun alert disponibile'),
                    infoFiltered: window.cqT('js.alerts.info_filtered', '(filtrati da _MAX_ alert totali)'),
                    zeroRecords: window.cqT('js.alerts.none_found', 'Nessun alert trovato'),
                    paginate: {
                        first: window.cqT('js.datatable.first', 'Prima'),
                        last: window.cqT('js.datatable.last', 'Ultima'),
                        next: window.cqT('js.datatable.next', 'Successiva'),
                        previous: window.cqT('js.datatable.previous', 'Precedente')
                    }
                }
            });
        }

        $(document).on('click', '.js-delete-alert', function () {
            const button = $(this);
            const alertId = button.data('alert-id');

            if (!window.confirm(window.cqT('js.alerts.delete.confirm', 'Sei sicuro di voler eliminare l\'alert selezionato?'))) {
                return;
            }

            fetch('/docenti/alerts/' + encodeURIComponent(alertId) + '/delete', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('js.delete.failed', 'Eliminazione non riuscita.'));
                        return;
                    }

                    showAlert('success', payload.message || window.cqT('js.alerts.delete.success', 'Alert eliminato correttamente.'));
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 250);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('js.alerts.delete.unexpected_error', 'Errore imprevisto durante l\'eliminazione dell\'alert.'));
                });
        });
    });
})();
