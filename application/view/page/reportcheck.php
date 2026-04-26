<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

is_level("Admin, Monitor, DPL");
?>
<style type="text/css">
	.comment-text-area {
		font-size: 12pt;
		width: calc(100% - 20px);
		min-height: 150px;
		height: auto;
		padding: 10px;
		border: 1px solid #ccc;
		font-style: normal;
		font-family: inherit;
		border-radius: 4px;
	}

	select {
		min-width: 100px;
		padding: 3px;
		border-radius: 4px;
		border: 1px solid #ccc;
		font-family: inherit;
		font-size: 12pt;
	}
</style>
<div class="settings-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
    } ?>

    <div class="settings-card">
        <div class="card-header">
            <h1 class="card-title">Data Laporan Mingguan <?= isset($config['CURENTYEAR']) ? htmlspecialchars($config['CURENTYEAR']) : '' ?></h1>
            <p class="card-subtitle">Manajemen dan verifikasi laporan aktivitas mingguan mahasiswa PLT.</p>
        </div>

        <?php
        $key = is_level("Admin, Monitor") ? "`NIPDPL` IS NOT NULL" : "`NIPDPL` = '" . session_get('USERID') . "'";
        $currentYear = isset($config['CURENTYEAR']) ? $config['CURENTYEAR'] : get_dbconfig('CURENTYEAR');
        $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
        $dbAccess->join('datapenempatan', 'NIPDPL = NIPDOSEN');
        $dbAccess->join('datamahasiswa', 'datamahasiswa.NPM = datapenempatan.NPMPESERTA');
        $dbAccess->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY');
        $dbAccess->where($key);
        if (!empty($currentYear)) {
            $dbAccess->where(["TAHUNDAFTAR" => $currentYear]);
        }
        $dataMahasiswa = $dbAccess->order(['NAMADOSEN', 'LOKASIDESA', 'LOKASISEKOLAH', 'NPM'])->result_array('dosen');
        
        if ($dataMahasiswa && count($dataMahasiswa) > 0) {
            $myDesa = "0";
            foreach ($dataMahasiswa as $index => $r) {
                if ($r['LOKASIDESA'] != $myDesa) {
                    if ($myDesa != "0") {
                        echo '</tbody></table></div></div>';
                    }
                    echo '
                    <div class="report-group-section" style="margin-top: 30px; margin-bottom: 20px;">
                        <h3 style="color: #a805a8; font-size: 16px; font-weight: 700; border-left: 4px solid #a805a8; padding-left: 15px; margin-bottom: 15px;">
                            Desa ' . htmlspecialchars($r['LOKASIDESA']) . 
                            (is_level("Admin, Monitor") ? ' <span style="font-weight: 400; color: #64748b; font-size: 14px; margin-left: 10px;">/ DPL: ' . htmlspecialchars($r['NAMADOSEN']) . '</span>' : "") . '
                        </h3>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Sekolah</th>
                                        <th width="150px">Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>';
                    $n = 1;
                }
                
                echo '
                <tr>
                    <td>' . $n . '</td>
                    <td>
                        <div style="font-weight: 600; color: #333;">' . htmlspecialchars($r["NAMA"]) . '</div>
                        <div style="font-size: 12px; color: #64748b;">' . htmlspecialchars($r["NPM"]) . '</div>
                    </td>
                    <td>' . htmlspecialchars($r["PROGRAMSTUDI"]) . '</td>
                    <td>' . htmlspecialchars($r["LOKASISEKOLAH"]) . '</td>
                    <td style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">';

                $qw = null;
                $item = $r["NPM"];
                $file = $dbAccess->reset()->where("`NPM`='" . $item . "'")->order('`FILENAME`')->result_array('laporan');
                $ln = 1;
                if ($file) {
                    foreach ($file as $l) {
                        $namaLaporan = $l['FILENAME'];
                        $fileext = $l['FILEEXT'];
                        $npm = $l['NPM'];
                        
                        $responClass = 'btn-view';
                        if (!empty($l['RESPONSE'])) {
                            $responClass = ($l['RESPONSE'] == "Cukup") ? 'btn-ok' : 'btn-cancel';
                        }

                        $filePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $l["FILEPATH"] . $namaLaporan . $fileext;
                        if ($ln <= 9 && isset($l['FILENAME']) && file_exists($filePath)) {
                            echo '
                            <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                                <a href="' . $l['FILELINK'] . '" class="btn btn-tiny btn-download" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 10px;" title="Download Laporan ' . $ln . '">' . $ln . '</a>
                                <a onclick="giveResponseLaporan(\'' . addslashes($r["NAMA"]) . '\', \'' . $npm . '\', \'' . addslashes($namaLaporan) . '\')" 
                                   class="btn btn-tiny ' . $responClass . '" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 10px;" title="Beri Respons">R</a>
                            </div>';
                        }
                        $ln++;
                        $qw += 1;
                    }
                }

                if (!isset($qw)) {
                    echo '<span style="color: #94a3b8; font-style: italic; font-size: 13px;">Belum Ada</span>';
                } elseif (isset($qw) && $qw > 1) {
                    echo '<a href="?access=downloads&id=laporan&sr=' . $r["TAHUNDAFTAR"] . '&file=' . $npm . '" class="btn btn-tiny btn-download" style="background: #64748b; padding: 4px 8px; font-size: 11px;">Semua</a>';
                }
                
                echo '</td></tr>';
                $n++;
                $myDesa = $r["LOKASIDESA"];
            }
            echo '</tbody></table></div></div>';
        } else { ?>
            <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                <div style="background: #f0e3fc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3 style="color: #333; font-weight: 700; margin-bottom: 10px;">Belum Ada Laporan</h3>
                <p style="color: #64748b; max-width: 400px; margin: 0 auto;">Saat ini belum ada data laporan mingguan mahasiswa yang tersedia untuk tahun akademik ini.</p>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal Respons Laporan -->
<div id="modal" class="modal">
    <div class="modal-centered" style="max-width: 450px; width: 90%;">
        <div class="content animate" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="container" id="contain" style="padding: 0;">
                <div class="card-header" style="background: #a805a8; padding: 20px 25px; margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="card-title" style="color: #fff; margin: 0; padding: 0; border: none; font-size: 18px;">Response Laporan</h1>
                        <a onclick="document.getElementById('modal').style.display='none'" style="cursor: pointer; color: #fff; opacity: 0.8; transition: 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </a>
                    </div>
                </div>
                <div style="padding: 25px;">
                    <div id="res-ket"></div>
                    <div id="dta-mhs" style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0;"></div>
                    <div id="dta-ket" style="margin-bottom: 15px;"></div>
                    <div id="dta-lap" style="font-weight: 600; margin-bottom: 15px; color: #a805a8;"></div>
                    
                    <form action="" method="post" enctype="multipart/form-data" id="res-lap-form">
                        <div class="settings-form-group">
                            <label for="res-lap">Status Respons</label>
                            <select id="res-lap" name="respons" onChange="displayKomentarLaporan(this.value)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                                <option value="Tidak Ada" hidden>Pilih Response</option>
                                <option value="Cukup">Cukup (Diterima)</option>
                                <option value="Kurang">Kurang (Butuh Revisi)</option>
                            </select>
                        </div>
                        <div class="settings-form-group" id="komentarLaporan" style="display:none; margin-top: 15px;">
                            <label for="komentar">Komentar / Catatan Revisi</label>
                            <textarea class="comment-text-area" id="komentar" name="komentar" placeholder="Tambahkan komentar detail mengapa laporan kurang..." maxlength="250" style="width: 100%; border-radius: 8px; border: 1px solid #ddd; padding: 12px;"></textarea>
                        </div>
                        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                            <button id="sendResponse-Laporan" type="submit" name="action" value="ResponseLaporan" class="btn-save">Simpan Respons</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	function giveResponseLaporan(nama, npm, skl) {
		document.getElementById('modal').style.display = "block";
		document.getElementById('dta-mhs').innerHTML = "<b>Nama</b><br><a id=\"nama\">" + nama + "</a><br><b>NPM</b><br><a id=\"npm\">" + npm + "</a>";
		document.getElementById('dta-lap').innerHTML = "<br><b>Respons " + skl + "</b>";
		document.getElementById('res-ket').innerHTML = "";
		document.getElementById('dta-ket').innerHTML = "";
		document.getElementById('res-lap-form').action = "?page=cekLaporan&ajax=balas_laporan&id=" + npm + "&object=" + skl;
		document.getElementById('res-lap').selectedIndex = 0;
		document.getElementById('komentarLaporan').style.display = "none";
		readResponseLaporan(npm, skl);
	}

	function readResponseLaporan(npm, skl) {
		let aj_data = new gcAjax("POST", "?page=laporan&ajax=get_response&id=" + npm + "&object=" + skl)
			.setCallback(function(text, element) {
				element.innerHTML = '<br><a>Response Tersimpan:<br></a>' + text;
			}).send('dta-ket');
	}

	function displayKomentarLaporan(answer) {
		if (answer == "Kurang") {
			document.getElementById('komentarLaporan').style.display = "block";
		}
		if (answer == "Cukup") {
			document.getElementById('komentarLaporan').style.display = "none";
		}
	}


	function ajaxPOST(form, button, type) {
		console.log(form.attributes.action.value);
		let aj_data = new gcAjax(form);
		aj_data.addValue("status=" + type).setCallback(function(text, element) {
			let relodBtn = '<button class="btn btn-ok" onClick="location.reload()">Perbaharui Daftar</button></div>';
			element.innerHTML = text;
		}).send('res-ket', button, '#6424D9');
	}
	document.querySelector("#sendResponse-Laporan").addEventListener("click", function(event) {
		event.preventDefault();
		ajaxPOST(this.form, this, 'approved');
	}, false);
</script>
