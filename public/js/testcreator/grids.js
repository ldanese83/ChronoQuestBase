(function () {
    if (window.jQuery && typeof window.jQuery.fn.DataTable === 'function') {
        window.jQuery(function () {
            var table = window.jQuery('#testCreatorGridsTable');
            if (table.length > 0) {
                table.DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, window.cqT('js.filter.all', 'Tutti')]],
                    order: [[0, 'asc']],
                    language: {
                        search: window.cqT('testcreator.index.table.search', 'Cerca:'),
                        lengthMenu: window.cqT('testcreator.grids.table.length_menu', 'Mostra _MENU_ griglie'),
                        info: window.cqT('testcreator.grids.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ griglie'),
                        infoEmpty: window.cqT('testcreator.grids.table.info_empty', 'Nessuna griglia disponibile'),
                        infoFiltered: window.cqT('testcreator.grids.table.info_filtered', '(filtrate da _MAX_ griglie totali)'),
                        zeroRecords: window.cqT('testcreator.grids.table.zero_records', 'Nessuna griglia trovata'),
                        paginate: {
                            first: window.cqT('testcreator.index.table.first', 'Prima'),
                            last: window.cqT('testcreator.index.table.last', 'Ultima'),
                            next: window.cqT('testcreator.index.table.next', 'Successiva'),
                            previous: window.cqT('testcreator.index.table.previous', 'Precedente')
                        }
                    }
                });
            }

            window.jQuery(document).on('submit', '.js-delete-grid-form', function (event) {
                var gridName = window.jQuery(this).find('button[type="submit"]').data('grid-name') || window.cqT('testcreator.grids.confirm.default_grid', 'questa griglia');
                var message = window.cqT('testcreator.grids.confirm.delete', 'Sei sicuro di voler eliminare la griglia "%s"?').replace('%s', gridName);
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    }
})();
