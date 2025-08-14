<div class="row">
  <div class="col-md-7">
    <h4>Penugasan Siswa</h4>
    <table class="table table-bordered">
      <thead><tr><th>Siswa</th><th>Mentor</th><th>Perusahaan</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['student_name']) ?></td>
            <td><?= htmlspecialchars($r['mentor_name']) ?></td>
            <td><?= htmlspecialchars($r['company_name']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-md-5">
    <h4>Tambah Penugasan</h4>
    <form method="post" action="<?= base_url('admin/assignments/store') ?>">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <div class="mb-3">
        <label class="form-label">Siswa</label>
        <select name="student_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($students as $s): ?>
            <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Mentor</label>
        <select name="mentor_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($mentors as $m): ?>
            <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Perusahaan</label>
        <select name="company_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($companies as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>