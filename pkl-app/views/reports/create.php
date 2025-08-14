<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Unggah Laporan PKL</div>
      <div class="card-body">
        <form method="post" action="<?= base_url('reports') ?>" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <div class="mb-3">
            <label class="form-label">File (PDF/JPG/PNG)</label>
            <input type="file" name="file" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Unggah</button>
        </form>
      </div>
    </div>
  </div>
</div>