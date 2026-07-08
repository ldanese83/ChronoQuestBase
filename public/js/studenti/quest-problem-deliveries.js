(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined' || !$.fn.DataTable || !$('#studentQuestProblemDeliveriesTable').length) {
            return;
        }

        $('#studentQuestProblemDeliveriesTable').DataTable({
            pageLength: parseInt($('#studentQuestProblemDeliveriesTable').data('page-length'), 10) || 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
            order: [[0, 'asc'], [1, 'asc']],
            language: {
                search: window.cqT('js.table.search', 'Cerca:'),
                lengthMenu: window.cqT('student.quest.problems.table.length_menu', 'Mostra _MENU_ esercizi'),
                info: window.cqT('student.quest.problems.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ esercizi'),
                infoEmpty: window.cqT('student.quest.problems.table.info_empty', 'Nessun esercizio con problemi'),
                infoFiltered: window.cqT('student.quest.problems.table.info_filtered', '(filtrati da _MAX_ esercizi totali)'),
                zeroRecords: window.cqT('student.quest.problems.table.zero_records', 'Nessun esercizio trovato'),
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
