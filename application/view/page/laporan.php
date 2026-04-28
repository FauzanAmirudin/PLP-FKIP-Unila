<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Mahasiswa');
?>

<div class="laporan-container">
	<?php 
	$msg = '';
	if (isset($response) && $response != null) {
		$msg = $response;
	} elseif (isset($alert) && $alert != null) {
		$msg = $alert;
	}
	
	if ($msg != '') { 
		$isError = stripos($msg, 'gagal') !== false || stripos($msg, 'error') !== false || stripos($msg, 'belum disetujui') !== false;
	?>
		<!-- Modern Toast Notification Server Response -->
		<div id="toastServer" class="toast-notification <?= $isError ? 'toast-error' : 'toast-success' ?>">
			<div class="toast-icon">
				<?php if ($isError) { ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
				<?php } else { ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
				<?php } ?>
			</div>
			<div class="toast-content">
				<div class="toast-title"><?= $isError ? 'Gagal' : 'Berhasil' ?></div>
				<div class="toast-message"><?php echo str_replace(['<br>', '<br/>'], '', $msg); ?></div>
			</div>
			<button class="toast-close" onclick="closeToastServer()">×</button>
		</div>
		
		<script>
			setTimeout(function() {
				var toast = document.getElementById('toastServer');
				if(toast) toast.classList.add('hide');
			}, 6000);
			function closeToastServer() {
				document.getElementById('toastServer').classList.add('hide');
			}
		</script>
		
		<style>
			.toast-notification {
				position: fixed; top: 24px; right: 24px; background: #fff;
				box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 16px 20px;
				border-radius: 8px; display: flex; align-items: center; gap: 16px;
				z-index: 99999; min-width: 300px;
				animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
			}
			.toast-notification.hide { animation: slideOutRight 0.4s ease-in forwards; }
			.toast-success { border-left: 4px solid #10b981; }
			.toast-error { border-left: 4px solid #ef4444; }
			.toast-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
			.toast-success .toast-icon { background: #d1fae5; color: #10b981; }
			.toast-error .toast-icon { background: #fee2e2; color: #ef4444; }
			.toast-icon svg { width: 18px; height: 18px; }
			.toast-title { font-weight: 600; color: #1f2937; font-size: 15px; margin-bottom: 4px; }
			.toast-message { color: #6b7280; font-size: 13px; line-height: 1.4; }
			.toast-close { background: transparent; border: none; font-size: 22px; color: #9ca3af; cursor: pointer; margin-left: auto; padding: 0 4px; display:flex; align-items:center; }
			.toast-close:hover { color: #4b5563; }
		</style>
	<?php } ?>

	<div class="laporan-card">
		<div class="card-header">
			<h1 class="card-title">Daftar Laporan</h1>
			<?php if (isset($registration_done) && $registration_done) { ?>
				<button class="btn-save" onclick="toggleUploadPanel()">+ Upload Laporan</button>
			<?php } ?>
		</div>
		
		<?php if (isset($registration_done) && $registration_done) { ?>
		<div id="uploadPanel" class="upload-panel-collapse">
			<form action="?page=laporan/simpan/<?= session_get('ID') ?>" method="post" enctype="multipart/form-data" class="laporan-form" id="formUploadLaporan" onsubmit="return validateLaporanUpload(event)">
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
					<input type="file" id="fileLaporan" name="file" class="input-control file-input" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required="required" style="height: auto; padding: 6px;">
					<span class="help-text">* Hanya file .doc, .docx dengan ukuran maximum 5 MB.</span>
				</div>

				<div class="form-actions">
					<button type="button" class="btn-cancel-modal" onclick="toggleUploadPanel()">Batal</button>
					<button type="submit" value="uplodlaporan" name="action" class="btn-save">Upload File</button>
				</div>
			</form>
		</div>
		<?php } else { ?>
			<div style="margin-bottom: 20px; padding: 12px 16px; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 6px; color: #dc2626; font-size: 14px;">
				⚠️ Anda belum bisa mengupload laporan karena pendaftaran Anda belum disetujui. Silakan selesaikan pendaftaran Anda terlebih dahulu.
			</div>
		<?php } ?>

		<div class="report-list">
			<?php
			// Use $report passed from controller (queried via report->direct())
			$laporan = isset($report) ? $report : array();
            if (!empty($laporan)) {
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

				$mark = '';
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

	function validateLaporanUpload(event) {
		const fileInput = document.getElementById('fileLaporan');
		const file = fileInput.files[0];
		
		if (!file) {
			showUploadAlert("Silakan pilih file laporan terlebih dahulu.");
			return false;
		}

		const fileName = file.name.toLowerCase();
		const fileSizeMB = file.size / (1024 * 1024);

		if (!fileName.endsWith('.doc') && !fileName.endsWith('.docx')) {
			showUploadAlert("Tipe file tidak valid. Harap unggah file dengan format dokumen (.doc atau .docx).");
			return false;
		}

		if (fileSizeMB > 5) {
			showUploadAlert("Ukuran file terlalu besar. Maksimal ukuran file adalah 5 MB. Ukuran file Anda: " + fileSizeMB.toFixed(2) + " MB.");
			return false;
		}

		return true;
	}

	function showUploadAlert(msg) {
		document.getElementById('uploadAlertMsg').innerText = msg;
		document.getElementById('uploadAlertModal').style.display = 'flex';
	}

	function closeUploadAlert() {
		document.getElementById('uploadAlertModal').style.display = 'none';
	}
</script>

<!-- Custom Alert Modal untuk Validasi Upload Laporan -->
<div id="uploadAlertModal" class="modal-overlay" style="display:none;">
	<div class="modal-content">
		<div class="modal-icon error">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
		</div>
		<h3 class="modal-title">Upload Gagal</h3>
		<p id="uploadAlertMsg" class="modal-message"></p>
		<button class="modal-btn" onclick="closeUploadAlert()">Mengerti</button>
	</div>
</div>

<style>
.modal-overlay {
	position: fixed; top: 0; left: 0; width: 100%; height: 100%;
	background: rgba(0, 0, 0, 0.6); z-index: 9999;
	display: flex; align-items: center; justify-content: center;
	backdrop-filter: blur(3px);
}
.modal-content {
	background: #fff; border-radius: 12px; padding: 24px;
	width: 90%; max-width: 320px; text-align: center;
	box-shadow: 0 10px 25px rgba(0,0,0,0.2);
	animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.modal-icon {
	width: 48px; height: 48px; border-radius: 50%;
	display: flex; align-items: center; justify-content: center;
	margin: 0 auto 16px;
}
.modal-icon.error { background: #fee2e2; color: #dc2626; }
.modal-icon svg { width: 24px; height: 24px; }
.modal-title { font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0 0 8px; }
.modal-message { font-size: 0.875rem; color: #4b5563; margin: 0 0 20px; line-height: 1.5; }
.modal-btn {
	background: #a805a8; color: #fff; border: none;
	padding: 10px 24px; border-radius: 8px; font-weight: 500;
	cursor: pointer; width: 100%; transition: background 0.2s;
}
.modal-btn:hover { background: #860486; }
@keyframes popIn {
	0% { opacity: 0; transform: scale(0.9); }
	100% { opacity: 1; transform: scale(1); }
}
</style>
