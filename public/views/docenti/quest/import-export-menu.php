<?php
use App\Service\PermissionService;
use App\Service\TranslationService;

$permissionStatus = $permissionStatus ?? PermissionService::STATUS_NOT_LOGGED;
$originalQuestsInClass = $originalQuestsInClass ?? [];
$translator = new TranslationService();
?>
<div class="container-fluid">
<?php if ($permissionStatus === PermissionService::STATUS_OK): ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4 gap-2 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.title')) ?></h1>
        <a href="/docenti/quest" class="btn btn-sm btn-secondary shadow-sm"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.back_to_quests')) ?></a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100"><div class="card-body">
                <h5><?= htmlspecialchars($translator->translate('teacher.quest.import_export.export_original_title')) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.export_original_desc')) ?></p>
                <form method="GET" action="/docenti/quest/import-export/esporta/0" id="exportQuestForm">
                    <select class="form-control mb-2" id="exportQuestSelect" required>
                        <option value=""><?= htmlspecialchars($translator->translate('teacher.quest.import_export.select_quest')) ?></option>
                        <?php foreach ($originalQuestsInClass as $quest): ?>
                            <option value="<?= (int) ($quest['id_quest'] ?? 0) ?>"><?= htmlspecialchars((string) ($quest['nome_quest'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.export_zip')) ?></button>
                </form>
            </div></div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100"><div class="card-body">
                <h5><?= htmlspecialchars($translator->translate('teacher.quest.import_export.import_file_title')) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.import_file_desc')) ?></p>
                <form method="POST" action="/docenti/quest/import-export/importa-file" enctype="multipart/form-data" id="importQuestForm">
                    <input type="file" class="form-control mb-2" name="quest_archive" accept=".zip" required>
                    <button class="btn btn-success btn-sm" type="submit"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.import_zip')) ?></button>
                </form>
            </div></div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100"><div class="card-body">
                <h5><?= htmlspecialchars($translator->translate('teacher.quest.import_export.import_other_class_title')) ?></h5>
                <p class="text-muted"></p>
                <a class="btn btn-info btn-sm" href="/docenti/quest/import-export/altre-classi"><?= htmlspecialchars($translator->translate('teacher.quest.import_export.open_list')) ?></a>
            </div></div>
        </div>
    </div>

    <div class="modal fade" id="questImportLoadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-success mb-3" role="status" aria-hidden="true"></div>
                    <h5 class="mb-2"><?= htmlspecialchars($translator->translate('teacher.quest.import.loading.title')) ?></h5>
                    <p class="text-muted mb-0"><?= htmlspecialchars($translator->translate('teacher.quest.import.loading.message')) ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        const jsT = (key, fallback) => window.cqT ? window.cqT(key, fallback) : fallback;
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
        const showImportLoading = () => $('#questImportLoadingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        const hideImportLoading = (afterHidden) => {
            const modal = $('#questImportLoadingModal');
            if (typeof afterHidden === 'function') {
                modal.one('hidden.bs.modal', afterHidden);
            }
            modal.modal('hide');
        };

        document.getElementById('exportQuestForm')?.addEventListener('submit', function (event) {
            event.preventDefault();
            const questId = document.getElementById('exportQuestSelect').value;
            if (!questId) return;
            window.location.href = '/docenti/quest/import-export/esporta/' + questId;
        });

        (function () {
            const form = document.getElementById('importQuestForm');
            if (!form) {
                return;
            }

            const buildDecisionMap = (payload) => {
                const decisions = {};
                document.querySelectorAll('#topicResolutionModal .js-topic-resolution-row').forEach((row) => {
                    const key = row.dataset.topicKey || '';
                    const modeElement = row.querySelector('.js-topic-resolution-mode');
                    const mode = modeElement ? modeElement.value : 'existing';
                    if (mode === 'existing') {
                        const topicSelect = row.querySelector('.js-existing-topic');
                        decisions[key] = { mode: 'existing', topic_id: parseInt(topicSelect?.value || '0', 10) || 0 };
                    } else {
                        const subjectSelect = row.querySelector('.js-new-topic-subject');
                        decisions[key] = { mode: 'create', subject_id: parseInt(subjectSelect?.value || '0', 10) || 0 };
                    }
                });
                return decisions;
            };

            const openResolutionModal = (payload, file) => {
                document.getElementById('topicResolutionModal')?.remove();
                let html = '<div class="modal fade" id="topicResolutionModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + jsT('teacher.quest.import.missing_topics.title', 'Risoluzione argomenti mancanti') + '</h5><button class="close" type="button" data-dismiss="modal"><span>&times;</span></button></div><div class="modal-body">';
                (payload.missing_topics || []).forEach((topic) => {
                    html += '<div class="border rounded p-2 mb-2 js-topic-resolution-row" data-topic-key="' + escapeHtml(topic.key) + '">';
                    html += '<strong>' + escapeHtml(topic.nome || jsT('teacher.quest.import.topic.label', 'Argomento')) + '</strong>';
                    html += '<div class="row mt-2"><div class="col-md-4"><label>' + jsT('teacher.quest.import.action.label', 'Azione') + '</label><select class="form-control js-topic-resolution-mode"><option value="existing">' + jsT('teacher.quest.import.action.associate_existing', 'Associa a esistente') + '</option><option value="create">' + jsT('teacher.quest.import.action.create_topic', 'Crea nuovo argomento') + '</option></select></div>';
                    html += '<div class="col-md-4"><label>' + jsT('teacher.quest.import.existing_topic.label', 'Argomento esistente') + '</label><select class="form-control js-existing-topic"><option value="0">' + jsT('teacher.quest.import.select_placeholder', 'Seleziona...') + '</option>';
                    (payload.available_topics || []).forEach((existing) => {
                        html += '<option value="' + parseInt(existing.id_argomento || '0', 10) + '">' + escapeHtml(existing.nome_materia) + ' / ' + escapeHtml(existing.nome_argomento) + '</option>';
                    });
                    html += '</select></div>';
                    html += '<div class="col-md-4"><label>' + jsT('teacher.quest.import.new_topic_subject.label', 'Materia per nuovo argomento') + '</label><select class="form-control js-new-topic-subject"><option value="0">' + jsT('teacher.quest.import.select_placeholder', 'Seleziona...') + '</option>';
                    (payload.available_subjects || []).forEach((subject) => {
                        html += '<option value="' + parseInt(subject.id_materia || '0', 10) + '">' + escapeHtml(subject.nome_materia) + '</option>';
                    });
                    html += '</select></div></div></div>';
                });
                html += '</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">' + jsT('teacher.quest.import.cancel', 'Annulla') + '</button><button type="button" class="btn btn-primary" id="confirmTopicResolution">' + jsT('teacher.quest.import.confirm', 'Conferma import') + '</button></div></div></div></div>';
                document.body.insertAdjacentHTML('beforeend', html);
                $('#topicResolutionModal').modal('show');

                document.getElementById('confirmTopicResolution')?.addEventListener('click', function () {
                    const decisionMap = buildDecisionMap(payload);
                    const retryData = new FormData();
                    retryData.append('quest_archive', file);
                    retryData.append('topic_resolution', JSON.stringify(decisionMap));
                    $('#topicResolutionModal').modal('hide');
                    showImportLoading();
                    fetch(form.action, { method: 'POST', body: retryData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((response) => response.json())
                        .then((result) => {
                            if (result.success) {
                                hideImportLoading(() => {
                                    alert(result.message || jsT('teacher.quest.import.file.success', 'Quest importata correttamente.'));
                                    window.location.reload();
                                });
                                return;
                            }
                            if (result.requires_topic_resolution) {
                                hideImportLoading(() => openResolutionModal(result, file));
                                return;
                            }
                            hideImportLoading(() => alert(result.message || jsT('teacher.quest.import.failed', 'Import non riuscito.')));
                        })
                        .catch(() => {
                            hideImportLoading(() => alert(jsT('teacher.quest.import.error', 'Errore durante l\'import.')));
                        });
                });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const fileInput = form.querySelector('input[name="quest_archive"]');
                const file = fileInput?.files?.[0];
                if (!file) {
                    return;
                }
                const formData = new FormData(form);
                showImportLoading();
                fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then((response) => response.json())
                    .then((payload) => {
                        if (payload.success) {
                            hideImportLoading(() => {
                                alert(payload.message || jsT('teacher.quest.import.file.success', 'Quest importata correttamente.'));
                                window.location.reload();
                            });
                            return;
                        }
                        if (payload.requires_topic_resolution) {
                            hideImportLoading(() => openResolutionModal(payload, file));
                            return;
                        }
                        hideImportLoading(() => alert(payload.message || jsT('teacher.quest.import.failed', 'Import non riuscito.')));
                    })
                    .catch(() => {
                        hideImportLoading(() => alert(jsT('teacher.quest.import.error', 'Errore durante l\'import.')));
                    });
            });
        })();
    </script>
<?php endif; ?>
</div>
