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
        <h1 class="card-title">Kelola Jadwal Kegiatan</h1>
        <p class="card-subtitle">Atur jadwal kegiatan PPL yang akan tampil di dashboard mahasiswa.</p>
      </div>
      <a href="<?php echo set_url("site/kelola_jadwal_form"); ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #a805a8; color: white; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='#8a0489'" onmouseout="this.style.background='#a805a8'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Jadwal
      </a>
    </div>

    <div style="margin-top: 20px; overflow-x: auto;">
      <style>
        .modern-table th { padding: 12px 15px; background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .modern-table td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 13px; color: #334155; }
        .modern-table tbody tr:hover { background: #f8fafc; }
        .action-btns { display: flex; gap: 8px; }
        .btn-edit, .btn-delete { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-edit { background: #f0e3fc; color: #a805a8; }
        .btn-edit:hover { background: #a805a8; color: #fff; }
        .btn-delete { background: #fef2f2; color: #dc2626; }
        .btn-delete:hover { background: #dc2626; color: #fff; }
      </style>
      <table class="modern-table" style="width: 100%; min-width: 900px; border-collapse: collapse; text-align: left;">
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Kegiatan</th>
            <th>Pelaksana</th>
            <th style="width: 120px;">Mulai</th>
            <th style="width: 120px;">Selesai</th>
            <th>Keterangan</th>
            <th style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($jadwal_list)) { $n = 1; foreach ($jadwal_list as $row) { ?>
          <tr>
            <td><?php echo $n++; ?></td>
            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['JENISKEGIATAN']); ?></td>
            <td><?php echo htmlspecialchars($row['PELAKSANA'] ?? '-'); ?></td>
            <td><?php echo !empty($row['WAKTUAWAL']) ? date("d M Y", strtotime($row['WAKTUAWAL'])) : '-'; ?></td>
            <td><?php echo !empty($row['WAKTUAKHIR']) ? date("d M Y", strtotime($row['WAKTUAKHIR'])) : '-'; ?></td>
            <td style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars(mb_strimwidth($row['KETERANGAN'], 0, 50, '...')); ?></td>
            <td>
              <div class="action-btns">
                <a href="<?php echo set_url("site/kelola_jadwal_form"); ?>&id=<?php echo $row['ID']; ?>" class="btn-edit" title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
                <button type="button" onclick="confirmDelete(<?php echo $row['ID']; ?>, '<?php echo htmlspecialchars(addslashes($row['JENISKEGIATAN']), ENT_QUOTES); ?>')" class="btn-delete" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php } } else { ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada jadwal kegiatan.</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Simple Delete Confirmation -->
<script>
function confirmDelete(id, title) {
  if (confirm("Apakah Anda yakin ingin menghapus jadwal '" + title + "'?")) {
    window.location.href = "<?php echo set_url('site/kelola_jadwal_delete'); ?>&id=" + id;
  }
}
</script>
