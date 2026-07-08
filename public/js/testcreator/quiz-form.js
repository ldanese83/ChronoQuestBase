(function () {
    function parseTopics() {
        var container = document.getElementById('quizTopicsContainer');
        if (!container) {
            return {};
        }

        try {
            return JSON.parse(container.getAttribute('data-topics') || '{}') || {};
        } catch (error) {
            return {};
        }
    }

    function parseSelectedTopics() {
        var container = document.getElementById('quizTopicsContainer');
        if (!container) {
            return [];
        }

        try {
            var parsed = JSON.parse(container.getAttribute('data-selected-topics') || '[]') || [];
            return Array.isArray(parsed) ? parsed.map(function (id) { return String(parseInt(id, 10) || 0); }) : [];
        } catch (error) {
            return [];
        }
    }

    function createTopicSelector(topicsBySubject) {
        var container = document.getElementById('quizTopicsContainer');
        var selectedContainer = document.getElementById('quizTopicsSelected');
        var hiddenInputsContainer = document.getElementById('quizTopicsHiddenInputs');
        var searchInput = document.getElementById('quizTopicSearch');
        var clearButton = document.getElementById('quizTopicsClearSelection');
        var subjectSelect = document.getElementById('quizSubject');

        if (!container || !selectedContainer || !hiddenInputsContainer) {
            return;
        }

        var selectedTopicIds = new Set(parseSelectedTopics().filter(function (id) {
            return id !== '0';
        }));

        function getTopicsForSubject(subjectId) {
            return topicsBySubject[String(subjectId)] || [];
        }

        function syncSelectionWithSubject() {
            var allowed = new Set(getTopicsForSubject(subjectSelect ? subjectSelect.value : 0).map(function (topic) {
                return String(parseInt(topic.id_argomento, 10) || 0);
            }));
            selectedTopicIds.forEach(function (id) {
                if (!allowed.has(id)) {
                    selectedTopicIds.delete(id);
                }
            });
        }

        function renderSelectedBadges() {
            selectedContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            var currentTopics = getTopicsForSubject(subjectSelect ? subjectSelect.value : 0);
            var topicsMap = {};
            currentTopics.forEach(function (topic) {
                var id = String(parseInt(topic.id_argomento, 10) || 0);
                topicsMap[id] = topic.nome_argomento || '';
            });

            var selectedIdsArray = Array.from(selectedTopicIds.values());
            if (selectedIdsArray.length === 0) {
                selectedContainer.innerHTML = '<small class="text-muted">' + window.cqT('testcreator.quizzes.form.no_topics_selected', 'Nessun argomento selezionato.') + '</small>';
                return;
            }

            selectedIdsArray.sort(function (a, b) { return Number(a) - Number(b); });
            selectedIdsArray.forEach(function (id) {
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'argomenti[]';
                hiddenInput.value = id;
                hiddenInputsContainer.appendChild(hiddenInput);

                var badge = document.createElement('span');
                badge.className = 'badge badge-primary d-inline-flex align-items-center';
                badge.style.gap = '6px';
                badge.textContent = topicsMap[id] || (window.cqT('testcreator.quizzes.form.topic_id_prefix', 'ID') + ' ' + id);

                var removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-sm btn-light py-0 px-1';
                removeButton.textContent = '×';
                removeButton.addEventListener('click', function () {
                    selectedTopicIds.delete(id);
                    renderTopicsList();
                    renderSelectedBadges();
                });
                badge.appendChild(removeButton);
                selectedContainer.appendChild(badge);
            });
        }

        function renderTopicsList() {
            var subjectId = subjectSelect ? subjectSelect.value : 0;
            var topics = getTopicsForSubject(subjectId);
            var searchTerm = (searchInput ? searchInput.value : '').trim().toLowerCase();

            container.innerHTML = '';
            if (topics.length === 0) {
                container.innerHTML = '<small class="text-muted">' + window.cqT('testcreator.quizzes.form.no_topics_for_subject', 'Nessun argomento disponibile per la materia selezionata.') + '</small>';
                return;
            }

            topics.forEach(function (topic) {
                var topicId = String(parseInt(topic.id_argomento, 10) || 0);
                var topicName = String(topic.nome_argomento || '');
                if (searchTerm !== '' && topicName.toLowerCase().indexOf(searchTerm) === -1) {
                    return;
                }

                var wrapper = document.createElement('div');
                wrapper.className = 'form-check';

                var checkbox = document.createElement('input');
                checkbox.className = 'form-check-input';
                checkbox.type = 'checkbox';
                checkbox.id = 'topic-' + topicId;
                checkbox.value = topicId;
                checkbox.checked = selectedTopicIds.has(topicId);
                checkbox.addEventListener('change', function () {
                    if (checkbox.checked) {
                        selectedTopicIds.add(topicId);
                    } else {
                        selectedTopicIds.delete(topicId);
                    }
                    renderSelectedBadges();
                });

                var label = document.createElement('label');
                label.className = 'form-check-label';
                label.setAttribute('for', checkbox.id);
                label.textContent = topicName;

                wrapper.appendChild(checkbox);
                wrapper.appendChild(label);
                container.appendChild(wrapper);
            });

            if (container.innerHTML === '') {
                container.innerHTML = '<small class="text-muted">' + window.cqT('testcreator.quizzes.form.no_search_results', 'Nessun risultato per la ricerca.') + '</small>';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', renderTopicsList);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                selectedTopicIds.clear();
                renderTopicsList();
                renderSelectedBadges();
            });
        }

        if (subjectSelect && !subjectSelect.disabled) {
            subjectSelect.addEventListener('change', function () {
                syncSelectionWithSubject();
                renderTopicsList();
                renderSelectedBadges();
            });
        }

        syncSelectionWithSubject();
        renderTopicsList();
        renderSelectedBadges();
    }

    function initTopics() {
        var container = document.getElementById('quizTopicsContainer');
        var subjectSelect = document.getElementById('quizSubject');
        if (!container) {
            return;
        }
        if (!subjectSelect) {
            return;
        }
        var topicsBySubject = parseTopics();
        createTopicSelector(topicsBySubject);
    }

    function createRuleRow(questionTypesOptions) {
        var row = document.createElement('div');
        row.className = 'row mb-2 js-rule-row';
        row.innerHTML =
            '<div class="col-md-7">' +
                '<select name="tipo_domande[]" class="form-control">' + questionTypesOptions + '</select>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<input type="number" min="1" name="num_domande[]" class="form-control" value="1">' +
            '</div>' +
            '<div class="col-md-2 text-end">' +
                '<button type="button" class="btn btn-danger btn-sm js-remove-rule">-</button>' +
            '</div>';
        return row;
    }

    function initTypeRules() {
        var isRandom = document.getElementById('isRandom');
        var typeRules = document.getElementById('quizTypeRules');
        var rowsContainer = document.getElementById('quizTypeRulesRows');
        if (!typeRules || !rowsContainer) {
            return;
        }

        function toggleVisibility() {
            var visible = !isRandom || isRandom.value === 'si';
            typeRules.style.display = visible ? '' : 'none';
        }

        var baseSelect = rowsContainer.querySelector('select[name="tipo_domande[]"]');
        var optionsHtml = baseSelect ? baseSelect.innerHTML : '<option value="0">' + window.cqT('testcreator.quizzes.form.any_type', 'Qualsiasi') + '</option>';

        rowsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('js-add-rule')) {
                rowsContainer.appendChild(createRuleRow(optionsHtml));
            }
            if (event.target.classList.contains('js-remove-rule')) {
                var row = event.target.closest('.js-rule-row');
                if (row) {
                    row.remove();
                }
            }
        });

        if (isRandom && !isRandom.disabled) {
            isRandom.addEventListener('change', toggleVisibility);
        }
        toggleVisibility();
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTopics();
        initTypeRules();
    });
})();
