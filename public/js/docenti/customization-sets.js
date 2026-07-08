(function () {
    function resetSetModal() {
        $('#setModalLabel').text(window.cqT('teacher.customizations.sets.button.add_set', 'Aggiungi set'));
        $('#setId').val('0');
        $('#setName').val('');
        $('#setType').val('Equipaggiamento');
        $('#setColor').val('#2f80ed');
    }

    function openAssignModal(setId, setName, setType) {
        $('#assignSetId').val(String(setId));
        $('#assignSetName').text(setName);
        $('#assignSetType').text(window.cqT('teacher.customizations.type.' + setType, setType));

        $('.assign-type-list').hide();
        const currentList = $('.assign-type-list[data-type="' + String(setType).replace(/"/g, '\\"') + '"]');
        currentList.show();

        $('.js-set-personalization-checkbox').prop('checked', false);
        currentList.find('.js-set-personalization-checkbox').each(function () {
            const currentSet = Number($(this).data('current-set') || 0);
            if (currentSet === Number(setId)) {
                $(this).prop('checked', true);
            }
        });

        $('#assignSetModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#setsTable').length) {
            $('#setsTable').DataTable({
                pageLength: 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.customizations.sets.table.length_menu', 'Mostra _MENU_ set'),
                    info: window.cqT('teacher.customizations.sets.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ set'),
                    infoEmpty: window.cqT('teacher.customizations.sets.table.info_empty', 'Nessun set disponibile'),
                    infoFiltered: window.cqT('teacher.customizations.sets.table.info_filtered', '(filtrati da _MAX_ set totali)'),
                    zeroRecords: window.cqT('teacher.customizations.sets.table.zero_records', 'Nessun set trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        resetSetModal();

        $('#openCreateSetModal').on('click', function () {
            resetSetModal();
        });

        $(document).on('click', '.js-edit-set', function () {
            const button = $(this);
            $('#setModalLabel').text(window.cqT('teacher.customizations.sets.modal.edit_title', 'Modifica set'));
            $('#setId').val(String(button.data('id') || '0'));
            $('#setName').val(String(button.data('name') || ''));
            $('#setType').val(String(button.data('type') || 'Equipaggiamento'));
            $('#setColor').val(String(button.data('color') || '#2f80ed'));
            $('#setModal').modal('show');
        });

        $(document).on('click', '.js-assign-set', function () {
            const button = $(this);
            openAssignModal(
                Number(button.data('id') || 0),
                String(button.data('name') || ''),
                String(button.data('type') || '')
            );
        });

        $('#exportSetForm').on('submit', function (event) {
            event.preventDefault();
            const setId = String($('#exportSetSelect').val() || '');
            if (setId === '') {
                return;
            }

            window.location.href = '/docenti/personalizzazioni/set/esporta/' + encodeURIComponent(setId);
        });
    });
})();
