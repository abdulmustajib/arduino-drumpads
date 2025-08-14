<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

final class DashboardController {
    public function index(): void {
        $user = current_user();
        $role = $user['role'] ?? 'guest';

        switch ($role) {
            case 'admin':
                render('dashboard/admin', ['title' => 'Dashboard Admin']);
                break;
            case 'guru_pembimbing':
                render('dashboard/guru_pembimbing', ['title' => 'Dashboard Guru Pembimbing']);
                break;
            case 'guru_industri':
                render('dashboard/guru_industri', ['title' => 'Dashboard Guru Industri']);
                break;
            case 'siswa':
                render('dashboard/siswa', ['title' => 'Dashboard Siswa']);
                break;
            default:
                http_response_code(403);
                echo 'Forbidden';
        }
    }
}