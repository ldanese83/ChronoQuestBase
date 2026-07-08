<?php

namespace App\Service;

use PDO;

// Espone una connessione PDO singleton riutilizzabile in tutta l'app.
class Database
{
    // Contiene l'istanza unica della connessione al DB.
    private static ?PDO $connection = null;

    // Crea (se serve) e restituisce la connessione al database MySQL.
    public static function getConnection(): PDO
    {
        // Se la connessione non è stata ancora inizializzata, la creiamo.
        if (self::$connection === null) {
            // Carica host, nome DB e credenziali dalla configurazione.
            $config = require __DIR__ . '/../../config/database.php';

            // Costruisce la connessione PDO con charset utf8mb4.
            self::$connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4",
                $config['user'],
                $config['pass']
            );

            // Imposta PDO in modalità eccezione per error handling esplicito.
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        // Ritorna sempre la stessa connessione già pronta.
        return self::$connection;
    }
}
