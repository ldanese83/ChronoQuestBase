(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable && $('#studentUploadsTable').length) {
            $('#studentUploadsTable').DataTable({
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.customizations.student_uploads.table.length_menu', 'Mostra _MENU_ record'),
                    info: window.cqT('teacher.customizations.student_uploads.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ record'),
                    infoEmpty: window.cqT('teacher.customizations.student_uploads.table.info_empty', 'Nessuna personalizzazione in attesa'),
                    infoFiltered: window.cqT('teacher.customizations.student_uploads.table.info_filtered', '(filtrate da _MAX_ record totali)'),
                    zeroRecords: window.cqT('teacher.customizations.student_uploads.table.zero_records', 'Nessun record trovato')
                }
            });
        }
    });
})();
