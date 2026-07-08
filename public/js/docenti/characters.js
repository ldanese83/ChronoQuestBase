(function () {
    function translateMessage(message, fallback) {
        if (message) {
            return window.cqT(message, message);
        }

        return fallback;
    }

    function showAlert(type, message) {
        const alertBox = $('#character-management-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type)
            .text(message || window.cqT('js.operation.completed', 'Operazione completata.'));

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function setCreateMode() {
        $('#characterFormModalLabel').text(window.cqT('teacher.characters.modal.add_title', 'Inserisci nuovo personaggio'));
        $('#characterId').val('0');
        $('#characterName').val('');
        $('#characterLife').val('1');
        $('#characterMana').val('1');
        $('#characterDescription').val('');
        $('#characterColor').val('#808080');
        $('#characterBorderColor').val('#efefef');
        $('#characterImage').val('');
        $('#characterNoBgImage').val('');
    }

    function setEditMode(button) {
        $('#characterFormModalLabel').text(window.cqT('teacher.characters.modal.edit_title', 'Modifica personaggio'));
        $('#characterId').val(button.data('character-id'));
        $('#characterName').val(button.data('character-name') || '');
        $('#characterLife').val(String(button.data('character-life') || 1));
        $('#characterMana').val(String(button.data('character-mana') || 1));
        $('#characterDescription').val(button.data('character-description') || '');
        $('#characterColor').val(button.data('character-color') || '#808080');
        $('#characterBorderColor').val(button.data('character-bordercolor') || '#efefef');
        $('#characterImage').val('');
        $('#characterNoBgImage').val('');
    }

    function handleJsonResponse(response) {
        return response.json().catch(function () {
            return { success: false, message: window.cqT('js.response.invalid', 'Risposta non valida dal server.') };
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#characterTable').length) {
            $('#characterTable').DataTable({
                pageLength: parseInt($('#characterTable').data('page-length'), 10) || 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.characters.table.length_menu', 'Mostra _MENU_ personaggi'),
                    info: window.cqT('teacher.characters.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ personaggi'),
                    infoEmpty: window.cqT('teacher.characters.table.info_empty', 'Nessun personaggio disponibile'),
                    infoFiltered: window.cqT('teacher.characters.table.info_filtered', '(filtrati da _MAX_ personaggi totali)'),
                    zeroRecords: window.cqT('teacher.characters.table.zero_records', 'Nessun personaggio trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        setCreateMode();

        $('#openCreateCharacterModal').on('click', function () {
            setCreateMode();
            $('#characterFormModal').modal('show');
        });

        $(document).on('click', '.js-edit-character', function () {
            setEditMode($(this));
            $('#characterFormModal').modal('show');
        });

        $('#characterForm').on('submit', function (event) {
            event.preventDefault();

            const characterId = parseInt($('#characterId').val(), 10) || 0;
            const name = String($('#characterName').val() || '').trim();
            const avatarInput = $('#characterImage').get(0);

            if (name === '') {
                showAlert('warning', window.cqT('teacher.characters.name.required', 'Inserisci il nome del personaggio.'));
                return;
            }

            if (characterId === 0 && (!avatarInput || !avatarInput.files || avatarInput.files.length === 0)) {
                showAlert('warning', window.cqT('teacher.characters.avatar.required', 'Per creare un nuovo personaggio devi caricare l\'avatar.'));
                return;
            }

            const formData = new FormData();
            formData.append('id_personaggio', String(characterId));
            formData.append('nome_personaggio', name);
            formData.append('vita_iniziale', String($('#characterLife').val() || '1'));
            formData.append('mana_iniziale', String($('#characterMana').val() || '1'));
            formData.append('descrizione', String($('#characterDescription').val() || ''));
            formData.append('color', String($('#characterColor').val() || '#808080'));
            formData.append('bordercolor', String($('#characterBorderColor').val() || '#efefef'));

            if (avatarInput && avatarInput.files && avatarInput.files.length > 0) {
                formData.append('immagine', avatarInput.files[0]);
            }

            const noBgInput = $('#characterNoBgImage').get(0);
            if (noBgInput && noBgInput.files && noBgInput.files.length > 0) {
                formData.append('img_senza_sfondo', noBgInput.files[0]);
            }

            $('#saveCharacterButton').prop('disabled', true);

            fetch('/docenti/personaggi/save', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(handleJsonResponse)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', translateMessage(payload.message, window.cqT('js.save.failed', 'Salvataggio non riuscito.')));
                        return;
                    }

                    $('#characterFormModal').modal('hide');
                    showAlert('success', translateMessage(payload.message, window.cqT('teacher.characters.saved.success', 'Personaggio salvato correttamente.')));
                    setTimeout(function () {
                        window.location.reload();
                    }, 900);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.characters.save.unexpected_error', 'Errore imprevisto durante il salvataggio del personaggio.'));
                })
                .finally(function () {
                    $('#saveCharacterButton').prop('disabled', false);
                });
        });
    });
})();
