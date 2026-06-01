<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong mb-20">' . $notification . '</div>';
  } ?>

  <div class="settings-card mb-20">
    <div class="card-header header-flex">
      <div>
        <h1 class="card-title">Kelola Gallery</h1>
        <p class="card-subtitle">Upload, edit keterangan, atau hapus foto yang ditampilkan di halaman galeri.</p>
      </div>
      <a href="<?php echo set_url("site/gallery_create"); ?>" class="btn-upload">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Upload Foto
      </a>
    </div>

    <!-- Gallery Grid Admin -->
    <div class="gallery-grid-wrapper">
      <?php if (!empty($gallery_list)) { ?>
      <div class="gallery-grid-admin">
        <?php foreach ($gallery_list as $photo) { ?>
        <div class="gallery-card-admin">
          <!-- Thumbnail -->
          <div class="gallery-thumb">
            <img src="<?php echo $photo['GAMBAR']; ?>" alt="Gallery" class="gallery-img" onerror="this.style.display='none'" />
          </div>
          <!-- Info + Aksi -->
          <div class="gallery-info-box">
            <p class="gallery-caption">
              <?php echo !empty($photo['KETERANGAN']) ? htmlspecialchars(mb_strimwidth($photo['KETERANGAN'], 0, 60, '...')) : '<em class="empty-caption">Tanpa keterangan</em>'; ?>
            </p>
            <p class="gallery-date">
              <?php echo date("d M Y, H:i", strtotime($photo['TIMECREATE'])); ?>
            </p>
            <div class="gallery-actions">
              <a href="<?php echo set_url("site/gallery_edit"); ?>&id=<?php echo $photo['ID']; ?>" title="Edit Keterangan" class="btn-edit-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
              </a>
              <button type="button" onclick="openDeleteModal(<?php echo $photo['ID']; ?>, <?php echo htmlspecialchars(json_encode(!empty($photo['KETERANGAN']) ? mb_strimwidth($photo['KETERANGAN'], 0, 30, '...') : 'foto ini'), ENT_QUOTES, 'UTF-8'); ?>)" title="Hapus" class="btn-delete-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>

      <?php } else { ?>
      <!-- Empty State -->
      <div class="empty-gallery-admin">
        <div class="empty-gallery-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        </div>
        <h3 class="empty-gallery-title">Belum Ada Foto</h3>
        <p class="empty-gallery-desc">Klik tombol "Upload Foto" untuk menambahkan foto ke galeri.</p>
        <a href="<?php echo set_url("site/gallery_create"); ?>" class="btn-upload-first">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Upload Foto Pertama
        </a>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="delete-modal-overlay" style="display: none;" onclick="if(event.target===this) closeDeleteModal()">
  <div class="delete-modal-box">
    <div class="delete-modal-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
    </div>
    <h3 class="delete-modal-title">Hapus Foto?</h3>
    <p id="deleteModalText" class="delete-modal-text">Foto ini akan dihapus secara permanen dari galeri dan server.</p>
    <div class="delete-modal-actions">
      <button onclick="closeDeleteModal()" class="btn-cancel-modal">Batal</button>
      <a id="deleteModalBtn" href="#" class="btn-delete-modal">Ya, Hapus</a>
    </div>
  </div>
</div>

<script>
function openDeleteModal(id, label) {
  document.getElementById('deleteModalText').innerHTML = 'Apakah Anda yakin ingin menghapus <strong>"' + label + '"</strong>? Foto akan dihapus dari server secara permanen.';
  document.getElementById('deleteModalBtn').href = '<?php echo set_url("site/gallery_delete"); ?>&id=' + id;
  document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
</script>
