(function () {
    function initTinyMce() {
        if (!window.tinymce || window.tinymce.get('gridEditor')) {
            return;
        }

        window.tinymce.init({
            selector: '#gridEditor',
            license_key: 'gpl',
            menubar: true,
            plugins: 'lists link table code fullscreen help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | table link | code fullscreen',
            height: 520,
            branding: false
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTinyMce();

        var form = document.getElementById('gridForm');
        if (!form) {
            return;
        }

        form.addEventListener('submit', function () {
            if (window.tinymce) {
                window.tinymce.triggerSave();
            }
        });
    });
})();
