(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable && $('#testCreatorQuestionsTable').length) {
            $('#testCreatorQuestionsTable').DataTable({
                pageLength: 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'asc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.questions.list.table.length_menu', 'Mostra _MENU_ domande'),
                    info: window.cqT('testcreator.questions.list.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ domande'),
                    infoEmpty: window.cqT('testcreator.questions.list.table.info_empty', 'Nessuna domanda disponibile'),
                    infoFiltered: window.cqT('testcreator.questions.list.table.info_filtered', '(filtrate da _MAX_ domande totali)'),
                    zeroRecords: window.cqT('testcreator.questions.list.table.zero_records', 'Nessuna domanda trovata'),
                    paginate: {
                        first: window.cqT('testcreator.index.table.first', 'Prima'),
                        last: window.cqT('testcreator.index.table.last', 'Ultima'),
                        next: window.cqT('testcreator.index.table.next', 'Successiva'),
                        previous: window.cqT('testcreator.index.table.previous', 'Precedente')
                    }
                }
            });
        }

        $(document).on('submit', '.js-remove-question-form', function (event) {
            if (!window.confirm(window.cqT('testcreator.questions.list.confirm.remove', 'Sei sicuro di voler eliminare la domanda dal tuo archivio?'))) {
                event.preventDefault();
            }
        });

        $(document).on('submit', '.js-remove-permanent-question-form', function (event) {
            if (!window.confirm(window.cqT('testcreator.questions.list.confirm.remove_permanent', "Confermi l'eliminazione definitiva della domanda?"))) {
                event.preventDefault();
            }
        });
    });
})();
