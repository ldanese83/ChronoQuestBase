(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable && $('#testCreatorQuizzesTable').length) {
            $('#testCreatorQuizzesTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[3, 'desc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.quizzes.table.length_menu', 'Mostra _MENU_ quiz'),
                    info: window.cqT('testcreator.quizzes.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ quiz'),
                    infoEmpty: window.cqT('testcreator.quizzes.table.info_empty', 'Nessun quiz disponibile'),
                    infoFiltered: window.cqT('testcreator.quizzes.table.info_filtered', '(filtrati da _MAX_ quiz totali)'),
                    zeroRecords: window.cqT('testcreator.quizzes.table.zero_records', 'Nessun quiz trovato'),
                    paginate: {
                        first: window.cqT('testcreator.index.table.first', 'Prima'),
                        last: window.cqT('testcreator.index.table.last', 'Ultima'),
                        next: window.cqT('testcreator.index.table.next', 'Successiva'),
                        previous: window.cqT('testcreator.index.table.previous', 'Precedente')
                    }
                }
            });
        }

        $(document).on('submit', '.js-delete-quiz-form', function (event) {
            var quizName = $(this).find('button[type="submit"]').data('quiz-name') || window.cqT('testcreator.quizzes.confirm.default_quiz', 'questo quiz');
            var message = window.cqT('testcreator.quizzes.confirm.delete', 'Sei sicuro di voler eliminare il quiz "%s"?').replace('%s', quizName);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
})();
