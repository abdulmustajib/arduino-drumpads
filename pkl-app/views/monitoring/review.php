<div class="card">
  <div class="card-header">Review Monitoring</div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Siswa</dt><dd class="col-sm-9"><?= htmlspecialchars($row['student_name']) ?></dd>
      <dt class="col-sm-3">Tanggal</dt><dd class="col-sm-9"><?= htmlspecialchars($row['date']) ?></dd>
      <dt class="col-sm-3">Aktivitas</dt><dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['activity'])) ?></dd>
      <dt class="col-sm-3">Jam</dt><dd class="col-sm-9"><?= (int)$row['hours'] ?></dd>
    </dl>
    <form method="post" action="<?= base_url('monitoring/approve') ?>" class="d-inline">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <button type="submit" class="btn btn-success">Setujui</button>
    </form>
    <form method="post" action="<?= base_url('monitoring/reject') ?>" class="d-inline ms-2">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <input type="text" name="reason" class="form-control d-inline-block w-auto" placeholder="Alasan penolakan">
      <button type="submit" class="btn btn-danger ms-1">Tolak</button>
    </form>
  </div>
</div>