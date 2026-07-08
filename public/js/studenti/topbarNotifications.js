(function () {
    function postJson(url, options) {
        return fetch(url, Object.assign({
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }, options || {})).then(function (response) {
            return response.json();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const markAllButton = document.getElementById('studentMarkAllAlertsReadButton');
        if (markAllButton) {
            markAllButton.addEventListener('click', function () {
                if (!window.confirm(window.cqT('js.alerts.mark_all_read.confirm', 'Vuoi segnare come letti tutti gli alert?'))) {
                    return;
                }

                postJson('/studenti/alerts/read-all')
                    .then(function (payload) {
                        if (!payload.success) {
                            window.alert(payload.message || window.cqT('js.operation.failed', 'Operazione non riuscita.'));
                            return;
                        }

                        window.location.reload();
                    })
                    .catch(function () {
                        window.alert(window.cqT('js.alerts.update.error', 'Errore durante l’aggiornamento degli alert.'));
                    });
            });
        }

        document.querySelectorAll('[data-student-alert-open-url]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const url = link.getAttribute('data-student-alert-open-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
})();
