(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if (!$.fn.DataTable || !$('#chapterExercisesTable').length) {
            return;
        }

        $('#chapterExercisesTable').DataTable({
            pageLength: parseInt($('#chapterExercisesTable').data('page-length'), 10) || 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
            order: [[0, 'asc']],
            language: {
                search: window.cqT('js.table.search', 'Cerca:'),
                lengthMenu: window.cqT('teacher.quest.chapter_detail.table.length_menu', 'Mostra _MENU_ esercizi'),
                info: window.cqT('teacher.quest.chapter_detail.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ esercizi'),
                infoEmpty: window.cqT('js.exercises.none_available', 'Nessun esercizio disponibile'),
                infoFiltered: window.cqT('teacher.quest.chapter_detail.table.info_filtered', '(filtrati da _MAX_ esercizi totali)'),
                zeroRecords: window.cqT('js.exercises.none_found', 'Nessun esercizio trovato'),
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
