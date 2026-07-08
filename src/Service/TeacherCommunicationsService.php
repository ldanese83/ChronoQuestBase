<?php

namespace App\Service;

use DateInterval;
use DateTimeImmutable;
use PDO;
use Throwable;

class TeacherCommunicationsService
{
    private const ALERT_ICON_MAP = [
        'Esercizi' => ['bg-success', 'fa-donate'],
        'UsatoPotere' => ['bg-secondary', 'fa-bolt'],
        'Punizioni' => ['bg-warning', 'fa-handshake'],
        'Personalizzazioni' => ['bg-info', 'fa-palette'],
        'SquadreStudenti' => ['bg-primary', 'fa-users'],
    ];

    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getTopbarData(): array
    {
        $guard = $this->guardTeacherClassAccess();
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
        $teacherId = $this->getCurrentTeacherIdOrFail();
        $alerts = $this->getUnreadAlerts($classId);
        $messages = $this->getUnreadMessages($classId, $teacherId);

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

        $classId = $this->getCurrentClassIdOrFail();
        $base['alerts'] = $this->getAllAlerts($classId);

        return $base;
    }

    public function markAlertAsReadAndResolveLink(int $alertId): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        if ($alertId <= 0) {
            return $this->error($this->t('teacher.communications.alert.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT id_alert, link
             FROM ct_alerts
             WHERE id_alert = :id_alert
               AND fk_classe = :fk_classe
               AND doc_stud = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_alert' => $alertId,
            'fk_classe' => $classId,
        ]);
        $alert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$alert) {
            return $this->error($this->t('teacher.communications.alert.not_found'));
        }

        $stmt = $pdo->prepare('UPDATE ct_alerts SET letto = 1 WHERE id_alert = :id_alert');
        $stmt->execute(['id_alert' => $alertId]);

        return [
            'success' => true,
            'redirect' => $this->resolveAlertLink((string) ($alert['link'] ?? '')),
        ];
    }

    public function markAllAlertsAsRead(): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_alerts
             SET letto = 1
             WHERE fk_classe = :fk_classe
               AND doc_stud = 0'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return [
            'success' => true,
            'message' => $this->t('teacher.communications.alert.mark_all_read.success'),
        ];
    }

    public function deleteAlert(int $alertId): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        if ($alertId <= 0) {
            return $this->error($this->t('teacher.communications.alert.invalid'));
        }

        $classId = $this->getCurrentClassIdOrFail();
        $stmt = Database::getConnection()->prepare(
            'DELETE FROM ct_alerts
             WHERE id_alert = :id_alert
               AND fk_classe = :fk_classe
               AND doc_stud = 0'
        );
        $stmt->execute([
            'id_alert' => $alertId,
            'fk_classe' => $classId,
        ]);

        if ($stmt->rowCount() < 1) {
            return $this->error($this->t('teacher.communications.alert.not_found_or_deleted'));
        }

        return [
            'success' => true,
            'message' => $this->t('teacher.communications.alert.delete.success'),
        ];
    }

    public function getMessagesPageData(): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $classId = $this->getCurrentClassIdOrFail();
        $teacherId = $this->getCurrentTeacherIdOrFail();
        $base['messages'] = $this->getAllMessages($classId, $teacherId);

        return $base;
    }

    public function getMessageDetailPageData(int $messageId): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $teacherId = $this->getCurrentTeacherIdOrFail();
        $message = $this->getTeacherMessageDetail($messageId, $teacherId);
        if ($message === null) {
            Flash::add('danger', $this->t('teacher.communications.message.not_found'));
            header('Location: /docenti/messages');
            exit;
        }

        $this->markMessageAsRead($messageId, $teacherId);

        $base['message'] = $message;
        $base['conversation'] = $this->getConversationHistory($message);

        return $base;
    }

    public function getComposeBulkPageData(array $studentIds): array
    {
        $base = $this->baseProtectedPageData();
        if (($base['permissionStatus'] ?? null) !== PermissionService::STATUS_OK) {
            return $base;
        }

        $teacherId = $this->getCurrentTeacherIdOrFail();
        $classId = $this->getCurrentClassIdOrFail();
        $base['selectedStudents'] = $this->getClassStudentsByIds($classId, $teacherId, $studentIds);
        $base['selectedStudentIds'] = array_map(
            static fn (array $row): int => (int) ($row['id'] ?? 0),
            $base['selectedStudents']
        );

        return $base;
    }

    public function sendBulkMessage(array $studentIds, array $input): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $studentIds = array_values(array_unique(array_filter(array_map('intval', $studentIds), static fn (int $id): bool => $id > 0)));
        if ($studentIds === []) {
            return $this->error($this->t('teacher.communications.message.student_required'));
        }

        $subject = trim((string) ($input['oggettoRisposta'] ?? ''));
        $body = trim((string) ($input['testoRisposta'] ?? ''));
        if ($subject === '' || $body === '') {
            return $this->error($this->t('teacher.communications.message.subject_body_required'));
        }

        $teacherId = $this->getCurrentTeacherIdOrFail();
        $classId = $this->getCurrentClassIdOrFail();
        $recipients = $this->getClassStudentsByIds($classId, $teacherId, $studentIds);
        if ($recipients === []) {
            return $this->error($this->t('teacher.communications.message.no_valid_students'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $mailService = new MailService();

            $stmt = $pdo->prepare(
                'INSERT INTO ct_messages (testo_messaggio, fk_docente, fk_studente, fk_classe, data_messaggio, letto, doc_stud, fk_last_msg_rel, oggetto_messaggio)
                 VALUES (:testo_messaggio, :fk_docente, :fk_studente, :fk_classe, :data_messaggio, 0, 1, 0, :oggetto_messaggio)'
            );

            $now = date('Y-m-d H:i:s');
            $mailAttempts = 0;
            foreach ($recipients as $recipient) {
                $stmt->execute([
                    'testo_messaggio' => $body,
                    'fk_docente' => $teacherId,
                    'fk_studente' => (int) $recipient['id'],
                    'fk_classe' => $classId,
                    'data_messaggio' => $now,
                    'oggetto_messaggio' => htmlentities($subject, ENT_QUOTES, 'UTF-8'),
                ]);

                if ((int) ($recipient['receive_mail'] ?? 0) === 1 && (string) ($recipient['email'] ?? '') !== '') {
                    $mailAttempts++;
                    $teacher = $this->getTeacherUser((int) $teacherId);
                    $teacherSurname = (string) ($teacher['cognome'] ?? '');
                    $mailSent = $mailService->sendMail(
                        sprintf($this->t('teacher.communications.mail.new_message.body'), htmlspecialchars($teacherSurname, ENT_QUOTES, 'UTF-8'), $body),
                        $subject,
                        '',
                        (string) $recipient['email']
                    );
                    if (!$mailSent) {
                        throw new \RuntimeException(sprintf(
                            $this->t('teacher.communications.mail.send_failed'),
                            (string) $recipient['email'],
                            $mailService->getLastError() !== '' ? $mailService->getLastError() : $this->t('teacher.communications.mail.unknown_error')
                        ));
                    }
                }
            }

            $pdo->commit();

            return [
                'success' => true,
                'message' => $mailAttempts > 0
                    ? $this->t('teacher.communications.message.bulk_send.success')
                    : $this->t('teacher.communications.message.bulk_send.no_external_email'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error(sprintf($this->t('teacher.communications.message.bulk_send.error'), $exception->getMessage()));
        }
    }

    public function replyToMessage(int $messageId, array $input): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $teacherId = $this->getCurrentTeacherIdOrFail();
        $message = $this->getTeacherMessageDetail($messageId, $teacherId);
        if ($message === null) {
            return $this->error($this->t('teacher.communications.message.not_found'));
        }

        $subject = trim((string) ($input['oggettoRisposta'] ?? ''));
        $body = trim((string) ($input['testoRisposta'] ?? ''));

        if ($subject === '' || $body === '') {
            return $this->error($this->t('teacher.communications.message.reply_subject_body_required'));
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();
            $mailService = new MailService();

            $stmt = $pdo->prepare(
                'INSERT INTO ct_messages (testo_messaggio, fk_docente, fk_studente, fk_classe, data_messaggio, letto, doc_stud, fk_last_msg_rel, oggetto_messaggio)
                 VALUES (:testo_messaggio, :fk_docente, :fk_studente, :fk_classe, :data_messaggio, 0, 1, :fk_last_msg_rel, :oggetto_messaggio)'
            );
            $stmt->execute([
                'testo_messaggio' => $body,
                'fk_docente' => $teacherId,
                'fk_studente' => (int) $message['student_id'],
                'fk_classe' => (int) $message['class_id'],
                'data_messaggio' => date('Y-m-d H:i:s'),
                'fk_last_msg_rel' => $messageId,
                'oggetto_messaggio' => htmlentities($subject, ENT_QUOTES, 'UTF-8'),
            ]);

            $student = $this->getStudentRecipient((int) $message['student_id']);
            if ($student !== null && (int) ($student['ricevi_mail'] ?? 0) === 1) {
                $teacher = $this->getTeacherUser((int) $teacherId);
                $teacherSurname = (string) ($teacher['cognome'] ?? '');
                $mailSent = $mailService->sendMail(
                    sprintf($this->t('teacher.communications.mail.new_message.body'), htmlspecialchars($teacherSurname, ENT_QUOTES, 'UTF-8'), $body),
                    $subject,
                    '',
                    (string) ($student['email'] ?? '')
                );
                if (!$mailSent) {
                    throw new \RuntimeException(sprintf(
                        $this->t('teacher.communications.mail.send_failed'),
                        (string) ($student['email'] ?? ''),
                        $mailService->getLastError() !== '' ? $mailService->getLastError() : $this->t('teacher.communications.mail.unknown_error')
                    ));
                }
            }

            $pdo->commit();

            return [
                'success' => true,
                'message' => $this->t('teacher.communications.message.send.success'),
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error(sprintf($this->t('teacher.communications.message.reply.error'), $exception->getMessage()));
        }
    }

    public function deleteMessages(array $ids): array
    {
        $guard = $this->guardTeacherClassAccess();
        if ($guard !== null) {
            return $guard;
        }

        $ids = array_values(array_filter(array_map('intval', $ids), static fn (int $id): bool => $id > 0));
        if ($ids === []) {
            return $this->error($this->t('teacher.communications.message.none_selected'));
        }

        $teacherId = $this->getCurrentTeacherIdOrFail();
        $classId = $this->getCurrentClassIdOrFail();
        $pdo = Database::getConnection();
        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql =
            'UPDATE ct_messages
             SET eliminato = 1
             WHERE id_messaggio IN (' . $placeholders . ')
               AND fk_docente = ?
               AND fk_classe = ?
               AND doc_stud = 0';

        $stmt = $pdo->prepare($sql);
        $position = 1;
        foreach ($ids as $id) {
            $stmt->bindValue($position++, $id, PDO::PARAM_INT);
        }
        $stmt->bindValue($position++, $teacherId, PDO::PARAM_INT);
        $stmt->bindValue($position, $classId, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'success' => true,
            'message' => $this->t('teacher.communications.message.delete.success'),
            'deleted' => $stmt->rowCount(),
        ];
    }

    private function baseProtectedPageData(): array
    {
        $permissionService = new PermissionService();
        $permissionStatus = $permissionService->checkPermissionsTeacher();
        $data = [
            'permissionStatus' => $permissionStatus,
            'classroom' => null,
        ];

        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $data;
        }

        $classId = $permissionService->getCurrentClassId();
        if ($classId === null) {
            $data['permissionStatus'] = PermissionService::STATUS_NO_CLASS;
            return $data;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
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

        $data['classroom'] = $classroom;

        return $data;
    }

    private function getUnreadAlerts(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_alert, testo, data_alert, tipologia, link
             FROM ct_alerts
             WHERE fk_classe = :fk_classe
               AND letto = 0
               AND doc_stud = 0
             ORDER BY data_alert DESC'
        );
        $stmt->execute(['fk_classe' => $classId]);

        return $this->mapAlerts($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function getAllAlerts(int $classId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_alert, testo, data_alert, tipologia, link, letto
             FROM ct_alerts
             WHERE fk_classe = :fk_classe
               AND doc_stud = 0
             ORDER BY data_alert DESC'
        );
        $stmt->execute(['fk_classe' => $classId]);

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

    private function getUnreadMessages(int $classId, int $teacherId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT m.id_messaggio,
                    m.oggetto_messaggio,
                    m.data_messaggio,
                    u.nome,
                    u.cognome,
                    u.sesso
             FROM ct_messages m
             INNER JOIN ct_studenti s ON s.id_studente = m.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE m.fk_classe = :fk_classe
               AND m.fk_docente = :fk_docente
               AND m.letto = 0
               AND m.doc_stud = 0
               AND m.eliminato = 0
             ORDER BY m.data_messaggio DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_docente' => $teacherId,
        ]);

        return $this->mapMessages($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function getAllMessages(int $classId, int $teacherId): array
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
             INNER JOIN ct_studenti s ON s.id_studente = m.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE m.fk_classe = :fk_classe
               AND m.fk_docente = :fk_docente
               AND m.doc_stud = 0
               AND m.eliminato = 0
             ORDER BY m.data_messaggio DESC'
        );
        $stmt->execute([
            'fk_classe' => $classId,
            'fk_docente' => $teacherId,
        ]);

        return $this->mapMessages($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    private function mapMessages(array $rows): array
    {
        return array_map(function (array $row): array {
            $gender = (string) ($row['sesso'] ?? 'M');

            return [
                'id' => (int) $row['id_messaggio'],
                'subject' => html_entity_decode((string) ($row['oggetto_messaggio'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'date' => (string) ($row['data_messaggio'] ?? ''),
                'dateLabel' => $this->formatDateTime((string) ($row['data_messaggio'] ?? '')),
                'relativeTime' => $this->formatRelativeTime((string) ($row['data_messaggio'] ?? '')),
                'senderName' => trim(((string) ($row['nome'] ?? '')) . ' ' . ((string) ($row['cognome'] ?? ''))),
                'avatar' => $gender === 'F' ? '/assets/images/undraw_profile_1.svg' : '/assets/images/undraw_profile_2.svg',
                'isRead' => isset($row['letto']) ? (int) $row['letto'] === 1 : false,
            ];
        }, $rows);
    }

    private function getTeacherMessageDetail(int $messageId, int $teacherId): ?array
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
                    m.fk_studente,
                    m.fk_classe,
                    u.nome,
                    u.cognome,
                    u.email,
                    u.sesso
             FROM ct_messages m
             INNER JOIN ct_studenti s ON s.id_studente = m.fk_studente
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE m.id_messaggio = :id_messaggio
               AND m.fk_docente = :fk_docente
               AND m.doc_stud = 0
               AND m.eliminato = 0
             LIMIT 1'
        );
        $stmt->execute([
            'id_messaggio' => $messageId,
            'fk_docente' => $teacherId,
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
            'student_name' => trim(((string) $row['nome']) . ' ' . ((string) $row['cognome'])),
            'student_email' => (string) $row['email'],
            'student_id' => (int) $row['fk_studente'],
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
            ? trim(((string) ($row['teacher_name'] ?? '')) . ' ' . ((string) ($row['teacher_surname'] ?? '')))
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

    private function markMessageAsRead(int $messageId, int $teacherId): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE ct_messages
             SET letto = 1
             WHERE id_messaggio = :id_messaggio
               AND fk_docente = :fk_docente'
        );
        $stmt->execute([
            'id_messaggio' => $messageId,
            'fk_docente' => $teacherId,
        ]);
    }

    private function getStudentRecipient(int $studentId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT u.email, u.ricevi_mail
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             WHERE s.id_studente = :id_studente
             LIMIT 1'
        );
        $stmt->execute(['id_studente' => $studentId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getClassStudentsByIds(int $classId, int $teacherId, array $studentIds): array
    {
        $studentIds = array_values(array_unique(array_filter(array_map('intval', $studentIds), static fn (int $id): bool => $id > 0)));
        if ($studentIds === []) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($studentIds), '?'));
        $sql =
            'SELECT s.id_studente,
                    u.nome,
                    u.cognome,
                    u.email,
                    u.ricevi_mail
             FROM ct_studenti s
             INNER JOIN ct_utenti u ON u.id_utente = s.fk_utente
             INNER JOIN ct_studenti_classi sc ON sc.fk_studente = s.id_studente
             INNER JOIN ct_utenti_classi uc ON uc.fk_classe = sc.fk_classe
             WHERE sc.fk_classe = ?
               AND uc.fk_utente = ?
               AND s.id_studente IN (' . $placeholders . ')
             ORDER BY u.cognome, u.nome';

        $stmt = Database::getConnection()->prepare($sql);
        $position = 1;
        $stmt->bindValue($position++, $classId, PDO::PARAM_INT);
        $stmt->bindValue($position++, $teacherId, PDO::PARAM_INT);
        foreach ($studentIds as $studentId) {
            $stmt->bindValue($position++, $studentId, PDO::PARAM_INT);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id_studente'] ?? 0),
                'name' => trim(((string) ($row['nome'] ?? '')) . ' ' . ((string) ($row['cognome'] ?? ''))),
                'email' => (string) ($row['email'] ?? ''),
                'receive_mail' => (int) ($row['ricevi_mail'] ?? 0),
            ];
        }, $rows);
    }

    private function getTeacherUser(int $teacherId): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT nome, cognome FROM ct_utenti WHERE id_utente = :id_utente LIMIT 1');
        $stmt->execute(['id_utente' => $teacherId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function resolveAlertLink(string $link): string
    {
        $link = trim($link);
        if ($link === '') {
            return '/docenti/alerts';
        }

        if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://') || str_starts_with($link, '/')) {
            return $link;
        }

        return match ($link) {
            'all_alerts.php' => '/docenti/alerts',
            'all_messages.php' => '/docenti/messages',
            'lista_studenti.php' => '/docenti/studenti',
            default => '/docenti/dashboard',
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
            $diff = $sentAt->diff($now);
            return $this->italianInterval($diff);
        } catch (Throwable) {
            return '';
        }
    }

    private function italianInterval(DateInterval $diff): string
    {
        $parts = [];

        if ($diff->h > 0) {
            $parts[] = sprintf($this->t($diff->h > 1 ? 'teacher.communications.relative.hours' : 'teacher.communications.relative.hour'), $diff->h);
        }

        if ($diff->i > 0) {
            $parts[] = sprintf($this->t($diff->i > 1 ? 'teacher.communications.relative.minutes' : 'teacher.communications.relative.minute'), $diff->i);
        }

        if ($diff->d > 0 && $parts === []) {
            $parts[] = sprintf($this->t($diff->d > 1 ? 'teacher.communications.relative.days' : 'teacher.communications.relative.day'), $diff->d);
        }

        if ($parts === []) {
            $parts[] = $this->t('teacher.communications.relative.few_seconds');
        }

        return sprintf($this->t('teacher.communications.relative.ago'), implode(' ' . $this->t('teacher.communications.relative.join') . ' ', $parts));
    }

    private function guardTeacherClassAccess(): ?array
    {
        $permissionService = new PermissionService();
        $status = $permissionService->checkPermissionsTeacher();

        if ($status === PermissionService::STATUS_OK) {
            return null;
        }

        return match ($status) {
            PermissionService::STATUS_NOT_LOGGED => $this->error($this->t('teacher.communications.session_expired')),
            PermissionService::STATUS_NOT_TEACHER => $this->error($this->t('teacher.communications.permission_denied')),
            PermissionService::STATUS_NO_CLASS => $this->error($this->t('teacher.communications.class_required')),
            PermissionService::STATUS_NOT_CLASS_OWNER => $this->error($this->t('teacher.communications.class_access_denied')),
            default => $this->error($this->t('teacher.communications.operation_not_allowed')),
        };
    }

    private function getCurrentClassIdOrFail(): int
    {
        $classId = (new PermissionService())->getCurrentClassId();
        if ($classId === null) {
            throw new \RuntimeException($this->t('teacher.communications.class_not_selected'));
        }

        return $classId;
    }

    private function getCurrentTeacherIdOrFail(): int
    {
        $teacherId = (new PermissionService())->getCurrentUserId();
        if ($teacherId === null) {
            throw new \RuntimeException($this->t('teacher.communications.user_not_authenticated'));
        }

        return $teacherId;
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
