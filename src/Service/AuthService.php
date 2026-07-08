<?php

namespace App\Service;

use PDO;

// Service che centralizza login, registrazione e validazione utenti.
class AuthService
{
    // Tenta login per docente/amministratore e salva utente in sessione.
    public function attemptDoc(string $username, string $password): bool
    {
        // Recupera connessione condivisa al database.
        $pdo = Database::getConnection();

        // Cerca utente per username limitando ai ruoli doc/amministratore.
        $stmt = $pdo->prepare("SELECT * FROM (ct_utenti u INNER JOIN ct_utenti_tipi ut ON u.id_utente = ut.fk_utente) INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente WHERE username = :username AND (tu.tipo_utente = 'docente' OR tu.tipo_utente = 'amministratore')");
        $stmt->execute(['username' => $username]);

        // Legge la prima riga utente corrispondente.
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se utente non trovato login fallisce.
        if (!$user) {
            return false;
        }

        // Verifica password hashata lato server.
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Azzeriamo l'eventuale classe precedente per evitare che un nuovo login erediti il contesto di una sessione passata.
        Session::set('class', null);

        // Salva payload minimo in sessione per middleware/route.
        Session::set('user', [
            'id' => $user['id_utente'],
            'email' => $user['email'],
            'role' => $user['tipo_utente'],
        ]);
        Session::set('lang', $this->normalizeLanguage((string) ($user['language'] ?? 'en')));

        // Login completato correttamente.
        return true;
    }

    // Tenta login specifico per utente con ruolo studente.
    public function attemptStud(string $username, string $password): bool
    {
        // Ottiene connessione PDO singleton.
        $pdo = Database::getConnection();

        // Cerca utente filtrando solo il tipo 'studente'.
        $stmt = $pdo->prepare("SELECT * FROM (ct_utenti u INNER JOIN ct_utenti_tipi ut ON u.id_utente = ut.fk_utente) INNER JOIN ct_tipo_utente tu ON tu.id_tipo_utente = ut.fk_tipo_utente WHERE username = :username AND tu.tipo_utente = 'studente'");
        $stmt->execute(['username' => $username]);

        // Legge record studente trovato.
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nessun utente trovato: autenticazione negativa.
        if (!$user) {
            return false;
        }

        // Password non valida: autenticazione negativa.
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Azzeriamo l'eventuale classe precedente per evitare riusi impropri del contesto studenti.
        Session::set('class', null);

        // Salva dati sessione usati nel resto dell'app.
        Session::set('user', [
            'id' => $user['id_utente'],
            'email' => $user['email'],
            'role' => $user['tipo_utente'],
        ]);
        Session::set('lang', $this->normalizeLanguage((string) ($user['language'] ?? 'en')));

        // Login studente andato a buon fine.
        return true;
    }

    // Effettua logout eliminando la sessione corrente.
    public function logout(): void
    {
        Session::destroy();
    }

    // Controlla se uno username è già presente in anagrafica utenti.
    public function usernameExists(string $username): bool
    {
        $pdo = Database::getConnection();

        // Query rapida con LIMIT per verifica esistenza.
        $stmt = $pdo->prepare('SELECT id_utente FROM ct_utenti WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);

        // Ritorna true quando esiste almeno una riga.
        return (bool) $stmt->fetch();
    }

    // Valida email in registrazione: disponibilità + whitelist abilitate.
    public function validateEmail(string $email): int
    {
        $pdo = Database::getConnection();

        // 1) verifica email già usata da un altro utente.
        $stmt = $pdo->prepare('SELECT COUNT(1) as tot FROM ct_utenti WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 0 = email già in uso.
        if ((int) ($row['tot'] ?? 0) > 0) {
            return 0;
        }

        // 2) verifica email presente tra quelle autorizzate.
        $stmt = $pdo->prepare('SELECT COUNT(1) as tot FROM ct_mail_abilitate WHERE mail = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2 = email non in whitelist.
        if ((int) ($row['tot'] ?? 0) === 0) {
            return 2;
        }

        // 1 = email valida per registrazione.
        return 1;
    }

    // Valida utente da link mail verificando id/codice e settando validato=1.
    public function validateUser(int $idUser, string $codice): bool
    {
        $pdo = Database::getConnection();

        // Controlla esistenza combinazione id utente + codice conferma.
        $stmt = $pdo->prepare('SELECT COUNT(*) AS tot FROM ct_utenti WHERE codice_conf = :codice AND id_utente = :id_user');
        $stmt->execute([
            'codice' => $codice,
            'id_user' => $idUser,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se non trovata corrispondenza la validazione fallisce.
        if ((int) ($row['tot'] ?? 0) === 0) {
            return false;
        }

        // Aggiorna utente marcandolo come validato.
        $update = $pdo->prepare('UPDATE ct_utenti SET validato = 1 WHERE codice_conf = :codice AND id_utente = :id_user');
        $update->execute([
            'codice' => $codice,
            'id_user' => $idUser,
        ]);

        // Validazione eseguita con successo.
        return true;
    }

    // Registra un nuovo docente con verifiche pre-inserimento e invio mail.
    public function registerTeacher(
        string $nome,
        string $cognome,
        string $username,
        string $email,
        string $pass1,
        string $pass2
    ): array {
        $pdo = Database::getConnection();

        // Flag di controllo per replica logica storica del progetto.
        $ok1 = false; // email libera
        $ok2 = false; // username libero
        $ok3 = false; // password uguali
        $ok4 = false; // email abilitata

        // Genera codice conferma random alfanumerico da 8 caratteri.
        $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $shfl = str_shuffle($comb);
        $codice = substr($shfl, 0, 8);

        // 1) verifica email non già usata in ct_utenti.
        $stmt = $pdo->prepare('SELECT COUNT(1) as tot FROM ct_utenti WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['tot'] ?? 0) === 0) {
            $ok1 = true;
        }

        // 2) verifica username non già assegnato.
        $stmt = $pdo->prepare('SELECT COUNT(1) as tot FROM ct_utenti WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['tot'] ?? 0) === 0) {
            $ok2 = true;
        }

        // 3) verifica corrispondenza password + conferma.
        if ($pass1 === $pass2) {
            $ok3 = true;
        }

        // 4) verifica email presente nella tabella abilitata.
        $stmt = $pdo->prepare('SELECT COUNT(1) as tot FROM ct_mail_abilitate WHERE mail = :email');
        $stmt->execute(['email' => $email]);
        $rowm = $stmt->fetch(PDO::FETCH_ASSOC);
        if ((int) ($rowm['tot'] ?? 0) > 0) {
            $ok4 = true;
        }

        // Ritorna errore se email già usata/non valida secondo regole correnti.
        if (!$ok1) {
            return [
                'success' => false,
                'message' => 'register.error_email_invalid',
            ];
        }

        // Ritorna errore se username occupato.
        if (!$ok2) {
            return [
                'success' => false,
                'message' => 'register.error_username_required',
            ];
        }

        // Ritorna errore se le password non coincidono.
        if (!$ok3) {
            return [
                'success' => false,
                'message' => 'register.error_password_mismatch',
            ];
        }

        // Ritorna errore se email non autorizzata.
        if (!$ok4) {
            return [
                'success' => false,
                'message' => 'register.error_email_invalid',
            ];
        }

        // Inserimento anagrafica utente docente con validato = 0.
        $stmt = $pdo->prepare('INSERT INTO ct_utenti (nome, cognome, username, password, email, codice_conf, validato, language) VALUES (:nome, :cognome, :username, :password, :email, :codice_conf, :validato, :language)');
        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'username' => $username,
            'password' => password_hash($pass1, PASSWORD_DEFAULT),
            'email' => $email,
            'codice_conf' => $codice,
            'validato' => 0,
            'language' => 'en',
        ]);

        // Recupera id del nuovo utente per tabelle relazionali.
        $idUtente = (int) $pdo->lastInsertId();

        // Associa tipologia docente (fk_tipo_utente = 3).
        $stmt = $pdo->prepare('INSERT INTO ct_utenti_tipi (fk_utente, fk_tipo_utente) VALUES (:fk_utente, :fk_tipo_utente)');
        $stmt->execute([
            'fk_utente' => $idUtente,
            'fk_tipo_utente' => 3,
        ]);

        // Associa classe di default come nel codice legacy.
        $stmt = $pdo->prepare('INSERT INTO ct_utenti_classi (fk_utente, fk_classe) VALUES (:fk_utente, :fk_classe)');
        $stmt->execute([
            'fk_utente' => $idUtente,
            'fk_classe' => 1,
        ]);

        // Invio email contenente il link di validazione account.
        $mailSent = $this->sendTeacherValidationEmail($idUtente, $username, $email, $codice);

        // Ritorna payload utile a eventuale logging/diagnostica.
        return [
            'success' => true,
            'message' => $mailSent
                ? 'register.success_teacher'
                : 'register.success_teacher_mail_failed',
            'user_id' => $idUtente,
            'username' => $username,
            'email' => $email,
            'codice_conf' => $codice,
        ];
    }

    // Costruisce e invia la mail di conferma registrazione docente.
    public function sendTeacherValidationEmail(
        int $idUtente,
        string $username,
        string $email,
        string $codice
    ): bool {
        // Determina schema (http/https) in base al server attuale.
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        // Usa host corrente con fallback localhost.
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;

        // Compone URL assoluto usato nel link di validazione.
        $validationUrl = $baseUrl . '/validate-user?id_user=' . urlencode((string) $idUtente) . '&codice=' . urlencode($codice);

        // Crea body HTML email con contenuto minimale.
        $testo = '<h1>Mail da ChronoQuest</h1>';
        $testo .= '<p>The user:<br>' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ' has just been created on ChronoQuest.</p>';
        $testo .= '<p>Click the link to validate the user:</p>';
        $testo .= "<p><a href='{$validationUrl}'>VALIDATE USER</a></p>";
        $testo .= '<br><p>Thanks for your registration and enjoy ChronoQuest</p>';

        // Definisce oggetto e mittente della comunicazione.
        $oggetto = 'User Validation on ChronoQuest';
        $from = '';

        // Delega invio reale al servizio email.
        $mailService = new MailService();
        return $mailService->sendMail($testo, $oggetto, $from, $email);
    }

    private function normalizeLanguage(string $language): string
    {
        return in_array($language, ['en', 'it'], true) ? $language : 'en';
    }
}
