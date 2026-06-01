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
        <h1 class="card-title">Kelola Informasi</h1>
        <p class="card-subtitle">Tambah, edit, atau hapus informasi yang ditampilkan di halaman publik.</p>
      </div>
      <a href="<?php echo set_url("site/informasi_create"); ?>" class="btn-action-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Informasi
      </a>
    </div>

    <div class="table-responsive-wrapper">
      <?php if (!empty($informasi_list)) { ?>
      <table class="modern-table admin-table">
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th>Judul</th>
            <th class="col-date">Tanggal</th>
            <th class="col-author">Penulis</th>
            <th class="col-category">Kategori</th>
            <th class="col-img">Gambar</th>
            <th class="col-actions">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $n = 1; foreach ($informasi_list as $info) { ?>
          <tr>
            <td><?php echo $n++; ?></td>
            <td class="td-title-bold"><?php echo htmlspecialchars(mb_strimwidth($info['JUDUL'], 0, 60, '...')); ?></td>
            <td><?php echo date("d M Y", strtotime($info['TANGGAL'])); ?></td>
            <td><?php echo htmlspecialchars($info['PENULIS']); ?></td>
            <td><span class="badge-custom badge-purple"><?php echo htmlspecialchars($info['TAG']); ?></span></td>
            <td>
              <?php if (!empty($info['GAMBAR'])) { ?>
                <span class="badge-custom badge-success">Ada</span>
              <?php } else { ?>
                <span class="badge-custom badge-gray">Tidak</span>
              <?php } ?>
            </td>
            <td>
              <div class="actions-flex">
                <a href="<?php echo set_url("site/informasi_edit"); ?>&id=<?php echo $info['ID']; ?>" title="Edit" class="btn-square btn-edit-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
                <button type="button" onclick="openDeleteModal(<?php echo $info['ID']; ?>, <?php echo htmlspecialchars(json_encode($info['JUDUL']), ENT_QUOTES, 'UTF-8'); ?>)" title="Hapus" class="btn-square btn-delete-red">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <!-- Empty State -->
      <div class="empty-info-admin">
        <div class="empty-info-icon-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        </div>
        <h3 class="empty-info-title">Belum Ada Informasi</h3>
        <p class="empty-info-desc">Klik tombol "Tambah Informasi" untuk membuat informasi pertama.</p>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay-custom" style="display: none;" onclick="if(event.target===this) closeDeleteModal()">
  <div class="modal-box-custom">
    <div class="modal-icon-danger">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
    </div>
    <h3 class="modal-title">Hapus Informasi?</h3>
    <p id="deleteModalText" class="modal-text">Apakah Anda yakin ingin menghapus informasi ini? Tindakan ini tidak dapat dibatalkan.</p>
    <div class="modal-actions-flex">
      <button onclick="closeDeleteModal()" class="btn-cancel-modal">Batal</button>
      <a id="deleteModalBtn" href="#" class="btn-confirm-delete">Ya, Hapus</a>
    </div>
  </div>
</div>



<script>
function openDeleteModal(id, title) {
  document.getElementById('deleteModalText').innerHTML = 'Apakah Anda yakin ingin menghapus informasi <strong>"' + title + '"</strong>? Tindakan ini tidak dapat dibatalkan.';
  document.getElementById('deleteModalBtn').href = '<?php echo set_url("site/informasi_delete"); ?>&id=' + id;
  document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
</script>
