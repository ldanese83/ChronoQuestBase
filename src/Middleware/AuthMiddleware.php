<?php

namespace App\Middleware;

use App\Service\Session;

// Middleware statico per proteggere route autenticata e ruoli.
class AuthMiddleware
{
    // Blocca l'accesso se l'utente non è loggato in sessione.
    public static function requireLogin(): void
    {
        // Se manca utente in sessione, redirige alla pagina login.
        if (!Session::has('user')) {
            header('Location: /login');
            exit;
        }
    }

    // Verifica che l'utente loggato abbia il ruolo richiesto.
    public static function requireRole(string $role): void
    {
        // Prima assicura sempre presenza di login.
        self::requireLogin();

        // Recupera il payload utente già salvato in sessione.
        $user = Session::get('user');

        // Se il ruolo non coincide, ritorna 403 con messaggio semplice.
        if ($user['role'] !== $role) {
            http_response_code(403);
            echo 'Accesso negato';
            exit;
        }
    }
}
