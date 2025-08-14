<div class="row">
  <div class="col-md-6">
    <h4>Perusahaan</h4>
    <table class="table table-bordered">
      <thead><tr><th>Nama</th><th>Alamat</th><th>Kontak</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['address']) ?></td>
            <td><?= htmlspecialchars($r['contact_person']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-md-6">
    <h4>Tambah Perusahaan</h4>
    <form method="post" action="<?= base_url('admin/companies/store') ?>">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" name="address" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Kontak</label>
        <input type="text" name="contact" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>