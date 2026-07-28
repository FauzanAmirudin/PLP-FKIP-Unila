<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Monitor, Operator');

// Helper untuk group data by PLP dan hitung rowspan untuk Minggu Ke-
$data_plp = ['PLP1' => [], 'PLP2' => []];
if (!empty($jadwal_list)) {
    foreach ($jadwal_list as $row) {
        $jenis = $row['JENIS_PLP'] ?? 'PLP1';
        $data_plp[$jenis][] = $row;
    }
}
?>

<div class="settings-container">
  <?php if (isset($notification) && $notification != '') {
    echo '<div class="notif notif-primary-strong mb-20">' . $notification . '</div>';
  } ?>

  <div class="settings-card mb-20">
    <div class="card-header header-flex">
      <div>
        <h1 class="card-title">Kelola Jadwal Kegiatan</h1>
        <p class="card-subtitle">Atur jadwal kegiatan akademik PLP 1 dan PLP 2.</p>
      </div>
      <a href="<?php echo set_url("site/kelola_jadwal_form"); ?>" class="btn-action-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Jadwal
      </a>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container mt-20 mb-20">
      <button class="tab-btn active" onclick="switchTab('tab-plp1', this)">PLP 1</button>
      <button class="tab-btn" onclick="switchTab('tab-plp2', this)">PLP 2</button>
    </div>

    <!-- Tab Content PLP 1 -->
    <div id="tab-plp1" class="tab-content active" style="display: block;">
      <div class="table-responsive-wrapper">
        <table class="modern-table admin-table">
          <thead>
            <tr>
              <th class="col-no-50">Minggu Ke-</th>
              <th>Rincian Kegiatan</th>
              <th class="col-date-120" style="text-align: center;">Nomor Kegiatan</th>
              <th class="col-date-120" style="text-align: center;">JP/Hari</th>
              <th class="col-actions-100" style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php render_table_rows($data_plp['PLP1'], $csrf_token); ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tab Content PLP 2 -->
    <div id="tab-plp2" class="tab-content" style="display: none;">
      <div class="table-responsive-wrapper">
        <table class="modern-table admin-table">
          <thead>
            <tr>
              <th class="col-no-50">Minggu Ke-</th>
              <th>Rincian Kegiatan</th>
              <th class="col-date-120" style="text-align: center;">Nomor Kegiatan</th>
              <th class="col-date-120" style="text-align: center;">JP/Hari</th>
              <th class="col-actions-100" style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php render_table_rows($data_plp['PLP2'], $csrf_token); ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<?php
function render_table_rows($rows, $csrf_token) {
    if (empty($rows)) {
        echo '<tr><td colspan="5" class="td-empty">Belum ada jadwal kegiatan.</td></tr>';
        return;
    }
    
    $minggu_counts = [];
    foreach ($rows as $row) {
        $m = $row['MINGGU_KE'];
        if (!isset($minggu_counts[$m])) $minggu_counts[$m] = 0;
        $minggu_counts[$m]++;
    }

    $current_minggu = null;
    $total_kegiatan = 0;
    $total_jp = 0;

    foreach ($rows as $row) {
        $m = $row['MINGGU_KE'];
        $total_kegiatan++;
        $total_jp += (float)$row['JP_HARI'];
        
        echo '<tr>';
        if ($current_minggu !== $m) {
            echo '<td rowspan="' . $minggu_counts[$m] . '" style="text-align: center; vertical-align: middle; font-weight: bold;">' . $m . '</td>';
            $current_minggu = $m;
        }
        
        echo '<td style="border: 1px solid #cbd5e1; padding: 12px 15px; color: #a805a8; font-weight: 500;">' . nl2br(htmlspecialchars($row['JENISKEGIATAN'])) . '</td>';
        echo '<td style="text-align: center;">' . htmlspecialchars($row['NOMOR_KEGIATAN']) . '</td>';
        echo '<td style="text-align: center;">' . htmlspecialchars($row['JP_HARI']) . '</td>';
        echo '<td style="text-align: center;">
                <div class="actions-flex" style="justify-content: center;">
                  <a href="' . set_url("site/kelola_jadwal_form") . '&id=' . $row['ID'] . '" class="btn-square btn-edit-purple" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </a>
                  <button type="button" onclick="confirmDelete(' . $row['ID'] . ', \'' . htmlspecialchars(addslashes($row['JENISKEGIATAN']), ENT_QUOTES) . '\', \'' . $csrf_token . '\')" class="btn-square btn-delete-red" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </div>
              </td>';
        echo '</tr>';
    }
    
    // Baris Total
    echo '<tr style="background-color: #f8fafc; font-weight: bold;">
            <td colspan="2" style="text-align: center;">Total JP layak/Kegiatan</td>
            <td style="text-align: center;">' . $total_kegiatan . ' Kegiatan</td>
            <td style="text-align: center;">' . str_replace('.0', '', (string)$total_jp) . ' JP</td>
            <td></td>
          </tr>';
}
?>

<style>
.tabs-container {
    display: flex;
    gap: 10px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1px;
}
.tab-btn {
    background: none;
    border: none;
    padding: 10px 20px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}
.tab-btn:hover {
    color: #475569;
}
.tab-btn.active {
    color: #6a1b9a;
    border-bottom: 2px solid #6a1b9a;
}
</style>

<script>
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(function(el) {
        el.style.display = 'none';
        el.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(function(el) {
        el.classList.remove('active');
    });
    
    document.getElementById(tabId).style.display = 'block';
    setTimeout(() => document.getElementById(tabId).classList.add('active'), 10);
    btn.classList.add('active');
}

function confirmDelete(id, title, csrf) {
  if (confirm("Apakah Anda yakin ingin menghapus kegiatan ini?")) {
    window.location.href = "<?php echo set_url('site/kelola_jadwal_delete'); ?>&id=" + id + "&csrf=" + csrf;
  }
}
</script>
