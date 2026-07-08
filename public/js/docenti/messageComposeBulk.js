(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="/docenti/messages/new-bulk"]');
        const body = document.getElementById('composeBody');

        if (form && body) {
            form.addEventListener('submit', function (event) {
                if (typeof window.tinymce !== 'undefined') {
                    window.tinymce.triggerSave();
                }

                const plainText = body.value.replace(/<[^>]*>/g, '').trim();
                if (plainText === '') {
                    event.preventDefault();
                    window.alert(window.cqT('teacher.communications.compose.body_required', 'Inserisci il testo del messaggio.'));
                    if (typeof window.tinymce !== 'undefined' && window.tinymce.get('composeBody')) {
                        window.tinymce.get('composeBody').focus();
                    } else {
                        body.focus();
                    }
                }
            });
        }

        if (typeof window.tinymce === 'undefined' || !body) {
            return;
        }

        window.tinymce.init({
            selector: '#composeBody',
            license_key: 'gpl',
            menubar: false,
            plugins: 'link lists code table',
            toolbar: `
                undo redo |
                bold italic underline strikethrough |
                forecolor backcolor |
                alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent
            `,
            height: 320
        });
    });
})();
