<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>

<div class="schedule-container">
  <div class="schedule-card">
    <h1 class="card-title">Jadwal Kegiatan</h1>
    
    <?php
    $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
    $jadwals = $dbAccess->reset()->where("`WAKTUAKHIR` ORDER BY `WAKTUAWAL` DESC")->result_array('jadwal');
    
    if ($jadwals != FALSE) { ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th style="width: 20%;">Kegiatan</th>
                <th style="width: 30%;">Deskripsi</th>
                <th style="width: 15%;">Tanggal Mulai</th>
                <th style="width: 15%;">Tanggal Berakhir</th>
                <th style="width: 20%;">Pelaksana</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jadwals as $jadwal) { ?>
                <tr>
                  <td><?= htmlspecialchars($jadwal['JENISKEGIATAN']) ?></td>
                  <td>Penjadwalan standar aktivitas sesuai dengan arahan program secara resmi.</td>
                  <td><?= htmlspecialchars($jadwal['WAKTUAWAL']) ?></td>
                  <td><?= htmlspecialchars($jadwal['WAKTUAKHIR']) ?></td>
                  <td><?= nl2br(htmlspecialchars($jadwal['KETERANGAN'])) ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
    <?php } else { ?>
        <div class="empty-state">
            <p>Jadwal kegiatan belum tersedia saat ini.</p>
        </div>
    <?php } ?>
  </div>
</div>
