<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header">Masuk</div>
      <div class="card-body">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" action="<?= base_url('login') ?>">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Masuk</button>
        </form>
        <div class="mt-3">
          <a href="<?= base_url('install') ?>">Instalasi awal</a>
        </div>
      </div>
    </div>
  </div>
</div>