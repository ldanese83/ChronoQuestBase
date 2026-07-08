(function () {
    function initDataTable() {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#testCreatorBooksTable').length) {
            $('#testCreatorBooksTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                order: [[3, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('testcreator.books.table.length_menu', 'Mostra _MENU_ libri'),
                    info: window.cqT('testcreator.books.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ libri'),
                    infoEmpty: window.cqT('testcreator.books.table.info_empty', 'Nessun libro disponibile'),
                    infoFiltered: window.cqT('testcreator.books.table.info_filtered', '(filtrati da _MAX_ libri totali)'),
                    zeroRecords: window.cqT('testcreator.books.table.zero_records', 'Nessun libro trovato'),
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
        $(document).on('submit', '.js-deactivate-book-form', function (event) {
            var button = $(this).find('button[type="submit"]');
            var bookTitle = button.data('book-title') || window.cqT('testcreator.books.confirm.default_book', 'questo libro');
            var message = window.cqT('testcreator.books.confirm.deactivate', 'Sei sicuro di voler disattivare il libro "%s"?').replace('%s', bookTitle);
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    }

    function initBookModal() {
        $(document).on('click', '.js-open-book-modal', function () {
            var bookId = parseInt($(this).data('book-id'), 10) || 0;
            var $bookId = $('#book-id');
            var $bookTitle = $('#book-title');
            var $bookPublisher = $('#book-publisher');
            var $bookAuthors = $('#book-authors');
            var $modalTitle = $('#bookModalLabel');

            $bookId.val('0');
            $bookTitle.val('');
            $bookPublisher.val('');
            $bookAuthors.val('');
            $modalTitle.text(window.cqT('testcreator.books.modal.new_title', 'Nuovo libro'));

            var url = '/testcreator/libri/' + bookId + '/form-data';
            $.getJSON(url)
                .done(function (response) {
                    if (!response || response.success !== true || !response.book) {
                        window.alert(window.cqT('testcreator.books.alert.fetch_failed', 'Impossibile recuperare i dati del libro.'));
                        return;
                    }

                    $bookId.val(response.book.id_libro_testo || 0);
                    $bookTitle.val(response.book.titolo_libro || '');
                    $bookPublisher.val(response.book.casa_editrice || '');
                    $bookAuthors.val(response.book.autori || '');
                    $modalTitle.text((response.book.id_libro_testo || 0) > 0
                        ? window.cqT('testcreator.books.modal.edit_title', 'Modifica libro')
                        : window.cqT('testcreator.books.modal.new_title', 'Nuovo libro'));
                })
                .fail(function () {
                    window.alert(window.cqT('testcreator.books.alert.load_error', 'Errore durante il caricamento del libro.'));
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        initDataTable();
        initConfirmations();
        initBookModal();
    });
})();
