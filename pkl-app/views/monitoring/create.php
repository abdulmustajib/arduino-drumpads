<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Catat Monitoring</div>
      <div class="card-body">
        <form method="post" action="<?= base_url('monitoring') ?>">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Aktivitas</label>
            <textarea name="activity" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah Jam</label>
            <input type="number" name="hours" class="form-control" min="1" required>
          </div>
          <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
      </div>
    </div>
  </div>
</div>