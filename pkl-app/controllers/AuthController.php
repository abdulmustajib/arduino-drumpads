<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

final class AuthController {
    public function login(): void {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        render('auth/login', [
            'title' => 'Masuk',
            'error' => flash_get('error')
        ]);
    }

    public function loginPost(): void {
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            flash_set('error', 'Email dan password wajib diisi');
            redirect('login');
        }

        $pdo = get_pdo();
        $stmt = $pdo->prepare('SELECT id, name, email, role, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash_set('error', 'Kredensial tidak valid');
            redirect('login');
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        redirect('dashboard');
    }

    public function logout(): void {
        start_session_if_needed();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        redirect('login');
    }

    public function install(): void {
        $pdo = get_pdo();
        $exists = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
        if (!$exists) {
            $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
            $pdo->exec($schema);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf'] ?? '')) {
                http_response_code(400);
                echo 'Invalid CSRF token';
                return;
            }
            $name = trim((string)($_POST['name'] ?? 'Administrator'));
            $email = trim((string)($_POST['email'] ?? 'admin@example.com'));
            $password = (string)($_POST['password'] ?? 'admin123');

            if ($email === '' || $password === '') {
                flash_set('error', 'Email dan password wajib diisi');
                redirect('install');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, role, password_hash, created_at) VALUES (?, ?, "admin", ?, NOW())');
            $stmt->execute([$name, $email, $hash]);
            flash_set('success', 'Admin berhasil dibuat. Silakan login.');
            redirect('login');
        }

        render('auth/install', [
            'title' => 'Instalasi Awal',
            'error' => flash_get('error'),
            'success' => flash_get('success'),
        ]);
    }
}