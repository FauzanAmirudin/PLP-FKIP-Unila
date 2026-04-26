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
                <!-- <?php
                $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
                $info = $dbAccess->reset()->where("`USRKEY` = " . session_get("ID") . " ORDER BY `ID` ASC")->result_row_array('statusberkas');
                if ($info != FALSE) { ?>
                    <div class="dashboard-banner banner-status">
                        <div class="banner-icon-wrapper">
                            <svg class="banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                        </div>
                        <div class="banner-text">
                            <span class="banner-title">Status Pendaftaran</span>
                            <span class="banner-value"><?php echo $info['STATUSBERKAS']; ?></span>
                        </div>
                        <?php if(!empty($info["NOTEBERKAS"])) { ?>
                            <div style="font-size: 11px; margin-top: 5px; opacity: 0.8">Catatan: <?php echo $info["NOTEBERKAS"]; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?> -->

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
                        $jadwalList = $dbAccess->reset()->where("`WAKTUAKHIR` >= CURDATE() ORDER BY `WAKTUAWAL` DESC")->result_array('jadwal');
                        
                        if (empty($jadwalList)) {
                            echo '<tr><td colspan="5" style="text-align: center; border-bottom: none; color: #a1a1aa; padding: 20px;">Jadwal tidak tersedia.</td></tr>';
                        } else {
                            $idx = 0;
                            foreach ($jadwalList as $r) {
                                $tglMulai = ($r['WAKTUAWAL']) ? date("Y-m-d", strtotime($r['WAKTUAWAL'])) : '-';
                                $tglAkhir = ($r['WAKTUAKHIR']) ? date("Y-m-d", strtotime($r['WAKTUAKHIR'])) : '-';
                                
                                // Mock statuses based on index to mimic the image
                                $badgesData = [
                                    ['label' => 'New', 'class' => 'status-new'],
                                    ['label' => 'Awaiting verification', 'class' => 'status-verif'],
                                    ['label' => 'Planned', 'class' => 'status-planned'],
                                    ['label' => 'Triggered the trigger', 'class' => 'status-trigger'],
                                    ['label' => 'Escalation', 'class' => 'status-escalation']
                                ];
                                $bIdx = $idx % count($badgesData);
                                $statusLabel = $badgesData[$bIdx]['label'];
                                $statusClass = $badgesData[$bIdx]['class'];

                                echo '<tr>';
                                echo '<td class="heavy">' . htmlspecialchars($r['JENISKEGIATAN']) . '</td>';
                                echo '<td>' . htmlspecialchars(substr($r['KETERANGAN'], 0, 50)) . '...</td>';
                                echo '<td>' . $tglMulai . '</td>';
                                echo '<td>' . $tglAkhir . '</td>';
                                echo '<td><span class="dashboard-badge ' . $statusClass . '">' . $statusLabel . '</span></td>';
                                echo '</tr>';
                                $idx++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
