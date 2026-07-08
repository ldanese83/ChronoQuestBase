<?php

namespace App\Service;

use PDO;

class TeacherManagementService
{
    public TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getTeacherListPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'classTeachers' => [],
            'availableTeachers' => [],
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classroom'] = $this->getClassroom($classId);
        if ($data['classroom'] === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $data['classTeachers'] = $this->getTeachersInClass($classId);
        $data['availableTeachers'] = $this->getAvailableTeachers($classId);

        return $data;
    }

    public function addTeacherToClass(int $teacherId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($teacherId <= 0) {
            return $this->error($this->translator->translate('teacher.management.teacher.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();

        // Query legacy equivalente: verifica ruolo docente.
        $roleCheck = $pdo->prepare(
            'SELECT COUNT(*) AS tot
             FROM ct_utenti_tipi
             WHERE fk_utente = :fk_utente
               AND fk_tipo_utente = 3'
        );
        $roleCheck->execute(['fk_utente' => $teacherId]);
        if ((int) $roleCheck->fetchColumn() < 1) {
            return $this->error($this->translator->translate('teacher.management.user_not_teacher'));
        }

        $existingCheck = $pdo->prepare(
            'SELECT COUNT(*) AS tot
             FROM ct_utenti_classi
             WHERE fk_classe = :fk_classe
               AND fk_utente = :fk_utente'
        );
        $existingCheck->execute([
            'fk_classe' => $classId,
            'fk_utente' => $teacherId,
        ]);

        if ((int) $existingCheck->fetchColumn() > 0) {
            return $this->error($this->translator->translate('teacher.management.teacher_already_in_class'));
        }

        // Query legacy equivalente di add_docente_classe.php
        $insert = $pdo->prepare(
            'INSERT INTO ct_utenti_classi (fk_utente, fk_classe)
             VALUES (:fk_utente, :fk_classe)'
        );
        $insert->execute([
            'fk_utente' => $teacherId,
            'fk_classe' => $classId,
        ]);

        return [
            'success' => true,
            'message' => $this->translator->translate('teacher.management.teacher_added'),
        ];
    }

    private function getClassroom(int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function getTeachersInClass(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.id_utente, u.nome, u.cognome, u.email, u.username
             FROM (ct_utenti u
             INNER JOIN ct_utenti_tipi ut ON ut.fk_utente = u.id_utente)
             INNER JOIN ct_utenti_classi uc ON uc.fk_utente = u.id_utente
             WHERE ut.fk_tipo_utente = 3
               AND uc.fk_classe = :fk_classe
               AND u.validato = 1
             ORDER BY u.cognome'
        );
        $stmt->execute(['fk_classe' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function getAvailableTeachers(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.id_utente, u.nome, u.cognome, u.email, u.username
             FROM ct_utenti u
             INNER JOIN ct_utenti_tipi ut ON ut.fk_utente = u.id_utente
             WHERE ut.fk_tipo_utente = 3
               AND u.id_utente NOT IN (
                    SELECT fk_utente
                    FROM ct_utenti_classi
                    WHERE fk_classe = :fk_classe
               )
               AND u.validato = 1
             ORDER BY u.cognome'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $permission = $permissionService->checkPermissionsTeacher();

        if ($permission === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($permission) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->translator->translate('teacher.management.permission.invalid_session')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->translator->translate('teacher.management.permission.denied')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->translator->translate('teacher.management.permission.no_class_selected')),
            PermissionService::STATUS_NOT_CLASS_OWNER => $this->error($this->translator->translate('teacher.management.permission.not_class_owner')),
            default => $this->error($this->translator->translate('teacher.management.permission.operation_not_allowed')),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $class = Session::get('class');
        $classId = isset($class['id']) ? (int) $class['id'] : 0;
        if ($classId <= 0) {
            throw new \RuntimeException($this->translator->translate('teacher.management.permission.no_class_selected'));
        }

        return $classId;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
