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

    <script>
        
        const jsT = (key, fallback) => window.cqT ? window.cqT(key, fallback) : fallback;

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
                (payload.missing_topics || []).forEach((topic) => {
                    const key = topic.key;
                    const modeElement = document.querySelector('[name="mode_' + key + '"]');
                    const mode = modeElement ? modeElement.value : 'existing';
                    if (mode === 'existing') {
                        const topicSelect = document.querySelector('[name="existing_' + key + '"]');
                        decisions[key] = { mode: 'existing', topic_id: parseInt(topicSelect?.value || '0', 10) || 0 };
                    } else {
                        const subjectSelect = document.querySelector('[name="subject_' + key + '"]');
                        decisions[key] = { mode: 'create', subject_id: parseInt(subjectSelect?.value || '0', 10) || 0 };
                    }
                });
                return decisions;
            };

            const openResolutionModal = (payload, file) => {
                let html = '<div class="modal fade" id="topicResolutionModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + jsT('teacher.quest.import.missing_topics.title', 'Risoluzione argomenti mancanti') + '</h5><button class="close" type="button" data-dismiss="modal"><span>&times;</span></button></div><div class="modal-body">';
                (payload.missing_topics || []).forEach((topic) => {
                    html += '<div class="border rounded p-2 mb-2">';
                    html += '<strong>' + (topic.nome || jsT('teacher.quest.import.topic.label', 'Argomento')) + '</strong>';
                    html += '<input type="hidden" name="topic_key" value="' + topic.key + '">';
                    html += '<div class="row mt-2"><div class="col-md-4"><label>' + jsT('teacher.quest.import.action.label', 'Azione') + '</label><select class="form-control" name="mode_' + topic.key + '"><option value="existing">' + jsT('teacher.quest.import.action.associate_existing', 'Associa a esistente') + '</option><option value="create">' + jsT('teacher.quest.import.action.create_topic', 'Crea nuovo argomento') + '</option></select></div>';
                    html += '<div class="col-md-4"><label>' + jsT('teacher.quest.import.existing_topic.label', 'Argomento esistente') + '</label><select class="form-control" name="existing_' + topic.key + '"><option value="0">' + jsT('teacher.quest.import.select_placeholder', 'Seleziona...') + '</option>';
                    (payload.available_topics || []).forEach((existing) => {
                        html += '<option value="' + existing.id_argomento + '">' + existing.nome_materia + ' / ' + existing.nome_argomento + '</option>';
                    });
                    html += '</select></div>';
                    html += '<div class="col-md-4"><label>' + jsT('teacher.quest.import.new_topic_subject.label', 'Materia per nuovo argomento') + '</label><select class="form-control" name="subject_' + topic.key + '"><option value="0">' + jsT('teacher.quest.import.select_placeholder', 'Seleziona...') + '</option>';
                    (payload.available_subjects || []).forEach((subject) => {
                        html += '<option value="' + subject.id_materia + '">' + subject.nome_materia + '</option>';
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
                    fetch(form.action, { method: 'POST', body: retryData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((response) => response.json())
                        .then((result) => {
                            if (result.success) {
                                window.location.reload();
                                return;
                            }
                            alert(result.message || jsT('teacher.quest.import.failed', 'Import non riuscito.'));
                        })
                        .catch(() => alert(jsT('teacher.quest.import.error', 'Errore durante l\'import.')));
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
                fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then((response) => response.json())
                    .then((payload) => {
                        if (payload.success) {
                            window.location.reload();
                            return;
                        }
                        if (payload.requires_topic_resolution) {
                            openResolutionModal(payload, file);
                            return;
                        }
                        alert(payload.message || jsT('teacher.quest.import.failed', 'Import non riuscito.'));
                    })
                    .catch(() => alert(jsT('teacher.quest.import.error', 'Errore durante l\'import.')));
            });
        })();
    </script>
<?php endif; ?>
</div>
