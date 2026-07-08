(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#assignedPunishmentTable').length) {
            $('#assignedPunishmentTable').DataTable({
                pageLength: parseInt($('#assignedPunishmentTable').data('page-length'), 10) || 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[3, 'desc'], [1, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.punishments.assigned.table.length_menu', 'Mostra _MENU_ assegnazioni'),
                    info: window.cqT('teacher.punishments.assigned.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ assegnazioni'),
                    infoEmpty: window.cqT('teacher.punishments.assigned.table.info_empty', 'Nessuna punizione assegnata'),
                    infoFiltered: window.cqT('teacher.punishments.assigned.table.info_filtered', '(filtrate da _MAX_ assegnazioni totali)'),
                    zeroRecords: window.cqT('teacher.punishments.assigned.table.zero_records', 'Nessuna assegnazione trovata'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }
    });
})();
