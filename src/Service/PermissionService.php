<?php

namespace App\Service;


// Service che centralizza i controlli di accesso per docenti/amministratori e classi selezionate.
class PermissionService
{
    public const STATUS_NOT_LOGGED = 0;
    public const STATUS_OK = 1;
    public const STATUS_NOT_TEACHER = 2;
    public const STATUS_NO_CLASS = 3;
    public const STATUS_NOT_CLASS_OWNER = 4;
    public const STATUS_NOT_STUDENT = 5;

    // Controlla se l'utente corrente può entrare nell'area docenti, senza richiedere ancora una classe selezionata.
    public function checkTeacherAreaAccess(): int
    {
        $userId = $this->getCurrentUserId();
        if ($userId === null) {
            return self::STATUS_NOT_LOGGED;
        }

        return $this->isTeacherOrAdmin($userId)
            ? self::STATUS_OK
            : self::STATUS_NOT_TEACHER;
    }


    // Controlla se l'utente corrente può entrare nell'area studenti senza richiedere ancora una classe selezionata.
    public function checkStudentAreaAccess(): int
    {
        $userId = $this->getCurrentUserId();
        if ($userId === null) {
            return self::STATUS_NOT_LOGGED;
        }

        return $this->isStudent($userId)
            ? self::STATUS_OK
            : self::STATUS_NOT_STUDENT;
    }

    // Controlla se l'utente corrente può accedere all'area interna studenti per la classe salvata in sessione.
    public function checkPermissionsStudent(): int
    {
        $studentAreaStatus = $this->checkStudentAreaAccess();
        if ($studentAreaStatus !== self::STATUS_OK) {
            return $studentAreaStatus;
        }

        $userId = $this->getCurrentUserId();
        $classId = $this->getCurrentClassId();
        if ($classId === null) {
            return self::STATUS_NO_CLASS;
        }

        $pdo = Database::getConnection();

        // Verifica che la classe in sessione appartenga davvero allo studente autenticato.
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti u
             INNER JOIN ct_studenti s ON s.fk_utente = u.id_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE u.id_utente = :id_utente
               AND sc.fk_classe = :id_classe'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);

        return ((int) $stmt->fetchColumn() > 0)
            ? self::STATUS_OK
            : self::STATUS_NOT_CLASS_OWNER;
    }

    // Controlla se l'utente corrente può accedere all'area docenti relativa alla classe in sessione.
    public function checkPermissionsTeacher(): int
    {
        $teacherAreaStatus = $this->checkTeacherAreaAccess();
        if ($teacherAreaStatus !== self::STATUS_OK) {
            return $teacherAreaStatus;
        }

        $userId = $this->getCurrentUserId();
        $classId = $this->getCurrentClassId();
        if ($classId === null) {
            return self::STATUS_NO_CLASS;
        }

        $pdo = Database::getConnection();

        // Verifica che il docente sia associato alla classe attualmente selezionata.
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_classi
             WHERE fk_classe = :id_classe
               AND fk_utente = :id_utente'
        );
        $stmt->execute([
            'id_classe' => $classId,
            'id_utente' => $userId,
        ]);

        return ((int) $stmt->fetchColumn() > 0)
            ? self::STATUS_OK
            : self::STATUS_NOT_CLASS_OWNER;
    }

    // Verifica se l'utente indicato ha ruolo docente o amministratore.
    public function isTeacherOrAdmin(int $userId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :id_utente
               AND tu.tipo_utente IN ("docente", "amministratore")'
        );
        $stmt->execute(['id_utente' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function isAdmin(int $userId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :id_utente
               AND tu.tipo_utente = "amministratore"'
        );
        $stmt->execute(['id_utente' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }


    // Verifica se l'utente indicato ha ruolo studente.
    public function isStudent(int $userId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :id_utente
               AND tu.tipo_utente = "studente"'
        );
        $stmt->execute(['id_utente' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Restituisce l'id dell'utente autenticato, se presente e valido.
    public function getCurrentUserId(): ?int
    {
        $user = Session::get('user');
        $userId = isset($user['id']) ? (int) $user['id'] : 0;

        return $userId > 0 ? $userId : null;
    }

    // Restituisce l'id della classe selezionata in sessione, se presente e valido.
    public function getCurrentClassId(): ?int
    {
        $class = Session::get('class');
        $classId = isset($class['id']) ? (int) $class['id'] : 0;

        return $classId > 0 ? $classId : null;
    }
}
