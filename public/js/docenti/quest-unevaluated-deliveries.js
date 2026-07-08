(function () {
    function showModal(modalEl) {
        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modalEl).modal('show');
            return;
        }

        if (
            window.bootstrap
            && window.bootstrap.Modal
            && typeof window.bootstrap.Modal.getOrCreateInstance === 'function'
        ) {
            window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            return;
        }

        if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
            new window.bootstrap.Modal(modalEl).show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable && $('#questUnevaluatedDeliveriesTable').length) {
            $('#questUnevaluatedDeliveriesTable').DataTable({
                pageLength: parseInt($('#questUnevaluatedDeliveriesTable').data('page-length'), 10) || 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.quest.unevaluated.table.length_menu', 'Mostra _MENU_ consegne'),
                    info: window.cqT('teacher.quest.unevaluated.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ consegne'),
                    infoEmpty: window.cqT('teacher.quest.unevaluated.table.info_empty', 'Nessuna consegna da valutare'),
                    infoFiltered: window.cqT('teacher.quest.unevaluated.table.info_filtered', '(filtrate da _MAX_ consegne totali)'),
                    zeroRecords: window.cqT('teacher.quest.unevaluated.table.zero_records', 'Nessuna consegna trovata'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        const modal = document.getElementById('deliveryProblemModal');
        const form = document.getElementById('deliveryProblemForm');
        const checkbox = document.getElementById('deliveryProblemCheckbox');
        const description = document.getElementById('deliveryProblemDescription');
        if (!modal || !form || !checkbox || !description) {
            return;
        }

        document.addEventListener('click', function (event) {
            const target = event.target instanceof Element ? event.target : event.target.parentElement;
            const button = target ? target.closest('.delivery-problem-btn') : null;
            if (!button) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            form.action = button.dataset.action || '';
            checkbox.checked = button.dataset.problem === '1';
            description.value = button.dataset.description || '';
            showModal(modal);
        }, true);
    });
})();
