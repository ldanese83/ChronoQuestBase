<?php

namespace App\Controller;

use App\Core\View;
use App\Service\Flash;
use App\Service\PermissionService;
use App\Service\Session;
use App\Service\TeacherCharacterService;

class TeacherCharacterController
{
    public function index(): void
    {
        $pageData = (new TeacherCharacterService())->getCharactersPageData();

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

        View::render('docenti/personaggi/index', array_merge($pageData, [
            'title' => 'teacher.characters.section.available',
            'pageStyles' => [
                '/css/headers.css',
                '/css/classes.css',
                '/css/docenti-dashboard.css',
                '/css/quest-legacy.css',
            ],
            'pageScripts' => [
                '/js/docenti/characters.js',
            ],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    public function save(): void
    {
        $this->json((new TeacherCharacterService())->saveCharacter($_POST, $_FILES));
    }

    public function importExportMenu(): void
    {
        $pageData = (new TeacherCharacterService())->getImportExportMenuPageData();
        $this->renderImportExportPageOrRedirect($pageData, 'teacher.character.import_export.title', 'docenti/personaggi/import-export-menu');
    }

    public function externalOriginalCharacters(): void
    {
        $pageData = (new TeacherCharacterService())->getExternalOriginalCharactersPageData();
        $this->renderImportExportPageOrRedirect($pageData, 'teacher.character.import_external.title', 'docenti/personaggi/import-external-list');
    }

    public function importOriginalCharacterFromAnotherClass(string $characterId): void
    {
        $result = (new TeacherCharacterService())->importOriginalCharacterFromAnotherClass((int) $characterId);
        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'Operazione non completata.'));
        header('Location: /docenti/personaggi/import-export/altre-classi');
        exit;
    }

    public function exportCharacterArchive(string $characterId): void
    {
        $result = (new TeacherCharacterService())->buildCharacterExportArchive((int) $characterId);
        if (!($result['success'] ?? false)) {
            Flash::add('danger', (string) ($result['message'] ?? 'teacher.character.export.error'));
            header('Location: /docenti/personaggi/import-export');
            exit;
        }

        $absolutePath = (string) ($result['absolutePath'] ?? '');
        $fileName = (string) ($result['fileName'] ?? 'personaggio-export.zip');
        if ($absolutePath === '' || !is_file($absolutePath)) {
            Flash::add('danger', 'File ZIP non trovato dopo l\'esportazione.');
            header('Location: /docenti/personaggi/import-export');
            exit;
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
        @unlink($absolutePath);
        exit;
    }

    public function importCharacterArchive(): void
    {
        $result = (new TeacherCharacterService())->importCharacterFromArchive($_FILES);
        Flash::add($result['success'] ? 'success' : 'danger', (string) ($result['message'] ?? 'teacher.character.import.error'));
        header('Location: /docenti/personaggi/import-export');
        exit;
    }

    private function renderImportExportPageOrRedirect(array $pageData, string $title, string $view): void
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
            'pageScripts' => [],
            'useMathJax' => false,
        ]), 'mainDocLayout');
    }

    private function json(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
