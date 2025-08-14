<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function app_config(): array {
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config/config.php';
    }
    return $config;
}

function start_session_if_needed(): void {
    $config = app_config();
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name($config['security']['session_name']);
        session_start();
    }
}

function base_url(string $path = ''): string {
    $base = rtrim(app_config()['base_url'], '/');
    $path = ltrim($path, '/');
    return $base . ($path ? '/' . $path : '');
}

function redirect(string $path): void {
    header('Location: ' . base_url($path));
    exit;
}

function csrf_token(): string {
    start_session_if_needed();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    start_session_if_needed();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function is_logged_in(): bool {
    start_session_if_needed();
    return isset($_SESSION['user']);
}

function current_user(): ?array {
    start_session_if_needed();
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('login');
    }
}

function require_role(array $roles): void {
    require_login();
    $user = current_user();
    if (!in_array($user['role'], $roles, true)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

function render(string $view, array $data = []): void {
    extract($data);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        http_response_code(404);
        echo 'View not found: ' . htmlspecialchars($view);
        return;
    }
    include __DIR__ . '/../views/layout/header.php';
    include $viewFile;
    include __DIR__ . '/../views/layout/footer.php';
}

function flash_set(string $key, string $message): void {
    start_session_if_needed();
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string {
    start_session_if_needed();
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}