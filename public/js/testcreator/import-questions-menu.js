(function () {
    function escapeRegExp(value) {
        return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function applySubjectFilter(table, subjectId, updateQuery) {
        var normalizedSubjectId = parseInt(subjectId, 10) || 0;
        var searchValue = normalizedSubjectId > 0 ? '^' + escapeRegExp(String(normalizedSubjectId)) + '$' : 'a^';
        table.column(0).search(searchValue, true, false).draw();

        if (updateQuery) {
            var url = new URL(window.location.href);
            if (normalizedSubjectId > 0) {
                url.searchParams.set('materia', String(normalizedSubjectId));
            } else {
                url.searchParams.delete('materia');
            }
            window.history.replaceState({}, '', url.toString());
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!(window.jQuery && $.fn.DataTable && $('#testCreatorImportTopicsTable').length)) {
            return;
        }

        var table = $('#testCreatorImportTopicsTable').DataTable({
            pageLength: 25,
            order: [[2, 'asc'], [1, 'asc']],
            columnDefs: [{ targets: [0], visible: false, searchable: true }],
            language: {
                search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                lengthMenu: window.cqT('testcreator.topics.table.length_menu', 'Mostra _MENU_ argomenti'),
                info: window.cqT('testcreator.topics.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ argomenti'),
                infoEmpty: window.cqT('testcreator.topics.table.info_empty', 'Nessun argomento disponibile'),
                infoFiltered: window.cqT('testcreator.topics.table.info_filtered', '(filtrati da _MAX_ argomenti totali)'),
                zeroRecords: window.cqT('testcreator.topics.table.zero_records', 'Nessun argomento trovato'),
                paginate: {
                    first: window.cqT('testcreator.index.table.first', 'Prima'),
                    last: window.cqT('testcreator.index.table.last', 'Ultima'),
                    next: window.cqT('testcreator.index.table.next', 'Successiva'),
                    previous: window.cqT('testcreator.index.table.previous', 'Precedente')
                }
            }
        });

        var initialSubjectId = parseInt($('#testCreatorImportTopicsTable').data('initial-subject-id'), 10) || 0;
        applySubjectFilter(table, initialSubjectId, false);

        $('#importSubjectFilter').on('change', function () {
            applySubjectFilter(table, $(this).val(), true);
        });

    });
})();
