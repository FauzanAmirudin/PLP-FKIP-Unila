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
        <h1 class="card-title">Kelola Jadwal Kegiatan</h1>
        <p class="card-subtitle">Atur jadwal kegiatan PPL yang akan tampil di dashboard mahasiswa.</p>
      </div>
      <a href="<?php echo set_url("site/kelola_jadwal_form"); ?>" class="btn-action-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Jadwal
      </a>
    </div>

    <div class="table-responsive-wrapper">
      <table class="modern-table admin-table">
        <thead>
          <tr>
            <th class="col-no-50">No</th>
            <th>Kegiatan</th>
            <th>Pelaksana</th>
            <th class="col-date-120">Mulai</th>
            <th class="col-date-120">Selesai</th>
            <th>Keterangan</th>
            <th class="col-actions-100">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($jadwal_list)) { $n = 1; foreach ($jadwal_list as $row) { ?>
          <tr>
            <td><?php echo $n++; ?></td>
            <td class="td-title-bold"><?php echo htmlspecialchars($row['JENISKEGIATAN']); ?></td>
            <td><?php echo htmlspecialchars($row['PELAKSANA'] ?? '-'); ?></td>
            <td><?php echo $row['WAKTUAWAL_FORMATTED']; ?></td>
            <td><?php echo $row['WAKTUAKHIR_FORMATTED']; ?></td>
            <td class="td-desc-sm"><?php echo htmlspecialchars(mb_strimwidth($row['KETERANGAN'], 0, 50, '...')); ?></td>
            <td>
              <div class="actions-flex">
                <a href="<?php echo set_url("site/kelola_jadwal_form"); ?>&id=<?php echo $row['ID']; ?>" class="btn-square btn-edit-purple" title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
                <button type="button" onclick="confirmDelete(<?php echo $row['ID']; ?>, '<?php echo htmlspecialchars(addslashes($row['JENISKEGIATAN']), ENT_QUOTES); ?>', '<?php echo $csrf_token; ?>')" class="btn-square btn-delete-red" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php } } else { ?>
          <tr>
            <td colspan="7" class="td-empty">Belum ada jadwal kegiatan.</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Simple Delete Confirmation -->
<script>
function confirmDelete(id, title, csrf) {
  if (confirm("Apakah Anda yakin ingin menghapus jadwal '" + title + "'?")) {
    window.location.href = "<?php echo set_url('site/kelola_jadwal_delete'); ?>&id=" + id + "&csrf=" + csrf;
  }
}
</script>
