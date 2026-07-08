<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\StudentCommunicationsService;

class StudentCommunicationsController
{
    public function alertsIndex(): void
    {
        $pageData = (new StudentCommunicationsService())->getAlertsPageData();
        $this->renderProtectedPage('studenti/alerts/index', 'student.alerts.title', $pageData, [
            '/js/studenti/alertsIndex.js',
        ]);
    }

    public function openAlert(string $alertId): void
    {
        $result = (new StudentCommunicationsService())->markAlertAsReadAndResolveLink((int) $alertId);

        if (!($result['success'] ?? false)) {
            Flash::add('danger', $result['message'] ?? 'student.communications.alert.open.error');
            header('Location: /studenti/alerts');
            exit;
        }

        header('Location: ' . ($result['redirect'] ?? '/studenti/alerts'));
        exit;
    }

    public function markAllAlertsAsRead(): void
    {
        $this->json((new StudentCommunicationsService())->markAllAlertsAsRead());
    }

    public function deleteAlert(string $alertId): void
    {
        $this->json((new StudentCommunicationsService())->deleteAlert((int) $alertId));
    }

    public function messagesIndex(): void
    {
        $pageData = (new StudentCommunicationsService())->getMessagesPageData();
        $this->renderProtectedPage('studenti/messages/index', 'student.communications.messages.title', $pageData, [
            '/js/studenti/messagesIndex.js',
        ]);
    }

    public function composeMessage(): void
    {
        $pageData = (new StudentCommunicationsService())->getComposePageData();
        $this->renderProtectedPage('studenti/messages/compose', 'student.communications.message.new', $pageData, [
            '/assets/tinymce/tinymce.min.js',
            '/js/studenti/messageCompose.js',
        ]);
    }

    public function storeMessage(): void
    {
        $result = (new StudentCommunicationsService())->sendNewMessage($_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'js.operation.completed');
        header('Location: ' . (($result['success'] ?? false) ? '/studenti/messages/new' : '/studenti/messages/new'));
        exit;
    }

    public function showMessage(string $messageId): void
    {
        $pageData = (new StudentCommunicationsService())->getMessageDetailPageData((int) $messageId);
        $this->renderProtectedPage('studenti/messages/show', 'student.communications.message.title', $pageData, [
            '/assets/tinymce/tinymce.min.js',
            '/js/studenti/messageShow.js',
        ]);
    }

    public function replyToMessage(string $messageId): void
    {
        $result = (new StudentCommunicationsService())->replyToMessage((int) $messageId, $_POST);

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', $result['message'] ?? 'js.operation.completed');
        header('Location: /studenti/messages/' . (int) $messageId);
        exit;
    }

    public function deleteMessages(): void
    {
        $ids = $_POST['ids'] ?? [];
        $this->json((new StudentCommunicationsService())->deleteMessages(is_array($ids) ? $ids : []));
    }

    private function renderProtectedPage(string $view, string $title, array $pageData, array $pageScripts = []): void
    {
        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NO_CLASS) {
            Flash::add('danger', 'permission.noclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_CLASS_OWNER) {
            Session::set('class', null);
            Flash::add('danger', 'permission.notyourclass');
            header('Location: /studenti/dashboard');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_STUDENT) {
            Flash::add('danger', 'permission.nostudent');
            header('Location: /loginStud');
            exit;
        }

        if (($pageData['permissionStatus'] ?? null) === PermissionService::STATUS_NOT_LOGGED) {
            header('Location: /loginStud');
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
        ]), 'mainStudLayout');
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
