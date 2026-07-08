<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherCommunicationsService;

class TeacherCommunicationsController
{
    public function alertsIndex(): void
    {
        $pageData = (new TeacherCommunicationsService())->getAlertsPageData();
        $this->renderProtectedPage('docenti/alerts/index', 'teacher.alerts.title', $pageData, [
            '/js/docenti/alertsIndex.js',
        ]);
    }

    public function openAlert(string $alertId): void
    {
        $result = (new TeacherCommunicationsService())->markAlertAsReadAndResolveLink((int) $alertId);

        if (!($result['success'] ?? false)) {
            Flash::add('danger', $result['message'] ?? 'teacher.communications.alert.open.error');
            header('Location: /docenti/alerts');
            exit;
        }

        header('Location: ' . ($result['redirect'] ?? '/docenti/alerts'));
        exit;
    }

    public function markAllAlertsAsRead(): void
    {
        $this->json((new TeacherCommunicationsService())->markAllAlertsAsRead());
    }

    public function deleteAlert(string $alertId): void
    {
        $this->json((new TeacherCommunicationsService())->deleteAlert((int) $alertId));
    }

    public function messagesIndex(): void
    {
        $pageData = (new TeacherCommunicationsService())->getMessagesPageData();
        $this->renderProtectedPage('docenti/messages/index', 'teacher.communications.messages.title', $pageData, [
            '/js/docenti/messagesIndex.js',
        ]);
    }

    public function composeBulkMessage(): void
    {
        $studentIds = (string) ($_GET['studenti'] ?? '');
        $ids = array_values(array_filter(array_map('intval', explode(',', $studentIds)), static fn (int $id): bool => $id > 0));

        $pageData = (new TeacherCommunicationsService())->getComposeBulkPageData($ids);
        $this->renderProtectedPage('docenti/messages/compose-bulk', 'teacher.communications.message.bulk_new.title', $pageData, [
            '/assets/tinymce/tinymce.min.js',
            '/js/docenti/messageComposeBulk.js',
        ]);
    }

    public function storeBulkMessage(): void
    {
        $ids = $_POST['ids'] ?? '';
        $studentIds = is_array($ids)
            ? array_values(array_filter(array_map('intval', $ids), static fn (int $id): bool => $id > 0))
            : array_values(array_filter(array_map('intval', explode(',', (string) $ids)), static fn (int $id): bool => $id > 0));

        $result = (new TeacherCommunicationsService())->sendBulkMessage($studentIds, $_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'js.operation.completed');
        $redirectQuery = $studentIds !== [] ? ('?studenti=' . implode(',', $studentIds)) : '';
        header('Location: /docenti/messages/new-bulk' . $redirectQuery);
        exit;
    }

    public function showMessage(string $messageId): void
    {
        $pageData = (new TeacherCommunicationsService())->getMessageDetailPageData((int) $messageId);
        $this->renderProtectedPage('docenti/messages/show', 'teacher.communications.message.title', $pageData, [
            '/assets/tinymce/tinymce.min.js',
            '/js/docenti/messageShow.js',
        ]);
    }

    public function replyToMessage(string $messageId): void
    {
        $result = (new TeacherCommunicationsService())->replyToMessage((int) $messageId, $_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'js.operation.completed');
        header('Location: /docenti/messages/' . (int) $messageId);
        exit;
    }

    public function deleteMessages(): void
    {
        $ids = $_POST['ids'] ?? [];
        $this->json((new TeacherCommunicationsService())->deleteMessages(is_array($ids) ? $ids : []));
    }

    private function renderProtectedPage(string $view, string $title, array $pageData, array $pageScripts = []): void
    {
        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            header('Location: /docenti/classi');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'teacher.classes.select.error');
            header('Location: /docenti/classi');
            exit;
        }

        View::render($view, array_merge($pageData, [
            'title' => $title,
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => $pageScripts,
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
