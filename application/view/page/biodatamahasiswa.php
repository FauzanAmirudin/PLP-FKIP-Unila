<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<!doctype html>
<html>
<script type="text/javascript">
	function displayQuestElement(answer) {
		document.getElementById(answer).style.display = "block";
		document.getElementById(answer).required = true;
		document.getElementById('prodyDummy').required = false;
		if (answer == "Ilmu Pendidikan") {
			document.getElementById('Pendidikan Bahasa dan Seni').style.display = "none";
			document.getElementById('Pendidikan IPS').style.display = "none";
			document.getElementById('Pendidikan MIPA').style.display = "none";
			document.getElementById('prodyDummy').style.display = "none";
			document.getElementById('Pendidikan Bahasa dan Seni').value = "";
			document.getElementById('Pendidikan IPS').value = "";
			document.getElementById('Pendidikan MIPA').value = "";
			document.getElementById('prodyDummy').value = "";
			document.getElementById('Pendidikan Bahasa dan Seni').required = false;
			document.getElementById('Pendidikan IPS').required = false;
			document.getElementById('Pendidikan MIPA').required = false;
			document.getElementById('prodyDummy').required = false;
		}
		if (answer == "Pendidikan Bahasa dan Seni") {
			document.getElementById('Ilmu Pendidikan').style.display = "none";
			document.getElementById('Pendidikan IPS').style.display = "none";
			document.getElementById('Pendidikan MIPA').style.display = "none";
			document.getElementById('prodyDummy').style.display = "none";
			document.getElementById('Ilmu Pendidikan').value = "";
			document.getElementById('Pendidikan IPS').value = "";
			document.getElementById('Pendidikan MIPA').value = "";
			document.getElementById('prodyDummy').value = "";
			document.getElementById('Ilmu Pendidikan').required = false;
			document.getElementById('Pendidikan IPS').required = false;
			document.getElementById('Pendidikan MIPA').required = false;
			document.getElementById('prodyDummy').required = false;
		}
		if (answer == "Pendidikan IPS") {
			document.getElementById('Pendidikan Bahasa dan Seni').style.display = "none";
			document.getElementById('Ilmu Pendidikan').style.display = "none";
			document.getElementById('Pendidikan MIPA').style.display = "none";
			document.getElementById('prodyDummy').style.display = "none";
			document.getElementById('Pendidikan Bahasa dan Seni').value = "";
			document.getElementById('Ilmu Pendidikan').value = "";
			document.getElementById('Pendidikan MIPA').value = "";
			document.getElementById('prodyDummy').value = "";
			document.getElementById('Ilmu Pendidikan').required = false;
			document.getElementById('Pendidikan Bahasa dan Seni').required = false;
			document.getElementById('Pendidikan MIPA').required = false;
			document.getElementById('prodyDummy').required = false;
		}
		if (answer == "Pendidikan MIPA") {
			document.getElementById('Pendidikan Bahasa dan Seni').style.display = "none";
			document.getElementById('Pendidikan IPS').style.display = "none";
			document.getElementById('Ilmu Pendidikan').style.display = "none";
			document.getElementById('prodyDummy').style.display = "none";
			document.getElementById('Pendidikan Bahasa dan Seni').value = "";
			document.getElementById('Pendidikan IPS').value = "";
			document.getElementById('Ilmu Pendidikan').value = "";
			document.getElementById('prodyDummy').value = "";
			document.getElementById('Ilmu Pendidikan').required = false;
			document.getElementById('Pendidikan Bahasa dan Seni').required = false;
			document.getElementById('Pendidikan IPS').required = false;
			document.getElementById('prodyDummy').required = false;
		} else {}
	}
</script>

<?php
$set = 0;
$dbAccess = clone $this->database('default', 'dbconfig', TRUE);
$dataID = empty($_GET['NPM']) ? session_get('USERID') : strip_tags($_GET['NPM']);
$r = $dbAccess->reset()->where("`NPM` = '$dataID'")->result_row_array('datamahasiswa');
?>
<style>
	@media print {

		@page {
			margin: 20mm;
			size: A4 portrait;
		}

		body {
			width: 240mm;
			height: 297mm;
		}

		content .content .header {
			font-size: 14pt;
			margin-top: 5mm;
			page-break-after: avoid;
		}

		content .content .field {
			page-break-after: avoid;
		}

		.form .form-group {
			padding: 1mm !important;
		}

		.form .dot {
			margin-top: 1mm !important;
			top: 0mm !important;
		}

		.form .form-group label {
			margin-top: 1mm !important;
			top: 0mm !important;
		}

		#form1 {
			text-align: center;
		}

		#form1 button {
			display: none !important;
		}
	}
</style>
<div class="profile-container">
	<?php if (isset($notification) && $notification != null) {
		echo '<div class="notif notif-primary-strong">' . $notification . '</div>';
	} ?>

	<div class="profile-layout-wrapper">
		<div class="profile-left-pane">
			<div class="avatar-interactive" onclick="toggleUploadPanel()">
				<?php if ($r['FTPROFIL'] != "") { ?>
					<img src="<?= $r['FTPROFIL'] ?>" alt="Profile Photo">
				<?php } else { ?>
					<div class="fallback-avatar">Upload Foto</div>
				<?php } ?>
			</div>
			<div class="profile-name">
				<h2><?= $r["NAMA"] ?></h2>
				<p>NPM: <?php echo $r["NPM"]; ?></p>
			</div>
			
			<div id="uploadPanel" class="upload-panel-collapse">
				<form action="?page=mahasiswa/simpan_foto/<?= $r['USRKEY'] ?>" method="post" enctype="multipart/form-data" onsubmit="return validateUpload(event)">
					<div class="upload-form-group">
						<input type="file" id="fotoInput" name="file" accept="image/jpeg, image/jpg">
						<div class="action-buttons">
							<button type="submit" name="action" value="uploadFoto" class="btnOk">Upload</button>
							<button type="reset" class="btnCancel" onclick="toggleUploadPanel()">Batal</button>
						</div>
						<div class="help-block"><a>*</a> hanya file image .jpg dengan ukuran maximum 100 Kb.</div>
					</div>
				</form>
			</div>
		</div>

		<div class="profile-right-pane">
			<h3 class="card-title">Data Biodata Personal</h3>
			<form id="form2" method="post" action="?page=mahasiswa/simpan_biodata/<?= $r['USRKEY'] ?>">
				<div class="form-grid">
				<div class="form-group-modern">
					<label for="nama">Nama<span class="required">*</span></label>
					<input class="bioFormInput" id="nama" name="nama" value="<?= $r["NAMA"] ?>" placeholder="Masukan Nama" type="text" required="required" autofocus>
				</div>
				
				<div class="form-group-modern">
					<label for="npm">NPM<span class="required">*</span></label>
					<input disabled id="npm" name="npm" value="<?php echo $r["NPM"]; ?>" placeholder="Masukan NPM" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="jurusan">Jurusan<span class="required">*</span></label>
					<select class="bioFormInput" id="jurusan" name="jurusan" required onChange="displayQuestElement(this.value)">
						<option value="" hidden>Pilih Jurusan</option>
						<option value="Ilmu Pendidikan">Ilmu Pendidikan</option>
						<option value="Pendidikan Bahasa dan Seni">Pendidikan Bahasa dan Seni</option>
						<option value="Pendidikan IPS">Pendidikan IPS</option>
						<option value="Pendidikan MIPA">Pendidikan MIPA</option>
					</select>
				</div>

				<div class="form-group-modern">
					<label for="prodyDummy">Program Studi<span class="required">*</span></label>
					<select class="bioFormInput" id="prodyDummy" name="programStudy" onChange="displayQuestElement(this.value)">
						<option value="">Pilih Program studi</option>
						<option value="">Pilih jurusan terlebih dahulu</option>
					</select>
					<select class="bioFormInput" id="Ilmu Pendidikan" name="programStudy1" onChange="displayQuestElement(this.value)" style="display:none">
						<option value="">Pilih Program studi</option>
						<option value="Bimbingan dan Konseling">Bimbingan dan Konseling</option>
						<option value="Pendidikan Guru PAUD">Pendidikan Guru PAUD</option>
						<option value="Pendidikan Guru Sekolah Dasar">Pendidikan Guru Sekolah Dasar</option>
						<option value="Penjaskes">Penjaskes</option>
					</select>
					<select class="bioFormInput" id="Pendidikan Bahasa dan Seni" name="programStudy2" onChange="displayQuestElement(this.value)" style="display:none">
						<option value="">Pilih Program studi</option>
						<option value="Pendidikan Bahasa dan Sastra Indonesia">Pendidikan Bahasa dan Sastra Indonesia</option>
						<option value="Pendidikan Bahasa Inggris">Pendidikan Bahasa Inggris</option>
						<option value="Pendidikan Bahasa Lampung">Pendidikan Bahasa Lampung</option>
						<option value="Pendidikan Bahasa Prancis">Pendidikan Bahasa Prancis</option>
						<option value="Pendidikan Musik">Pendidikan Musik</option>
						<option value="Pendidikan Tari">Pendidikan Tari</option>
					</select>
					<select class="bioFormInput" id="Pendidikan IPS" name="programStudy3" onChange="displayQuestElement(this.value)" style="display:none">
						<option value="">Pilih Program studi</option>
						<option value="Pendidikan Ekonomi">Pendidikan Ekonomi</option>
						<option value="Pendidikan Geografi">Pendidikan Geografi</option>
						<option value="Pendidikan Pancasila dan Kewarganegaraan">Pendidikan Pancasila dan Kewarganegaraan</option>
						<option value="Pendidikan Sejarah">Pendidikan Sejarah</option>
					</select>
					<select class="bioFormInput" id="Pendidikan MIPA" name="programStudy4" onChange="displayQuestElement(this.value)" style="display:none">
						<option value="">Pilih Program studi</option>
						<option value="Pendidikan Biologi">Pendidikan Biologi</option>
						<option value="Pendidikan Fisika">Pendidikan Fisika</option>
						<option value="Pendidikan Kimia">Pendidikan Kimia</option>
						<option value="Pendidikan Matematika">Pendidikan Matematika</option>
						<option value="Pendidikan Teknologi Informasi">Pendidikan Teknologi Informasi</option>
					</select>
				</div>

				<div class="form-group-modern">
					<label for="sks">SKS<span class="required">*</span></label>
					<input class="bioFormInput" id="sks" name="sks" value="<?php echo $r["SKS"]; ?>" placeholder="Masukan Jumlah SKS" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="ipk">IPK<span class="required">*</span></label>
					<input class="bioFormInput" id="ipk" name="ipk" value="<?php echo $r["IPK"]; ?>" placeholder="Masukan Nilai IPK" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="jenisKelamin">Jenis Kelamin<span class="required">*</span></label>
					<select class="bioFormInput" id="jenisKelamin" name="jenisKelamin" required>
						<option value="" selected hidden disabled>Pilih Jenis Kelamin</option>
						<option value="Laki-Laki">Laki-Laki</option>
						<option value="Perempuan">Perempuan</option>
					</select>
				</div>

				<div class="form-group-modern">
					<label for="Agama">Agama<span class="required">*</span></label>
					<select class="bioFormInput" id="Agama" name="agama" required>
						<option value="" hidden disabled>Pilih Agama</option>
						<option value="Islam">Islam</option>
						<option value="Kristen">Kristen</option>
						<option value="Katolik">Katolik</option>
						<option value="Hindu">Hindu</option>
						<option value="Budha">Budha</option>
						<option value="Konghuchu">Konghuchu</option>
					</select>
				</div>

				<div class="form-group-modern">
					<label for="noHp">No Handphone<span class="required">*</span></label>
					<input class="bioFormInput" id="noHp" name="noHp" value="<?php echo $r["NOTELEPON"]; ?>" placeholder="Masukan Nonmor Handphone" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="ukuranBaju">Semester Periode<span class="required">*</span></label>
					<select class="bioFormInput" id="ukuranBaju" name="ukuranBaju" required>
						<?php
						$cy = get_dbconfig('CURENTYEAR');
						$cs = get_dbconfig('CURENTSEMESTER');
						$currentPeriodStr = $cs . " " . $cy;
						
						echo '<option value="'.$currentPeriodStr.'">'.$currentPeriodStr.' (Periode Aktif)</option>';
						
						$savedPeriod = $r["SEMESTERPERIODE"];
						if(!empty($savedPeriod) && $savedPeriod != $currentPeriodStr) {
							echo '<option value="'.$savedPeriod.'">'.$savedPeriod.' (Data Tersimpan)</option>';
						}
						?>
					</select>
				</div>

				<div class="form-group-modern full-width">
					<label for="ketrampilan">Ketrampilan<span class="required">*</span></label>
					<input class="bioFormInput" id="ketrampilan" name="ketrampilan" value="<?php echo $r["KETRAMPILAN"]; ?>" placeholder="Masukan Ketrampilan Khusus Yang Dimiliki" type="text" required="required" />
				</div>

				<div class="form-group-modern full-width">
					<label for="organisasi">Organisasi<span class="required">*</span></label>
					<input class="bioFormInput" id="organisasi" name="organisasi" value="<?php echo $r["ORGANISASI"]; ?>" placeholder="Masukan Nama Organisasi Yang Diikuti" type="text" required="required" />
				</div>

				<div class="form-group-modern full-width">
					<label for="alamatTinggal">Alamat Tinggal<span class="required">*</span></label>
					<input class="bioFormInput" id="alamatTinggal" name="alamatTinggal" value="<?php echo $r["ALAMATTINGGAL"]; ?>" placeholder="Masukan Alamat Tempat Anda Tinggal" type="text" required="required" />
				</div>

				<div class="form-group-modern full-width">
					<label>Nama Orang Tua<span class="required">*</span></label>
					<div class="alamat-grid">
						<input class="bioFormInput" id="namaAyah" name="namaAyah" value="<?php echo $r["NAMAAYAH"]; ?>" placeholder="Masukan Nama Ayah" type="text" required="required" />
						<input class="bioFormInput" id="namaIbu" name="namaIbu" value="<?php echo $r["NAMAIBU"]; ?>" placeholder="Masukan Nama Ibu" type="text" required="required" />
					</div>
				</div>

				<div class="form-group-modern">
					<label for="noHpOrangTUA">No. Hp. Orangtua<span class="required">*</span></label>
					<input class="bioFormInput" id="noHpOrangTUA" name="noHpOrangTUA" value="<?php echo $r["NOHPORTU"]; ?>" placeholder="Masukan No. HP. Orangtua" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="nameGenting">Nama Kontak Darurat<span class="required">*</span></label>
					<input class="bioFormInput" id="nameGenting" name="nameGenting" value="<?php echo $r["NAMAGENTING"]; ?>" placeholder="Masukan Nama Kontak Darurat" type="text" required="required" />
				</div>

				<div class="form-group-modern">
					<label for="noHpGenting">No. Hp. Kontak Darurat<span class="required">*</span></label>
					<input class="bioFormInput" id="noHpGenting" name="noHpGenting" value="<?php echo $r["NOHPGENTING"]; ?>" placeholder="Masukan No. HP. Darurat" type="text" required="required" />
				</div>

				<div class="form-group-modern full-width">
					<label>Alamat Rumah<span class="required">*</span></label>
					<div class="alamat-grid">
						<input class="bioFormInput" style="grid-column: 1 / -1;" id="alamatAsal" name="alamatAsal" value="<?php echo $r["ALAMATASAL"]; ?>" placeholder="Masukan Nama Jalan, No, RT/RW, atau Dusun" type="text" required="required" />
						<input class="bioFormInput" id="kecamatan" name="kecamatan" value="<?php echo $r["KECAMATAN"]; ?>" placeholder="Masukan Kecamatan" type="text" required="required" />
						<input class="bioFormInput" id="kabupaten" name="kabupaten" value="<?php echo $r["KABUPATEN"]; ?>" placeholder="Masukan Kabupaten" type="text" required="required" />
						<input class="bioFormInput" id="propinsi" name="propinsi" value="<?php echo $r["PROPINSI"]; ?>" placeholder="Masukan Propinsi" type="text" required="required" />
					</div>
				</div>

				<div id="passwordField" class="form-group-modern full-width" style="display:none;">
					<label for="password">Password verifikasi edit<span class="required">*</span></label>
					<input id="password" name="password" value="" placeholder="Masukan Password Akun Anda" type="password" required="required" />
				</div>
			</div>

			<div class="form-actions">
				<button type="button" class="btn-edit" id="btnEdit" onclick="editData()">Edit Data</button>
				<button type="button" class="btn-cancel" id="btnCancel" onclick="cancelEdit()" style="display:none;">Batalkan</button>
				<button type="submit" name="action" value="updatedata" class="btn-update" id="btnUpdate" style="display:none;">Perbarui Profil</button>
			</div>
		</form>
	</div>

	<script>
		function toggleUploadPanel() {
			const panel = document.getElementById('uploadPanel');
			panel.classList.toggle('active');
		}

		function setBioFormInput(set) {
			let bioFormInput = document.getElementsByClassName("bioFormInput");
			for (let input = 0; input < bioFormInput.length; input++) {
				const element = bioFormInput[input];
				element.disabled = !set;
			}
		}
		setBioFormInput(false);

		function editData() {
			setBioFormInput(true);
			document.getElementById('btnUpdate').style.display = "inline-block";
			document.getElementById('btnEdit').style.display = "none";
			document.getElementById('btnCancel').style.display = "inline-block";
			document.getElementById('passwordField').style.display = "flex";
		}

		function cancelEdit() {
			setBioFormInput(false);
			document.getElementById('btnUpdate').style.display = "none";
			document.getElementById('btnEdit').style.display = "inline-block";
			document.getElementById('btnCancel').style.display = "none";
			document.getElementById('passwordField').style.display = "none";
		}
	</script>
	</div>

	<?php
	if ($set != 1) { ?>
		<script>
			(function() {
				const jur = "<?php echo $r["JURUSAN"]; ?>";
				if (jur) {
					const jurEl = document.getElementById("jurusan");
					if (jurEl) jurEl.value = jur;
					
					const prodiEl = document.getElementById(jur);
					if (prodiEl) {
						prodiEl.value = "<?php echo $r["PROGRAMSTUDI"]; ?>";
						prodiEl.style.display = "block";
						if (document.getElementById("prodyDummy")) {
							document.getElementById("prodyDummy").style.display = "none";
						}
					}
				}
				
				const jkEl = document.getElementById("jenisKelamin");
				if (jkEl) jkEl.value = "<?php echo $r["JENISKELAMIN"]; ?>";
				
				const agEl = document.getElementById("Agama");
				if (agEl) agEl.value = "<?php echo $r["AGAMA"]; ?>";
				
				const ubEl = document.getElementById("ukuranBaju");
				if (ubEl) ubEl.value = "<?php echo $r["SEMESTERPERIODE"]; ?>";
			})();
		</script><?php $set = 1;
			}
				?>
</div>

<!-- Custom Alert Modal -->
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

<script>
function validateUpload(event) {
	const fileInput = document.getElementById('fotoInput');
	const file = fileInput.files[0];
	
	if (!file) {
		showUploadAlert("Silakan pilih file foto terlebih dahulu.");
		return false;
	}

	const fileType = file.name.split('.').pop().toLowerCase();
	const fileSize = file.size / 1024; // in KB

	if (fileType !== 'jpg' && fileType !== 'jpeg') {
		showUploadAlert("Tipe file tidak valid. Harap unggah file dengan format .jpg atau .jpeg.");
		return false;
	}

	if (fileSize > 100) {
		showUploadAlert("Ukuran file terlalu besar. Maksimal ukuran file adalah 100 KB. Ukuran file Anda: " + Math.round(fileSize) + " KB.");
		return false;
	}

	return true; // proceed with upload
}

function showUploadAlert(msg) {
	document.getElementById('uploadAlertMsg').innerText = msg;
	document.getElementById('uploadAlertModal').style.display = 'flex';
}

function closeUploadAlert() {
	document.getElementById('uploadAlertModal').style.display = 'none';
}
</script>

</html>
