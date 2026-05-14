<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>

<div class="schedule-container">
  <div class="schedule-card">
    <h1 class="card-title">Jadwal Kegiatan</h1>
    
    <?php if (!empty($jadwals)) { ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th class="col-kegiatan">Kegiatan</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-mulai">Mulai</th>
                <th class="col-selesai">Selesai</th>
                <th class="col-pelaksana">Pelaksana</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jadwals as $jadwal) { 
                $desc = $jadwal['KETERANGAN'];
                $isLong = strlen($desc) > 100;
              ?>
                <tr>
                  <td class="td-kegiatan"><?= htmlspecialchars($jadwal['JENISKEGIATAN']) ?></td>
                  <td>
                    <div class="desc-container">
                      <div class="desc-text" id="desc-<?= $jadwal['ID'] ?>">
                        <?= nl2br(htmlspecialchars($desc)) ?>
                      </div>
                      <?php if ($isLong) { ?>
                        <button class="btn-toggle-desc" onclick="toggleDesc(<?= $jadwal['ID'] ?>, this)">Lihat Selengkapnya</button>
                      <?php } ?>
                    </div>
                  </td>
                  <td><?= $jadwal['WAKTUAWAL_FORMATTED'] ?></td>
                  <td><?= $jadwal['WAKTUAKHIR_FORMATTED'] ?></td>
                  <td><span class="badge-pelaksana"><?= htmlspecialchars($jadwal['PELAKSANA'] ?? 'Panitia') ?></span></td>
                </tr>
              <?php } ?>
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

<script>
function toggleDesc(id, btn) {
  const textElement = document.getElementById('desc-' + id);
  if (textElement.classList.contains('expanded')) {
    textElement.classList.remove('expanded');
    btn.innerText = 'Lihat Selengkapnya';
  } else {
    textElement.classList.add('expanded');
    btn.innerText = 'Tutup';
  }
}
</script>
