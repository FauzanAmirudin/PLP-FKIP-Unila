<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Monitor, Operator');

$isEdit = isset($edit_mode) && $edit_mode === true;
$art = isset($article) && !empty($article) ? $article : null;
?>

<style>
.ck-editor__editable_inline {
    min-height: 250px;
}
</style>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong mb-20">' . $notification . '</div>';
  } ?>

  <div class="settings-card mb-20">
    <div class="card-header">
      <h1 class="card-title"><?php echo $isEdit ? 'Edit Informasi' : 'Tambah Informasi Baru'; ?></h1>
      <p class="card-subtitle"><?php echo $isEdit ? 'Perbarui konten informasi yang sudah ada.' : 'Buat informasi baru yang akan ditampilkan di halaman publik.'; ?></p>
    </div>

    <form method="post" action="<?php echo $isEdit ? '?page=site/informasi_edit&id=' . $art['ID'] : '?page=site/informasi_create'; ?>" enctype="multipart/form-data" class="mt-24">
      
      <!-- Judul -->
      <div class="settings-form-group mb-20">
        <label for="judul" class="form-label-bold">Judul Informasi <span class="asterisk">*</span></label>
        <input type="text" id="judul" name="judul" value="<?php echo $isEdit ? htmlspecialchars($art['JUDUL']) : ''; ?>" placeholder="Masukkan judul informasi" required class="form-input-custom" />
      </div>

      <!-- Tanggal & Tag -->
      <div class="form-grid-2">
        <div class="settings-form-group">
          <label for="tanggal" class="form-label-bold">Tanggal Publikasi <span class="asterisk">*</span></label>
          <input type="date" id="tanggal" name="tanggal" value="<?php echo $isEdit ? $art['TANGGAL'] : date('Y-m-d'); ?>" required class="form-input-custom" />
          <span class="hint-text-sm">Informasi dengan tanggal masa depan akan disembunyikan dari publik.</span>
        </div>
        <div class="settings-form-group">
          <label for="tag" class="form-label-bold">Kategori <span class="asterisk">*</span></label>
          <input type="text" id="tag" name="tag" value="<?php echo $isEdit ? htmlspecialchars($art['TAG']) : ''; ?>" placeholder="Contoh: Pengumuman, Akademik" required class="form-input-custom" />
        </div>
      </div>

      <!-- Penulis -->
      <div class="settings-form-group mb-20">
        <label for="penulis" class="form-label-bold">Penulis <span class="asterisk">*</span></label>
        <input type="text" id="penulis" name="penulis" value="<?php echo $isEdit ? htmlspecialchars($art['PENULIS']) : (isset($user['USERID']) ? htmlspecialchars($user['USERID']) : 'Admin'); ?>" required class="form-input-custom" />
      </div>

      <!-- Konten -->
      <div class="settings-form-group mb-20">
        <label for="informasi" class="form-label-bold">Isi Informasi <span class="asterisk">*</span></label>
        <textarea id="informasi" name="informasi" rows="10" placeholder="Tulis isi informasi di sini..." class="form-textarea-custom"><?php echo $isEdit ? htmlspecialchars($art['INFORMASI']) : ''; ?></textarea>
      </div>

      <!-- Gambar (Opsional) -->
      <div class="settings-form-group mb-20">
        <label class="form-label-bold">Gambar <span class="optional">(opsional, maks 1MB, format: jpg/jpeg/png)</span></label>
        
        <?php if ($isEdit && !empty($art['GAMBAR'])) { ?>
        <div id="currentImagePreview" class="current-img-preview-box">
          <p class="preview-label">Gambar saat ini:</p>
          <img src="<?php echo $art['GAMBAR']; ?>" alt="Preview" class="preview-img" />
          <div class="mt-10">
            <label class="delete-check-label">
              <input type="checkbox" name="hapus_gambar" value="1" />
              Hapus gambar ini
            </label>
          </div>
        </div>
        <?php } ?>

        <div id="imageUploadArea" class="upload-area" ondragover="event.preventDefault(); this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="event.preventDefault(); this.classList.remove('drag-over'); document.getElementById('gambarInput').files = event.dataTransfer.files; previewImage(event.dataTransfer.files[0]);">
          <input type="file" id="gambarInput" name="gambar" accept="image/jpeg,image/jpg,image/png" class="upload-input-hidden" onchange="if(this.files[0]) previewImage(this.files[0])" />
          <div id="uploadPlaceholder" class="upload-placeholder-content">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p class="placeholder-main">Klik atau seret gambar ke sini</p>
            <p class="placeholder-sub">JPG, JPEG, PNG — Maksimal 1MB</p>
          </div>
          <div id="imagePreview" class="upload-preview" style="display: none;">
            <img id="previewImg" src="" alt="Preview" class="upload-preview-img" />
            <p id="previewName" class="upload-preview-name"></p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-actions-flex">
        <button type="submit" class="btn-action-submit">
          <?php echo $isEdit ? 'Simpan Perubahan' : 'Publikasikan'; ?>
        </button>
        <a href="<?php echo set_url("site/informasi"); ?>" class="btn-action-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  var myEditor = null;

  function initEditor() {
    var target = document.querySelector('#informasi');
    if (!target) return;
    if (typeof ClassicEditor !== 'undefined') {
      ClassicEditor
        .create(target, {
          toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
        })
        .then(function (editor) {
          myEditor = editor;
        })
        .catch(function (error) {
          console.error('CKEditor Init Error:', error);
        });
    }
  }

  initEditor();

  var form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function (e) {
      if (myEditor) {
        var rawData = myEditor.getData();
        var textContent = rawData.replace(/<[^>]*>/g, '').trim();
        if (!textContent) {
          e.preventDefault();
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Isi informasi tidak boleh kosong!',
              confirmButtonColor: '#a805a8'
            });
          } else {
            alert('Isi informasi tidak boleh kosong!');
          }
          return false;
        }
        document.querySelector('#informasi').value = rawData;
      }
    });
  }
});

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
