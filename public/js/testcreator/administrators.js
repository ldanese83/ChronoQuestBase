(function () {
    function initDataTable() {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#testCreatorAdministratorsTable').length) {
            $('#testCreatorAdministratorsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.administrators.table.length_menu', 'Mostra _MENU_ docenti'),
                    info: window.cqT('testcreator.administrators.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ docenti'),
                    infoEmpty: window.cqT('testcreator.administrators.table.info_empty', 'Nessun docente disponibile'),
                    infoFiltered: window.cqT('testcreator.administrators.table.info_filtered', '(filtrati da _MAX_ docenti totali)'),
                    zeroRecords: window.cqT('testcreator.administrators.table.zero_records', 'Nessun docente trovato'),
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
        $(document).on('submit', '.js-promote-admin-form', function (event) {
            var user = $(this).find('button[type="submit"]').data('user') || window.cqT('testcreator.administrators.confirm.default_teacher', 'il docente selezionato');
            var message = window.cqT('testcreator.administrators.confirm.promote', 'Confermi di assegnare i privilegi amministratore a %s?').replace('%s', user);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });

        $(document).on('submit', '.js-remove-admin-form', function (event) {
            var user = $(this).find('button[type="submit"]').data('user') || window.cqT('testcreator.administrators.confirm.default_teacher', 'il docente selezionato');
            var message = window.cqT('testcreator.administrators.confirm.remove', 'Confermi di rimuovere i privilegi amministratore a %s?').replace('%s', user);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        initDataTable();
        initConfirmations();
    });
})();
