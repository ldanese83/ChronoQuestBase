(function () {
    function getAbilityOptions(type) {
        if (type === 'Pet') {
            return $('#abilityOptionsTemplatePet').html() || '';
        }
        return $('#abilityOptionsTemplateEquip').html() || '';
    }

    function updateAbilitiesVisibility(resetRows) {
        const type = String($('#personalizationType').val() || '');
        const isSupported = (type === 'Equipaggiamento' || type === 'Pet');
        const container = $('#abilitiesContainer');
        const title = $('#abilitiesTitle');

        if (!isSupported) {
            container.hide();
            $('#abilityRows').empty();
            $('#personalizationAbilitiesInput').val('[]');
            return;
        }

        container.show();
        title.text(type === 'Pet'
            ? window.cqT('teacher.customizations.abilities.pet', 'Abilità pet')
            : window.cqT('teacher.customizations.abilities.equipment', 'Abilità equipaggiamento'));
        if (resetRows) {
            $('#abilityRows').empty();
        }
        if ($('#abilityRows .ability-row').length === 0) {
            addAbilityRow('', 0);
        }
        syncAbilitiesInput();
    }

    function addAbilityRow(selectedId, aumento) {
        const optionsHtml = getAbilityOptions(String($('#personalizationType').val() || ''));
        if (optionsHtml === '') {
            return;
        }

        const row = $('<div class="row ability-row mt-2"></div>');
        const abilityCol = $('<div class="col-md-6"></div>');
        const amountCol = $('<div class="col-md-4"></div>');
        const removeCol = $('<div class="col-md-2 d-flex align-items-end"></div>');

        abilityCol.append('<label>' + window.cqT('teacher.customizations.abilities.label', 'Abilità') + '</label>');
        const select = $('<select class="form-control ability-select">' + optionsHtml + '</select>');
        if (selectedId !== '') {
            select.val(String(selectedId));
        }
        abilityCol.append(select);

        amountCol.append('<label>' + window.cqT('teacher.customizations.abilities.increase', 'Aumento') + '</label>');
        amountCol.append('<input type="number" class="form-control ability-aumento" value="' + String(aumento || 0) + '">');

        removeCol.append('<button type="button" class="btn btn-danger remove-ability">X</button>');
        row.append(abilityCol).append(amountCol).append(removeCol);
        $('#abilityRows').append(row);
    }

    function syncAbilitiesInput() {
        const payload = [];
        $('#abilityRows .ability-row').each(function () {
            const abilityId = Number($(this).find('.ability-select').val() || 0);
            if (abilityId <= 0) {
                return;
            }

            payload.push({
                id: abilityId,
                aumento: Number($(this).find('.ability-aumento').val() || 0)
            });
        });
        $('#personalizationAbilitiesInput').val(JSON.stringify(payload));
    }

    function setCreateMode() {
        $('#personalizationModalLabel').text(window.cqT('teacher.customizations.modal.add_title', 'Aggiungi personalizzazione'));
        $('#personalizationId').val('0');
        $('#personalizationName').val('');
        $('#personalizationType').val('Sfondo');
        $('#personalizationCost').val('0');
        $('#personalizationCharacter').val('0');
        $('#personalizationDescription').val('');
        $('#personalizationSuffix').val('');
        $('#personalizationImage').val('');
        updateAbilitiesVisibility(true);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        let typeFilter = '';

        if ($.fn.DataTable && $('#customizationTable').length) {
            $.fn.dataTable.ext.search.push(function (settings, rowData, dataIndex) {
                if (settings.nTable.id !== 'customizationTable') {
                    return true;
                }

                if (typeFilter === '') {
                    return false;
                }

                const rowNode = settings.aoData?.[dataIndex]?.nTr;
                return String($(rowNode).data('tipo') || '') === typeFilter;
            });

            const table = $('#customizationTable').DataTable({
                pageLength: 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[1, 'asc'], [0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.customizations.table.length_menu', 'Mostra _MENU_ personalizzazioni'),
                    info: window.cqT('teacher.customizations.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ personalizzazioni'),
                    infoEmpty: window.cqT('teacher.customizations.table.info_empty', 'Nessuna personalizzazione disponibile'),
                    infoFiltered: window.cqT('teacher.customizations.table.info_filtered', '(filtrate da _MAX_ personalizzazioni totali)'),
                    zeroRecords: window.cqT('teacher.customizations.table.zero_records', 'Nessuna personalizzazione trovata'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });

            $('#customizationTypeFilter').on('change', function () {
                typeFilter = String($(this).val() || '');
                table.draw();
            });

            table.draw();
        }

        setCreateMode();

        $('#openCreatePersonalizationModal').on('click', function () {
            setCreateMode();
        });

        $(document).on('click', '.js-edit-customization', function () {
            const button = $(this);
            $('#personalizationModalLabel').text(window.cqT('teacher.customizations.modal.edit_title', 'Modifica personalizzazione'));
            $('#personalizationId').val(String(button.data('id') || '0'));
            $('#personalizationName').val(String(button.data('name') || ''));
            $('#personalizationType').val(String(button.data('type') || 'Sfondo'));
            $('#personalizationCost').val(String(button.data('cost') || '0'));
            $('#personalizationCharacter').val(String(button.data('character-id') || '0'));
            $('#personalizationDescription').val(String(button.data('description') || ''));
            $('#personalizationSuffix').val(String(button.data('suffix') || ''));
            $('#personalizationImage').val('');
            updateAbilitiesVisibility(true);

            const rawAbilities = String(button.attr('data-abilities') || '[]');
            let abilities = [];
            try {
                abilities = JSON.parse(rawAbilities);
            } catch (e) {
                abilities = [];
            }

            if (Array.isArray(abilities) && abilities.length > 0) {
                $('#abilityRows').empty();
                abilities.forEach(function (item) {
                    addAbilityRow(item.id || '', item.aumento || 0);
                });
            }
            syncAbilitiesInput();
            $('#personalizationModal').modal('show');
        });

        $('#personalizationType').on('change', function () {
            updateAbilitiesVisibility(true);
        });

        $('#addAbilityRowButton').on('click', function () {
            addAbilityRow('', 0);
            syncAbilitiesInput();
        });

        $(document).on('click', '.remove-ability', function () {
            $(this).closest('.ability-row').remove();
            syncAbilitiesInput();
        });

        $(document).on('change keyup', '.ability-select, .ability-aumento', function () {
            syncAbilitiesInput();
        });

        $('#personalizationForm').on('submit', function () {
            syncAbilitiesInput();
        });
    });
})();
