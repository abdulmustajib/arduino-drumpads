<div class="card">
  <div class="card-header">Review Laporan</div>
  <div class="card-body">
    <p><strong>Siswa:</strong> <?= htmlspecialchars($row['student_name']) ?></p>
    <p><strong>Berkas:</strong> <a target="_blank" href="<?= base_url('uploads/'.rawurlencode($row['file_path'])) ?>">Unduh</a></p>
    <form method="post" action="<?= base_url('reports/approve') ?>" class="d-inline">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <button type="submit" class="btn btn-success">Setujui</button>
    </form>
    <form method="post" action="<?= base_url('reports/reject') ?>" class="d-inline ms-2">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <input type="text" name="reason" class="form-control d-inline-block w-auto" placeholder="Alasan penolakan">
      <button type="submit" class="btn btn-danger ms-1">Tolak</button>
    </form>
  </div>
</div>