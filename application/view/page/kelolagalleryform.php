<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Monitor, Operator');

$isEdit = isset($edit_mode) && $edit_mode === true;
$pht    = isset($photo) && !empty($photo) ? $photo : null;
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong mb-20">' . $notification . '</div>';
  } ?>

  <div class="settings-card mb-20">
    <div class="card-header">
      <h1 class="card-title"><?php echo $isEdit ? 'Edit Keterangan Foto' : 'Upload Foto Baru'; ?></h1>
      <p class="card-subtitle"><?php echo $isEdit ? 'Perbarui keterangan/caption dari foto ini.' : 'Upload foto baru ke galeri. Keterangan bersifat opsional.'; ?></p>
    </div>

    <form method="post"
          action="<?php echo $isEdit ? '?page=site/gallery_edit&id=' . $pht['ID'] : '?page=site/gallery_create'; ?>"
          enctype="multipart/form-data"
          class="mt-24">

      <?php if ($isEdit && !empty($pht)) { ?>
      <!-- Preview foto saat ini (mode edit) -->
      <div class="gallery-current-photo">
        <p class="gallery-current-label">Foto Saat Ini</p>
        <img src="<?php echo $pht['GAMBAR']; ?>" alt="Preview" class="gallery-current-img" />
        <p class="gallery-current-hint">Untuk mengganti foto, hapus entri ini lalu upload foto baru.</p>
      </div>
      <?php } ?>

      <?php if (!$isEdit) { ?>
      <!-- Upload Foto (mode create saja) -->
      <div class="settings-form-group mb-20">
        <label class="form-label-bold">
          Pilih Foto <span class="asterisk">*</span>
          <span class="hint">(JPG / JPEG / PNG, maks 1MB)</span>
        </label>

        <div id="imageUploadArea" class="upload-area"
             ondragover="event.preventDefault(); this.classList.add('drag-over')"
             ondragleave="this.classList.remove('drag-over')"
             ondrop="event.preventDefault(); this.classList.remove('drag-over'); handleDrop(event.dataTransfer.files[0]);">
          <input type="file" id="gambarInput" name="gambar" accept="image/jpeg,image/jpg,image/png" required
                 class="upload-input-hidden"
                 onchange="if(this.files[0]) previewImage(this.files[0])" />
          <div id="uploadPlaceholder" class="upload-placeholder-content">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p class="placeholder-main">Klik atau seret foto ke sini</p>
            <p class="placeholder-sub">JPG, JPEG, PNG — Maksimal 1MB</p>
          </div>
          <div id="imagePreview" class="upload-preview" style="display: none;">
            <img id="previewImg" src="" alt="Preview" class="upload-preview-img" />
            <p id="previewName" class="upload-preview-name"></p>
          </div>
        </div>
      </div>
      <?php } ?>

      <!-- Keterangan (opsional) -->
      <div class="settings-form-group mb-20">
        <label for="keterangan" class="form-label-bold">
          Keterangan <span class="optional">(opsional)</span>
        </label>
        <textarea id="keterangan" name="keterangan" rows="4"
                  placeholder="Masukkan keterangan atau caption foto..."
                  class="form-textarea-custom"><?php echo ($isEdit && $pht) ? htmlspecialchars($pht['KETERANGAN']) : ''; ?></textarea>
      </div>

      <!-- Tombol Aksi -->
      <div class="form-actions-flex">
        <button type="submit" class="btn-submit-main">
          <?php echo $isEdit ? 'Simpan Keterangan' : 'Upload ke Galeri'; ?>
        </button>
        <a href="<?php echo set_url("site/gallery"); ?>" class="btn-cancel-light">Batal</a>
      </div>
    </form>
  </div>
</div>



<script>
function previewImage(file) {
  if (!file) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('uploadPlaceholder').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'block';
    document.getElementById('previewImg').src = e.target.result;
    document.getElementById('previewName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
  };
  reader.readAsDataURL(file);
}
function handleDrop(file) {
  if (!file) return;
  // Assign to the actual file input
  var dt = new DataTransfer();
  dt.items.add(file);
  document.getElementById('gambarInput').files = dt.files;
  previewImage(file);
}
</script>
