<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

is_level("Admin, Monitor, DPL");
?>

<style type="text/css">
.schedule-container .filter-section form .filter-group.period-group {
    width: 150px;
}
@media screen and (max-width: 768px) {
    .schedule-container .filter-section form .filter-group.period-group {
        width: 100% !important;
    }
}
</style>

<div class="schedule-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong">' . $notification . '</div>';
    } ?>

    <div class="schedule-card">
        <div class="card-header">
            <h1 class="card-title">Data Laporan Mingguan <?= isset($tahun) ? htmlspecialchars($tahun) : '' ?><?= !empty($periode) ? ' (' . htmlspecialchars($periode) . ')' : ' (Semua Periode)' ?></h1>
            <p class="card-subtitle">Manajemen dan verifikasi laporan aktivitas mingguan mahasiswa PLT.</p>
        </div>

        <!-- Form Filter -->
        <div class="filter-section">
            <form action="<?= set_url($form_link) ?>" method="get">
                <input type="hidden" name="page" value="<?= htmlspecialchars($form_link) ?>">
                
                <div class="filter-group search-group">
                    <label>Cari Mahasiswa (NPM/Nama)</label>
                    <input type="text" name="npm" value="<?= isset($npm) ? htmlspecialchars($npm) : '' ?>" placeholder="Masukkan NPM...">
                </div>

                <div class="filter-group year-group">
                    <label>Tahun</label>
                    <select name="tahun">
                        <?php if(isset($alltahun) && !empty($alltahun)) { 
                            foreach($alltahun as $t) { 
                                if (empty($t['TAHUNDAFTAR'])) continue; // Lewati jika tahun kosong/null
                                ?>
                                <option value="<?= $t['TAHUNDAFTAR'] ?>" <?= (isset($tahun) && $tahun == $t['TAHUNDAFTAR']) ? 'selected' : '' ?>><?= $t['TAHUNDAFTAR'] ?></option>
                        <?php } } else { ?>
                            <option value="">-</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="filter-group period-group">
                    <label>Periode</label>
                    <select name="periode">
                        <option value="" <?= empty($periode) ? 'selected' : '' ?>>Semua Periode</option>
                        <?php if (isset($allperiode) && !empty($allperiode)) {
                            foreach ($allperiode as $p) {
                                if (empty($p['PERIODEDAFTAR'])) continue; // Lewati jika periode kosong/null
                                $isSelected = (isset($periode) && $periode == $p['PERIODEDAFTAR']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($p['PERIODEDAFTAR']) . '" ' . $isSelected . '>' . htmlspecialchars($p['PERIODEDAFTAR']) . '</option>';
                            }
                        } ?>
                    </select>
                </div>

                <button type="submit" class="btn-save btn-filter">Filter Data</button>
                <a href="<?= set_url($form_link) ?>" class="btn-cancel-modal btn-reset">Reset</a>
            </form>
        </div>

        <?php
        $dataMahasiswa = isset($mahasiswa) ? $mahasiswa : [];
        $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
        
        if ($dataMahasiswa && count($dataMahasiswa) > 0) {
            $myDesa = "0";
            foreach ($dataMahasiswa as $index => $r) {
                if ($r['LOKASIDESA'] != $myDesa) {
                    if ($myDesa != "0") {
                        echo '</tbody></table></div></div>';
                    }
                    echo '
                    <div class="report-group-section">
                        <h3 class="group-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            Desa ' . htmlspecialchars($r['LOKASIDESA'] ?? 'Tidak Diketahui') . 
                            (is_level("Admin, Monitor") ? ' <span class="dpl-info">DPL: ' . htmlspecialchars($r['NAMADOSEN'] ?? '') . '</span>' : "") . '
                        </h3>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th width="60px" class="text-center">No</th>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Sekolah</th>
                                        <th width="200px">Laporan & Respons</th>
                                    </tr>
                                </thead>
                                <tbody>';
                    $n = 1;
                }
                
                echo '
                <tr>
                    <td class="text-center text-muted font-medium">' . $n . '</td>
                    <td>
                        <div class="student-name">' . htmlspecialchars($r["NAMA"]) . '</div>
                        <div class="student-npm">' . htmlspecialchars($r["NPM"]) . '</div>
                    </td>
                    <td class="text-slate-600">' . htmlspecialchars($r["PROGRAMSTUDI"]) . '</td>
                    <td class="text-slate-600">' . htmlspecialchars($r["LOKASISEKOLAH"] ?? '-') . '</td>
                    <td>
                        <div class="reports-wrapper">';

                $qw = 0;
                $berkasId = null;
                $item = $r["NPM"];
                $file = $dbAccess->reset()->where("`NPM`='" . $item . "'")->order('`FILENAME`')->result_array('laporan');
                if (!empty($file) && is_array($file)) {
                    usort($file, function($a, $b) {
                        $nameA = isset($a['FILENAME']) ? $a['FILENAME'] : '';
                        $nameB = isset($b['FILENAME']) ? $b['FILENAME'] : '';
                        
                        $isAkhirA = (stripos($nameA, 'Akhir') !== false);
                        $isAkhirB = (stripos($nameB, 'Akhir') !== false);
                        
                        if ($isAkhirA && !$isAkhirB) return 1;
                        if (!$isAkhirA && $isAkhirB) return -1;
                        
                        return strnatcasecmp($nameA, $nameB);
                    });
                }
                
                if ($file) {
                    foreach ($file as $l) {
                        $namaLaporan = $l['FILENAME'];
                        $fileext = $l['FILEEXT'];
                        $npm = $l['NPM'];
                        if (!$berkasId && isset($l['BRKSKEY'])) $berkasId = $l['BRKSKEY'];
                        
                        $statusColor = '#cbd5e1'; 
                        $statusBg = '#f8fafc';
                        
                        if (!empty($l['RESPONSE'])) {
                            if ($l['RESPONSE'] == "Cukup") {
                                $statusColor = '#16a34a'; 
                                $statusBg = '#dcfce7';
                            } else {
                                $statusColor = '#dc2626'; 
                                $statusBg = '#fee2e2';
                            }
                        }

                        $relativeFilePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $l["FILELINK"]);
                        $fullFilePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $relativeFilePath;
                        
                         if (isset($l['FILENAME']) && file_exists($fullFilePath)) {
                            $qw++;
                            
                            // Parse dynamic label for the button
                            $label = '';
                            if (stripos($namaLaporan, 'Akhir PLP 1') !== false || stripos($namaLaporan, 'Akhir I') !== false) {
                                $label = 'A1';
                            } elseif (stripos($namaLaporan, 'Akhir PLP 2') !== false || stripos($namaLaporan, 'Akhir II') !== false) {
                                $label = 'A2';
                            } else {
                                preg_match('/\d+/', $namaLaporan, $matches);
                                $label = !empty($matches) ? $matches[0] : '?';
                            }
                            
                            // Link menuju halaman form review
                            $urlReview = set_url('laporan/review_form/' . $l['ID'] . '/' . urlencode($r["NAMA"]));
                            
                            echo '
                            <div class="report-item">
                                <a href="' . $l['FILELINK'] . '" target="_blank" class="btn-download-small" title="' . htmlspecialchars($namaLaporan) . '">' . htmlspecialchars($label) . '</a>
                                <a href="' . $urlReview . '"
                                   class="btn-respond-small btn-give-response"
                                   style="background: ' . $statusBg . '; color: ' . $statusColor . '; border-color: ' . $statusColor . '33; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;"
                                   title="Beri Respons Untuk ' . htmlspecialchars($namaLaporan) . '">R</a>
                            </div>';
                        }
                    }
                }

                if ($qw === 0) {
                    echo '<span class="empty-report-text">Belum Ada Laporan</span>';
                } elseif ($qw > 1 && $berkasId) {
                    echo '<a href="' . set_url("downloads/reports/bundle/" . $berkasId) . '" class="btn-bundle">Semua</a>';
                }
                
                echo '</div></td></tr>';
                $n++;
                $myDesa = $r["LOKASIDESA"];
            }
            echo '</tbody></table></div></div>';
        } else { ?>
            <div class="empty-state">
                <div class="icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3>Belum Ada Laporan</h3>
                <p>Saat ini belum ada data laporan mingguan mahasiswa yang tersedia untuk tahun akademik ini.</p>
            </div>
        <?php } ?>
    </div>
</div>


