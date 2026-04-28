<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>

<style>
  .desc-container {
    position: relative;
    max-width: 300px;
  }
  .desc-text {
    max-height: 4.5em; /* Sekitar 3 baris */
    overflow: hidden;
    line-height: 1.5;
    transition: max-height 0.3s ease-out;
  }
  .desc-text.expanded {
    max-height: 1000px; /* Nilai besar agar bisa expand sepenuhnya */
  }
  .btn-toggle-desc {
    display: block;
    margin-top: 5px;
    background: none;
    border: none;
    color: #a805a8;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .btn-toggle-desc:hover {
    text-decoration: underline;
  }
</style>

<div class="schedule-container">
  <div class="schedule-card">
    <h1 class="card-title">Jadwal Kegiatan</h1>
    
    <?php if (!empty($jadwals)) { ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th style="width: 20%;">Kegiatan</th>
                <th style="width: 30%;">Deskripsi</th>
                <th style="width: 15%;">Mulai</th>
                <th style="width: 15%;">Selesai</th>
                <th style="width: 20%;">Pelaksana</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jadwals as $jadwal) { 
                $desc = $jadwal['KETERANGAN'];
                $isLong = strlen($desc) > 100;
              ?>
                <tr>
                  <td style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($jadwal['JENISKEGIATAN']) ?></td>
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
                  <td><?= !empty($jadwal['WAKTUAWAL']) ? date("d M Y", strtotime($jadwal['WAKTUAWAL'])) : '-' ?></td>
                  <td><?= !empty($jadwal['WAKTUAKHIR']) ? date("d M Y", strtotime($jadwal['WAKTUAKHIR'])) : '-' ?></td>
                  <td><span style="display: inline-block; padding: 4px 12px; background: #f0e3fc; color: #a805a8; border-radius: 20px; font-size: 12px; font-weight: 500;"><?= htmlspecialchars($jadwal['PELAKSANA'] ?? 'Panitia') ?></span></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
    <?php } else { ?>
        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
            <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h3 style="color: #475569; font-size: 18px; margin: 0 0 8px;">Jadwal Belum Tersedia</h3>
            <p style="color: #94a3b8; font-size: 14px; margin: 0;">Jadwal kegiatan terbaru akan segera diumumkan.</p>
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
