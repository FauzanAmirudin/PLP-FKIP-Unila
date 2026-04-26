<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');

$isEdit = isset($edit_mode) && $edit_mode === true;
$pht    = isset($photo) && !empty($photo) ? $photo : null;
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
  } ?>

  <div class="settings-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h1 class="card-title"><?php echo $isEdit ? 'Edit Keterangan Foto' : 'Upload Foto Baru'; ?></h1>
      <p class="card-subtitle"><?php echo $isEdit ? 'Perbarui keterangan/caption dari foto ini.' : 'Upload foto baru ke galeri. Keterangan bersifat opsional.'; ?></p>
    </div>

    <form method="post"
          action="<?php echo $isEdit ? '?page=site/gallery_edit&id=' . $pht['ID'] : '?page=site/gallery_create'; ?>"
          enctype="multipart/form-data"
          style="margin-top: 24px;">

      <?php if ($isEdit && !empty($pht)) { ?>
      <!-- Preview foto saat ini (mode edit) -->
      <div style="margin-bottom: 24px; padding: 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
        <p style="font-size: 12px; font-weight: 600; color: #64748b; margin: 0 0 10px; text-transform: uppercase; letter-spacing: 0.5px;">Foto Saat Ini</p>
        <img src="<?php echo $pht['GAMBAR']; ?>" alt="Preview" style="max-width: 320px; max-height: 220px; border-radius: 8px; object-fit: cover; display: block;" />
        <p style="font-size: 11px; color: #94a3b8; margin: 8px 0 0;">Untuk mengganti foto, hapus entri ini lalu upload foto baru.</p>
      </div>
      <?php } ?>

      <?php if (!$isEdit) { ?>
      <!-- Upload Foto (mode create saja) -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">
          Pilih Foto <span style="color: #dc2626;">*</span>
          <span style="color: #94a3b8; font-weight: 400;">(JPG / JPEG / PNG, maks 1MB)</span>
        </label>

        <div id="imageUploadArea" style="position: relative; padding: 36px 20px; border: 2px dashed #d1d5db; border-radius: 10px; text-align: center; transition: all 0.2s; cursor: pointer; background: #fafafa;"
             ondragover="event.preventDefault(); this.style.borderColor='#a805a8'; this.style.background='#faf5ff'"
             ondragleave="this.style.borderColor='#d1d5db'; this.style.background='#fafafa'"
             ondrop="event.preventDefault(); this.style.borderColor='#d1d5db'; this.style.background='#fafafa'; handleDrop(event.dataTransfer.files[0]);">
          <input type="file" id="gambarInput" name="gambar" accept="image/jpeg,image/jpg,image/png" required
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;"
                 onchange="if(this.files[0]) previewImage(this.files[0])" />
          <div id="uploadPlaceholder">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom: 10px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p style="margin: 0; font-size: 14px; color: #64748b; font-weight: 500;">Klik atau seret foto ke sini</p>
            <p style="margin: 4px 0 0; font-size: 12px; color: #94a3b8;">JPG, JPEG, PNG — Maksimal 1MB</p>
          </div>
          <div id="imagePreview" style="display: none;">
            <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 260px; border-radius: 8px; object-fit: contain; pointer-events: none;" />
            <p id="previewName" style="margin: 8px 0 0; font-size: 12px; color: #64748b;"></p>
          </div>
        </div>
      </div>
      <?php } ?>

      <!-- Keterangan (opsional) -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label for="keterangan" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">
          Keterangan <span style="color: #94a3b8; font-weight: 400;">(opsional)</span>
        </label>
        <textarea id="keterangan" name="keterangan" rows="4"
                  placeholder="Masukkan keterangan atau caption foto..."
                  style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; outline: none; resize: vertical; line-height: 1.6; font-family: inherit; transition: all 0.2s; color: #1e293b;"
                  onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'"
                  onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'"><?php echo ($isEdit && $pht) ? htmlspecialchars($pht['KETERANGAN']) : ''; ?></textarea>
      </div>

      <!-- Tombol Aksi -->
      <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
        <button type="submit" style="padding: 12px 28px; background: #a805a8; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#8a0489'" onmouseout="this.style.background='#a805a8'">
          <?php echo $isEdit ? 'Simpan Keterangan' : 'Upload ke Galeri'; ?>
        </button>
        <a href="<?php echo set_url("site/gallery"); ?>" style="padding: 12px 28px; background: #f1f5f9; color: #475569; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Batal</a>
      </div>
    </form>
  </div>
</div>

<style>
  input[type="date"] { color: #1e293b !important; }
  @media (max-width: 480px) {
    #imageUploadArea { padding: 24px 12px !important; }
  }
</style>

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
