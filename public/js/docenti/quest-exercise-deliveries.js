(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined' || !$.fn.DataTable || !$('#exerciseDeliveriesTable').length) {
            return;
        }

        $('#exerciseDeliveriesTable').DataTable({
            pageLength: parseInt($('#exerciseDeliveriesTable').data('page-length'), 10) || 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
            order: [[1, 'asc'], [0, 'asc']],
            language: {
                search: window.cqT('js.table.search', 'Cerca:'),
                lengthMenu: window.cqT('teacher.quest.deliveries.table.length_menu', 'Mostra _MENU_ studenti'),
                info: window.cqT('teacher.quest.deliveries.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ studenti'),
                infoEmpty: window.cqT('js.students.none_available', 'Nessuno studente disponibile'),
                infoFiltered: window.cqT('teacher.quest.deliveries.table.info_filtered', '(filtrati da _MAX_ studenti totali)'),
                zeroRecords: window.cqT('js.students.none_found', 'Nessuno studente trovato'),
                paginate: {
                    first: window.cqT('js.table.paginate.first', 'Prima'),
                    last: window.cqT('js.table.paginate.last', 'Ultima'),
                    next: window.cqT('js.table.paginate.next', 'Successiva'),
                    previous: window.cqT('js.table.paginate.previous', 'Precedente')
                }
            }
        });
    });
})();
