(function () {
    function initDataTable() {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#testCreatorTeacherEmailsTable').length) {
            $('#testCreatorTeacherEmailsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[0, 'asc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.teacher_emails.table.length_menu', 'Mostra _MENU_ email'),
                    info: window.cqT('testcreator.teacher_emails.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ email'),
                    infoEmpty: window.cqT('testcreator.teacher_emails.table.info_empty', 'Nessuna email disponibile'),
                    infoFiltered: window.cqT('testcreator.teacher_emails.table.info_filtered', '(filtrate da _MAX_ email totali)'),
                    zeroRecords: window.cqT('testcreator.teacher_emails.table.zero_records', 'Nessuna email trovata'),
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
        $(document).on('submit', '.js-delete-email-form', function (event) {
            var email = $(this).find('button[type="submit"]').data('email') || window.cqT('testcreator.teacher_emails.confirm.default_email', 'questa mail');
            var message = window.cqT('testcreator.teacher_emails.confirm.delete', 'Sei sicuro di voler eliminare la mail %s?').replace('%s', email);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });

        $(document).on('submit', '#importTeacherEmailsForm', function (event) {
            if (!window.confirm(window.cqT('testcreator.teacher_emails.confirm.import', "L'import cancellerà tutte le mail attuali. Continuare?"))) {
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
