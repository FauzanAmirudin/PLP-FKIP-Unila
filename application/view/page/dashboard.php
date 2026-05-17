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
                $statusColor = "#64748b"; 
                $statusBg = "linear-gradient(135deg, #1e293b, #475569)";
                $note = "";

                if (!empty($regInfo)) {
                    if (empty($regInfo['STATUSBERKAS'])) {
                        $statusText = "Pengajuan";
                        $statusColor = "#f59e0b"; 
                        $statusBg = "linear-gradient(135deg, #b45309, #f59e0b)";
                    } else {
                        $statusText = $regInfo['STATUSBERKAS'];
                        if ($statusText == "Disetujui") {
                            $statusColor = "#10b981"; 
                            $statusBg = "linear-gradient(135deg, #065f46, #10b981)";
                        } else if ($statusText == "Ditolak") {
                            $statusColor = "#ef4444"; 
                            $statusBg = "linear-gradient(135deg, #991b1b, #ef4444)";
                        } else {
                            $statusColor = "#f59e0b"; 
                            $statusBg = "linear-gradient(135deg, #b45309, #f59e0b)";
                        }
                    }
                    $note = isset($regInfo["NOTEBERKAS"]) ? $regInfo["NOTEBERKAS"] : "";
                }
                ?>
                <div class="dashboard-banner banner-status-editorial" style="background: <?php echo $statusBg; ?>;">
                    <div class="banner-bg-text">STATUS</div>
                    <div class="banner-content-wrapper">
                        <div class="banner-badge">PENDAFTARAN</div>
                        <h3 class="banner-editorial-title"><?php echo $statusText; ?></h3>
                        
                        <?php if(!empty($note)) { ?>
                            <div class="status-editorial-note">
                                <span class="note-label">Catatan:</span> <?php echo htmlspecialchars($note); ?>
                            </div>
                        <?php } else { ?>
                            <p class="banner-editorial-desc">
                            <?php
                                if ($statusText == 'Disetujui') {
                                    echo 'Selamat! Pendaftaran Anda telah resmi disetujui.';
                                } elseif ($statusText == 'Ditolak') {
                                    echo 'Berkas Anda ditolak. Silakan hubungi operator untuk informasi lebih lanjut.';
                                } elseif ($statusText == 'Pengajuan') {
                                    echo 'Berkas Anda sedang dalam antrian verifikasi oleh operator.';
                                } else {
                                    echo 'Silakan ajukan berkas pendaftaran PLP Anda secepatnya.';
                                }
                            ?>
                            </p>
                        <?php } ?>
                    </div>
                </div>

                <?php
                $info = $dbAccess->reset()->tabel('informasi')->where("`TANGGAL` <= CURDATE()")->order("`TANGGAL`", "DESC")->order("`ID`", "DESC")->limit(1)->result_fetch_array();
                $infoTitle = ($info == FALSE) ? "Belum ada informasi" : $info['JUDUL']; 
                $infoData = ($info == FALSE) ? "Silakan periksa kembali nanti." : $info['INFORMASI']; 
                $infoId = ($info == FALSE) ? "" : $info['ID'];
                ?>
                <div class="dashboard-banner banner-info-editorial">
                    <div class="banner-bg-text">LATEST</div>
                    <div class="banner-content-wrapper">
                        <div class="banner-badge">INFORMASI TERBARU</div>
                        <h3 class="banner-editorial-title"><?php echo htmlspecialchars(mb_strimwidth($infoTitle, 0, 50, '...')); ?></h3>
                        <p class="banner-editorial-desc"><?php echo htmlspecialchars(mb_strimwidth($infoData, 0, 110, '...')); ?></p>
                        <?php if($info != FALSE) { ?>
                            <a href="?page=detailinformasi&id=<?php echo (int)$infoId; ?>" class="editorial-read-more">Baca Selengkapnya <span class="arrow">&rarr;</span></a>
                        <?php } ?>
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
