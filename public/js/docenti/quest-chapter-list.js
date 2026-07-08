(function () {
    function showAlert(type, message) {
        const alertBox = $('#chapter-list-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type)
            .text(message || window.cqT('js.operation.completed', 'Operazione completata.'));

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function parseJson(response) {
        return response.json().catch(function () {
            return { success: false, message: window.cqT('js.response.invalid', 'Risposta non valida dal server.') };
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.jQuery === 'undefined') {
            return;
        }

        if ($.fn.DataTable && $('#chapterListTable').length) {
            $('#chapterListTable').DataTable({
                pageLength: parseInt($('#chapterListTable').data('page-length'), 10) || 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, window.cqT('js.filter.all', 'Tutti')]],
                order: [[0, 'asc']],
                language: {
                    search: window.cqT('js.table.search', 'Cerca:'),
                    lengthMenu: window.cqT('teacher.quest.chapter_list.table.length_menu', 'Mostra _MENU_ capitoli'),
                    info: window.cqT('teacher.quest.chapter_list.table.info', 'Visualizzazione da _START_ a _END_ di _TOTAL_ capitoli'),
                    infoEmpty: window.cqT('teacher.quest.chapter_list.table.info_empty', 'Nessun capitolo disponibile'),
                    infoFiltered: window.cqT('teacher.quest.chapter_list.table.info_filtered', '(filtrati da _MAX_ capitoli totali)'),
                    zeroRecords: window.cqT('teacher.quest.chapter_list.table.zero_records', 'Nessun capitolo trovato'),
                    paginate: {
                        first: window.cqT('js.table.paginate.first', 'Prima'),
                        last: window.cqT('js.table.paginate.last', 'Ultima'),
                        next: window.cqT('js.table.paginate.next', 'Successiva'),
                        previous: window.cqT('js.table.paginate.previous', 'Precedente')
                    }
                }
            });
        }

        $(document).on('click', '.js-edit-chapter', function () {
            const button = $(this);
            $('#editChapterId').val(String(button.data('chapter-id') || 0));
            $('#editChapterName').val(String(button.data('chapter-name') || ''));
            $('#editChapterProgressive').val(String(button.data('chapter-progressive') || 1));
            $('#editChapterCoordX').val(String(button.data('chapter-x') || 0));
            $('#editChapterCoordY').val(String(button.data('chapter-y') || 0));
            $('#editChapterModal').modal('show');
        });

        $('#editChapterForm').on('submit', function (event) {
            event.preventDefault();

            const questId = Number((window.questChapterListData || {}).questId || 0);
            const chapterId = parseInt($('#editChapterId').val(), 10) || 0;
            const chapterName = String($('#editChapterName').val() || '').trim();
            const progressive = parseInt($('#editChapterProgressive').val(), 10) || 0;
            const coordX = parseInt($('#editChapterCoordX').val(), 10);
            const coordY = parseInt($('#editChapterCoordY').val(), 10);

            if (questId <= 0 || chapterId <= 0) {
                showAlert('danger', window.cqT('teacher.quest.chapter_list.invalid_chapter', 'Capitolo non valido.'));
                return;
            }

            if (chapterName === '' || progressive <= 0 || Number.isNaN(coordX) || Number.isNaN(coordY) || coordX < 0 || coordY < 0) {
                showAlert('warning', window.cqT('teacher.quest.chapter_list.validation_required', 'Compila correttamente nome, progressivo e coordinate.'));
                return;
            }

            $('#saveChapterChangesButton').prop('disabled', true);

            const body = new URLSearchParams();
            body.append('nome_capitolo', chapterName);
            body.append('progressivo', String(progressive));
            body.append('coord_x', String(coordX));
            body.append('coord_y', String(coordY));

            fetch('/docenti/quest/' + questId + '/capitoli/' + chapterId + '/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body.toString()
            })
                .then(parseJson)
                .then(function (payload) {
                    if (!payload.success) {
                        showAlert('danger', payload.message || window.cqT('teacher.quest.chapter_list.update_failed', 'Aggiornamento non riuscito.'));
                        return;
                    }

                    $('#editChapterModal').modal('hide');
                    showAlert('success', payload.message || window.cqT('teacher.quest.chapter_list.update_success', 'Capitolo aggiornato correttamente.'));
                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                })
                .catch(function () {
                    showAlert('danger', window.cqT('teacher.quest.chapter_list.update_unexpected', 'Errore imprevisto durante l\'aggiornamento del capitolo.'));
                })
                .finally(function () {
                    $('#saveChapterChangesButton').prop('disabled', false);
                });
        });
    });
})();
