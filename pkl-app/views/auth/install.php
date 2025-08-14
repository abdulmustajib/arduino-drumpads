<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Instalasi Awal</div>
      <div class="card-body">
        <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" action="<?= base_url('install') ?>">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label">Nama Admin</label>
            <input type="text" name="name" class="form-control" value="Administrator" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="admin@example.com" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Buat Admin</button>
        </form>
      </div>
    </div>
  </div>
</div>