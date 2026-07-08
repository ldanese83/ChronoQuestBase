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

    function initDataTable() {
        if (typeof window.jQuery === 'undefined' || !$.fn.DataTable || !$('#testCreatorTopicsTable').length) {
            return null;
        }

        return $('#testCreatorTopicsTable').DataTable({
            pageLength: 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
            order: [[2, 'asc'], [1, 'asc']],
            columnDefs: [
                { targets: [0], visible: false, searchable: true }
            ],
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
    }

    function initConfirmations() {
        $(document).on('submit', '.js-delete-topic-form', function (event) {
            var button = $(this).find('button[type="submit"]');
            var topicName = button.data('topic-name') || window.cqT('testcreator.topics.confirm.default_topic', 'questo argomento');
            var message = window.cqT('testcreator.topics.confirm.delete', "Sei sicuro di voler eliminare l'argomento %s?").replace('%s', topicName);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    }

    function initTopicModal() {
        $(document).on('click', '.js-open-topic-modal', function () {
            var topicId = parseInt($(this).data('topic-id'), 10) || 0;
            var topicName = String($(this).data('topic-name') || '');
            var topicSubjectId = parseInt($(this).data('topic-subject-id'), 10) || 0;

            $('#topic-id').val(topicId);
            $('#topic-name').val(topicName);
            $('#topic-subject').val(topicSubjectId > 0 ? String(topicSubjectId) : '');
            $('#topicModalLabel').text(topicId > 0
                ? window.cqT('testcreator.topics.modal.edit_title', 'Modifica argomento')
                : window.cqT('testcreator.topics.modal.new_title', 'Nuovo argomento'));
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        var table = initDataTable();
        initConfirmations();
        initTopicModal();

        if (!table) {
            return;
        }

        var initialSubjectId = parseInt($('#testCreatorTopicsTable').data('initial-subject-id'), 10) || 0;
        applySubjectFilter(table, initialSubjectId, false);

        $('#subjectFilter').on('change', function () {
            var selectedSubjectId = parseInt($(this).val(), 10) || 0;
            applySubjectFilter(table, selectedSubjectId, true);
        });
    });
})();
