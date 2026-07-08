(function () {
    const form = document.getElementById('form_capitolo');
    if (!form) {
        return;
    }

    const alertBox = document.getElementById('add-exercise-alert');
    const subjectSelect = document.getElementById('materia');
    const topicSelect = document.getElementById('argomento');
    const materialSelect = document.getElementById('materiale');
    const isReadOnly = Boolean(window.questAddExerciseData?.readOnly);

    const showAlert = (message, type = 'danger') => {
        if (!alertBox) {
            return;
        }

        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
    };

    const resetAlert = () => {
        if (!alertBox) {
            return;
        }

        alertBox.className = 'd-none';
        alertBox.textContent = '';
    };

    const buildOptions = (items, valueKey, textKey) => {
        return items.map((item) => `<option value="${item[valueKey]}">${item[textKey]}</option>`).join('');
    };

    const fetchJson = async (url) => {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error(window.cqT('teacher.quest.add_exercise.network_error', 'Errore di rete.'));
        }

        return response.json();
    };

    const loadTopics = async () => {
        const subjectId = subjectSelect?.value || '';
        if (!subjectId || !topicSelect) {
            return;
        }

        const template = subjectSelect.dataset.topicsUrlTemplate || '';
        const url = template.replace('{id}', encodeURIComponent(subjectId));

        const payload = await fetchJson(url);
        if (!payload.success) {
            throw new Error(payload.message || window.cqT('teacher.quest.add_exercise.topics_load_error', 'Errore nel caricamento degli argomenti.'));
        }

        topicSelect.innerHTML = buildOptions(payload.topics || [], 'id_argomento', 'nome_argomento');
        await loadMaterials();
    };

    const loadMaterials = async () => {
        const topicId = topicSelect?.value || '';
        if (!topicId || !materialSelect) {
            if (materialSelect) {
                materialSelect.innerHTML = '';
            }
            return;
        }

        const template = topicSelect.dataset.materialsUrlTemplate || '';
        const url = template.replace('{id}', encodeURIComponent(topicId));

        const payload = await fetchJson(url);
        if (!payload.success) {
            throw new Error(payload.message || window.cqT('teacher.quest.add_exercise.materials_load_error', 'Errore nel caricamento dei materiali.'));
        }

        materialSelect.innerHTML = buildOptions(payload.materials || [], 'id_materiale', 'nome_materiale');
    };

    const insertMathDelimiter = (editorId, type) => {
        const editor = window.tinymce?.get(editorId);
        if (!editor) {
            return;
        }

        const placeholder = window.cqT('teacher.quest.add_exercise.math.placeholder', 'Formula');
        const delimiter = type === 'block' ? `\n$$ ${placeholder} $$\n` : `$ ${placeholder} $`;
        editor.insertContent(delimiter);
    };

    const initTinyMce = () => {
        if (!window.tinymce) {
            return;
        }

        window.tinymce.init({
            selector: '.quest-richtext',
            license_key: 'gpl',
            menubar: false,
            plugins: 'lists image link code searchreplace fullscreen',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | image link | removeformat code fullscreen',
            images_upload_url: window.questAddExerciseData?.uploadUrl || '',
            automatic_uploads: true,
            file_picker_types: 'image',
            convert_urls: false,
            branding: false,
            height: 360,
            readonly: isReadOnly,
        });
    };

    subjectSelect?.addEventListener('change', async () => {
        resetAlert();
        try {
            await loadTopics();
        } catch (error) {
            showAlert(error.message || window.cqT('teacher.quest.add_exercise.topics_materials_load_error', 'Errore nel caricamento argomenti/materiali.'));
        }
    });

    topicSelect?.addEventListener('change', async () => {
        resetAlert();
        try {
            await loadMaterials();
        } catch (error) {
            showAlert(error.message || window.cqT('teacher.quest.add_exercise.materials_load_error.short', 'Errore nel caricamento materiali.'));
        }
    });

    document.querySelectorAll('[data-insert-math]').forEach((button) => {
        button.addEventListener('click', () => {
            const type = button.getAttribute('data-insert-math') || 'inline';
            const target = button.getAttribute('data-editor-target') || '';
            insertMathDelimiter(target, type);
        });
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (isReadOnly) {
            return;
        }
        resetAlert();

        window.tinymce?.triggerSave();

        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.setAttribute('disabled', 'disabled');
        }

        try {
            const response = await fetch(window.questAddExerciseData?.saveUrl || form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || window.cqT('teacher.quest.add_exercise.save_error', 'Errore durante il salvataggio.'));
            }

            if (payload.redirectUrl) {
                window.location.href = payload.redirectUrl;
                return;
            }

            showAlert(payload.message || window.cqT('teacher.quest.exercise.saved', 'Esercizio salvato correttamente.'), 'success');
        } catch (error) {
            showAlert(error.message || window.cqT('teacher.quest.add_exercise.save_error', 'Errore durante il salvataggio.'));
        } finally {
            if (submitButton) {
                submitButton.removeAttribute('disabled');
            }
        }
    });

    initTinyMce();
})();
