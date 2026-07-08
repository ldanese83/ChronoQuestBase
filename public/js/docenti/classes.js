(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        $(document).on('click', '.js-edit-class', function () {
            const button = $(this);
            const classId = Number(button.data('id') || 0);
            if (classId <= 0) {
                return;
            }

            $('#edit_class_name').val(String(button.data('name') || ''));
            $('#edit_class_icon').val(String(button.data('icon') || 'fa-flag'));
            $('#edit_class_color').val(String(button.data('color') || '#0d6efd'));
            $('#teacherClassEditForm').attr('action', '/docenti/classi/' + classId + '/modifica');
        });
    });
})();
