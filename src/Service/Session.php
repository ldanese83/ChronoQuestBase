<?php

namespace App\Service;

// Gestisce operazioni basilari sulla sessione applicativa.
class Session
{
    // Salva un valore in sessione sotto una chiave specifica.
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    // Restituisce il valore della chiave, oppure null se assente.
    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    // Verifica rapidamente se una chiave sessione esiste.
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    // Distrugge la sessione corrente (logout globale).
    public static function destroy(): void
    {
        session_destroy();
    }
}
