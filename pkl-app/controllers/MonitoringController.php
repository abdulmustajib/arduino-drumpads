<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

final class MonitoringController {
    public function index(): void {
        $user = current_user();
        $pdo = get_pdo();

        if ($user['role'] === 'siswa') {
            $stmt = $pdo->prepare('SELECT m.*, u.name AS reviewer_name FROM monitorings m LEFT JOIN users u ON u.id = m.reviewed_by WHERE m.student_id = ? ORDER BY m.date DESC');
            $stmt->execute([$user['id']]);
        } elseif (in_array($user['role'], ['guru_pembimbing', 'guru_industri'], true)) {
            $stmt = $pdo->prepare('SELECT m.*, s.name AS student_name FROM monitorings m JOIN users s ON s.id = m.student_id WHERE m.assigned_mentor_id = ? ORDER BY m.date DESC');
            $stmt->execute([$user['id']]);
        } else {
            $stmt = $pdo->query('SELECT m.*, s.name AS student_name FROM monitorings m JOIN users s ON s.id = m.student_id ORDER BY m.date DESC');
        }

        $rows = $stmt->fetchAll();
        render('monitoring/index', ['title' => 'Monitoring PKL', 'rows' => $rows]);
    }

    public function create(): void {
        require_role(['siswa']);
        render('monitoring/create', ['title' => 'Catatan Monitoring']);
    }

    public function store(): void {
        require_role(['siswa']);
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $pdo = get_pdo();
        $user = current_user();

        $date = $_POST['date'] ?? date('Y-m-d');
        $activity = trim((string)($_POST['activity'] ?? ''));
        $hours = (int)($_POST['hours'] ?? 0);

        if ($activity === '' || $hours <= 0) {
            flash_set('error', 'Aktivitas dan durasi jam wajib diisi dengan benar');
            redirect('monitoring/create');
        }

        $stmt = $pdo->prepare('INSERT INTO monitorings (student_id, assigned_mentor_id, date, activity, hours, status, created_at) VALUES (?, (SELECT assigned_mentor_id FROM student_assignments WHERE student_id = ? LIMIT 1), ?, ?, ?, "submitted", NOW())');
        $stmt->execute([$user['id'], $user['id'], $date, $activity, $hours]);
        flash_set('success', 'Catatan monitoring berhasil dikirim');
        redirect('monitoring');
    }

    public function review(): void {
        require_role(['guru_pembimbing', 'guru_industri']);
        $pdo = get_pdo();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT m.*, s.name AS student_name FROM monitorings m JOIN users s ON s.id = m.student_id WHERE m.id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo 'Data tidak ditemukan';
            return;
        }
        render('monitoring/review', ['title' => 'Review Monitoring', 'row' => $row]);
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
        $stmt = $pdo->prepare('UPDATE monitorings SET status = "approved", reviewed_by = ?, reviewed_at = NOW() WHERE id = ?');
        $stmt->execute([$user['id'], $id]);
        redirect('monitoring');
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
        $stmt = $pdo->prepare('UPDATE monitorings SET status = "rejected", reviewed_by = ?, reviewed_at = NOW(), rejection_reason = ? WHERE id = ?');
        $stmt->execute([$user['id'], $reason, $id]);
        redirect('monitoring');
    }
}