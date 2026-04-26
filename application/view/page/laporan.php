<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Mahasiswa');
?>

<div class="laporan-container">
	<?php if (isset($response) && $response != null) {
		echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
	} ?>

	<div class="laporan-card">
		<div class="card-header">
			<h1 class="card-title">Daftar Laporan</h1>
			<button class="btn-save" onclick="toggleUploadPanel()">+ Upload Laporan</button>
		</div>
		
		<div id="uploadPanel" class="upload-panel-collapse">
			<form action="?page=laporan" method="post" enctype="multipart/form-data" class="laporan-form">
				<div class="form-row">
					<div class="form-group-modern">
						<label for="laporan">Laporan yang akan di upload <span class="required">*</span></label>
						<select id="laporan" name="laporan" class="input-control" required="required">
							<?php
							$ming = 1;
							while ($ming <= $config['MAXREPORT']) { ?>
								<option value="Laporan Minggu Ke-<?= $ming; ?>">Laporan Minggu Ke-<?= $ming; ?></option>
							<?php
								$ming++;
							} ?>
						</select>
					</div>
					<div class="form-group-modern checkbox-group" style="padding-top: 30px;">
						<label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
							<input type="checkbox" name="timpa" value="1">
							Perbaharui laporan lama
						</label>
					</div>
				</div>

				<div class="form-group-modern">
					<label>Pilih file laporan <span class="required">*</span></label>
					<input type="file" name="file" class="input-control file-input" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required="required" style="height: auto; padding: 6px;">
					<span class="help-text">* Hanya file .doc, .docx dengan ukuran maximum 5 MB.</span>
				</div>

				<div class="form-actions">
					<button type="button" class="btn-cancel-modal" onclick="toggleUploadPanel()">Batal</button>
					<button type="submit" value="uplodlaporan" name="action" class="btn-save">Upload File</button>
				</div>
			</form>
		</div>

		<div class="report-list">
			<?php
			$laporanAccess = clone $this->database('default', 'dbconfig', TRUE);
			$laporan = $laporanAccess->reset()->where("`NPM`='" . session_get('USERID') . "'")->order('`FILENAME`', FALSE)->result_array('laporan');
            if ($laporan) {
			foreach ($laporan as $r) {
				// Status Logic mapped from existing checks
				if ($r['RESPONSE'] != NULL && $r['RESPONSE'] != "") {
					if ($r['RESPONSE'] == "Cukup") {
                        $responBtn = "btn-view";
						$prop = 'disabled="true"';
						$statusBadge = '<span class="badge badge-read">Sudah Diperiksa</span>';
					} else {
                        $responBtn = "btn-cancel";
						$prop = "";
						$statusBadge = '<span class="badge badge-unread">Perlu Revisi</span>';
					}
				} else {
					$responBtn = "btn-disable";
					$prop = 'disabled="true"';
					$statusBadge = '<span class="badge badge-pending">Belum Diperiksa</span>';
				}

				if (isset($namaLaporan) && $namaLaporan == $r['FILENAME']) {
					$mark = '<span class="update-mark"> (Terbaru)</span>';
				} else {
					$mark = '';
				}
				?>
				<div class="report-item">
					<div class="report-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2V8H20" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 13H8" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 17H8" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 9H9H8" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
					<div class="report-info">
						<h3 class="report-filename"><?= htmlspecialchars($r['FILENAME']) ?><?= $mark ?></h3>
						<div class="report-status"><?= $statusBadge ?></div>
					</div>
					<div class="report-actions">
						<a class="btn btn-tiny btn-download" style="padding: 6px 12px; height: auto; text-decoration: none;" href="<?= htmlspecialchars($r['FILELINK']) ?>">Unduh</a>
						<button class="btn btn-tiny <?= $responBtn ?>" <?= $prop ?> onclick="readResponseLaporan('<?= $r['NPM'] ?>', '<?= $r['FILENAME'] ?>')" style="padding: 6px 12px; height: auto;">Respon</button>
					</div>
				</div>
			<?php } 
            }
            if (empty($laporan)) {
                echo '<div class="empty-state"><p>Tidak Ada Laporan</p></div>';
            }
            ?>
		</div>
	</div>
</div>

<div id="modal" class="modal">
	<div class="modal-centered" style="width: 450px;">
		<div class="animate">
			<div class="content" style="background:#fff; border-radius:8px; overflow:hidden;">
				<div class="title" style="background:#f8f9fa; padding:15px; border-bottom:1px solid #eaeaea;">
					<h1 style="display:flex; justify-content:space-between; margin:0; font-size:16px; color:#a805a8;">Respons Laporan
						<span class="action-right">
							<a onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right; cursor:pointer;"></a>
						</span>
					</h1>
				</div>
				<div class="container" id="contain" style="padding: 20px;">
					<div class="field">
						<div id="response"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function toggleUploadPanel() {
		const panel = document.getElementById('uploadPanel');
		panel.classList.toggle('active');
	}

	function readResponseLaporan(npm, skl) {
		var xhttp = new XMLHttpRequest();
		document.getElementById('modal').style.display = "block";
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("response").innerHTML = this.responseText;
			}
		};
		xhttp.open("GET", "?page=laporan&ajax=get_response&id=" + npm + "&object=" + skl, true);
		xhttp.send();
	}
</script>
