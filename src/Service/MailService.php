<?php

namespace App\Service;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Incapsula l'invio email HTML tramite PHPMailer via SMTP.
class MailService
{
    private array $config;
    private string $lastError = '';
    private string $lastDebug = '';

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
    }

    // Compone e invia il messaggio al destinatario.
    public function sendMail(string $testo, string $oggetto, string $from, string $mail): bool
    {
        $mailer = new PHPMailer(true);
        $this->lastError = '';
        $this->lastDebug = '';

        try {
            $mailer->isSMTP();
            $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $mailer->Debugoutput = function (string $message, int $level): void {
                $this->appendDebug($message, $level);
            };
            $mailer->Host = $this->config['host'];
            $mailer->Port = (int) $this->config['port'];

            $hasAuth = $this->config['username'] !== '' && $this->config['password'] !== '';
            $mailer->SMTPAuth = $hasAuth;

            if ($hasAuth) {
                $mailer->Username = $this->config['username'];
                $mailer->Password = $this->config['password'];
            }

            if ($this->config['encryption'] !== '') {
                $mailer->SMTPSecure = $this->config['encryption'];
            }

            $fromAddress = $from !== '' ? $from : $this->config['from_address'];
            $mailer->setFrom($fromAddress, $this->config['from_name']);
            $mailer->Sender = $fromAddress;
            $mailer->addAddress($mail);
            $mailer->isHTML(true);
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = $oggetto;
            $mailer->Body = $testo;

            return $mailer->send();
        } catch (Exception $e) {
            $this->lastError = $this->buildErrorMessage($mailer, $e->getMessage(), $from !== '' ? $from : $this->config['from_address']);
            return false;
        } catch (\Throwable $e) {
            $this->lastError = $this->buildErrorMessage($mailer, $e->getMessage(), $from !== '' ? $from : $this->config['from_address']);
            return false;
        }
    }

    public function getLastError(): string
    {
        return trim($this->lastError);
    }

    private function buildErrorMessage(PHPMailer $mailer, string $fallback, string $fromAddress): string
    {
        $parts = [];
        $errorInfo = trim($mailer->ErrorInfo);
        $parts[] = $errorInfo !== '' ? $errorInfo : $fallback;

        $diagnostics = $this->buildDiagnostics($fromAddress);
        if ($diagnostics !== '') {
            $parts[] = $diagnostics;
        }

        $debug = trim($this->lastDebug);
        if ($debug !== '') {
            $parts[] = 'SMTP debug: ' . $debug;
        }

        return implode(' | ', array_filter($parts));
    }

    private function buildDiagnostics(string $fromAddress): string
    {
        $diagnostics = [
            'host=' . (string) ($this->config['host'] ?? ''),
            'port=' . (string) ($this->config['port'] ?? ''),
            'encryption=' . ((string) ($this->config['encryption'] ?? '') !== '' ? (string) $this->config['encryption'] : 'none'),
            'auth=' . (((string) ($this->config['username'] ?? '') !== '' && (string) ($this->config['password'] ?? '') !== '') ? 'enabled' : 'disabled'),
            'username=' . (((string) ($this->config['username'] ?? '') !== '') ? (string) $this->config['username'] : 'not configured'),
            'from=' . $fromAddress,
        ];

        if ((string) ($this->config['username'] ?? '') !== '' && strcasecmp($fromAddress, (string) $this->config['username']) !== 0) {
            $diagnostics[] = 'warning=from address differs from SMTP username';
        }

        return 'Config: ' . implode(', ', $diagnostics);
    }

    private function appendDebug(string $message, int $level): void
    {
        $line = trim($message);
        if ($line === '') {
            return;
        }

        $line = $this->sanitizeDebugLine($line);
        $this->lastDebug .= ($this->lastDebug !== '' ? ' || ' : '') . '[' . $level . '] ' . $line;

        if (strlen($this->lastDebug) > 2500) {
            $this->lastDebug = substr($this->lastDebug, -2500);
        }
    }

    private function sanitizeDebugLine(string $line): string
    {
        $line = preg_replace('/(AUTH\s+(?:PLAIN|LOGIN)\s+).*/i', '$1[redacted]', $line) ?? $line;
        $line = preg_replace('/(Password:\s*).*/i', '$1[redacted]', $line) ?? $line;

        $password = (string) ($this->config['password'] ?? '');
        if ($password !== '') {
            $line = str_replace($password, '[redacted]', $line);
            $encoded = base64_encode($password);
            if ($encoded !== '') {
                $line = str_replace($encoded, '[redacted]', $line);
            }
        }

        return $line;
    }
}
