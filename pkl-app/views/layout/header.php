<?php
start_session_if_needed();
$config = app_config();
$title = $title ?? $config['app_name'];
$user = current_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars($config['app_name']) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('dashboard') ?>">PKL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('monitoring') ?>">Monitoring</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('reports') ?>">Laporan</a></li>
          <?php if ($user['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/users') ?>">Pengguna</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/companies') ?>">Perusahaan</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/assignments') ?>">Penugasan</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if ($user): ?>
          <li class="nav-item"><span class="navbar-text me-3">Halo, <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role']) ?>)</span></li>
          <li class="nav-item"><a class="btn btn-outline-light" href="<?= base_url('logout') ?>">Keluar</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-outline-light" href="<?= base_url('login') ?>">Masuk</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
<?php if ($msg = flash_get('success')): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($msg = flash_get('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div><?php endif; ?>