<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherBadgesService;

class TeacherBadgesController
{
    public function index(): void
    {
        $service = new TeacherBadgesService();
        $pageData = $service->getBadgesPageData($_GET);

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

        View::render('docenti/badge/index', array_merge($pageData, [
            'title' => 'teacher.badges.available',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/badge.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function assigned(): void
    {
        $service = new TeacherBadgesService();
        $pageData = $service->getAssignedBadgesPageData($_GET);

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

        View::render('docenti/badge/assigned', array_merge($pageData, [
            'title' => 'teacher.badges.assigned.page_title',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
            ],
            'pageScripts' => [
                '/js/docenti/badge-assigned.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherBadgesService())->saveBadge($_POST, $_FILES['badge_image'] ?? []));
    }

    public function delete(string $badgeId): void
    {
        $this->json((new TeacherBadgesService())->deleteBadge((int) $badgeId));
    }

    public function topicsBySubject(string $subjectId): void
    {
        $payload = (new TeacherBadgesService())->topicsBySubject((int) $subjectId);
        $this->json($payload, $payload['success'] ? 200 : 400);
    }

    public function exportArchive(string $subjectId): void
    {
        $result = (new TeacherBadgesService())->buildBadgesExportArchive((int) $subjectId);
        if (!($result['success'] ?? false)) {
            Flash::add('danger', (string) ($result['message'] ?? 'Export non riuscito.'));
            header('Location: /docenti/badge');
            exit;
        }

        $absolutePath = (string) ($result['absolutePath'] ?? '');
        $fileName = (string) ($result['fileName'] ?? 'badge-export.zip');
        if ($absolutePath === '' || !is_file($absolutePath)) {
            Flash::add('danger', 'File export non disponibile.');
            header('Location: /docenti/badge');
            exit;
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
        @unlink($absolutePath);
        exit;
    }

    public function importArchive(): void
    {
        $topicResolutionRaw = isset($_POST['topic_resolution']) ? (string) $_POST['topic_resolution'] : '';
        $topicResolution = [];
        if ($topicResolutionRaw !== '') {
            $decoded = json_decode($topicResolutionRaw, true);
            if (is_array($decoded)) {
                $topicResolution = $decoded;
            }
        }

        $result = (new TeacherBadgesService())->importBadgesFromArchive($_FILES, $topicResolution);
        $isAjax = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
        if ($isAjax) {
            $status = ($result['success'] ?? false) || ($result['requires_topic_resolution'] ?? false) ? 200 : 400;
            $this->json($result, $status);
            return;
        }

        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'Import non riuscito.'));
        header('Location: /docenti/badge');
        exit;
    }

    private function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
