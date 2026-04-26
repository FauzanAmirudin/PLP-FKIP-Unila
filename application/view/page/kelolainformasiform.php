<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');

$isEdit = isset($edit_mode) && $edit_mode === true;
$art = isset($article) && !empty($article) ? $article : null;
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
  } ?>

  <div class="settings-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h1 class="card-title"><?php echo $isEdit ? 'Edit Informasi' : 'Tambah Informasi Baru'; ?></h1>
      <p class="card-subtitle"><?php echo $isEdit ? 'Perbarui konten informasi yang sudah ada.' : 'Buat informasi baru yang akan ditampilkan di halaman publik.'; ?></p>
    </div>

    <form method="post" action="<?php echo $isEdit ? '?page=site/informasi_edit&id=' . $art['ID'] : '?page=site/informasi_create'; ?>" enctype="multipart/form-data" style="margin-top: 24px;">
      
      <!-- Judul -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label for="judul" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Judul Informasi <span style="color: #dc2626;">*</span></label>
        <input type="text" id="judul" name="judul" value="<?php echo $isEdit ? htmlspecialchars($art['JUDUL']) : ''; ?>" placeholder="Masukkan judul informasi" required style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'" onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'" />
      </div>

      <!-- Tanggal & Tag -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="settings-form-group">
          <label for="tanggal" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Tanggal Publikasi <span style="color: #dc2626;">*</span></label>
          <input type="date" id="tanggal" name="tanggal" value="<?php echo $isEdit ? $art['TANGGAL'] : date('Y-m-d'); ?>" required style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; color: #1e293b; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'" onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'" />
          <span style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">Informasi dengan tanggal masa depan akan disembunyikan dari publik.</span>
        </div>
        <div class="settings-form-group">
          <label for="tag" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Kategori <span style="color: #dc2626;">*</span></label>
          <input type="text" id="tag" name="tag" value="<?php echo $isEdit ? htmlspecialchars($art['TAG']) : ''; ?>" placeholder="Contoh: Pengumuman, Akademik" required style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'" onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'" />
        </div>
      </div>

      <!-- Penulis -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label for="penulis" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Penulis <span style="color: #dc2626;">*</span></label>
        <input type="text" id="penulis" name="penulis" value="<?php echo $isEdit ? htmlspecialchars($art['PENULIS']) : (isset($user['USERID']) ? htmlspecialchars($user['USERID']) : 'Admin'); ?>" required style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'" onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'" />
      </div>

      <!-- Konten -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label for="informasi" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Isi Informasi <span style="color: #dc2626;">*</span></label>
        <textarea id="informasi" name="informasi" rows="10" placeholder="Tulis isi informasi di sini..." required style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; outline: none; resize: vertical; line-height: 1.6; font-family: inherit; transition: all 0.2s;" onfocus="this.style.borderColor='#a805a8';this.style.boxShadow='0 0 0 3px rgba(168,5,168,0.1)'" onblur="this.style.borderColor='#ddd';this.style.boxShadow='none'"><?php echo $isEdit ? htmlspecialchars($art['INFORMASI']) : ''; ?></textarea>
      </div>

      <!-- Gambar (Opsional) -->
      <div class="settings-form-group" style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 14px;">Gambar <span style="color: #94a3b8; font-weight: 400;">(opsional, maks 1MB, format: jpg/jpeg/png)</span></label>
        
        <?php if ($isEdit && !empty($art['GAMBAR'])) { ?>
        <div id="currentImagePreview" style="margin-bottom: 12px; padding: 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
          <p style="font-size: 12px; color: #64748b; margin: 0 0 8px;">Gambar saat ini:</p>
          <img src="<?php echo $art['GAMBAR']; ?>" alt="Preview" style="max-width: 300px; max-height: 200px; border-radius: 8px; object-fit: cover;" />
          <div style="margin-top: 8px;">
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; font-size: 13px; color: #dc2626;">
              <input type="checkbox" name="hapus_gambar" value="1" style="accent-color: #dc2626;" />
              Hapus gambar ini
            </label>
          </div>
        </div>
        <?php } ?>

        <div id="imageUploadArea" style="position: relative; padding: 30px; border: 2px dashed #d1d5db; border-radius: 8px; text-align: center; transition: all 0.2s; cursor: pointer; background: #fafafa;" ondragover="event.preventDefault(); this.style.borderColor='#a805a8'; this.style.background='#faf5ff'" ondragleave="this.style.borderColor='#d1d5db'; this.style.background='#fafafa'" ondrop="event.preventDefault(); this.style.borderColor='#d1d5db'; this.style.background='#fafafa'; document.getElementById('gambarInput').files = event.dataTransfer.files; previewImage(event.dataTransfer.files[0]);">
          <input type="file" id="gambarInput" name="gambar" accept="image/jpeg,image/jpg,image/png" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="if(this.files[0]) previewImage(this.files[0])" />
          <div id="uploadPlaceholder">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom: 8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p style="margin: 0; font-size: 14px; color: #64748b;">Klik atau seret gambar ke sini</p>
            <p style="margin: 4px 0 0; font-size: 12px; color: #94a3b8;">JPG, JPEG, PNG — Maksimal 1MB</p>
          </div>
          <div id="imagePreview" style="display: none;">
            <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 250px; border-radius: 8px; object-fit: contain;" />
            <p id="previewName" style="margin: 8px 0 0; font-size: 12px; color: #64748b;"></p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
        <button type="submit" class="btn-save" style="padding: 12px 28px; background: #a805a8; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#8a0489'" onmouseout="this.style.background='#a805a8'">
          <?php echo $isEdit ? 'Simpan Perubahan' : 'Publikasikan'; ?>
        </button>
        <a href="<?php echo set_url("site/informasi"); ?>" style="padding: 12px 28px; background: #f1f5f9; color: #475569; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">Batal</a>
      </div>
    </form>
  </div>
</div>

<style>
  @media (max-width: 640px) {
    .settings-container form > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
  
  /* Memaksa warna teks pada input date menjadi hitam/gelap dan menimpa warna bawaan browser */
  input[type="date"]::-webkit-datetime-edit-text,
  input[type="date"]::-webkit-datetime-edit-month-field,
  input[type="date"]::-webkit-datetime-edit-day-field,
  input[type="date"]::-webkit-datetime-edit-year-field {
    color: #1e293b !important;
  }
  input[type="date"] {
    color: #1e293b !important;
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
</script>
