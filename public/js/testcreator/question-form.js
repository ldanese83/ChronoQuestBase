(function () {
    function syncCorrectHidden(row) {
        var checkbox = row.querySelector('.answer-correct');
        var hidden = row.querySelector('input[type="hidden"][name="corretta[]"]');
        if (checkbox && hidden) {
            hidden.value = checkbox.checked ? 'si' : 'no';
        }
    }

    function enforceSingleCorrectIfNeeded(container, typeId) {
        if (typeId !== 2) {
            return;
        }

        var checked = container.querySelectorAll('.answer-correct:checked');
        if (checked.length <= 1) {
            return;
        }

        checked.forEach(function (item, index) {
            if (index > 0) {
                item.checked = false;
                syncCorrectHidden(item.closest('.answer-row'));
            }
        });

        alert(window.cqT('testcreator.questions.form.alert.single_correct', 'Nel tipo di domanda Scelta Multipla può esserci una sola risposta corretta!'));
    }

    function applyTypeUi(typeId) {
        var answersWrapper = document.getElementById('answersWrapper');
        var numericWrapper = document.getElementById('numericExerciseWrapper');
        var numRighe = document.getElementById('num_righe');

        var showOpen = typeId === 1;
        var showAnswers = typeId === 2 || typeId === 3;
        var showNumeric = typeId === 4;

        if (answersWrapper) {
            answersWrapper.style.display = showAnswers ? 'block' : 'none';
        }
        if (numericWrapper) {
            numericWrapper.style.display = showNumeric ? 'block' : 'none';
        }
        if (numRighe) {
            numRighe.closest('.form-group').style.display = showOpen ? 'block' : 'none';
        }

        if (showNumeric && window.tinymce && !window.tinymce.get('ese_num')) {
            window.tinymce.init({
                selector: '#ese_num',
                license_key: 'gpl',
                menubar: false,
                plugins: 'lists image link code searchreplace fullscreen',
                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | image link | code fullscreen',
                images_upload_url: window.testCreatorQuestionFormData?.uploadImageUrl || '',
                automatic_uploads: true,
                file_picker_types: 'image',
                convert_urls: false,
                branding: false,
                height: 320
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var typeInput = document.getElementById('tipo_domanda');
        var answersContainer = document.getElementById('answersContainer');
        var addBtn = document.getElementById('addAnswerBtn');
        if (!typeInput || !answersContainer) {
            return;
        }

        var typeId = parseInt(typeInput.value, 10) || parseInt(window.testCreatorQuestionFormData?.currentType, 10) || 1;
        applyTypeUi(typeId);

        if (typeInput.tagName === 'SELECT') {
            typeInput.addEventListener('change', function () {
                typeId = parseInt(typeInput.value, 10) || 1;
                applyTypeUi(typeId);
            });
        }

        answersContainer.addEventListener('change', function (event) {
            var row = event.target.closest('.answer-row');
            if (!row) {
                return;
            }
            syncCorrectHidden(row);
            enforceSingleCorrectIfNeeded(answersContainer, typeId);
        });

        answersContainer.addEventListener('click', function (event) {
            if (!event.target.classList.contains('js-remove-answer')) {
                return;
            }
            event.target.closest('.answer-row')?.remove();
        });

        addBtn?.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'input-group mb-2 answer-row';
            row.innerHTML = '<input type="text" class="form-control" name="risposta[]"><div class="input-group-append input-group-text"><input type="checkbox" class="answer-correct" title="' + window.cqT('testcreator.questions.form.correct_answer', 'Risposta corretta') + '"></div><input type="hidden" name="corretta[]" value="no"><button type="button" class="btn btn-outline-danger js-remove-answer" title="' + window.cqT('testcreator.questions.form.remove_answer', 'Rimuovi risposta') + '">-</button>';
            answersContainer.appendChild(row);
        });

        document.getElementById('questionForm')?.addEventListener('submit', function () {
            window.tinymce?.triggerSave();
            answersContainer.querySelectorAll('.answer-row').forEach(syncCorrectHidden);
        });
    });
})();
