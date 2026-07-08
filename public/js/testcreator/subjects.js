(function () {
    function initDataTable() {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#testCreatorAllSubjectsTable').length) {
            $('#testCreatorAllSubjectsTable').DataTable({
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
    }

    function initConfirmations() {
        $(document).on('submit', '.js-delete-subject-form', function (event) {
            var button = $(this).find('button[type="submit"]');
            var subjectName = button.data('subject-name') || window.cqT('testcreator.index.confirm.default_subject', 'questa materia');
            var message = window.cqT('testcreator.subjects.confirm.delete', 'Sei sicuro di voler eliminare la materia %s?').replace('%s', subjectName);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });

        $(document).on('submit', '.js-unassign-form', function (event) {
            var button = $(this).find('button[type="submit"]');
            var subjectName = button.data('subject-name') || window.cqT('testcreator.index.confirm.default_subject', 'questa materia');
            var message = window.cqT('testcreator.subjects.confirm.unassign_me', 'Sei sicuro di voler disassegnarti la materia %s?').replace('%s', subjectName);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    }

    function initSubjectModal() {
        $(document).on('click', '.js-open-subject-modal', function () {
            var subjectId = parseInt($(this).data('subject-id'), 10) || 0;
            var $subjectId = $('#subject-id');
            var $subjectName = $('#subject-name');
            var $modalTitle = $('#subjectModalLabel');

            $subjectId.val('0');
            $subjectName.val('');
            $modalTitle.text(window.cqT('testcreator.subjects.modal.new_title', 'Nuova materia'));

            var url = '/testcreator/materie/' + subjectId + '/form-data';
            $.getJSON(url)
                .done(function (response) {
                    if (!response || response.success !== true || !response.subject) {
                        window.alert(window.cqT('testcreator.subjects.alert.fetch_failed', 'Impossibile recuperare i dati della materia.'));
                        return;
                    }

                    $subjectId.val(response.subject.id_materia || 0);
                    $subjectName.val(response.subject.nome_materia || '');
                    $modalTitle.text((response.subject.id_materia || 0) > 0
                        ? window.cqT('testcreator.subjects.modal.edit_title', 'Modifica materia')
                        : window.cqT('testcreator.subjects.modal.new_title', 'Nuova materia'));
                })
                .fail(function () {
                    window.alert(window.cqT('testcreator.subjects.alert.load_error', 'Errore durante il caricamento della materia.'));
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        initDataTable();
        initConfirmations();
        initSubjectModal();
    });
})();
