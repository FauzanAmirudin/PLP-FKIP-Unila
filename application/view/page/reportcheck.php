<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

is_level("Admin, Monitor, DPL");
?>
<div class="schedule-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
    } ?>

    <div class="schedule-card">
        <div class="card-header" style="margin-bottom: 25px;">
            <h1 class="card-title">Data Laporan Mingguan <?= isset($config['CURENTYEAR']) ? htmlspecialchars($config['CURENTYEAR']) : '' ?></h1>
            <p class="card-subtitle">Manajemen dan verifikasi laporan aktivitas mingguan mahasiswa PLT.</p>
        </div>

        <!-- Form Filter -->
        <div class="filter-section" style="background: #fdfdfd; padding: 20px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 25px;">
            <form action="<?= set_url($form_link) ?>" method="get" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                <input type="hidden" name="page" value="<?= htmlspecialchars($form_link) ?>">
                
                <div class="filter-group" style="flex: 1; min-width: 200px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 8px; display: block;">Cari Mahasiswa (NPM/Nama)</label>
                    <input type="text" name="npm" value="<?= isset($npm) ? htmlspecialchars($npm) : '' ?>" placeholder="Masukkan NPM..." style="width: 100%; padding: 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px;">
                </div>

                <div class="filter-group" style="width: 150px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 8px; display: block;">Tahun</label>
                    <select name="tahun" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <?php if(isset($alltahun) && !empty($alltahun)) { foreach($alltahun as $t) { ?>
                            <option value="<?= $t['TAHUNDAFTAR'] ?>" <?= (isset($tahun) && $tahun == $t['TAHUNDAFTAR']) ? 'selected' : '' ?>><?= $t['TAHUNDAFTAR'] ?></option>
                        <?php } } else { ?>
                            <option value="">-</option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-save" style="padding: 10px 25px; border-radius: 8px; height: 42px;">Filter Data</button>
                <a href="<?= set_url($form_link) ?>" class="btn-cancel-modal" style="padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 8px; line-height: 20px; height: 42px; box-sizing: border-box;">Reset</a>
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
                    <div class="report-group-section" style="margin-top: 40px; margin-bottom: 20px;">
                        <h3 style="color: #a805a8; font-size: 16px; font-weight: 700; border-left: 4px solid #a805a8; padding-left: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            Desa ' . htmlspecialchars($r['LOKASIDESA'] ?? 'Tidak Diketahui') . 
                            (is_level("Admin, Monitor") ? ' <span style="font-weight: 400; color: #64748b; font-size: 14px; margin-left: auto;">DPL: ' . htmlspecialchars($r['NAMADOSEN'] ?? '') . '</span>' : "") . '
                        </h3>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th width="60px" style="text-align: center;">No</th>
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
                    <td style="text-align: center; color: #64748b; font-weight: 500;">' . $n . '</td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b; font-size: 14px;">' . htmlspecialchars($r["NAMA"]) . '</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">' . htmlspecialchars($r["NPM"]) . '</div>
                    </td>
                    <td style="color: #475569; font-size: 13px;">' . htmlspecialchars($r["PROGRAMSTUDI"]) . '</td>
                    <td style="color: #475569; font-size: 13px;">' . htmlspecialchars($r["LOKASISEKOLAH"] ?? '-') . '</td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">';

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
                        
                        $responClass = 'btn-view';
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

                        // Fix path detection - use FILELINK which is stored correctly in DB
                        $relativeFilePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $l["FILELINK"]);
                        $fullFilePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $relativeFilePath;
                        
                        if (isset($l['FILENAME']) && file_exists($fullFilePath)) {
                            $qw++;
                            echo '
                            <div style="display: flex; gap: 4px; background: #f1f5f9; padding: 3px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <a href="' . $l['FILELINK'] . '" target="_blank" class="btn btn-tiny" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; background: #fff; color: #475569; border-radius: 6px; border: 1px solid #e2e8f0; text-decoration: none; cursor: pointer;" title="Download Laporan ' . $qw . '">' . $qw . '</a>
                                <button type="button" 
                                   data-nama="' . htmlspecialchars($r["NAMA"], ENT_QUOTES) . '" 
                                   data-npm="' . htmlspecialchars($npm, ENT_QUOTES) . '" 
                                   data-laporan="' . htmlspecialchars($namaLaporan, ENT_QUOTES) . '" 
                                   onclick="giveResponseLaporan(this.dataset.nama, this.dataset.npm, this.dataset.laporan)" 
                                   class="btn btn-tiny" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; background: ' . $statusBg . '; color: ' . $statusColor . '; border-radius: 6px; border: 1px solid ' . $statusColor . '33; cursor: pointer; padding: 0;" title="Beri Respons">R</button>
                            </div>';
                        }
                    }
                }

                if ($qw === 0) {
                    echo '<span style="color: #94a3b8; font-style: italic; font-size: 12px; background: #f8fafc; padding: 4px 10px; border-radius: 4px; border: 1px dashed #e2e8f0;">Belum Ada Laporan</span>';
                } elseif ($qw > 1 && $berkasId) {
                    echo '<a href="' . set_url("downloads/reports/bundle/" . $berkasId) . '" class="btn" style="background: #a805a8; color: white; padding: 6px 12px; font-size: 11px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; display: inline-block;">Semua</a>';
                }
                
                echo '</div></td></tr>';
                $n++;
                $myDesa = $r["LOKASIDESA"];
            }
            echo '</tbody></table></div></div>';
        } else { ?>
            <div class="empty-state" style="text-align: center; padding: 80px 20px;">
                <div style="background: #f0e3fc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3 style="color: #1e293b; font-weight: 700; font-size: 20px; margin-bottom: 12px;">Belum Ada Laporan</h3>
                <p style="color: #64748b; max-width: 420px; margin: 0 auto; font-size: 15px; line-height: 1.6;">Saat ini belum ada data laporan mingguan mahasiswa yang tersedia untuk tahun akademik ini.</p>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal Respons Laporan -->
<div id="modal" class="modal">
    <div class="modal-centered" style="max-width: 520px; width: 95%;">
        <div class="content animate" style="border-radius: 16px; border: none; background: #fff; display: flex; flex-direction: column; max-height: 85vh; width: 100%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div class="container" id="contain" style="padding: 0; width: 100%; display: flex; flex-direction: column; overflow: hidden; border-radius: 16px;">
                <div class="title" style="background: #a805a8; color: white; padding: 18px 24px; margin: 0; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                    <h1 style="font-size: 18px; font-weight: 600; margin: 0; padding: 0; border: none; color: white;">
                        Response Laporan
                    </h1>
                    <span onclick="document.getElementById('modal').style.display='none'" style="cursor: pointer; font-size: 24px; line-height: 1; color: white; opacity: 0.8; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'" title="Tutup">&times;</span>
                </div>
                <div class="field" style="padding: 24px; background: white; margin: 0; overflow-y: auto; flex-grow: 1;">
                    <div id="res-ket"></div>
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div id="dta-mhs" style="color: #475569; font-size: 14px; line-height: 1.5;"></div>
                        <div id="dta-lap" style="color: #475569; font-size: 14px; line-height: 1.5;"></div>
                        <div id="dta-ket" style="color: #475569; font-size: 14px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0;"></div>
                    </div>
                    
                    <form action="" method="post" enctype="multipart/form-data" id="res-lap-form">
                        <div style="margin-bottom: 18px;">
                            <label for="res-lap" style="font-weight: 600; display: block; margin-bottom: 8px; color: #1e293b; font-size: 13.5px;">Status Respons</label>
                            <select id="res-lap" name="respons" style="width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; outline: none; transition: all 0.2s; background: #fff; cursor: pointer;" required>
                                <option value="" hidden>Pilih Response</option>
                                <option value="Cukup">Cukup (Diterima)</option>
                                <option value="Kurang">Kurang (Butuh Revisi)</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 24px;" id="komentarLaporan">
                            <label for="komentar" style="font-weight: 600; display: block; margin-bottom: 8px; color: #1e293b; font-size: 13.5px;">Komentar / Catatan Review <span style="color: #ef4444;">*</span></label>
                            <textarea id="komentar" name="komentar" placeholder="Komentar atau catatan revisi wajib diisi..." maxlength="250" style="width: 100%; min-height: 110px; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; resize: vertical; outline: none; transition: all 0.2s; font-family: inherit; line-height: 1.5;" required></textarea>
                            <small style="display: block; margin-top: 8px; color: #64748b; font-size: 12px; font-style: italic;">Komentar wajib diisi terlepas dari status respons.</small>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                            <button type="button" onclick="document.getElementById('modal').style.display='none'" style="padding: 10px 18px; background: #fff; color: #64748b; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Batal</button>
                            <button id="sendResponse-Laporan" type="submit" name="action" value="ResponseLaporan" style="padding: 10px 22px; background: #a805a8; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(168, 5, 168, 0.25);" onmouseover="this.style.background='#8a048a'" onmouseout="this.style.background='#a805a8'">Simpan Respons</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	function giveResponseLaporan(nama, npm, skl) {
		var modal = document.getElementById('modal');
		if (!modal) { alert('Modal tidak ditemukan'); return; }
		modal.style.display = "block";
		document.getElementById('dta-mhs').innerHTML = "<b>Nama</b><br><span id=\"nama\">" + nama + "</span><br><b>NPM</b><br><span id=\"npm\">" + npm + "</span>";
		document.getElementById('dta-lap').innerHTML = "<br><b>Laporan: " + skl + "</b>";
		document.getElementById('res-ket').innerHTML = "";
		document.getElementById('dta-ket').innerHTML = "";
		document.getElementById('res-lap-form').action = "?page=laporan&ajax=balas_laporan&id=" + npm + "&object=" + encodeURIComponent(skl);
		document.getElementById('res-lap').value = "";
		document.getElementById('komentar').value = "";
		readResponseLaporan(npm, skl);
	}

	function readResponseLaporan(npm, skl) {
		try {
			let aj_data = new gcAjax("POST", "?page=laporan&ajax=get_response&id=" + npm + "&object=" + encodeURIComponent(skl))
				.setCallback(function(text, element) {
					if (text && text.trim() !== '') {
						element.innerHTML = '<br><a>Response Tersimpan:<br></a>' + text;
					}
				}).send('dta-ket');
		} catch(e) { /* silent if gcAjax unavailable */ }
	}

	function ajaxPOST(form, button, type) {
		let aj_data = new gcAjax(form);
		aj_data.addValue("status=" + type).setCallback(function(text, element) {
			element.innerHTML = text;
            if (text.includes('info-success')) {
                element.innerHTML += '<div style="margin-top: 15px;"><button class="btn btn-ok" onClick="location.reload()" style="width: 100%; padding: 10px; border-radius: 8px;">Tutup & Perbarui Daftar</button></div>';
            }
		}).send('res-ket', button, '#a805a8');
	}

	document.addEventListener('DOMContentLoaded', function() {
		var form = document.querySelector("#res-lap-form");
		if (form) {
			form.addEventListener("submit", function(event) {
				event.preventDefault();
				const select = document.getElementById('res-lap').value;
				const komentar = document.getElementById('komentar').value.trim();
				
				if (select === "" || komentar === "") {
					document.getElementById('res-ket').innerHTML = '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>Harap lengkapi status respons dan komentar.</a></div>';
					return false;
				}
				
				ajaxPOST(this, document.querySelector("#sendResponse-Laporan"), 'approved');
			}, false);
		}

		/* Klik di luar modal untuk menutup */
		var modal = document.getElementById('modal');
		if (modal) {
			modal.addEventListener('click', function(e) {
				if (e.target === modal) modal.style.display = 'none';
			});
		}
	});
</script>
