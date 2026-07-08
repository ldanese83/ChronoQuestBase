(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        $('#studentFilter').on('change', function () {
            $(this).closest('form').trigger('submit');
        });
    });
})();
