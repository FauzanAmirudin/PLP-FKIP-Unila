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
                <div class="dashboard-banner banner-status" style="background: <?php echo $statusBg; ?>; border-left: 4px solid <?php echo $statusColor; ?>; display: flex; flex-direction: column; align-items: flex-start;">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <div class="banner-icon-wrapper" style="background: #ffffff; color: <?php echo $statusColor; ?>; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <svg class="banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                        </div>
                        <div class="banner-text">
                            <span class="banner-title" style="color: #475569; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Status Pendaftaran</span>
                            <span class="banner-value" style="color: <?php echo $statusColor; ?>; font-weight: 700; font-size: 16px; margin-top: 2px;"><?php echo $statusText; ?></span>
                        </div>
                    </div>
                    <?php if(!empty($note)) { ?>
                        <div style="font-size: 12px; margin-top: 12px; width: 100%; opacity: 0.9; color: #1e293b; background: rgba(255,255,255,0.6); padding: 8px 12px; border-radius: 6px; border: 1px dashed <?php echo $statusColor; ?>;">
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
        <div class="dashboard-table-container">
            <h3 class="dashboard-section-title">Jadwal Kegiatan</h3>
            <div style="overflow-x: auto;">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Pelaksana</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
                        $jadwalList = $dbAccess->reset()->where("`WAKTUAKHIR` >= CURDATE() ORDER BY `WAKTUAWAL` ASC")->result_array('jadwal');
                        
                        if (empty($jadwalList)) {
                            echo '<tr><td colspan="5" style="text-align: center; border-bottom: none; color: #a1a1aa; padding: 20px;">Jadwal tidak tersedia.</td></tr>';
                        } else {
                            foreach ($jadwalList as $r) {
                                $tglMulai = ($r['WAKTUAWAL']) ? date("d M Y", strtotime($r['WAKTUAWAL'])) : '-';
                                $tglAkhir = ($r['WAKTUAKHIR']) ? date("d M Y", strtotime($r['WAKTUAKHIR'])) : '-';
                                
                                echo '<tr>';
                                echo '<td class="heavy">' . htmlspecialchars($r['JENISKEGIATAN']) . '</td>';
                                echo '<td style="font-size: 12px; color: #64748b;">' . htmlspecialchars(mb_strimwidth($r['KETERANGAN'], 0, 40, '...')) . '</td>';
                                echo '<td>' . $tglMulai . '</td>';
                                echo '<td>' . $tglAkhir . '</td>';
                                echo '<td><span class="dashboard-badge" style="background: #f0e3fc; color: #a805a8; border: none; font-size: 11px;">' . htmlspecialchars($r['PELAKSANA'] ?? 'Panitia') . '</span></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
