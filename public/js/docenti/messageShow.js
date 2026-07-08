(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('teacherReplyMessageForm');
        const body = document.getElementById('replyBody');
        const modal = document.getElementById('modalRispostaMessaggio');

        function focusEditor() {
            if (typeof window.tinymce !== 'undefined' && window.tinymce.get('replyBody')) {
                window.tinymce.get('replyBody').focus();
            } else if (body) {
                body.focus();
            }
        }

        function initReplyEditor() {
            if (typeof window.tinymce === 'undefined' || !body || window.tinymce.get('replyBody')) {
                return;
            }

            window.tinymce.init({
                selector: '#replyBody',
                license_key: 'gpl',
                menubar: false,
                height: 320,
                plugins: 'link lists code',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
                branding: false,
                promotion: false,
                setup: function (editor) {
                    editor.on('init', function () {
                        editor.mode.set('design');
                    });
                }
            });
        }

        if (form && body) {
            form.addEventListener('submit', function (event) {
                if (typeof window.tinymce !== 'undefined') {
                    window.tinymce.triggerSave();
                }

                const plainText = body.value.replace(/<[^>]*>/g, '').trim();
                if (plainText === '') {
                    event.preventDefault();
                    window.alert(window.cqT('teacher.communications.reply.body_required', 'Inserisci il testo della risposta.'));
                    focusEditor();
                }
            });
        }

        if (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                initReplyEditor();
                focusEditor();
            });
        }

        initReplyEditor();
    });
})();
