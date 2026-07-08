<?php

namespace App\Service;

// Gestisce i messaggi flash (visibili per una sola richiesta).
final class Flash
{
    // Chiave sessione dedicata ai messaggi temporanei.
    private const KEY = '_flash';

    // Aggiunge un messaggio flash con tipo bootstrap e chiave traduzione.
    public static function add(string $type, string $message): void
    {
        // Inizializza il contenitore quando non esiste ancora.
        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }

        // Accoda il nuovo messaggio alla coda dei flash.
        $_SESSION[self::KEY][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    // Restituisce e consuma tutti i flash disponibili.
    public static function all(): array
    {
        // Legge i messaggi presenti o array vuoto se assenti.
        $messages = $_SESSION[self::KEY] ?? [];

        // Rimuove i messaggi per evitare che ricompaiano al refresh.
        unset($_SESSION[self::KEY]);

        // Ritorna i messaggi alla vista chiamante.
        return $messages;
    }
}
