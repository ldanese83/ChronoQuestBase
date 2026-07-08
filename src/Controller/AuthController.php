<?php

namespace App\Controller;

use App\Core\View;
use App\Service\AuthService;
use App\Service\Flash;
use App\Service\Session;

// Controller dedicato ai flussi auth: login, logout, registrazione, validazioni.
class AuthController
{
    // Mostra la pagina login docenti (layout principale docenti).
    public function showLoginDoc(): void
    {
        View::render('auth/loginDoc', [
            'title' => 'login.docente',
            'pageStyles' => [
                '/css/login.css',
                'https://fonts.googleapis.com/css2?family=Uncial+Antiqua&display=swap',
            ],
            'useMathJax' => false,
        ], 'loginDocLayout');
    }

    // Mostra la pagina di registrazione docente.
    public function showRegistrazioneDoc(): void
    {
        View::render('auth/registerDoc', [
            'title' => 'register',
            'pageStyles' => [
                '/css/register.css',
            ],
            'useMathJax' => false,
            'pageScripts' => ['/js/register.js'],
        ], 'loginDocLayout');
    }

    // Mostra login dedicato agli studenti.
    public function showLoginStud(): void
    {
        View::render('auth/loginStud', [
            'title' => 'login.studente',
            'pageStyles' => [
                '/css/login_studente.css',
            ],
            'useMathJax' => false,
        ], 'loginStudLayout');
    }

    // Esegue autenticazione docente/amministratore.
    public function loginDoc(): void
    {
        // Legge credenziali inviate dal form login docenti.
        $username = $_POST['inputUser'] ?? '';
        $password = $_POST['inputPassword'] ?? '';

        // Delega controllo credenziali al service.
        $auth = new AuthService();

        // Se login corretto redirige in dashboard docenti.
        if ($auth->attemptDoc($username, $password)) {
            if (Session::get('user')['role'] === 'amministratore' || Session::get('user')['role'] === 'docente') {
                header('Location: /docenti/classi');
            }

            exit;
        }

        // In caso errore mostra flash e torna al login.
        Flash::add('danger', 'auth.invalid_credentials');
        header('Location: /loginDoc');
        exit;
    }

    // Esegue autenticazione studente.
    public function loginStud(): void
    {
        // Legge credenziali inviate dal form login studenti.
        $username = $_POST['username'] ?? '';
        $password = $_POST['pass'] ?? '';

        // Delega validazione credenziali al service.
        $auth = new AuthService();

        // Se autenticato e ruolo corretto entra nella dashboard studenti.
        if ($auth->attemptStud($username, $password)) {

            if (Session::get('user')['role'] === 'studente') {
                // Dopo il login studente entriamo nella pagina di scelta classe, equivalente della homepage legacy.
                
                header('Location: /studenti/dashboard');
            }

            exit;
        }

        // Login fallito: flash errore + ritorno alla pagina studente.
        Flash::add('danger', 'auth.invalid_credentials');
        header('Location: /loginStud');
        exit;
    }

    // Gestisce submit registrazione docente con validazioni base.
    public function registerTeacher(): void
    {
        // Raccoglie campi testuali dal form, rimuovendo spazi esterni.
        $name = trim($_POST['validationCustom01'] ?? '');
        $surname = trim($_POST['validationCustom02'] ?? '');
        $username = trim($_POST['validationCustomUsername'] ?? '');
        $email = trim($_POST['validationCustom03'] ?? '');
        $password = $_POST['validationCustom04'] ?? '';
        $passwordConfirm = $_POST['validationCustom05'] ?? '';
        $gdprAccepted = isset($_POST['invalidCheck']);

        // Colleziona le chiavi errore da tradurre e mostrare in vista.
        $errors = [];

        // Validazione presenza nome.
        if ($name === '') {
            $errors[] = 'register.error_name_required';
        }

        // Validazione presenza cognome.
        if ($surname === '') {
            $errors[] = 'register.error_surname_required';
        }

        // Validazione presenza username.
        if ($username === '') {
            $errors[] = 'register.error_username_required';
        }

        // Validazione formato email.
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'register.error_email_invalid';
        }

        // Validazione password compilata e coerente con conferma.
        if ($password === '' || $password !== $passwordConfirm) {
            $errors[] = 'register.error_password_mismatch';
        }

        // Privacy/GDPR obbligatorio prima di procedere.
        if (!$gdprAccepted) {
            $errors[] = 'register.error_privacy';
        }

        // Service auth usato per controlli DB e inserimento utente.
        $authService = new AuthService();

        // Controllo unicità username in archivio utenti.
        if ($authService->usernameExists($username)) {
            $errors[] = 'register.error_username_taken';
        }

        // Controllo email: disponibile e presente in lista abilitata.
        if ($authService->validateEmail($email) !== 1) {
            $errors[] = 'register.error_email_taken';
        }

        // Se ci sono errori, li salva in flash e reindirizza al form.
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::add('danger', $error);
            }

            // Persistenza temporanea campi per ripopolare il form.
            $_SESSION['old'] = [
                'validationCustom01' => $name,
                'validationCustom02' => $surname,
                'validationCustomUsername' => $username,
                'validationCustom03' => $email,
            ];

            header('Location: /registrazioneDoc');
            exit;
        }

        // Inserisce utente docente e invia email con link validazione.
        $authService->registerTeacher(
            $name,
            $surname,
            $username,
            $email,
            $password,
            $passwordConfirm
        );

        // Mostra feedback positivo e torna al login.
        Flash::add('success', 'register.success_teacher');
        header('Location: /loginDoc');
        exit;
    }

    // Esegue logout docenti/amministratori.
    public function logoutDoc(): void
    {
        (new AuthService())->logout();
        header('Location: /loginDoc');
        exit;
    }

    // Esegue logout studenti.
    public function logoutStud(): void
    {
        (new AuthService())->logout();
        header('Location: /loginStud');
        exit;
    }

    // Endpoint AJAX per validazione email in fase registrazione.
    public function validateEmail(): void
    {
        // Legge email da query string parametro `mail`.
        $email = $_GET['mail'] ?? '';

        // Delego al service la logica di controllo su DB.
        $result = (new AuthService())->validateEmail($email);

        // Ritorna codice testuale plain-text (0/1/2).
        header('Content-Type: text/plain; charset=utf-8');
        echo $result;
        exit;
    }

    // Endpoint pubblico richiamato da link email per validare utente.
    public function validateUser(): void
    {
        // Estrae parametri query necessari a confermare l'account.
        $idUser = isset($_GET['id_user']) ? (int) $_GET['id_user'] : 0;
        $codice = trim($_GET['codice'] ?? '');
        $errore = true;

        // Se parametri valorizzati, tenta la validazione a DB.
        if ($idUser > 0 && $codice !== '') {
            $errore = !(new AuthService())->validateUser($idUser, $codice);
        }

        // Render pagina esito (successo/errore) nel layout docenti.
        View::render('auth/validateUser', [
            'title' => 'validate.user.title',
            'pageStyles' => [],
            'useMathJax' => false,
            'errore' => $errore,
        ], 'loginDocLayout');
    }

    // Endpoint AJAX per verificare disponibilità username.
    public function validateUsername(): void
    {
        // Legge username proposto dal client.
        $username = trim($_GET['user'] ?? '');

        // Risponde 0 se esiste già, 1 se disponibile.
        header('Content-Type: text/plain; charset=utf-8');
        echo (new AuthService())->usernameExists($username) ? '0' : '1';
        exit;
    }
}
