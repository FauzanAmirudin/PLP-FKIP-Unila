<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

is_level("Admin, Monitor, DPL");
?>
<div class="schedule-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong">' . $notification . '</div>';
    } ?>

    <div class="schedule-card">
        <div class="card-header">
            <h1 class="card-title">Data Laporan Mingguan <?= isset($config['CURENTYEAR']) ? htmlspecialchars($config['CURENTYEAR']) : '' ?></h1>
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
                        <?php if(isset($alltahun) && !empty($alltahun)) { foreach($alltahun as $t) { ?>
                            <option value="<?= $t['TAHUNDAFTAR'] ?>" <?= (isset($tahun) && $tahun == $t['TAHUNDAFTAR']) ? 'selected' : '' ?>><?= $t['TAHUNDAFTAR'] ?></option>
                        <?php } } else { ?>
                            <option value="">-</option>
                        <?php } ?>
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
                            echo '
                            <div class="report-item">
                                <a href="' . $l['FILELINK'] . '" target="_blank" class="btn-download-small" title="Download Laporan ' . $qw . '">' . $qw . '</a>
                                <button type="button"
                                   class="btn-respond-small btn-give-response"
                                   data-nama="' . htmlspecialchars($r["NAMA"], ENT_QUOTES) . '"
                                   data-npm="' . htmlspecialchars($npm, ENT_QUOTES) . '"
                                   data-laporan="' . htmlspecialchars($namaLaporan, ENT_QUOTES) . '"
                                   style="background: ' . $statusBg . '; color: ' . $statusColor . '; border-color: ' . $statusColor . '33;"
                                   title="Beri Respons">R</button>
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
<script>
(function () {
    // Variabel state di memori
    var _npm      = '';
    var _laporan  = '';

    function openModal(nama, npm, laporan) {
        _npm     = npm;
        _laporan = laporan;

        var modal      = document.getElementById('modal-response');
        var dtaMhs     = document.getElementById('dta-mhs');
        var dtaLap     = document.getElementById('dta-lap');
        var resKet     = document.getElementById('res-ket');
        var selectEl   = document.getElementById('res-lap');
        var komentarEl = document.getElementById('komentar');
        var saveBtn    = document.getElementById('rc-save-btn');

        if (!modal) { console.error("Modal element not found"); return; }

        if (dtaMhs) dtaMhs.innerHTML = '<b>Nama</b><br>' + nama + '<br><b>NPM</b><br>' + npm;
        if (dtaLap) dtaLap.innerHTML = '<b>Laporan:</b> ' + laporan;
        if (resKet) resKet.innerHTML = '';
        if (selectEl) selectEl.value = '';
        if (komentarEl) komentarEl.value = '';
        
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Simpan Respons';
        }

        modal.style.display = 'block';
    }

    function closeModal() {
        var modal = document.getElementById('modal-response');
        if (modal) modal.style.display = 'none';
    }

    function saveResponse() {
        var selectEl   = document.getElementById('res-lap');
        var komentarEl = document.getElementById('komentar');
        var resKet     = document.getElementById('res-ket');
        var saveBtn    = document.getElementById('rc-save-btn');

        if (!selectEl || !komentarEl) return;

        var respons  = selectEl.value.trim();
        var komentar = komentarEl.value.trim();

        if (!respons || !komentar) {
            if (resKet) resKet.innerHTML = '<div class="info info-danger info-ajax-wrapper"><a>Harap lengkapi status respons dan komentar.</a></div>';
            return;
        }

        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';
        }
        if (resKet) resKet.innerHTML = '';

        var body = 'id='      + encodeURIComponent(_npm)
                 + '&object=' + encodeURIComponent(_laporan)
                 + '&respons='  + encodeURIComponent(respons)
                 + '&komentar=' + encodeURIComponent(komentar);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '?page=laporan&ajax=balas_laporan', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Respons';
            }
            if (xhr.status === 200 && resKet) {
                resKet.innerHTML = xhr.responseText;
                if (xhr.responseText.indexOf('info-success') !== -1) {
                    resKet.innerHTML += '<div class="reload-btn-wrapper">'
                        + '<button type="button" class="btn btn-ok btn-reload" onclick="location.reload()">Tutup &amp; Perbarui Daftar</button>'
                        + '</div>';
                }
            } else if (resKet) {
                resKet.innerHTML = '<div class="info info-danger info-ajax-wrapper"><a>Gagal menyimpan. Status server: ' + xhr.status + '</a></div>';
            }
        };

        xhr.onerror = function () {
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Respons';
            }
            if (resKet) resKet.innerHTML = '<div class="info info-danger info-ajax-wrapper"><a>Gagal menghubungi server. Periksa koneksi Anda.</a></div>';
        };

        xhr.send(body);
    }

    // Satu Event Listener untuk mengatur semua aksi klik di halaman ini
    document.addEventListener('click', function (e) {
        // 1. Klik tombol "R"
        var btnR = e.target.closest ? e.target.closest('.btn-give-response') : null;
        if (btnR) {
            openModal(
                btnR.getAttribute('data-nama'),
                btnR.getAttribute('data-npm'),
                btnR.getAttribute('data-laporan')
            );
            return;
        }

        // 2. Klik latar belakang modal untuk menutup
        var modal = document.getElementById('modal-response');
        if (modal && e.target === modal) {
            closeModal();
            return;
        }

        // 3. Klik tombol "Tutup" atau "Batal"
        if (e.target.id === 'rc-close-x' || e.target.id === 'rc-close-btn') {
            closeModal();
            return;
        }

        // 4. Klik tombol "Simpan Respons"
        if (e.target.id === 'rc-save-btn') {
            saveResponse();
            return;
        }
    });

})();
</script>
