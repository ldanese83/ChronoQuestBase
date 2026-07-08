<?php

declare(strict_types=1);

$rootPath = dirname(__DIR__);
$envPath = $rootPath . '/.env';
$lockPath = $rootPath . '/var/installed.lock';
$sqlBasePath = $rootPath . '/sql/finali';
$sqlPackages = [
    'base' => [
        'label' => 'Base configuration',
        'file' => 'chronoquest_vuoto.sql',
    ],
    'italian' => [
        'label' => 'Italian configuration',
        'file' => 'chronoquest_italiano.sql',
    ],
    'english' => [
        'label' => 'English configuration',
        'file' => 'chronoquest_inglese.sql',
    ],
];

$installed = file_exists($envPath);
$errors = [];
$success = false;

$defaults = [
    'installation_package' => 'base',
    'db_host' => 'localhost',
    'db_name' => '',
    'db_user' => '',
    'db_password' => '',
    'mail_host' => 'smtps.aruba.it',
    'mail_port' => '465',
    'mail_encryption' => 'ssl',
    'mail_username' => '',
    'mail_password' => '',
    'mail_from_name' => 'ChronoQuest',
    'mail_from_address' => '',
    'admin_email' => '',
];

$values = $defaults;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$installed) {
    foreach ($values as $key => $default) {
        $values[$key] = trim((string) ($_POST[$key] ?? $default));
    }

    $adminPassword = (string) ($_POST['admin_password'] ?? '');
    $adminPasswordConfirm = (string) ($_POST['admin_password_confirm'] ?? '');
    $selectedPackage = $values['installation_package'];
    $sqlPath = isset($sqlPackages[$selectedPackage])
        ? $sqlBasePath . '/' . $sqlPackages[$selectedPackage]['file']
        : '';

    foreach (['db_host', 'db_name', 'db_user', 'mail_host', 'mail_port', 'mail_username', 'mail_from_address', 'admin_email'] as $requiredField) {
        if ($values[$requiredField] === '') {
            $errors[] = 'Please fill in all required fields.';
            break;
        }
    }

    if (!isset($sqlPackages[$selectedPackage])) {
        $errors[] = 'Please select a valid installation package.';
    }

    if (!filter_var($values['mail_from_address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid sender email address.';
    }

    if (!filter_var($values['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid administrator email address.';
    }

    if (!ctype_digit($values['mail_port']) || (int) $values['mail_port'] < 1 || (int) $values['mail_port'] > 65535) {
        $errors[] = 'The SMTP port must be a number between 1 and 65535.';
    }

    if (!in_array($values['mail_encryption'], ['ssl', 'tls', 'none'], true)) {
        $errors[] = 'Please select a valid email encryption option.';
    }

    if (strlen($adminPassword) < 8) {
        $errors[] = 'The administrator password must be at least 8 characters long.';
    }

    if ($adminPassword !== $adminPasswordConfirm) {
        $errors[] = 'The administrator passwords do not match.';
    }

    if ($sqlPath === '' || !is_readable($sqlPath)) {
        $errors[] = 'The installation SQL file was not found or is not readable.';
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $values['db_host'], $values['db_name']),
                $values['db_user'],
                $values['db_password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            importSqlFile($pdo, $sqlPath);

            $stmt = $pdo->prepare('UPDATE ct_utenti SET password = :password, email = :email WHERE username = :username');
            $stmt->execute([
                'password' => password_hash($adminPassword, PASSWORD_DEFAULT),
                'email' => $values['admin_email'],
                'username' => 'admin',
            ]);

            if ($stmt->rowCount() === 0) {
                throw new RuntimeException('Admin user not found in the newly imported database.');
            }

            $envContent = buildEnvContent($values);
            if (file_put_contents($envPath, $envContent) === false) {
                throw new RuntimeException('Unable to create the .env file.');
            }

            if (!is_dir(dirname($lockPath)) && !mkdir(dirname($lockPath), 0775, true)) {
                throw new RuntimeException('Unable to create the var folder.');
            }

            $lockContent = "ChronoQuest installed at " . date('c') . PHP_EOL;
            if (file_put_contents($lockPath, $lockContent) === false) {
                throw new RuntimeException('Unable to create the installation completion file.');
            }

            $success = true;
            $installed = true;
        } catch (Throwable $exception) {
            $errors[] = 'Installation could not be completed: ' . $exception->getMessage();
        }
    }
}

function buildEnvContent(array $values): string
{
    $mailEncryption = $values['mail_encryption'] === 'none' ? '' : $values['mail_encryption'];

    $lines = [
        'DB_HOST=' . envValue($values['db_host']),
        'DB_NAME=' . envValue($values['db_name']),
        'DB_USER=' . envValue($values['db_user']),
        'DB_PASSWORD=' . envValue($values['db_password']),
        '',
        'MAIL_HOST=' . envValue($values['mail_host']),
        'MAIL_PORT=' . envValue($values['mail_port']),
        'MAIL_ENCRYPTION=' . envValue($mailEncryption),
        'MAIL_USERNAME=' . envValue($values['mail_username']),
        'MAIL_PASSWORD=' . envValue($values['mail_password']),
        'MAIL_FROM_NAME=' . envValue($values['mail_from_name']),
        'MAIL_FROM_ADDRESS=' . envValue($values['mail_from_address']),
        '',
    ];

    return implode(PHP_EOL, $lines);
}

function importSqlFile(PDO $pdo, string $sqlPath): void
{
    $sql = file_get_contents($sqlPath);
    if ($sql === false) {
        throw new RuntimeException('Unable to read the installation SQL file.');
    }

    $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql) ?? $sql;
    $statements = splitSqlStatements($sql);

    foreach ($statements as $statement) {
        $statement = trim($statement);
        if ($statement === '') {
            continue;
        }

        $pdo->exec($statement);
    }
}

function splitSqlStatements(string $sql): array
{
    $statements = [];
    $statement = '';
    $length = strlen($sql);
    $quote = null;
    $lineComment = false;
    $blockComment = false;

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $i + 1 < $length ? $sql[$i + 1] : '';

        if ($lineComment) {
            if ($char === "\n") {
                $lineComment = false;
                $statement .= "\n";
            }
            continue;
        }

        if ($blockComment) {
            if ($char === '*' && $next === '/') {
                $blockComment = false;
                $i++;
            }
            continue;
        }

        if ($quote !== null) {
            $statement .= $char;

            if ($char === '\\' && $next !== '') {
                $statement .= $next;
                $i++;
                continue;
            }

            if ($char === $quote && $next === $quote) {
                $statement .= $next;
                $i++;
                continue;
            }

            if ($char === $quote) {
                $quote = null;
            }

            continue;
        }

        if (($char === '-' && $next === '-' && isSqlCommentBoundary($sql, $i + 2)) || $char === '#') {
            $lineComment = true;
            $i += $char === '-' ? 1 : 0;
            continue;
        }

        if ($char === '/' && $next === '*') {
            $blockComment = true;
            $i++;
            continue;
        }

        if ($char === '\'' || $char === '"' || $char === '`') {
            $quote = $char;
            $statement .= $char;
            continue;
        }

        if ($char === ';') {
            $statements[] = $statement;
            $statement = '';
            continue;
        }

        $statement .= $char;
    }

    if (trim($statement) !== '') {
        $statements[] = $statement;
    }

    return $statements;
}

function isSqlCommentBoundary(string $sql, int $position): bool
{
    if ($position >= strlen($sql)) {
        return true;
    }

    return ctype_space($sql[$position]);
}

function envValue(string $value): string
{
    if ($value === '') {
        return '""';
    }

    if (preg_match('/^[A-Za-z0-9_.@:-]+$/', $value)) {
        return $value;
    }

    return '"' . addcslashes($value, "\\\"") . '"';
}

function old(array $values, string $key): string
{
    return htmlspecialchars($values[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChronoQuest Installation</title>
    <link href="/assets/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/fontawesome-7.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
            color: #1f2933;
        }

        .installer-shell {
            max-width: 980px;
            margin: 0 auto;
        }

        .installer-header {
            padding: 2rem 0 1rem;
        }

        .installer-panel {
            background: #fff;
            border: 1px solid #d9e2ec;
            border-radius: 8px;
            box-shadow: 0 14px 32px rgba(31, 41, 51, 0.08);
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #102a43;
        }

        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>
<main class="installer-shell px-3 py-4">
    <div class="installer-header">
        <h1 class="h2 mb-2">ChronoQuest Installation</h1>
        <p class="text-secondary mb-0">Configure the database, email settings, and administrator access.</p>
    </div>

    <?php if ($success): ?>
        <div class="installer-panel p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-3">
                <i class="fa-solid fa-circle-check fa-2x text-success"></i>
                <div>
                    <h2 class="h4 mb-1">Installation Complete</h2>
                    <p class="mb-0 text-secondary">The .env file has been created and the database is ready.</p>
                </div>
            </div>
            <a class="btn btn-primary" href="/loginDoc">Go to Teacher Login</a>
        </div>
    <?php elseif ($installed): ?>
        <div class="installer-panel p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-3">
                <i class="fa-solid fa-lock fa-2x text-primary"></i>
                <div>
                    <h2 class="h4 mb-1">ChronoQuest is already installed</h2>
                    <p class="mb-0 text-secondary">For security reasons, the installer cannot be run again.</p>
                </div>
            </div>
            <a class="btn btn-primary" href="/loginDoc">Go to Teacher Login</a>
        </div>
    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Please check the following:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (array_unique($errors) as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="installerForm" class="installer-panel p-4 p-md-5" method="post" action="/install.php">
            <div class="mb-4">
                <div class="section-title mb-3">Installation Package</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="installation_package">Initial configuration</label>
                        <select class="form-select" id="installation_package" name="installation_package" required>
                            <?php foreach ($sqlPackages as $packageKey => $package): ?>
                                <option value="<?= htmlspecialchars($packageKey, ENT_QUOTES, 'UTF-8') ?>" <?= ($values['installation_package'] === $packageKey) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($package['label'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-4">
                <div class="section-title mb-3">Database</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="db_host">Database Host</label>
                        <input class="form-control" id="db_host" name="db_host" value="<?= old($values, 'db_host') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="db_name">Database Name</label>
                        <input class="form-control" id="db_name" name="db_name" value="<?= old($values, 'db_name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="db_user">Database Username</label>
                        <input class="form-control" id="db_user" name="db_user" value="<?= old($values, 'db_user') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="db_password">Database Password</label>
                        <input class="form-control" id="db_password" name="db_password" type="password" value="<?= old($values, 'db_password') ?>">
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-4">
                <div class="section-title mb-3">Email</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="mail_host">SMTP Host</label>
                        <input class="form-control" id="mail_host" name="mail_host" value="<?= old($values, 'mail_host') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="mail_port">Port</label>
                        <input class="form-control" id="mail_port" name="mail_port" inputmode="numeric" value="<?= old($values, 'mail_port') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="mail_encryption">Encryption</label>
                        <select class="form-select" id="mail_encryption" name="mail_encryption">
                            <?php foreach (['ssl' => 'SSL', 'tls' => 'TLS', 'none' => 'None'] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($values['mail_encryption'] === $value) ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mail_username">SMTP Username</label>
                        <input class="form-control" id="mail_username" name="mail_username" value="<?= old($values, 'mail_username') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mail_password">SMTP Password</label>
                        <input class="form-control" id="mail_password" name="mail_password" type="password" value="<?= old($values, 'mail_password') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mail_from_name">Sender Name</label>
                        <input class="form-control" id="mail_from_name" name="mail_from_name" value="<?= old($values, 'mail_from_name') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mail_from_address">Sender Email</label>
                        <input class="form-control" id="mail_from_address" name="mail_from_address" type="email" value="<?= old($values, 'mail_from_address') ?>" required>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-4">
                <div class="section-title mb-3">Administrator</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="admin_email">Admin Email</label>
                        <input class="form-control" id="admin_email" name="admin_email" type="email" value="<?= old($values, 'admin_email') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="admin_password">Admin Password</label>
                        <input class="form-control" id="admin_password" name="admin_password" type="password" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="admin_password_confirm">Confirm Admin Password</label>
                        <input class="form-control" id="admin_password_confirm" name="admin_password_confirm" type="password" minlength="8" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button id="installSubmit" class="btn btn-primary btn-lg" type="submit">
                    <i class="fa-solid fa-wand-magic-sparkles me-2"></i>Install ChronoQuest
                </button>
            </div>
        </form>

        <div class="modal fade" id="installProgressModal" tabindex="-1" aria-labelledby="installProgressTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="installProgressTitle">Installing ChronoQuest</h2>
                    </div>
                    <div class="modal-body">
                        <p id="installProgressMessage" class="mb-3 text-secondary">Checking database connection...</p>
                        <div class="progress" role="progressbar" aria-label="Installation progress" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100">
                            <div id="installProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 8%">8%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>
<script src="/assets/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('installerForm');
    var submitButton = document.getElementById('installSubmit');
    var modalElement = document.getElementById('installProgressModal');
    var progressElement = document.querySelector('.progress');
    var progressBar = document.getElementById('installProgressBar');
    var progressMessage = document.getElementById('installProgressMessage');

    if (!form || !modalElement || !progressElement || !progressBar || !progressMessage) {
        return;
    }

    var stages = [
        { percent: 8, message: 'Checking database connection...' },
        { percent: 22, message: 'Preparing the database structure...' },
        { percent: 48, message: 'Importing the selected ChronoQuest package...' },
        { percent: 68, message: 'Creating database constraints...' },
        { percent: 82, message: 'Updating the administrator password...' },
        { percent: 93, message: 'Writing the configuration files...' },
        { percent: 98, message: 'Finalizing the installation...' }
    ];

    function updateProgress(stage) {
        progressMessage.textContent = stage.message;
        progressBar.style.width = stage.percent + '%';
        progressBar.textContent = stage.percent + '%';
        progressElement.setAttribute('aria-valuenow', String(stage.percent));
    }

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            return;
        }

        event.preventDefault();

        if (submitButton) {
            submitButton.disabled = true;
        }

        var modal = new bootstrap.Modal(modalElement);
        var currentStage = 0;
        updateProgress(stages[currentStage]);
        modal.show();

        var timer = window.setInterval(function () {
            currentStage += 1;
            if (currentStage >= stages.length) {
                window.clearInterval(timer);
                return;
            }

            updateProgress(stages[currentStage]);
        }, 1200);

        window.setTimeout(function () {
            HTMLFormElement.prototype.submit.call(form);
        }, 150);
    });
});
</script>
</body>
</html>
