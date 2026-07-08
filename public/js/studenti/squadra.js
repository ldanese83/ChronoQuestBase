(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        const $ = window.jQuery;

        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }

        function toggleEmblemInputs() {
            const mode = $('input[name="emblema_tipo"]:checked').val();
            const useUpload = mode === 'upload';
            const fileInput = $('input[name="emblema_squadra"]');
            const defaultInputs = $('input[name="emblema_default"]');

            fileInput.prop('disabled', !useUpload);
            defaultInputs.prop('disabled', useUpload);
        }

        $(document).on('change', 'input[name="emblema_tipo"]', toggleEmblemInputs);
        toggleEmblemInputs();

        $(document).on('change', 'input[name="studenti_invito[]"]', function () {
            const checked = $('input[name="studenti_invito[]"]:checked');
            if (checked.length > 3) {
                this.checked = false;
                alert(window.cqT('student.teams.max_invites_alert', 'Puoi selezionare al massimo 3 compagni da invitare.'));
            }
        });
    });
})();
