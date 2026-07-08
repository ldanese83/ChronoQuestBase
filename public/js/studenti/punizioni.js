(function () {
    'use strict';

    if (typeof window.Dropzone === 'undefined') {
        return;
    }

    window.Dropzone.autoDiscover = false;

    document.querySelectorAll('.js-punishment-dropzone').forEach(function (form) {
        var punishmentIdInput = form.querySelector('input[name="id_punizione"]');
        var punishmentId = punishmentIdInput ? punishmentIdInput.value : '';
        var responseContainer = document.getElementById('responseContainer' + punishmentId);

        // eslint-disable-next-line no-new
        new window.Dropzone(form, {
            url: '/studenti/punizioni/consegna',
            paramName: 'file',
            maxFiles: 1,
            autoProcessQueue: true,
            acceptedFiles: null,
            addRemoveLinks: true,
            dictDefaultMessage: window.cqT('js.upload.drag_drop_single', 'Trascina il file qui o clicca per caricarlo'),
            dictMaxFilesExceeded: window.cqT('student.punishments.upload.max_files_exceeded', 'Puoi caricare solo un file.'),
            init: function () {
                var dropzone = this;

                dropzone.on('sending', function (_file, _xhr, formData) {
                    formData.append('id_punizione', punishmentId);
                });

                dropzone.on('success', function (_file, responseText) {
                    if (!responseContainer) {
                        return;
                    }

                    responseContainer.textContent = responseText;
                    responseContainer.className = 'alert alert-success punishment-response';
                });

                dropzone.on('error', function (_file, errorMessage) {
                    if (!responseContainer) {
                        return;
                    }

                    var message = typeof errorMessage === 'string' ? errorMessage : window.cqT('student.punishments.upload.error', 'Errore durante il caricamento del file.');
                    responseContainer.textContent = message;
                    responseContainer.className = 'alert alert-danger punishment-response';
                });
            }
        });
    });
})();
