(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#testCreatorSubjectsTable').length) {
            $('#testCreatorSubjectsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[0, 'asc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.index.table.length_menu', 'Mostra _MENU_ materie'),
                    info: window.cqT('testcreator.index.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ materie'),
                    infoEmpty: window.cqT('testcreator.index.table.info_empty', 'Nessuna materia disponibile'),
                    infoFiltered: window.cqT('testcreator.index.table.info_filtered', '(filtrate da _MAX_ materie totali)'),
                    zeroRecords: window.cqT('testcreator.index.table.zero_records', 'Nessuna materia trovata'),
                    paginate: {
                        first: window.cqT('testcreator.index.table.first', 'Prima'),
                        last: window.cqT('testcreator.index.table.last', 'Ultima'),
                        next: window.cqT('testcreator.index.table.next', 'Successiva'),
                        previous: window.cqT('testcreator.index.table.previous', 'Precedente')
                    }
                }
            });
        }

        $(document).on('submit', '.js-unassign-form', function (event) {
            var button = $(this).find('button[type="submit"]');
            var subjectName = button.data('subject-name') || window.cqT('testcreator.index.confirm.default_subject', 'questa materia');
            var message = window.cqT('testcreator.index.confirm.unassign', 'Sei sicuro di voler disassegnare la materia %s?').replace('%s', subjectName);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
})();
