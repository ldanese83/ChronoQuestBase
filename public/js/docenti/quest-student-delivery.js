(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const data = window.studentDeliveryData || {};
        if (window.tinymce && !data.isEvaluated) {
            window.tinymce.init({
                selector: '.quest-comment',
                license_key: 'gpl',
                menubar: false,
                plugins: 'lists link code',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
                height: 280,
                branding: false,
            });
        }

        const geminiButton = document.getElementById('gemini-valutazione-btn');
        if (!geminiButton || data.isEvaluated) {
            return;
        }

        const messageBox = document.getElementById('gemini-message');
        const gradeInput = document.getElementById('valutazione');

        geminiButton.addEventListener('click', async function () {
            geminiButton.disabled = true;
            if (messageBox) {
                messageBox.className = 'alert alert-warning mt-2';
                messageBox.textContent = window.cqT('teacher.quest.student_delivery.gemini_waiting', 'Attendo risposta da Gemini...');
            }

            try {
                const response = await fetch(data.suggestUrl || '', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || window.cqT('teacher.quest.student_delivery.gemini_request_error', 'Errore nella richiesta a Gemini.'));
                }

                if (gradeInput) {
                    gradeInput.value = String(payload.valutazione || '');
                }

                if (window.tinymce && window.tinymce.get('commento') && payload.commento) {
                    window.tinymce.get('commento').setContent(payload.commento);
                } else if (payload.commento) {
                    const genericComment = document.getElementById('commento');
                    if (genericComment) {
                        genericComment.value = payload.commento;
                    }
                }

                if (messageBox) {
                    messageBox.className = 'alert alert-success mt-2';
                    messageBox.textContent = window.cqT('teacher.quest.student_delivery.gemini_success', 'Suggerimento Gemini inserito correttamente.');
                }
            } catch (error) {
                if (messageBox) {
                    messageBox.className = 'alert alert-danger mt-2';
                    messageBox.textContent = error.message || window.cqT('teacher.quest.student_delivery.gemini_suggestion_error', 'Errore durante il suggerimento Gemini.');
                }
            } finally {
                geminiButton.disabled = false;
            }
        });

        const form = document.getElementById('studentDeliveryForm');
        form?.addEventListener('submit', function () {
            window.tinymce?.triggerSave();
        });
    });
})();
