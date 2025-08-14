<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Tambah Pengguna</div>
      <div class="card-body">
        <form method="post" action="<?= base_url('admin/users/store') ?>">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <option value="siswa">Siswa</option>
              <option value="guru_pembimbing">Guru Pembimbing</option>
              <option value="guru_industri">Guru Industri</option>
              <option value="admin">Administrator</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>