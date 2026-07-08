<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherCustomizationService;

class TeacherCustomizationController
{
    public function index(): void
    {
        $pageData = (new TeacherCustomizationService())->getPersonalizationsPageData();
        $this->renderProtectedPage($pageData, 'teacher.customizations.title', 'docenti/personalizzazioni/index', ['/js/docenti/customizations.js']);
    }

    public function save(): void
    {
        $result = (new TeacherCustomizationService())->savePersonalization($_POST, $_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni');
        exit;
    }

    public function uploadCostume(): void
    {
        $result = (new TeacherCustomizationService())->uploadCostume($_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni');
        exit;
    }

    public function discountDays(): void
    {
        $pageData = (new TeacherCustomizationService())->getDiscountDaysPageData();
        $this->renderProtectedPage($pageData, 'teacher.customizations.discount.title', 'docenti/personalizzazioni/discount-days', [
            '/assets/tinymce/tinymce.min.js',
            '/js/docenti/customizations-discounts.js',
        ]);
    }

    public function saveDiscountDay(): void
    {
        $result = (new TeacherCustomizationService())->saveDiscountDay($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/giornate-sconti');
        exit;
    }

    public function deleteDiscountDay(string $id): void
    {
        $result = (new TeacherCustomizationService())->deleteDiscountDay((int) $id);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/giornate-sconti');
        exit;
    }

    public function studentUploads(): void
    {
        $pageData = (new TeacherCustomizationService())->getStudentUploadsPageData();
        $this->renderProtectedPage($pageData, 'teacher.customizations.student_uploads.title', 'docenti/personalizzazioni/student-uploads', ['/js/docenti/customizations-student-uploads.js']);
    }

    public function approveStudentUpload(string $id): void
    {
        $result = (new TeacherCustomizationService())->updateStudentUploadStatus((int) $id, true);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/studenti');
        exit;
    }

    public function rejectStudentUpload(string $id): void
    {
        $result = (new TeacherCustomizationService())->updateStudentUploadStatus((int) $id, false);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/studenti');
        exit;
    }

    public function inUse(): void
    {
        $pageData = (new TeacherCustomizationService())->getInUsePageData($_GET);
        $this->renderProtectedPage($pageData, 'teacher.customizations.in_use.title', 'docenti/personalizzazioni/in-use', ['/js/docenti/customizations-in-use.js']);
    }

    public function sets(): void
    {
        $pageData = (new TeacherCustomizationService())->getSetsPageData();
        $this->renderProtectedPage($pageData, 'teacher.customizations.sets.title', 'docenti/personalizzazioni/sets', ['/js/docenti/customization-sets.js']);
    }

    public function saveSet(): void
    {
        $result = (new TeacherCustomizationService())->saveSet($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/set');
        exit;
    }

    public function assignSetPersonalizations(): void
    {
        $result = (new TeacherCustomizationService())->assignSetPersonalizations($_POST);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customizations.operation.failed'));
        header('Location: /docenti/personalizzazioni/set');
        exit;
    }

    public function exportSetArchive(string $setId): void
    {
        $result = (new TeacherCustomizationService())->buildSetExportArchive((int) $setId);
        if (!($result['success'] ?? false)) {
            Flash::add('danger', (string) ($result['message'] ?? 'teacher.customization.export.error'));
            header('Location: /docenti/personalizzazioni/set');
            exit;
        }

        $absolutePath = (string) ($result['absolutePath'] ?? '');
        $fileName = (string) ($result['fileName'] ?? 'set-export.zip');
        if ($absolutePath === '' || !is_file($absolutePath)) {
            Flash::add('danger', 'teacher.customizations.export.zip_missing_after_export');
            header('Location: /docenti/personalizzazioni/set');
            exit;
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
        @unlink($absolutePath);
        exit;
    }

    public function importSetArchive(): void
    {
        $result = (new TeacherCustomizationService())->importSetFromArchive($_FILES);
        Flash::add(($result['success'] ?? false) ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.customization.import.error'));
        header('Location: /docenti/personalizzazioni/set');
        exit;
    }

    private function renderProtectedPage(array $pageData, string $title, string $view, array $scripts = []): void
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
                '/css/quest-legacy.css',
            ],
            'pageScripts' => $scripts,
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }
}
