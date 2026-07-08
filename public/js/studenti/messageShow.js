(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('studentReplyMessageForm');
        const body = document.getElementById('replyBody');

        if (form && body) {
            form.addEventListener('submit', function (event) {
                if (typeof window.tinymce !== 'undefined') {
                    window.tinymce.triggerSave();
                }

                const plainText = body.value.replace(/<[^>]*>/g, '').trim();
                if (plainText === '') {
                    event.preventDefault();
                    window.alert(window.cqT('student.communications.reply.body_required', 'Inserisci il testo della risposta.'));
                    if (typeof window.tinymce !== 'undefined' && window.tinymce.get('replyBody')) {
                        window.tinymce.get('replyBody').focus();
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
            selector: '#replyBody',
            license_key: 'gpl',
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
            height: 260
        });
    });
})();
