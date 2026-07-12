<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

// Group data
$data_plp = ['PLP1' => [], 'PLP2' => []];
if (!empty($jadwals)) {
    foreach ($jadwals as $row) {
        $jenis = $row['JENIS_PLP'] ?? 'PLP1';
        $data_plp[$jenis][] = $row;
    }
}
?>

<div class="schedule-container">
  <div class="schedule-card" style="padding: 30px;">
    <h1 class="card-title" style="margin-bottom: 30px; text-align: center;">Jadwal Kegiatan Pengenalan Lapangan Persekolahan (PLP)</h1>
    
    <?php if (!empty($jadwals)) { ?>
      
      <!-- Tabel PLP 1 -->
      <h3 style="font-family: 'Poppins', sans-serif; margin-bottom: 15px; color: #1e293b;">A. Rangkaian Kegiatan PLP 1</h3>
      <div class="table-responsive" style="margin-bottom: 40px;">
        <table class="modern-table" style="border: 1px solid #cbd5e1;">
          <thead>
            <tr>
              <th rowspan="2" style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Minggu<br>Ke-</th>
              <th rowspan="2" style="border: 1px solid #cbd5e1; background-color: #f1f5f9; text-align: center;">Rincian Kegiatan</th>
              <th colspan="2" style="text-align: center; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Beban Belajar</th>
            </tr>
            <tr>
              <th style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Kegiatan</th>
              <th style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">JP/Hari</th>
            </tr>
          </thead>
          <tbody>
            <?php render_academic_table($data_plp['PLP1'], 'PLP 1'); ?>
          </tbody>
        </table>
      </div>

      <!-- Tabel PLP 2 -->
      <h3 style="font-family: 'Poppins', sans-serif; margin-bottom: 15px; color: #1e293b;">B. Rangkaian Kegiatan PLP 2</h3>
      <div class="table-responsive">
        <table class="modern-table" style="border: 1px solid #cbd5e1;">
          <thead>
            <tr>
              <th rowspan="2" style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Minggu<br>Ke-</th>
              <th rowspan="2" style="border: 1px solid #cbd5e1; background-color: #f1f5f9; text-align: center;">Rincian Kegiatan</th>
              <th colspan="2" style="text-align: center; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Beban Belajar</th>
            </tr>
            <tr>
              <th style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">Kegiatan</th>
              <th style="text-align: center; width: 100px; border: 1px solid #cbd5e1; background-color: #f1f5f9;">JP/Hari</th>
            </tr>
          </thead>
          <tbody>
            <?php render_academic_table($data_plp['PLP2'], 'PLP 2'); ?>
          </tbody>
        </table>
      </div>

    <?php } else { ?>
        <div class="empty-state empty-schedule">
            <div class="empty-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h3 class="empty-title">Jadwal Belum Tersedia</h3>
            <p class="empty-desc">Jadwal kegiatan terbaru akan segera diumumkan.</p>
        </div>
    <?php } ?>
  </div>
</div>

<?php
function render_academic_table($rows, $plp_name) {
    if (empty($rows)) {
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px; border: 1px solid #cbd5e1;">Belum ada jadwal kegiatan untuk ' . $plp_name . '.</td></tr>';
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
            echo '<td rowspan="' . $minggu_counts[$m] . '" style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">' . $m . '</td>';
            $current_minggu = $m;
        }
        
        echo '<td style="border: 1px solid #cbd5e1; padding: 12px 15px; color: #a805a8; font-weight: 500;">' . nl2br(htmlspecialchars($row['JENISKEGIATAN'])) . '</td>';
        echo '<td style="text-align: center; border: 1px solid #cbd5e1;">' . htmlspecialchars($row['NOMOR_KEGIATAN']) . '</td>';
        echo '<td style="text-align: center; border: 1px solid #cbd5e1;">' . htmlspecialchars($row['JP_HARI']) . '</td>';
        echo '</tr>';
    }
    
    // Baris Total
    echo '<tr style="font-weight: bold; background-color: #f8fafc;">
            <td colspan="2" style="text-align: center; border: 1px solid #cbd5e1; padding: 12px;">Total JP layak/Kegiatan</td>
            <td style="text-align: center; border: 1px solid #cbd5e1;">' . $total_kegiatan . ' Kegiatan</td>
            <td style="text-align: center; border: 1px solid #cbd5e1;">' . str_replace('.0', '', (string)$total_jp) . ' JP</td>
          </tr>';
    
    // Baris Footer Analisis
    echo '<tr style="font-weight: bold; background-color: #ffffff;">
            <td colspan="4" style="text-align: center; border: 1px solid #cbd5e1; padding: 15px;">Analisis dan Pelaporan PKL/Magang ' . $plp_name . '</td>
          </tr>';
}
?>
