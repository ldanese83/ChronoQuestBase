(function () {
    function renderAnswers(rows, errorMessage) {
        var body = $('#importAnswersRows');
        body.empty();

        if (errorMessage) {
            body.append('<tr><td colspan="2" class="text-danger">' + errorMessage + '</td></tr>');
            return;
        }

        if (!rows.length) {
            body.append('<tr><td colspan="2" class="text-muted">' + window.cqT('testcreator.import_questions.external.no_answers', 'Nessuna risposta disponibile.') + '</td></tr>');
            return;
        }

        rows.forEach(function (row) {
            var correctBadge = row.corretta === 1
                ? '<span class="badge badge-success">' + window.cqT('testcreator.common.yes', 'SI') + '</span>'
                : '<span class="badge badge-secondary">' + window.cqT('testcreator.common.no', 'No') + '</span>';

            body.append('<tr><td>' + String(row.risposta || '') + '</td><td>' + correctBadge + '</td></tr>');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!(window.jQuery && $.fn.DataTable && $('#testCreatorImportExternalTable').length)) {
            return;
        }

        $('#testCreatorImportExternalTable').DataTable({
            pageLength: 25,
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

        $(document).on('submit', '.js-import-question-form', function (event) {
            if (!window.confirm(window.cqT('testcreator.import_questions.external.confirm_import', 'Vuoi importare tra le tue la domanda selezionata?'))) {
                event.preventDefault();
            }
        });

        $(document).on('click', '.js-open-answers-modal', function () {
            var questionId = parseInt($(this).data('question-id'), 10) || 0;
            renderAnswers([], null);

            if (questionId <= 0) {
                renderAnswers([], window.cqT('testcreator.import_questions.external.invalid_question', 'Domanda non valida.'));
                return;
            }

            $.getJSON('/testcreator/import-domande/' + questionId + '/risposte')
                .done(function (payload) {
                    if (!payload || payload.success !== true) {
                        renderAnswers([], window.cqT('testcreator.import_questions.external.answers_load_failed', 'Impossibile caricare le risposte.'));
                        return;
                    }
                    renderAnswers(payload.answers || [], null);
                })
                .fail(function () {
                    renderAnswers([], window.cqT('testcreator.import_questions.external.answers_load_error', 'Errore durante il caricamento delle risposte.'));
                });
        });
    });
})();
