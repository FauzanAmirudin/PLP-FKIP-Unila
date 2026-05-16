<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<div class="dashboard-content-wrapper">
    <div class="dashboard-card">
        <!-- TOP BANNERS -->
        <div class="dashboard-card-row">
            <?php if (is_level('Mahasiswa')) { ?>
                <?php
                $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
                
                // Get registration status
                $regInfo = $dbAccess->reset()
                    ->tabel('databerkas')
                    ->column(array('`datastatus`.`STATUSBERKAS`', '`datastatus`.`NOTEBERKAS`'))
                    ->join('datastatus', '`datastatus`.`BRKSKEY` = `databerkas`.`ID`', 'LEFT')
                    ->where("`databerkas`.`USRKEY` = " . session_get("ID"))
                    ->order("`databerkas`.`ID`", "DESC")
                    ->result_row_array();

                $statusText = "Belum Mengajukan";
                $statusColor = "#0f172a"; // Black/Dark slate
                $statusBg = "#f8fafc";
                $note = "";

                if (!empty($regInfo)) {
                    if (empty($regInfo['STATUSBERKAS'])) {
                        $statusText = "Pengajuan";
                        $statusColor = "#f59e0b"; // Yellow
                        $statusBg = "#fef3c7";
                    } else {
                        $statusText = $regInfo['STATUSBERKAS'];
                        if ($statusText == "Disetujui") {
                            $statusColor = "#10b981"; // Green
                            $statusBg = "#dcfce7";
                        } else if ($statusText == "Ditolak") {
                            $statusColor = "#ef4444"; // Red
                            $statusBg = "#fee2e2";
                        } else {
                            $statusColor = "#f59e0b"; // Yellow
                            $statusBg = "#fef3c7";
                        }
                    }
                    $note = isset($regInfo["NOTEBERKAS"]) ? $regInfo["NOTEBERKAS"] : "";
                }
                ?>
                <div class="dashboard-banner banner-status" style="background: <?php echo $statusBg; ?>; border-left: 4px solid <?php echo $statusColor; ?>;">
                    <div class="banner-status-header">
                        <div class="banner-icon-wrapper banner-icon-status" style="color: <?php echo $statusColor; ?>;">
                            <svg class="banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                        </div>
                        <div class="banner-text">
                            <span class="banner-title status-title">Status Pendaftaran</span>
                            <span class="banner-value status-value" style="color: <?php echo $statusColor; ?>;"><?php echo $statusText; ?></span>
                        </div>
                    </div>
                    <?php if(!empty($note)) { ?>
                        <div class="status-note" style="border: 1px dashed <?php echo $statusColor; ?>;">
                            <strong>Catatan:</strong> <?php echo htmlspecialchars($note); ?>
                        </div>
                    <?php } ?>
                </div>

                <?php
                $info = $dbAccess->reset()->where("`TANGGAL` <= CURDATE() ORDER BY `TANGGAL` DESC")->result_row_array('informasi');
                $infoData = ($info == FALSE) ? "-" : $info['INFORMASI']; 
                ?>
                <div class="dashboard-banner banner-info">
                    <div class="banner-icon-wrapper">
                        <svg class="banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </div>
                    <div class="banner-text">
                        <span class="banner-title">Informasi</span>
                        <span class="banner-value"><?php echo htmlspecialchars(substr($infoData, 0, 80)) . (strlen($infoData) > 80 ? '...' : ''); ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- JADWAL KEGIATAN -->
        <div class="schedule-container">
          <div class="schedule-card">
            <div class="schedule-header">
                <h1 class="card-title">Jadwal Kegiatan</h1>
                <a href="<?php echo set_url('kegiatan/jadwal'); ?>" class="schedule-link">Lihat Semua &rarr;</a>
            </div>

            <?php if (!empty($jadwal_list)) { ?>
                <div class="table-responsive">
                  <table class="modern-table schedule-table">
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
                      <?php foreach ($jadwal_list as $jadwal) { ?>
                        <tr>
                          <td class="td-kegiatan"><?= htmlspecialchars($jadwal['JENISKEGIATAN']) ?></td>
                          <td class="td-deskripsi"><?= nl2br(htmlspecialchars($jadwal['KETERANGAN'])) ?></td>
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
    </div>
</div>
