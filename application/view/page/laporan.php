<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Mahasiswa');
?>
<style>
.laporan-container .report-list .report-item .report-actions .btn-action-laporan.btn-response-success {
    background: #16a34a;
    color: white;
}
.laporan-container .report-list .report-item .report-actions .btn-action-laporan.btn-response-success:hover {
    background: #15803d;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
}
.laporan-container .upload-panel-collapse select.input-control {
    background-size: 12px !important;
}
</style>

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
							<option value="Laporan Akhir PLP 1">Laporan Akhir PLP 1</option>
							<option value="Laporan Akhir PLP 2">Laporan Akhir PLP 2</option>
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
					<input type="file" id="fileLaporan" name="file" class="input-control file-input" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required="required">
					<span class="help-text">* Hanya file .doc, .docx dengan ukuran maximum 5 MB.</span>
				</div>

				<div class="form-actions">
					<button type="button" class="btn-cancel-modal" onclick="toggleUploadPanel()">Batal</button>
					<button type="submit" value="uplodlaporan" name="action" class="btn-save">Upload File</button>
				</div>
			</form>
		</div>
		<?php } else { ?>
			<div class="alert-danger-modern">
				⚠️ Anda belum bisa mengupload laporan karena pendaftaran Anda belum disetujui. Silakan selesaikan pendaftaran Anda terlebih dahulu.
			</div>
		<?php } ?>

		<div class="report-list">
			<?php
			// Use $report passed from controller (queried via report->direct())
			$laporan = isset($report) ? $report : array();
			if (!empty($laporan) && is_array($laporan)) {
				usort($laporan, function($a, $b) {
					$nameA = isset($a['FILENAME']) ? $a['FILENAME'] : '';
					$nameB = isset($b['FILENAME']) ? $b['FILENAME'] : '';
					
					$isAkhirA = (stripos($nameA, 'Akhir') !== false);
					$isAkhirB = (stripos($nameB, 'Akhir') !== false);
					
					if ($isAkhirA && !$isAkhirB) return 1;
					if (!$isAkhirA && $isAkhirB) return -1;
					
					return strnatcasecmp($nameA, $nameB);
				});
			}
            if (!empty($laporan)) {
			foreach ($laporan as $r) {
				// Status Logic mapped from existing checks
				$responAvailable = ($r['RESPONSE'] != NULL && $r['RESPONSE'] != "");
				if ($responAvailable) {
					if ($r['RESPONSE'] == "Cukup") {
                        $responBtn = "btn-response-success"; // Green Solid
						$statusBadge = '<span class="badge badge-read" style="background: #dcfce7; color: #16a34a;">Selesai (Cukup)</span>';
					} else {
                        $responBtn = "btn-response-warning"; // Oranye/Kuning Outline
						$statusBadge = '<span class="badge badge-unread" style="background: #fef3c7; color: #d97706;">Perlu Revisi (Kurang)</span>';
					}
					$prop = ""; // Selalu bisa diklik jika ada respon
				} else {
					$responBtn = "btn-response-disabled";
					$prop = 'disabled="true"';
					$statusBadge = '<span class="badge badge-pending" style="background: #f1f5f9; color: #64748b;">Menunggu Diperiksa</span>';
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
						<h3 class="report-filename"><?= htmlspecialchars($r['FILENAME']) ?></h3>
						<div class="report-status"><?= $statusBadge ?></div>
					</div>
					<div class="report-actions">
						<a class="btn-action-laporan btn-download-outline" href="?page=downloads/reports/file/<?= $r['ID'] ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Unduh
                        </a>
						<button class="btn-action-laporan <?= $responBtn ?>" <?= $prop ?> onclick="readResponseLaporan('<?= $r['ID'] ?>', '<?= $r['FILENAME'] ?>')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            Respon
                        </button>
					</div>
				</div>
			<?php } 
            }
            if (empty($laporan)) {
                echo '<div class="empty-state"><p>Tidak Ada Laporan yang Diupload</p></div>';
            }
            ?>
		</div>
	</div>
</div>

<!-- Modal Detail Respons -->
<div id="modal" class="modal modal-laporan-detail">
    <div class="modal-centered">
        <div class="content animate">
            <div class="container" id="contain">
                <div class="title">
                    <h1>Hasil Review Laporan</h1>
                    <span class="close" onclick="document.getElementById('modal').style.display='none'" title="Tutup">&times;</span>
                </div>
                <div class="field">
                    <div id="response-content">
                        <div class="loading-wrapper">
                            <div class="loading-spinner"></div>
                            <p>Memuat respon...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel-modal" onclick="document.getElementById('modal').style.display='none'">Tutup</button>
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

	function readResponseLaporan(reportId, skl) {
		const contentArea = document.getElementById("response-content");
		const modal = document.getElementById('modal');
		
		/* Reset content and show modal */
		contentArea.innerHTML = `
			<div class="loading-wrapper">
				<div class="loading-spinner"></div>
				<p>Memuat respon...</p>
			</div>
		`;
		modal.style.display = "block";

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					var trimmed = this.responseText.trim();
					if (trimmed !== "" && trimmed !== "null" && trimmed.length > 5) {
						contentArea.innerHTML = `
							<div class="response-box">
								<div class="response-header">
									<span class="label">Laporan</span>
									<div class="value">${skl}</div>
								</div>
								${trimmed}
							</div>
						`;
					} else {
						contentArea.innerHTML = '<div class="loading-wrapper"><p>Belum ada respon untuk laporan ini.</p></div>';
					}
				} else {
					contentArea.innerHTML = '<div class="loading-wrapper"><p style="color: #ef4444;">Gagal memuat data. Silakan coba lagi.</p></div>';
				}
			}
		};
		var ts = new Date().getTime();
		xhttp.open("GET", "?page=laporan&ajax=get_response&id=" + reportId + "&object=" + encodeURIComponent(skl) + "&_=" + ts, true);
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
