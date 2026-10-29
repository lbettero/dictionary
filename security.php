<?php
declare(strict_types=1);

const DICTIONARY_MAX_REQUEST_BYTES = 1048576;
const DICTIONARY_MAX_ENTRIES = 2000;
const DICTIONARY_MAX_TERM_LENGTH = 200;
const DICTIONARY_MAX_DEFINITION_LENGTH = 5000;

function dictionary_editor_enabled(): bool
{
    return getenv('DICTIONARY_EDITOR_ENABLED') === '1';
}

function dictionary_send_security_headers(): void
{
    $nonce = dictionary_csp_nonce();
    header('Cache-Control: no-store, max-age=0');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self' 'nonce-{$nonce}'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'");
}

function dictionary_csp_nonce(): string
{
    static $nonce = null;
    if ($nonce === null) {
        $nonce = base64_encode(random_bytes(18));
    }
    return $nonce;
}

function dictionary_fail(int $status, string $message): never
{
    http_response_code($status);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $message;
    exit;
}

function dictionary_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_name('dictionary_admin');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secure,
        'samesite' => 'Strict',
        'path' => '/',
    ]);
    session_start();
}

function dictionary_require_editor_access(): void
{
    dictionary_send_security_headers();
    if (!dictionary_editor_enabled()) {
        dictionary_fail(403, 'Dictionary editor is disabled.');
    }

    $expectedUser = getenv('DICTIONARY_ADMIN_USER');
    $expectedPassword = getenv('DICTIONARY_ADMIN_PASSWORD');
    if (!is_string($expectedUser) || $expectedUser === '' || !is_string($expectedPassword) || $expectedPassword === '') {
        dictionary_fail(503, 'Dictionary editor credentials are not configured.');
    }

    $providedUser = $_SERVER['PHP_AUTH_USER'] ?? '';
    $providedPassword = $_SERVER['PHP_AUTH_PW'] ?? '';
    if (!is_string($providedUser) || !is_string($providedPassword)
        || !hash_equals($expectedUser, $providedUser)
        || !hash_equals($expectedPassword, $providedPassword)) {
        header('WWW-Authenticate: Basic realm="Dictionary administration", charset="UTF-8"');
        dictionary_fail(401, 'Authentication required.');
    }

    dictionary_start_session();
}

function dictionary_csrf_token(): string
{
    if (empty($_SESSION['dictionary_csrf'])) {
        $_SESSION['dictionary_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['dictionary_csrf'];
}

function dictionary_require_secure_post(): void
{
    dictionary_require_editor_access();
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Allow: POST');
        dictionary_fail(405, 'POST required.');
    }

    $contentLength = filter_var($_SERVER['CONTENT_LENGTH'] ?? null, FILTER_VALIDATE_INT);
    if ($contentLength !== false && $contentLength !== null && $contentLength > DICTIONARY_MAX_REQUEST_BYTES) {
        dictionary_fail(413, 'Request is too large.');
    }

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if ($origin !== '') {
        $originParts = parse_url($origin);
        $requestHost = strtolower($_SERVER['HTTP_HOST'] ?? '');
        $requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $originScheme = strtolower($originParts['scheme'] ?? '');
        $originHost = strtolower($originParts['host'] ?? '');
        $originPort = isset($originParts['port']) ? ':' . $originParts['port'] : '';
        if ($originHost === '' || $originScheme !== $requestScheme || $originHost . $originPort !== $requestHost) {
            dictionary_fail(403, 'Invalid request origin.');
        }
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(dictionary_csrf_token(), $token)) {
        dictionary_fail(403, 'Invalid CSRF token.');
    }
}

function dictionary_atomic_write(string $path, string $content): void
{
    $directory = dirname($path);
    if (!is_dir($directory) || !is_writable($directory)) {
        dictionary_fail(500, 'Storage directory is not writable.');
    }

    $temporaryPath = tempnam($directory, '.dictionary-');
    if ($temporaryPath === false) {
        dictionary_fail(500, 'Could not create a temporary file.');
    }

    try {
        $written = file_put_contents($temporaryPath, $content, LOCK_EX);
        if ($written !== strlen($content)) {
            dictionary_fail(500, 'Could not write the complete file.');
        }
        if (!rename($temporaryPath, $path)) {
            dictionary_fail(500, 'Could not replace the destination file.');
        }
    } finally {
        if (is_file($temporaryPath)) {
            @unlink($temporaryPath);
        }
    }
}

function dictionary_string_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}
