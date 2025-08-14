<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Pengguna</h4>
  <a class="btn btn-primary" href="<?= base_url('admin/users/create') ?>">Tambah</a>
</div>
<table class="table table-bordered">
  <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['role']) ?></td>
        <td><?= htmlspecialchars($r['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>