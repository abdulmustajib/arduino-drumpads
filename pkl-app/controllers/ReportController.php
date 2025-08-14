<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

final class ReportController {
    public function index(): void {
        $user = current_user();
        $pdo = get_pdo();

        if ($user['role'] === 'siswa') {
            $stmt = $pdo->prepare('SELECT r.*, u.name AS reviewer_name FROM reports r LEFT JOIN users u ON u.id = r.reviewed_by WHERE r.student_id = ? ORDER BY r.created_at DESC');
            $stmt->execute([$user['id']]);
        } elseif (in_array($user['role'], ['guru_pembimbing', 'guru_industri', 'admin'], true)) {
            $stmt = $pdo->query('SELECT r.*, s.name AS student_name FROM reports r JOIN users s ON s.id = r.student_id ORDER BY r.created_at DESC');
        } else {
            $stmt = $pdo->query('SELECT * FROM reports ORDER BY created_at DESC');
        }

        $rows = $stmt->fetchAll();
        render('reports/index', ['title' => 'Laporan Siswa', 'rows' => $rows]);
    }

    public function create(): void {
        require_role(['siswa']);
        render('reports/create', ['title' => 'Unggah Laporan PKL']);
    }

    public function store(): void {
        require_role(['siswa']);
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }

        $user = current_user();
        $pdo = get_pdo();

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            flash_set('error', 'File wajib diunggah');
            redirect('reports/create');
        }

        $config = app_config()['upload'];
        $file = $_FILES['file'];
        if ($file['size'] > $config['max_size_bytes']) {
            flash_set('error', 'Ukuran file melebihi batas');
            redirect('reports/create');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $config['allowed_mime'], true)) {
            flash_set('error', 'Tipe file tidak diizinkan');
            redirect('reports/create');
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'report_' . $user['id'] . '_' . time() . '.' . $ext;
        $dest = rtrim($config['dir'], '/') . '/' . $filename;
        if (!is_dir($config['dir'])) {
            mkdir($config['dir'], 0775, true);
        }
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            flash_set('error', 'Gagal menyimpan file');
            redirect('reports/create');
        }

        $stmt = $pdo->prepare('INSERT INTO reports (student_id, file_path, status, created_at) VALUES (?, ?, "submitted", NOW())');
        $stmt->execute([$user['id'], $filename]);
        flash_set('success', 'Laporan berhasil diunggah');
        redirect('reports');
    }

    public function review(): void {
        require_role(['guru_pembimbing', 'guru_industri']);
        $pdo = get_pdo();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT r.*, s.name AS student_name FROM reports r JOIN users s ON s.id = r.student_id WHERE r.id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo 'Data tidak ditemukan';
            return;
        }
        render('reports/review', ['title' => 'Review Laporan', 'row' => $row]);
    }

    public function approve(): void {
        require_role(['guru_pembimbing', 'guru_industri']);
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $pdo = get_pdo();
        $id = (int)($_POST['id'] ?? 0);
        $user = current_user();
        $stmt = $pdo->prepare('UPDATE reports SET status = "approved", reviewed_by = ?, reviewed_at = NOW() WHERE id = ?');
        $stmt->execute([$user['id'], $id]);
        redirect('reports');
    }

    public function reject(): void {
        require_role(['guru_pembimbing', 'guru_industri']);
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $pdo = get_pdo();
        $id = (int)($_POST['id'] ?? 0);
        $user = current_user();
        $reason = trim((string)($_POST['reason'] ?? 'Tidak sesuai'));
        $stmt = $pdo->prepare('UPDATE reports SET status = "rejected", reviewed_by = ?, reviewed_at = NOW(), rejection_reason = ? WHERE id = ?');
        $stmt->execute([$user['id'], $reason, $id]);
        redirect('reports');
    }
}