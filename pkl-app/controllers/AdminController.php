<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

final class AdminController {
    public function users(): void {
        $pdo = get_pdo();
        $rows = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
        render('admin/users', ['title' => 'Manajemen Pengguna', 'rows' => $rows]);
    }

    public function usersCreate(): void {
        render('admin/users_create', ['title' => 'Tambah Pengguna']);
    }

    public function usersStore(): void {
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = (string)($_POST['role'] ?? 'siswa');
        $password = (string)($_POST['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            flash_set('error', 'Semua field wajib diisi');
            redirect('admin/users/create');
        }
        $pdo = get_pdo();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, role, password_hash, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$name, $email, $role, $hash]);
        flash_set('success', 'Pengguna berhasil ditambahkan');
        redirect('admin/users');
    }

    public function companies(): void {
        $pdo = get_pdo();
        $rows = $pdo->query('SELECT id, name, address, contact_person FROM companies ORDER BY name')->fetchAll();
        render('admin/companies', ['title' => 'Perusahaan', 'rows' => $rows]);
    }

    public function companiesStore(): void {
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $name = trim((string)($_POST['name'] ?? ''));
        $address = trim((string)($_POST['address'] ?? ''));
        $contact = trim((string)($_POST['contact'] ?? ''));
        if ($name === '') {
            flash_set('error', 'Nama perusahaan wajib diisi');
            redirect('admin/companies');
        }
        $pdo = get_pdo();
        $stmt = $pdo->prepare('INSERT INTO companies (name, address, contact_person) VALUES (?, ?, ?)');
        $stmt->execute([$name, $address, $contact]);
        flash_set('success', 'Perusahaan ditambahkan');
        redirect('admin/companies');
    }

    public function assignments(): void {
        $pdo = get_pdo();
        $rows = $pdo->query('SELECT sa.id, s.name AS student_name, u.name AS mentor_name, c.name AS company_name FROM student_assignments sa JOIN users s ON s.id = sa.student_id JOIN users u ON u.id = sa.assigned_mentor_id JOIN companies c ON c.id = sa.company_id ORDER BY sa.id DESC')->fetchAll();
        $students = $pdo->query("SELECT id, name FROM users WHERE role = 'siswa' ORDER BY name")->fetchAll();
        $mentors = $pdo->query("SELECT id, name FROM users WHERE role IN ('guru_pembimbing','guru_industri') ORDER BY name")->fetchAll();
        $companies = $pdo->query('SELECT id, name FROM companies ORDER BY name')->fetchAll();
        render('admin/assignments', [
            'title' => 'Penugasan Siswa',
            'rows' => $rows,
            'students' => $students,
            'mentors' => $mentors,
            'companies' => $companies,
        ]);
    }

    public function assignmentsStore(): void {
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $student = (int)($_POST['student_id'] ?? 0);
        $mentor = (int)($_POST['mentor_id'] ?? 0);
        $company = (int)($_POST['company_id'] ?? 0);

        if ($student <= 0 || $mentor <= 0 || $company <= 0) {
            flash_set('error', 'Data tidak lengkap');
            redirect('admin/assignments');
        }
        $pdo = get_pdo();
        $stmt = $pdo->prepare('INSERT INTO student_assignments (student_id, assigned_mentor_id, company_id, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$student, $mentor, $company]);
        flash_set('success', 'Penugasan dibuat');
        redirect('admin/assignments');
    }
}