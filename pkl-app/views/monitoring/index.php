<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Monitoring PKL</h4>
  <?php if ((current_user()['role'] ?? '') === 'siswa'): ?>
    <a class="btn btn-primary" href="<?= base_url('monitoring/create') ?>">Catat Monitoring</a>
  <?php endif; ?>
</div>
<table class="table table-striped">
  <thead><tr><th>Tanggal</th><th>Siswa</th><th>Aktivitas</th><th>Jam</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['date']) ?></td>
        <td><?= htmlspecialchars($r['student_name'] ?? ($r['reviewer_name'] ? 'Saya' : 'Saya')) ?></td>
        <td><?= nl2br(htmlspecialchars($r['activity'])) ?></td>
        <td><?= (int)$r['hours'] ?></td>
        <td><span class="badge bg-<?= $r['status']==='approved'?'success':($r['status']==='rejected'?'danger':'secondary') ?>"><?= htmlspecialchars($r['status']) ?></span></td>
        <td>
          <?php if (in_array((current_user()['role'] ?? ''), ['guru_pembimbing','guru_industri'])): ?>
            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('monitoring/review?id='.(int)$r['id']) ?>">Review</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>