<?php

return [
    'host' => $_ENV['MAIL_HOST'] ?? 'smtps.aruba.it',
    'port' => (int) ($_ENV['MAIL_PORT'] ?? 465),
    'username' => $_ENV['MAIL_USERNAME'] ?? 'info@incognitaelephantes.it',
    'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'ssl',
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'info@incognitaelephantes.it',
    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'ChronoQuest',
];
