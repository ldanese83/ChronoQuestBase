<?php

namespace App\Service;

use DateInterval;
use DateTimeImmutable;
use PDO;
use Throwable;

class StudentCommunicationsService
{
    private const ALERT_ICON_MAP = [
        'ValutazioneEsercizio' => ['bg-warning', 'fa-wrench'],
        'SaliLivello' => ['bg-success', 'fa-trophy'],
        'PersoCuori' => ['bg-danger', 'fa-heart-broken'],
        'Morte' => ['bg-danger', 'fa-ghost'],
        'MorteIstantanea' => ['bg-secondary', 'fa-skull'],
        'UsatoPotere' => ['bg-secondary', 'fa-bolt'],
        'OttieniBadge' => ['bg-info', 'fa-trophy'],
        'AttivazioneEsercizio' => ['bg-info', 'fa-book'],
        'PersonalizzazioneApprovata' => ['bg-success', 'fa-check'],
        'PersonalizzazioneRifiutata' => ['bg-danger', 'fa-times'],
        'InvitoSquadra' => ['bg-primary', 'fa-users'],
        'SquadraApprovata' => ['bg-success', 'fa-check'],
        'SquadraRifiutata' => ['bg-danger', 'fa-times'],
        'RispostaInvitoSquadra' => ['bg-warning', 'fa-user-check'],
        'PotereSquadra' => ['bg-warning', 'fa-bolt'],
        'RisultatoCorsa' => ['bg-success', 'fa-flag-checkered'],
    ];

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getTopbarData(): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return [
                'enabled' => false,
                'alerts' => [],
                'messages' => [],
                'alertsCount' => 0,
                'messagesCount' => 0,
                'classId' => null,
            ];
        }

        $classId = $this->getCurrentClassIdOrFail();
        $studentId = $this->getCurrentStudentIdOrFail();
        $alerts = $this->getUnreadAlerts($classId, $studentId);
        $messages = $this->getUnreadMessages($classId, $studentId);

        return [
            'enabled' => true,
            'alerts' => $alerts,
            'messages' => $messages,
            'alertsCount' => count($alerts),
            'messagesCount' => count($messages),
            'classId' => $classId,
        ];
    }

    public function getAlertsPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $base['alerts'] = $this->getAllAlerts(
            $this->getCurrentClassIdOrFail(),
            $this->getCurrentStudentIdOrFail()
        );

        return $base;
    }

    public function markAlertAsReadAndResolveLink(int $alertId): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        if ($alertId <= 0) {
            return $this->error($this->t('student.communications.alert.invalid'));
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_alert, link
             FROM ct_alerts
             WHERE id_alert = :id_alert
               AND fk_classe = :fk_classe
               AND fk_studente = :fk_studente
               AND doc_stud = 1
             LIMIT 1'
        );
        $stmt->execute([
            'id_alert' => $alertId,
            'fk_classe' => $this->getCurrentClassIdOrFail(),
            'fk_studente' => $this->getCurrentStudentIdOrFail(),
        ]);
        $alert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$alert) {
            return $this->error($this->t('student.communications.alert.not_found'));
        }

        $stmt = Database::getConnection()->prepare('UPDATE ct_alerts SET letto = 1 WHERE id_alert = :id_alert');
        $stmt->execute(['id_alert' => $alertId]);

        return [
            'success' => true,
            'redirect' => $this->resolveAlertLink((string) ($alert['link'] ?? '')),
        ];
    }

    public function markAllAlertsAsRead(): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_alerts
             SET letto = 1
             WHERE fk_classe = :fk_classe
               AND fk_studente = :fk_studente
               AND doc_stud = 1'
        );
        $stmt->execute([
            'fk_classe' => $this->getCurrentClassIdOrFail(),
            'fk_studente' => $this->getCurrentStudentIdOrFail(),
        ]);

        return [
            'success' => true,
            'message' => $this->t('student.communications.alert.mark_all_read.success'),
        ];
    }

    public function deleteAlert(int $alertId): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        if ($alertId <= 0) {
            return $this->error($this->t('student.communications.alert.invalid'));
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_alerts
             WHERE id_alert = :id_alert
               AND fk_classe = :fk_classe
               AND fk_studente = :fk_studente
               AND doc_stud = 1'
        );
        $stmt->execute([
            'id_alert' => $alertId,
            'fk_classe' => $this->getCurrentClassIdOrFail(),
            'fk_studente' => $this->getCurrentStudentIdOrFail(),
        ]);

        if ($stmt->rowCount() < 1) {
            return $this->error($this->t('student.communications.alert.not_found_or_deleted'));
        }

        return [
            'success' => true,
            'message' => $this->t('student.communications.alert.delete.success'),
        ];
    }

    public function getMessagesPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $base['messages'] = $this->getAllMessages(
            $this->getCurrentClassIdOrFail(),
            $this->getCurrentStudentIdOrFail()
        );

        return $base;
    }

    public function getComposePageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $base['teachers'] = $this->getClassTeachers($classId);

        return $base;
    }

    public function getMessageDetailPageData(int $messageId): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $studentId = $this->getCurrentStudentIdOrFail();
        $message = $this->getStudentMessageDetail($messageId, $studentId);
        if ($message === null) {
            Flash::add('danger', $this->t('student.communications.message.not_found'));
            header('Location: /studenti/messages');
            exit;
        }

        $this->markMessageAsRead($messageId, $studentId);

        $base['message'] = $message;
        $base['conversation'] = $this->getConversationHistory($message);

        return $base;
    }

    public function sendNewMessage(array $input): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $teacherId = isset($input['profRisposta']) ? (int) $input['profRisposta'] : 0;
        $subject = trim((string) ($input['oggettoRisposta'] ?? ''));
        $body = trim((string) ($input['testoRisposta'] ?? ''));

        if ($teacherId <= 0) {
            return $this->error($this->t('student.communications.message.teacher_required'));
        }

        if ($subject === '' || $body === '') {
            return $this->error($this->t('student.communications.message.subject_body_required'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        if (!$this->teacherBelongsToClass($teacherId, $classId)) {
            return $this->error($this->t('student.communications.message.teacher_not_in_class'));
        }

        return $this->storeStudentOutgoingMessage($teacherId, $subject, $body, 0);
    }

    public function replyToMessage(int $messageId, array $input): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $studentId = $this->getCurrentStudentIdOrFail();
        $message = $this->getStudentMessageDetail($messageId, $studentId);
        if ($message === null) {
            return $this->error($this->t('student.communications.message.not_found'));
        }

        $subject = trim((string) ($input['oggettoRisposta'] ?? ''));
        $body = trim((string) ($input['testoRisposta'] ?? ''));

        if ($subject === '' || $body === '') {
            return $this->error($this->t('student.communications.message.reply_subject_body_required'));
        }

        return $this->storeStudentOutgoingMessage((int) $message['teacher_id'], $subject, $body, $messageId);
    }

    public function deleteMessages(array $ids): array
    {
        $guard = $this->guardStudentClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $ids = array_values(array_filter(array_map('intval', $ids), static fn (int $id): bool => $id > 0));
        if ($ids === []) {
            return $this->error($this->t('student.communications.message.none_selected'));
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql =
            'UPDATE ct_messages
             SET eliminato = 1
             WHERE id_messaggio IN (' . $placeholders . ')
               AND fk_studente = ?
               AND fk_classe = ?
               AND doc_stud = 1';

        $stmt = Database::getConnection()->prepare($sql);
        $position = 1;
        foreach ($ids as $id) {
            $stmt->bindValue($position++, $id, PDO::PARAM_INT);
        }
        $stmt->bindValue($position++, $this->getCurrentStudentIdOrFail(), PDO::PARAM_INT);
        $stmt->bindValue($position, $this->getCurrentClassIdOrFail(), PDO::PARAM_INT);
        $stmt->execute();

        return [
            'success' => true,
            'message' => $this->t('student.communications.message.delete.success'),
            'deleted' => $stmt->rowCount(),
        ];
    }

    private function baseProtectedPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsStudent();
        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
            'student' => null,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        $userId = $permissionService->getCurrentUserId();
        if ($classId === null || $userId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, c.colore, c.icona, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);
        $classroom = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$classroom) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente, u.nome, u.cognome, u.email, u.username
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE u.id_utente = :id_utente
               AND sc.fk_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute([
            'id_utente' => $userId,
            'id_classe' => $classId,
        ]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            $data['permissionStatus'] = PermissionService::STATUS_NOT_CLASS_OWNER;
            return $data;
        }

        $data['classroom'] = $classroom;
        $data['student'] = $student;

        return $data;
    }

    private function getUnreadAlerts(int $classId, int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_alert, testo, data_alert, tipologia, link
             FROM ct_alerts
             WHERE fk_classe = :fk_classe
               AND fk_studente = :fk_studente
               AND letto = 0
               AND doc_stud = 1
             ORDER BY data_alert DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return $this->mapAlerts($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function getAllAlerts(int $classId, int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_alert, testo, data_alert, tipologia, link, letto
             FROM ct_alerts
             WHERE fk_classe = :fk_classe
               AND fk_studente = :fk_studente
               AND doc_stud = 1
             ORDER BY data_alert DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return $this->mapAlerts($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function mapAlerts(array $rows): array
    {
        return array_map(function (array $row): array {
            [$bgClass, $iconClass] = self::ALERT_ICON_MAP[(string) ($row['tipologia'] ?? '')] ?? ['bg-primary', 'fa-bell'];

            return [
                'id' => (int) $row['id_alert'],
                'text' => html_entity_decode((string) ($row['testo'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'date' => (string) ($row['data_alert'] ?? ''),
                'dateLabel' => $this->formatDateLabel((string) ($row['data_alert'] ?? '')),
                'type' => (string) ($row['tipologia'] ?? ''),
                'link' => $this->resolveAlertLink((string) ($row['link'] ?? '')),
                'isRead' => isset($row['letto']) ? (int) $row['letto'] === 1 : false,
                'iconBgClass' => $bgClass,
                'iconClass' => $iconClass,
            ];
        }, $rows);
    }

    private function getUnreadMessages(int $classId, int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_messaggio,
                    m.oggetto_messaggio,
                    m.data_messaggio,
                    u.nome,
                    u.cognome,
                    u.sesso
             FROM ct_messages m
             INNER JOIN ct_utenti u ON u.id_utente = m.fk_docente
             WHERE m.fk_classe = :fk_classe
               AND m.fk_studente = :fk_studente
               AND m.letto = 0
               AND m.doc_stud = 1
               AND m.eliminato = 0
             ORDER BY m.data_messaggio DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return $this->mapMessages($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [], $this->t('student.communications.teacher_prefix'));
    }

    private function getAllMessages(int $classId, int $studentId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_messaggio,
                    m.oggetto_messaggio,
                    m.data_messaggio,
                    m.letto,
                    u.nome,
                    u.cognome,
                    u.sesso
             FROM ct_messages m
             INNER JOIN ct_utenti u ON u.id_utente = m.fk_docente
             WHERE m.fk_classe = :fk_classe
               AND m.fk_studente = :fk_studente
               AND m.doc_stud = 1
               AND m.eliminato = 0
             ORDER BY m.data_messaggio DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_studente' => $studentId,
        ]);

        return $this->mapMessages($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [], $this->t('student.communications.teacher_prefix'));
    }

    private function mapMessages(array $rows, string $senderPrefix = ''): array
    {
        return array_map(function (array $row) use ($senderPrefix): array {
            $gender = (string) ($row['sesso'] ?? 'M');

            return [
                'id' => (int) $row['id_messaggio'],
                'subject' => html_entity_decode((string) ($row['oggetto_messaggio'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'date' => (string) ($row['data_messaggio'] ?? ''),
                'dateLabel' => $this->formatDateTime((string) ($row['data_messaggio'] ?? '')),
                'relativeTime' => $this->formatRelativeTime((string) ($row['data_messaggio'] ?? '')),
                'senderName' => $senderPrefix . trim(((string) ($row['nome'] ?? '')) . ' ' . ((string) ($row['cognome'] ?? ''))),
                'avatar' => $gender === 'F' ? '/assets/images/undraw_profile_1.svg' : '/assets/images/undraw_profile_2.svg',
                'isRead' => isset($row['letto']) ? (int) $row['letto'] === 1 : false,
            ];
        }, $rows);
    }

    private function getStudentMessageDetail(int $messageId, int $studentId): ?array
    {
        if ($messageId <= 0) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_messaggio,
                    m.oggetto_messaggio,
                    m.testo_messaggio,
                    m.data_messaggio,
                    m.fk_last_msg_rel,
                    m.fk_docente,
                    m.fk_classe,
                    u.nome,
                    u.cognome,
                    u.email,
                    u.sesso
             FROM ct_messages m
             INNER JOIN ct_utenti u ON u.id_utente = m.fk_docente
             WHERE m.id_messaggio = :id_messaggio
               AND m.fk_studente = :fk_studente
               AND m.doc_stud = 1
               AND m.eliminato = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_messaggio' => $messageId,
            'fk_studente' => $studentId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row['id_messaggio'],
            'subject' => html_entity_decode((string) $row['oggetto_messaggio'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'body' => html_entity_decode((string) $row['testo_messaggio'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'date' => (string) $row['data_messaggio'],
            'teacher_name' => $this->t('student.communications.teacher_prefix') . trim(((string) $row['nome']) . ' ' . ((string) $row['cognome'])),
            'teacher_email' => (string) $row['email'],
            'teacher_id' => (int) $row['fk_docente'],
            'class_id' => (int) $row['fk_classe'],
            'last_related_id' => (int) $row['fk_last_msg_rel'],
        ];
    }

    private function getConversationHistory(array $message): array
    {
        $history = [];
        $currentId = (int) ($message['last_related_id'] ?? 0);

        while ($currentId > 0) {
            $entry = $this->getConversationMessage($currentId);
            if ($entry === null) {
                break;
            }

            $history[] = $entry;
            $currentId = (int) ($entry['last_related_id'] ?? 0);
        }

        return $history;
    }

    private function getConversationMessage(int $messageId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_messaggio,
                    m.testo_messaggio,
                    m.data_messaggio,
                    m.doc_stud,
                    m.fk_last_msg_rel,
                    su.nome AS student_name,
                    su.cognome AS student_surname,
                    du.nome AS teacher_name,
                    du.cognome AS teacher_surname
             FROM ct_messages m
             LEFT JOIN ct_studenti s ON s.id_studente = m.fk_studente
             LEFT JOIN ct_utenti su ON su.id_utente = s.fk_utente
             LEFT JOIN ct_utenti du ON du.id_utente = m.fk_docente
             WHERE m.id_messaggio = :id_messaggio
             LIMIT 1'
        );
        $stmt->execute(['id_messaggio' => $messageId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $isTeacher = (int) ($row['doc_stud'] ?? 0) === 1;
        $senderName = $isTeacher
            ? $this->t('student.communications.teacher_prefix') . trim(((string) ($row['teacher_name'] ?? '')) . ' ' . ((string) ($row['teacher_surname'] ?? '')))
            : trim(((string) ($row['student_name'] ?? '')) . ' ' . ((string) ($row['student_surname'] ?? '')));

        return [
            'id' => (int) $row['id_messaggio'],
            'sender_name' => $senderName,
            'sender_type' => $isTeacher ? 'docente' : 'studente',
            'date' => (string) $row['data_messaggio'],
            'body' => html_entity_decode((string) ($row['testo_messaggio'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'last_related_id' => (int) ($row['fk_last_msg_rel'] ?? 0),
        ];
    }

    private function markMessageAsRead(int $messageId, int $studentId): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_messages
             SET letto = 1
             WHERE id_messaggio = :id_messaggio
               AND fk_studente = :fk_studente'
        );
        $stmt->execute([
            'id_messaggio' => $messageId,
            'fk_studente' => $studentId,
        ]);
    }

    private function storeStudentOutgoingMessage(int $teacherId, string $subject, string $body, int $lastRelatedId): array
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $mailService = new MailService();

            $stmt = $pdo->prepare(
                'INSERT INTO ct_messages (testo_messaggio, fk_docente, fk_studente, fk_classe, data_messaggio, letto, doc_stud, fk_last_msg_rel, oggetto_messaggio)
                 VALUES (:testo_messaggio, :fk_docente, :fk_studente, :fk_classe, :data_messaggio, 0, 0, :fk_last_msg_rel, :oggetto_messaggio)'
            );
            $stmt->execute([
                'testo_messaggio' => $body,
                'fk_docente' => $teacherId,
                'fk_studente' => $this->getCurrentStudentIdOrFail(),
                'fk_classe' => $this->getCurrentClassIdOrFail(),
                'data_messaggio' => date('Y-m-d H:i:s'),
                'fk_last_msg_rel' => $lastRelatedId,
                'oggetto_messaggio' => htmlentities($subject, ENT_QUOTES, 'UTF-8'),
            ]);

            $mailAttempts = 0;
            $teacher = $this->getTeacherRecipient($teacherId);
            if ($teacher !== null && (int) ($teacher['ricevi_mail'] ?? 0) === 1 && (string) ($teacher['email'] ?? '') !== '') {
                $mailAttempts++;
                $student = $this->getCurrentStudentUser();
                $studentName = trim(((string) ($student['nome'] ?? '')) . ' ' . ((string) ($student['cognome'] ?? '')));
                $mailSent = $mailService->sendMail(
                    sprintf($this->t('student.communications.mail.new_message.body'), htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'), $body),
                    $subject,
                    '',
                    (string) ($teacher['email'] ?? '')
                );
                if (!$mailSent) {
                    throw new \RuntimeException(sprintf(
                        $this->t('student.communications.mail.send_failed'),
                        (string) ($teacher['email'] ?? ''),
                        $mailService->getLastError() !== '' ? $mailService->getLastError() : $this->t('student.communications.mail.unknown_error')
                    ));
                }
            }

            $pdo->commit();

            return [
                'success' => true,
                'message' => $mailAttempts > 0
                    ? $this->t('student.communications.message.send.success')
                    : $this->t('student.communications.message.send.no_external_email'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error(sprintf($this->t('student.communications.message.send.error'), $exception->getMessage()));
        }
    }

    private function getClassTeachers(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT DISTINCT u.id_utente, u.nome, u.cognome
             FROM ct_utenti u
             INNER JOIN ct_utenti_classi uc ON uc.fk_utente = u.id_utente
             INNER JOIN ct_utenti_tipi ut ON ut.fk_utente = u.id_utente
             INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente
             WHERE uc.fk_classe = :fk_classe
               AND tu.tipo_utente IN ("docente", "amministratore")
             ORDER BY u.cognome, u.nome'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id_utente'],
                'name' => trim(((string) ($row['nome'] ?? '')) . ' ' . ((string) ($row['cognome'] ?? ''))),
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function teacherBelongsToClass(int $teacherId, int $classId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*)
             FROM ct_utenti_classi
             WHERE fk_utente = :fk_utente
               AND fk_classe = :fk_classe'
        );
        $stmt->execute([
            'fk_utente' => $teacherId,
            'fk_classe' => $classId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function getTeacherRecipient(int $teacherId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT email, ricevi_mail
             FROM ct_utenti
             WHERE id_utente = :id_utente
             LIMIT 1'
        );
        $stmt->execute(['id_utente' => $teacherId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getCurrentStudentUser(): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.nome, u.cognome
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $this->getCurrentStudentIdOrFail()]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getCurrentStudentIdOrFail(): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT s.id_studente
             FROM ct_studenti s
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             WHERE s.fk_utente = :fk_utente
               AND sc.fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_utente' => $this->getCurrentUserIdOrFail(),
            'fk_classe' => $this->getCurrentClassIdOrFail(),
        ]);
        $studentId = (int) ($stmt->fetchColumn() ?: 0);

        if ($studentId <= 0) {
            throw new \RuntimeException($this->t('student.communications.student_not_found'));
        }

        return $studentId;
    }

    private function resolveAlertLink(string $link): string
    {
        $link = trim($link);
        if ($link === '') {
            return '/studenti/alerts';
        }

        if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://') || str_starts_with($link, '/')) {
            return $link;
        }

        return match ($link) {
            'all_alerts_studenti.php' => '/studenti/alerts',
            'all_messages_studente.php' => '/studenti/messages',
            'invia_messaggio_docente.php' => '/studenti/messages/new',
            'classe_studente.php' => '/studenti/classe/dashboard',
            'student_quest.php' => '/studenti/classe/dashboard',
            'student_powers.php' => '/studenti/poteri',
            'student_power_add.php' => '/studenti/poteri/aggiungi',
            'personalizza_personaggio.php' => '/studenti/classe/dashboard',
            'classmates.php' => '/studenti/classe/dashboard',
            'squadra_studente.php' => '/studenti/classe/dashboard',
            'forzieri_vinti.php' => '/studenti/forzieri',
            'student_punishment.php' => '/studenti/classe/dashboard',
            default => '/studenti/classe/dashboard',
        };
    }

    private function formatDateLabel(string $dateTime): string
    {
        try {
            return (new DateTimeImmutable($dateTime))->format('d/m/Y H:i');
        } catch (Throwable) {
            return $dateTime;
        }
    }

    private function formatDateTime(string $dateTime): string
    {
        try {
            return (new DateTimeImmutable($dateTime))->format('Y-m-d H:i');
        } catch (Throwable) {
            return $dateTime;
        }
    }

    private function formatRelativeTime(string $dateTime): string
    {
        try {
            $sentAt = new DateTimeImmutable($dateTime);
            $now = new DateTimeImmutable('now');
            return $this->italianInterval($sentAt->diff($now));
        } catch (Throwable) {
            return '';
        }
    }

    private function italianInterval(DateInterval $diff): string
    {
        $parts = [];

        if ($diff->h > 0) {
            $parts[] = sprintf($this->t($diff->h > 1 ? 'student.communications.relative.hours' : 'student.communications.relative.hour'), $diff->h);
        }

        if ($diff->i > 0) {
            $parts[] = sprintf($this->t($diff->i > 1 ? 'student.communications.relative.minutes' : 'student.communications.relative.minute'), $diff->i);
        }

        if ($diff->d > 0 && $parts === []) {
            $parts[] = sprintf($this->t($diff->d > 1 ? 'student.communications.relative.days' : 'student.communications.relative.day'), $diff->d);
        }

        if ($parts === []) {
            $parts[] = $this->t('student.communications.relative.few_seconds');
        }

        return sprintf($this->t('student.communications.relative.ago'), implode(' ' . $this->t('student.communications.relative.join') . ' ', $parts));
    }

    private function guardStudentClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $status = $permissionService->checkPermissionsStudent();

        if ($status === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($status) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->t('student.communications.session_expired')),
            PermissionService::STATUS_NOT_STUDENT => $this->error($this->t('student.communications.permission_denied')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->t('student.communications.class_required')),
            PermissionService::STATUS_NOT_CLASS_OWNER => $this->error($this->t('student.communications.class_access_denied')),
            default => $this->error($this->t('student.communications.operation_not_allowed')),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            throw new \RuntimeException($this->t('student.communications.class_not_selected'));
        }

        return $classId;
    }

    private function getCurrentUserIdOrFail(): int
    {
        $userId = (new PermissionService())->getCurrentUserId();
        if ($userId === null) {
            throw new \RuntimeException($this->t('student.communications.user_not_authenticated'));
        }

        return $userId;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    private function t(string $key): string
    {
        return $this->translator->translate($key);
    }
}
