<?php

namespace App\Core;

// Render minimale: carica view, inietta dati, applica layout.
final class View
{
    // Compone output della view nel layout selezionato.
    public static function render(string $view, array $data = [], string $layout = 'mainDoc'): void
    {
        // Percorso base delle viste pubbliche.
        $viewsPath = __DIR__ . '/../../public/views/';
        // Percorso del layout scelto (es. mainDoc/mainStud).
        $layoutPath = $viewsPath . 'layouts/' . $layout . '.php';
        // Percorso del file view richiesto.
        $viewPath = $viewsPath . $view . '.php';

        // Fail-fast se la view non esiste fisicamente.
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View non trovata: {$viewPath}");
        }

        // Fail-fast se manca il layout associato.
        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout non trovato: {$layoutPath}");
        }

        // Estrae i dati in variabili locali per la view.
        extract($data, EXTR_SKIP);

        // Bufferizza la view per inserirla nel placeholder $content.
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Render finale includendo il layout.
        require $layoutPath;
    }
}
