<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
  } ?>

  <div class="settings-card" style="margin-bottom: 20px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 class="card-title">Kelola Gallery</h1>
        <p class="card-subtitle">Upload, edit keterangan, atau hapus foto yang ditampilkan di halaman galeri.</p>
      </div>
      <a href="<?php echo set_url("site/gallery_create"); ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #a805a8; color: white; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='#8a0489'" onmouseout="this.style.background='#a805a8'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Upload Foto
      </a>
    </div>

    <!-- Gallery Grid Admin -->
    <div style="margin-top: 24px;">
      <?php if (!empty($gallery_list)) { ?>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
        <?php foreach ($gallery_list as $photo) { ?>
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.06)'">
          <!-- Thumbnail -->
          <div style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #f0e3fc;">
            <img src="<?php echo $photo['GAMBAR']; ?>" alt="Gallery" style="width: 100%; height: 100%; object-fit: cover; display: block;" onerror="this.style.display='none'" />
          </div>
          <!-- Info + Aksi -->
          <div style="padding: 10px 12px 12px;">
            <p style="font-size: 12px; color: #64748b; margin: 0 0 4px; line-height: 1.4; min-height: 34px;">
              <?php echo !empty($photo['KETERANGAN']) ? htmlspecialchars(mb_strimwidth($photo['KETERANGAN'], 0, 60, '...')) : '<em style="color: #cbd5e1;">Tanpa keterangan</em>'; ?>
            </p>
            <p style="font-size: 11px; color: #94a3b8; margin: 0 0 10px;">
              <?php echo date("d M Y, H:i", strtotime($photo['TIMECREATE'])); ?>
            </p>
            <div style="display: flex; gap: 6px;">
              <a href="<?php echo set_url("site/gallery_edit"); ?>&id=<?php echo $photo['ID']; ?>" title="Edit Keterangan" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 10px; background: #f0e3fc; color: #a805a8; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#a805a8';this.style.color='#fff'" onmouseout="this.style.background='#f0e3fc';this.style.color='#a805a8'">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
              </a>
              <button type="button" onclick="openDeleteModal(<?php echo $photo['ID']; ?>, '<?php echo htmlspecialchars(addslashes(!empty($photo['KETERANGAN']) ? mb_strimwidth($photo['KETERANGAN'], 0, 30, '...') : 'foto ini'), ENT_QUOTES); ?>')" title="Hapus" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #fef2f2; color: #dc2626; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; flex-shrink: 0;" onmouseover="this.style.background='#dc2626';this.style.color='#fff'" onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>

      <?php } else { ?>
      <!-- Empty State -->
      <div style="text-align: center; padding: 70px 20px;">
        <div style="width: 80px; height: 80px; background: #f0e3fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        </div>
        <h3 style="color: #475569; font-size: 16px; margin: 0 0 8px;">Belum Ada Foto</h3>
        <p style="color: #94a3b8; font-size: 14px; margin: 0 0 20px;">Klik tombol "Upload Foto" untuk menambahkan foto ke galeri.</p>
        <a href="<?php echo set_url("site/gallery_create"); ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #a805a8; color: white; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Upload Foto Pertama
        </a>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
  <div style="background: white; border-radius: 16px; padding: 30px; max-width: 420px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.25); text-align: center; animation: galModalIn 0.2s ease;">
    <div style="width: 56px; height: 56px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
    </div>
    <h3 style="margin: 0 0 8px; font-size: 18px; color: #1e293b;">Hapus Foto?</h3>
    <p id="deleteModalText" style="color: #64748b; font-size: 14px; margin: 0 0 24px; line-height: 1.5;">Foto ini akan dihapus secara permanen dari galeri dan server.</p>
    <div style="display: flex; gap: 10px; justify-content: center;">
      <button onclick="closeDeleteModal()" style="padding: 10px 24px; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Batal</button>
      <a id="deleteModalBtn" href="#" style="padding: 10px 24px; background: #dc2626; color: white; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Ya, Hapus</a>
    </div>
  </div>
</div>

<style>
@keyframes galModalIn {
  from { opacity: 0; transform: scale(0.95); }
  to   { opacity: 1; transform: scale(1); }
}
</style>

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
