<?php

namespace App\Service;

// Fornisce accesso alle traduzioni in base alla lingua di sessione.
class TranslationService
{
    // Dizionario key => testo caricato da file lingua.
    private array $translations;

    public function __construct()
    {
        // Se la lingua non è impostata, usa inglese come default.
        $lang = $_SESSION['lang'] ?? 'en';
        if (!in_array($lang, ['en', 'it'], true)) {
            $lang = 'en';
        }

        // Carica il file traduzioni corrispondente alla lingua corrente.
        $this->translations = require __DIR__ . "/../../translations/$lang.php";

        $moduleDir = __DIR__ . "/../../translations/$lang";
        if (is_dir($moduleDir)) {
            foreach (glob($moduleDir . '/*.php') ?: [] as $moduleFile) {
                $moduleTranslations = require $moduleFile;
                if (is_array($moduleTranslations)) {
                    $this->translations = array_replace($this->translations, $moduleTranslations);
                }
            }
        }
    }

    // Traduce una chiave; fallback sulla chiave stessa se non trovata.
    public function translate(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }

    // Restituisce tutte le traduzioni caricate (utile per esporle al frontend JS).
    public function all(): array
    {
        return $this->translations;
    }
}
