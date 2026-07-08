<?php

namespace App\Service;

use PDO;
use Throwable;
use App\Service\TranslationService;

class StudentManagementService
{
    private const CSV_MAX_SIZE = 50000;
    public TranslationService $translator;

    /**
     * @var string[]
     */
    private array $passwordAnimals = [
        'antelope',
        'badger',
        'beaver',
        'buffalo',
        'cheetah',
        'cougar',
        'dolphin',
        'eagle',
        'falcon',
        'fox',
        'gecko',
        'jaguar',
        'koala',
        'lemur',
        'lynx',
        'otter',
        'panda',
        'rabbit',
        'tiger',
        'wolf',
    ];

    public function __construct()
    {
        $this->translator=new TranslationService();
    }

    public function getStudentListPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();

        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'students' => [],
            'availableImportClasses' => [],
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

        $data['students'] = $this->getStudentsForClass($classId);
        $data['availableImportClasses'] = $this->getAvailableImportClasses($classId);

        return $data;
    }

    public function saveStudent(array $input): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $studentId = isset($input['id_studente']) ? (int) $input['id_studente'] : 0;
        $name = trim((string) ($input['nome_studente'] ?? ''));
        $surname = trim((string) ($input['cognome_studente'] ?? ''));
        $email = trim((string) ($input['email_studente'] ?? ''));
        $gender = strtoupper(trim((string) ($input['sesso_studente'] ?? 'M')));
        $l104 = isset($input['studente104']) && (int) $input['studente104'] === 1 ? 1 : 0;
        $password = trim((string) ($input['password_studente'] ?? ''));

        if ($name === '' || $surname === '') {
            return $this->error($this->translator->translate('studmanage.insertname'));
        }

        if (!in_array($gender, ['M', 'F'], true)) {
            return $this->error($this->translator->translate('studmanage.selectgender'));
        }

        if ($studentId === 0 && ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            return $this->error($this->translator->translate('studmanage.selectgender'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ($studentId === 0) {
                $message = $this->createStudent($pdo, $classId, $name, $surname, $email, $gender, $l104);
            } else {
                $message = $this->updateStudent($pdo, $classId, $studentId, $name, $surname, $gender, $l104, $password);
            }

            $pdo->commit();

            return [
                'success' => true,
                'message' => $message,
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function importStudentsFromCsv(array $uploadedFile): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if (($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return $this->error($this->translator->translate('studmanage.errorupload'));
        }

        $originalName = (string) ($uploadedFile['name'] ?? '');
        $tmpName = (string) ($uploadedFile['tmp_name'] ?? '');
        $size = (int) ($uploadedFile['size'] ?? 0);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            return $this->error($this->translator->translate('studmanage.onlycsv'));
        }

        if ($size > self::CSV_MAX_SIZE) {
            return $this->error($this->translator->translate('studmanage.filebig'));
        }

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return $this->error($this->translator->translate('studmanage.invalidupload'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $handle = fopen($tmpName, 'rb');
        if ($handle === false) {
            return $this->error($this->translator->translate('studmanage.cantread'));
        }

        $pdo = Database::getConnection();
        $summary = [
            'created' => 0,
            'linked' => 0,
            'already_present' => 0,
            'emailed' => 0,
            'mail_failed' => 0,
            'invalid_rows' => 0,
        ];
        $details = [];
        $rowNumber = 0;

        try {
            fgetcsv($handle, 0, ';');

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $rowNumber++;
                if ($this->isCsvRowEmpty($row)) {
                    continue;
                }

                [$name, $surname, $email, $gender, $l104] = $this->normalizeCsvRow($row);

                if ($name === '' || $surname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($gender, ['M', 'F'], true)) {
                    $summary['invalid_rows']++;
                    $details[] = 'Row ' . ($rowNumber + 1) . ': invalid data, student ignored.';
                    continue;
                }

                $pdo->beginTransaction();
                try {
                    $result = $this->importCsvStudentRow($pdo, $classId, $name, $surname, $email, $gender, $l104);
                    $pdo->commit();
                } catch (Throwable $exception) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }

                    $summary['invalid_rows']++;
                    $details[] = trim($name . ' ' . $surname) . ': ' . $exception->getMessage();
                    continue;
                }

                if ($result['status'] === 'already_present') {
                    $summary['already_present']++;
                    $details[] = trim($name . ' ' . $surname) . ': already present in class.';
                    continue;
                }

                if ($result['status'] === 'created') {
                    $summary['created']++;
                }

                if ($result['status'] === 'linked_existing') {
                    $summary['linked']++;
                }

                $mailResult = $this->sendStudentCredentialsEmail(
                    $email,
                    (string) $result['username'],
                    (string) $result['plain_password'],
                    $name,
                    $surname
                );

                if ($mailResult['sent']) {
                    $summary['emailed']++;
                    $details[] = trim($name . ' ' . $surname) . ': imported and email sent.';
                } else {
                    $summary['mail_failed']++;
                    $details[] = trim($name . ' ' . $surname) . ': imported but email not sent: ' . $mailResult['error'];
                }
            }
        } finally {
            fclose($handle);
        }

        $messageParts = [];
        if ($summary['created'] > 0) {
            $messageParts[] = $summary['created'] . ' new students created';
        }
        if ($summary['linked'] > 0) {
            $messageParts[] = $summary['linked'] . ' existing students associated to class';
        }
        if ($summary['already_present'] > 0) {
            $messageParts[] = $summary['already_present'] . ' already present';
        }
        if ($summary['emailed'] > 0) {
            $messageParts[] = $summary['emailed'] . ' email sent';
        }
        if ($summary['mail_failed'] > 0) {
            $messageParts[] = $summary['mail_failed'] . ' email not sent';
        }
        if ($summary['invalid_rows'] > 0) {
            $messageParts[] = $summary['invalid_rows'] . ' invalid rows';
        }

        return [
            'success' => true,
            'message' => $messageParts !== []
                ? 'Import CSV completed: ' . implode(', ', $messageParts) . '.'
                : 'No students imported from CSV.',
            'details' => $details,
            'summary' => $summary,
        ];
    }

    public function importStudentsFromClass(int $sourceClassId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        $classId = $this->getCurrentClassIdOrFail();
        if ($sourceClassId <= 0) {
            return $this->error($this->translator->translate('studmanage.import.class.select_valid'));
        }

        if ($sourceClassId === $classId) {
            return $this->error($this->translator->translate('studmanage.import.class.current_not_allowed'));
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT sc.fk_studente, u.nome, u.cognome
             FROM ct_studenti_classi sc
             INNER JOIN ct_studenti s ON s.id_studente = sc.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE sc.fk_classe = :fk_classe
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_classe' => $sourceClassId]);
        $sourceStudents = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $imported = 0;
        $alreadyPresent = 0;
        $details = [];

        foreach ($sourceStudents as $sourceStudent) {
            $studentId = (int) ($sourceStudent['fk_studente'] ?? 0);
            if ($studentId <= 0) {
                continue;
            }

            $check = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_studenti_classi
                 WHERE fk_classe = :fk_classe
                   AND fk_studente = :fk_studente'
            );
            $check->execute([
                'fk_classe' => $classId,
                'fk_studente' => $studentId,
            ]);

            $studentLabel = trim(((string) ($sourceStudent['nome'] ?? '')) . ' ' . ((string) ($sourceStudent['cognome'] ?? '')));

            if ((int) $check->fetchColumn() > 0) {
                $alreadyPresent++;
                $details[] = $studentLabel . ': already present in class.';
                continue;
            }

            $insert = $pdo->prepare(
                'INSERT INTO ct_studenti_classi (fk_classe, fk_studente)
                 VALUES (:fk_classe, :fk_studente)'
            );
            $insert->execute([
                'fk_classe' => $classId,
                'fk_studente' => $studentId,
            ]);

            $imported++;
            $details[] = $studentLabel . ': imported correctly.';
        }

        $message = 'Import from other class completed: ' . $imported . ' imported';
        if ($alreadyPresent > 0) {
            $message .= ', ' . $alreadyPresent . ' already present';
        }
        $message .= '.';

        return [
            'success' => true,
            'message' => $message,
            'details' => $details,
            'summary' => [
                'imported' => $imported,
                'already_present' => $alreadyPresent,
            ],
        ];
    }

    public function deleteStudent(int $studentId): array
    {
        $access = $this->guardTeacherClassAccess();
        if ($access !== null) {
            return $access;
        }

        if ($studentId <= 0) {
            return $this->error($this->translator->translate('studmanage.student.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare(
            'DELETE FROM ct_studenti_classi
             WHERE fk_studente = :fk_studente
               AND fk_classe = :fk_classe'
        );
        $stmt->execute([
            'fk_studente' => $studentId,
            'fk_classe' => $classId,
        ]);

        if ($stmt->rowCount() < 1) {
            return $this->error($this->translator->translate('studmanage.student.not_in_selected_class'));
        }

        return [
            'success' => true,
            'message' => $this->translator->translate('studmanage.student.removed_from_class'),
        ];
    }

    private function getClassroom(int $classId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
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

    private function getStudentsForClass(int $classId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT s.id_studente,
                    s.l104,
                    u.nome,
                    u.cognome,
                    u.email,
                    u.username,
                    u.sesso
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE sc.fk_classe = :id_classe
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['id_classe' => $classId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id_studente'],
                'name' => (string) $row['nome'],
                'surname' => (string) $row['cognome'],
                'email' => (string) $row['email'],
                'username' => (string) $row['username'],
                'gender' => (string) $row['sesso'],
                'l104' => (int) $row['l104'] === 1,
            ];
        }, $rows);
    }

    private function getAvailableImportClasses(int $currentClassId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe <> :id_classe
             ORDER BY a.anno_scolastico DESC, c.nome_classe ASC'
        );
        $stmt->execute(['id_classe' => $currentClassId]);

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id_classe'],
                'name' => (string) $row['nome_classe'],
                'school_year' => (string) $row['anno_scolastico'],
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function createStudent(PDO $pdo, int $classId, string $name, string $surname, string $email, string $gender, int $l104): string
    {
        $username = $this->generateUsername($pdo, $name, $surname);
        $passwordHash = password_hash($username, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('SELECT id_utente FROM ct_utenti WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $existingUserId = (int) ($stmt->fetchColumn() ?: 0);

        if ($existingUserId > 0) {
            $stmt = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_studenti s
                 INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
                 WHERE s.fk_utente = :fk_utente
                   AND sc.fk_classe = :fk_classe'
            );
            $stmt->execute([
                'fk_utente' => $existingUserId,
                'fk_classe' => $classId,
            ]);

            if ((int) $stmt->fetchColumn() > 0) {
                throw new \RuntimeException($this->translator->translate('studmanage.student.already_in_selected_class'));
            }

            $userId = $existingUserId;
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO ct_utenti (nome, cognome, email, username, password, validato, fk_tipo_utente, sesso)
                 VALUES (:nome, :cognome, :email, :username, :password, 1, 2, :sesso)'
            );
            $stmt->execute([
                'nome' => $name,
                'cognome' => $surname,
                'email' => $email,
                'username' => $username,
                'password' => $passwordHash,
                'sesso' => $gender,
            ]);
            $userId = (int) $pdo->lastInsertId();

            $stmt = $pdo->prepare(
                'INSERT INTO ct_utenti_tipi (fk_utente, fk_tipo_utente)
                 VALUES (:fk_utente, 2)'
            );
            $stmt->execute(['fk_utente' => $userId]);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO ct_studenti (fk_utente, l104)
             VALUES (:fk_utente, :l104)'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'l104' => $l104,
        ]);
        $newStudentId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            'INSERT INTO ct_studenti_classi (fk_classe, fk_studente)
             VALUES (:fk_classe, :fk_studente)'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $newStudentId,
        ]);

        return $this->translator->translate('studmanage.student.created');
    }

    private function updateStudent(PDO $pdo, int $classId, int $studentId, string $name, string $surname, string $gender, int $l104, string $password): string
    {
        $stmt = $pdo->prepare(
            'SELECT s.id_studente, s.fk_utente
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.id_studente = :id_studente
               AND sc.fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_studente' => $studentId,
            'fk_classe' => $classId,
        ]);
        $studentRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$studentRow) {
            throw new \RuntimeException($this->translator->translate('studmanage.student.not_in_selected_class'));
        }

        if ($password === '') {
            $stmt = $pdo->prepare(
                'UPDATE ct_utenti
                 SET nome = :nome,
                     cognome = :cognome,
                     sesso = :sesso
                 WHERE id_utente = :id_utente'
            );
            $stmt->execute([
                'nome' => $name,
                'cognome' => $surname,
                'sesso' => $gender,
                'id_utente' => (int) $studentRow['fk_utente'],
            ]);
        } else {
            $stmt = $pdo->prepare(
                'UPDATE ct_utenti
                 SET nome = :nome,
                     cognome = :cognome,
                     password = :password,
                     sesso = :sesso
                 WHERE id_utente = :id_utente'
            );
            $stmt->execute([
                'nome' => $name,
                'cognome' => $surname,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'sesso' => $gender,
                'id_utente' => (int) $studentRow['fk_utente'],
            ]);
        }

        $stmt = $pdo->prepare(
            'UPDATE ct_studenti
             SET l104 = :l104
             WHERE id_studente = :id_studente'
        );
        $stmt->execute([
            'l104' => $l104,
            'id_studente' => $studentId,
        ]);

        return $this->translator->translate('studmanage.student.updated');
    }

    private function importCsvStudentRow(PDO $pdo, int $classId, string $name, string $surname, string $email, string $gender, int $l104): array
    {
        $stmt = $pdo->prepare('SELECT id_utente, username FROM ct_utenti WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($existingUser !== null) {
            $existingUserId = (int) ($existingUser['id_utente'] ?? 0);
            if (!$this->userCanBeManagedAsStudent($pdo, $existingUserId)) {
                throw new \RuntimeException($this->translator->translate('studmanage.import.csv.email_non_student'));
            }

            $stmt = $pdo->prepare(
                'SELECT s.id_studente
                 FROM ct_studenti s
                 WHERE s.fk_utente = :fk_utente
                 LIMIT 1'
            );
            $stmt->execute(['fk_utente' => $existingUserId]);
            $existingStudentId = (int) ($stmt->fetchColumn() ?: 0);

            if ($existingStudentId <= 0) {
                $stmt = $pdo->prepare(
                    'INSERT INTO ct_studenti (fk_utente, l104)
                     VALUES (:fk_utente, :l104)'
                );
                $stmt->execute([
                    'fk_utente' => $existingUserId,
                    'l104' => $l104,
                ]);
                $existingStudentId = (int) $pdo->lastInsertId();
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE ct_studenti
                     SET l104 = :l104
                     WHERE id_studente = :id_studente'
                );
                $stmt->execute([
                    'l104' => $l104,
                    'id_studente' => $existingStudentId,
                ]);
            }

            $check = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM ct_studenti_classi
                 WHERE fk_classe = :fk_classe
                   AND fk_studente = :fk_studente'
            );
            $check->execute([
                'fk_classe' => $classId,
                'fk_studente' => $existingStudentId,
            ]);

            if ((int) $check->fetchColumn() > 0) {
                return [
                    'status' => 'already_present',
                    'username' => (string) ($existingUser['username'] ?? ''),
                    'plain_password' => '',
                ];
            }

            $plainPassword = $this->generateRandomStudentPassword();
            $stmt = $pdo->prepare(
                'UPDATE ct_utenti
                 SET nome = :nome,
                     cognome = :cognome,
                     username = :username,
                     password = :password,
                     sesso = :sesso,
                     validato = 1,
                     fk_tipo_utente = 2
                 WHERE id_utente = :id_utente'
            );
            $stmt->execute([
                'nome' => $name,
                'cognome' => $surname,
                'username' => (string) ($existingUser['username'] ?? ''),
                'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
                'sesso' => $gender,
                'id_utente' => $existingUserId,
            ]);

            if (!$this->userHasStudentRole($pdo, $existingUserId)) {
                $roleStmt = $pdo->prepare(
                    'INSERT INTO ct_utenti_tipi (fk_utente, fk_tipo_utente)
                     VALUES (:fk_utente, 2)'
                );
                $roleStmt->execute(['fk_utente' => $existingUserId]);
            }

            $insertClass = $pdo->prepare(
                'INSERT INTO ct_studenti_classi (fk_classe, fk_studente)
                 VALUES (:fk_classe, :fk_studente)'
            );
            $insertClass->execute([
                'fk_classe' => $classId,
                'fk_studente' => $existingStudentId,
            ]);

            return [
                'status' => 'linked_existing',
                'username' => (string) ($existingUser['username'] ?? ''),
                'plain_password' => $plainPassword,
            ];
        }

        $username = $this->generateUsername($pdo, $name, $surname);
        $plainPassword = $this->generateRandomStudentPassword();

        $stmt = $pdo->prepare(
            'INSERT INTO ct_utenti (nome, cognome, email, username, password, validato, fk_tipo_utente, sesso)
             VALUES (:nome, :cognome, :email, :username, :password, 1, 2, :sesso)'
        );
        $stmt->execute([
            'nome' => $name,
            'cognome' => $surname,
            'email' => $email,
            'username' => $username,
            'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
            'sesso' => $gender,
        ]);
        $userId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            'INSERT INTO ct_utenti_tipi (fk_utente, fk_tipo_utente)
             VALUES (:fk_utente, 2)'
        );
        $stmt->execute(['fk_utente' => $userId]);

        $stmt = $pdo->prepare(
            'INSERT INTO ct_studenti (fk_utente, l104)
             VALUES (:fk_utente, :l104)'
        );
        $stmt->execute([
            'fk_utente' => $userId,
            'l104' => $l104,
        ]);
        $studentId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            'INSERT INTO ct_studenti_classi (fk_classe, fk_studente)
             VALUES (:fk_classe, :fk_studente)'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return [
            'status' => 'created',
            'username' => $username,
            'plain_password' => $plainPassword,
        ];
    }

    private function normalizeCsvRow(array $row): array
    {
        $name = trim((string) ($row[0] ?? ''));
        $surname = trim((string) ($row[1] ?? ''));
        $email = trim((string) ($row[2] ?? ''));
        $gender = strtoupper(trim((string) ($row[3] ?? 'M')));
        $l104 = $this->normalizeBooleanLikeValue((string) ($row[4] ?? '0')) ? 1 : 0;

        return [$name, $surname, $email, $gender, $l104];
    }

    private function normalizeBooleanLikeValue(string $value): bool
    {
        $normalized = strtolower(trim($value));
        return in_array($normalized, ['1', 'true', 'si', 'sì', 'yes', 'y'], true);
    }

    private function isCsvRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function generateRandomStudentPassword(): string
    {
        $animal = $this->passwordAnimals[array_rand($this->passwordAnimals)];
        return $animal . str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
    }

    private function sendStudentCredentialsEmail(string $email, string $username, string $plainPassword, string $name, string $surname): array
    {
        $studentName = trim($name . ' ' . $surname);
        $subject = $this->translator->translate('studmanage.mail.subject');
        $body = '<h1>' . $this->translator->translate('studmanage.mail.title') . '</h1>';
        $body .= '<p>' . sprintf($this->translator->translate('studmanage.mail.greeting'), htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8')) . '</p>';
        $body .= '<p>' . $this->translator->translate('studmanage.mail.credentials_updated') . '</p>';
        $body .= '<p><strong>Username:</strong> ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '<br>';
        $body .= '<strong>Password:</strong> ' . htmlspecialchars($plainPassword, ENT_QUOTES, 'UTF-8') . '</p>';
        $body .= '<p>' . $this->translator->translate('studmanage.mail.keep_safe') . '</p>';

        $mailService = new MailService();
        $sent = $mailService->sendMail($body, $subject, '', $email);

        return [
            'sent' => $sent,
            'error' => $sent ? '' : ($mailService->getLastError() !== '' ? $mailService->getLastError() : 'Unknown mail error'),
        ];
    }


    private function userCanBeManagedAsStudent(PDO $pdo, int $userId): bool
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :fk_utente
               AND tu.tipo_utente IN ("docente", "amministratore")'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return (int) $stmt->fetchColumn() === 0;
    }

    private function userHasStudentRole(PDO $pdo, int $userId): bool
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_tipi ut
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE ut.fk_utente = :fk_utente
               AND tu.tipo_utente = "studente"'
        );
        $stmt->execute(['fk_utente' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function generateUsername(PDO $pdo, string $name, string $surname): string
    {
        $normalizedName = preg_replace('/[^a-z0-9]/i', '', $name) ?? '';
        $normalizedSurname = preg_replace('/[^a-z0-9]/i', '', $surname) ?? '';
        $baseUsername = strtolower(substr($normalizedName, 0, 1) . $normalizedSurname);
        $baseUsername = $baseUsername !== '' ? $baseUsername : 'studente';
        $candidate = $baseUsername;
        $counter = 1;

        while ($this->usernameExists($pdo, $candidate)) {
            $counter++;
            $candidate = $baseUsername . $counter;
        }

        return $candidate;
    }

    private function usernameExists(PDO $pdo, string $username): bool
    {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ct_utenti WHERE username = :username');
        $stmt->execute(['username' => $username]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $status = $permissionService->checkPermissionsTeacher();

        return match ($status) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->translator->translate('studmanage.permission.session_expired')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->translator->translate('studmanage.permission.not_allowed')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->translator->translate('studmanage.permission.select_class_first')),
            PermissionService::STATUS_NOT_CLASS_OWNER => $this->error($this->translator->translate('studmanage.permission.not_class_owner')),
            default => null,
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            throw new \RuntimeException($this->translator->translate('studmanage.permission.class_not_selected'));
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
