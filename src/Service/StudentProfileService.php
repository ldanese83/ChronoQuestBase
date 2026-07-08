<?php

namespace App\Service;

use PDO;

class StudentProfileService
{
    private TranslationService $translator;

    public function __construct()
    {
        $this->translator = new TranslationService();
    }

    public function getCurrentUserProfile(): ?array
    {
        $userId = $this->getCurrentUserId();
        if ($userId === null) {
            return null;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT id_utente, nome, cognome, username, ricevi_mail, language
             FROM ct_utenti
             WHERE id_utente = :id_utente
             LIMIT 1'
        );
        $stmt->execute(['id_utente' => $userId]);

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$profile) {
            return null;
        }

        $profile['language'] = $this->normalizeLanguage((string) ($profile['language'] ?? 'en'));

        return $profile;
    }

    public function updateCurrentUserProfile(array $payload): array
    {
        $userId = $this->getCurrentUserId();
        if ($userId === null) {
            return $this->error('permission.nologin');
        }

        $receiveMail = ((int) ($payload['receive_mail'] ?? 0)) === 1 ? 1 : 0;
        $password = (string) ($payload['password'] ?? '');
        $passwordConfirm = (string) ($payload['password_confirm'] ?? '');
        $language = $this->normalizeLanguage((string) ($payload['language'] ?? 'en'));

        if ($password !== '' && $password !== $passwordConfirm) {
            return $this->error($this->t('profile.password.mismatch'));
        }

        $pdo = Database::getConnection();
        $params = [
            'id_utente' => $userId,
            'ricevi_mail' => $receiveMail,
            'language' => $language,
        ];

        if ($password !== '') {
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'UPDATE ct_utenti
                 SET password = :password,
                     ricevi_mail = :ricevi_mail,
                     language = :language
                 WHERE id_utente = :id_utente'
            );
        } else {
            $stmt = $pdo->prepare(
                'UPDATE ct_utenti
                 SET ricevi_mail = :ricevi_mail,
                     language = :language
                 WHERE id_utente = :id_utente'
            );
        }

        $stmt->execute($params);

        Session::set('lang', $language);

        return [
            'success' => true,
            'message' => $this->t('profile.save.success'),
        ];
    }

    private function getCurrentUserId(): ?int
    {
        $user = Session::get('user');
        $userId = isset($user['id']) ? (int) $user['id'] : 0;

        return $userId > 0 ? $userId : null;
    }

    private function normalizeLanguage(string $language): string
    {
        return in_array($language, ['en', 'it'], true) ? $language : 'en';
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
