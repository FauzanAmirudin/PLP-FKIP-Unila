<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
?>
<content>
	<section id="mainContent">
		<script type="text/javascript">
			function displayQuestElement(answer) {
				let pendidikan_bahasa_dan_seni = document.getElementById('pendidikan_bahasa_dan_seni');
				let pendidikan_ips = document.getElementById('pendidikan_ips');
				let pendidikan_mipa = document.getElementById('pendidikan_mipa');
				let ilmu_pendidikan = document.getElementById('ilmu_pendidikan');
				let prodyDummy = document.getElementById('prodyDummy');

				pendidikan_bahasa_dan_seni.display =
					pendidikan_ips.style.display =
					pendidikan_mipa.style.display =
					prodyDummy.style.display =
					ilmu_pendidikan.style.display = "none";

				pendidikan_bahasa_dan_seni.value =
					pendidikan_ips.value =
					pendidikan_mipa.value =
					prodyDummy.value =
					ilmu_pendidikan.value = "";

				pendidikan_bahasa_dan_seni.required =
					pendidikan_ips.required =
					pendidikan_mipa.required =
					prodyDummy.required =
					ilmu_pendidikan.required = false;

				prodyDummy.required = false;

				let id = answer.toLowerCase().replace(/\s+/g, '_');
				document.getElementById(id).style.display = "block";
				document.getElementById(id).required = true;
			}
		</script>
		<?php $set = 0; ?>
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
		<div class="content">
			<div class="header">
				<a>BIODATA</a>
			</div><?php if (isset($notification) && !empty($notification)) {
						echo '
			<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
					} ?>
			<div class="field">
				<h1>Foto
					<span class="field-action action-right"></span>
				</h1>
				<?php
				if (form_imput_value($biodata, 'FTPROFIL') != "") { ?>
					<div id="form1" class="bingkai-foto" style="position: relative;">
						<img src="<?php echo set_url(form_imput_value($biodata, 'FTPROFIL') . "?=" . form_imput_value($biodata, 'TIMEUPDATE')); ?>" width="149px" height="200" class="pasfoto">
						<button style="position: absolute; top: 0px; left: 0; z-index: 10; background: rgba(1, 1, 1, 0.44); color: white; padding: 3px; padding-left: 8px; padding-right: 8px; font-size: 12pt; font-style: normal; font-weight: lighter; cursor: pointer; border: none;" onclick="document.getElementById('uploaderFoto').style.display='block'" class="btn-edit"> Edit </button>
						<div id="uploaderFoto" class="modal">
							<div class="modal-centered" style="width: 480px;">
								<div class="content animate">
									<div class="title">
										<h1>Upload
											<span class="action-right">
												<a onclick="document.getElementById('uploaderFoto').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right;"></a>
											</span>
										</h1>
									</div>
									<div class="field">
										<form class="form" action="<?php echo set_url("mahasiswa/simpan_foto/" . $user['ID']); ?>" method="post" enctype="multipart/form-data">
											<div class="form-group">
												<label class="top-label">Pilih file:</label>
												<input type="file" name="file"><br />
												<a class="help-block"><a style="color:#ff0000;">*</a> hanya file image .jpg dengan ukuran maximum 100 Kb.</a>
											</div>
											<div class="form-group action-right">
												<button type="submit" class="btn btn-medium btn-ok">Upload</button>
												<button type="reset" class="btn btn-medium btn-cancel">Reset</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
				} else { ?>
					<form action="<?php echo set_url("mahasiswa/simpan_foto/" . $user['ID']); ?>" method="post" enctype="multipart/form-data" class="form">
						<div class="form-group">
							<label>Pilih file:</label>
							<input type="file" name="file"><br />
							<a class="help-block"><a style="color:#ff0000;">*</a> hanya file image .jpg dengan ukuran maximum 100 Kb.</a>
						</div>
						<div class="form-group" style="text-align: right;">
							<button type="submit" class="btnOk">Upload</button>
							<button type="reset" class="btnCancel">Reset</button>
						</div>
					</form>
				<?php
				} ?>
			</div>
			<div class="field">
				<h1>Biodata
					<span class="field-action action-right"> </span>
				</h1>
				<h4>Penulisan data Biodata gunakan <span style="color:red">Huruf kapital</span> sebagai <span style="color:red">huruf pertama</span>.</h4>
				<div class="form">
					<form id="formBiodata" method="post" action="<?php echo set_url("mahasiswa/simpan_biodata/" . $user['ID']); ?>">
						<div class="form-group row">
							<label for="name" class="inline-label">Name<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-4">
								<span>
									<input class="bioFormInput" id="nama" name="nama" <?= input_value($biodata, 'NAMA'); ?> placeholder="Masukan Nama" type="text" required="required" autofocus>
								</span>
							</div>
						</div> <!-- nama -->
						<div class="form-group row">
							<label for="npm" class="inline-label">NPM<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-2">
								<input <?= empty($biodata["NPM"]) ? 'class="bioFormInput"' : "disabled"; ?> id="npm" name="npm" <?= input_value($biodata, 'NPM'); ?> placeholder="Masukan NPM" type="text" required="required" />
							</div>
						</div> <!-- npm -->
						<div class="form-group row">
							<label for="subject" class="inline-label">Jurusan<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-3">
								<select class="bioFormInput" id="jurusan" name="jurusan" required onChange="displayQuestElement(this.value)">
									<option value="" hidden>Pilih Jurusan</option>
									<option value="Ilmu Pendidikan">Ilmu Pendidikan</option>
									<option value="Pendidikan Bahasa dan Seni">Pendidikan Bahasa dan Seni</option>
									<option value="Pendidikan IPS">Pendidikan IPS</option>
									<option value="Pendidikan MIPA">Pendidikan MIPA</option>
								</select>
							</div>
						</div> <!-- jurusan -->
						<div class="form-group row">
							<label for="programStudy" class="inline-label">Program Study<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-4">
								<select class="bioFormInput" id="prodyDummy" name="programStudy" onChange="displayQuestElement(this.value)" type="hidden">
									<option value="">Pilih Program studi</option>
									<option value="">Pilih jurusan terlebih dahulu</option>
								</select>
								<select class="bioFormInput" id="ilmu_pendidikan" name="programStudy1" onChange="displayQuestElement(this.value)" style="display:none">
									<option value="">Pilih Program studi</option>
									<option value="Bimbingan Konseling">Bimbingan Konseling</option>
									<option value="Penjaskes">Penjaskes</option>
									<option value="PG-PAUD">PG-PAUD</option>
									<option value="PGSD">PGSD</option>
								</select>
								<select class="bioFormInput" id="pendidikan_bahasa_dan_seni" name="programStudy2" onChange="displayQuestElement(this.value)" style="display:none">
									<option value="">Pilih Program studi</option>
									<option value="Pendidikan Bahasa dan Sastra Indonesia">Pendidikan Bahasa dan Sastra Indonesia</option>
									<option value="Pendidikan Bahasa Ingris">Pendidikan Bahasa Ingris</option>
									<option value="Pendidikan Bahasa Prancis">Pendidikan Bahasa Prancis</option>
									<option value="Pendidikan Tari, Drama dan Musik">Pendidikan Seni Tari</option>
									<option value="Pendidikan Seni Musik">Pendidikan Seni Musik</option>
								</select>
								<select class="bioFormInput" id="pendidikan_ips" name="programStudy3" onChange="displayQuestElement(this.value)" style="display:none">
									<option value="">Pilih Program studi</option>
									<option value="Pendidikan Ekonomi">Pendidikan Ekonomi</option>
									<option value="Pendidikan Geografi">Pendidikan Geografi</option>
									<option value="Pendidikan PKN">Pendidikan PKN</option>
									<option value="Pendidikan Sejarah">Pendidikan Sejarah</option>
								</select>
								<select class="bioFormInput" id="pendidikan_mipa" name="programStudy4" onChange="displayQuestElement(this.value)" style="display:none">
									<option value="">Pilih Program studi</option>
									<option value="Pendidikan Biologi">Pendidikan Biologi</option>
									<option value="Pendidikan Fisika">Pendidikan Fisika</option>
									<option value="Pendidikan Kimia">Pendidikan Kimia</option>
									<option value="Pendidikan Matematika">Pendidikan Matematika</option>
									<option value="Pendidikan Teknologi Informasi">Pendidikan Teknologi Informasi</option>
								</select>
							</div>
						</div> <!-- Program Studi -->
						<div class="form-group row">
							<label for="name" class="inline-label">SKS<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-1">
								<span>
									<input class="bioFormInput" id="sks" name="sks" <?= input_value($biodata, 'SKS'); ?> placeholder="Masukan Jumlah SKS" type="text" required="required" />
								</span>
							</div>
						</div> <!-- SKS -->
						<div class="form-group row">
							<label for="name" class="inline-label">IPK<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-1">
								<span>
									<input class="bioFormInput" id="ipk" name="ipk" <?= input_value($biodata, 'IPK'); ?> placeholder="Masukan Nilai IPK" type="text" required="required" />
								</span>
							</div>
						</div> <!-- IPK -->
						<div class="form-group row">
							<label for="jenisKelamin" class="inline-label">Jenis Kelamin<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-1">
								<select class="bioFormInput" id="jenisKelamin" name="jenisKelamin" required>
									<option value="" selected hidden disabled>Pilih Jenis Kelamin</option>
									<option value="Laki-Laki">Laki-Laki</option>
									<option value="Perempuan">Perempuan</option>
								</select>
							</div>
						</div> <!-- Jenis Kelamin -->
						<div class="form-group row">
							<label for="Agama" class="inline-label">Agama<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-1">
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
						</div> <!-- Agama -->
						<div class="form-group row">
							<label for="npHp" class="inline-label">No Handphone<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-3">
								<span>
									<input class="bioFormInput" id="noHp" name="noHp" <?= input_value($biodata, 'NOTELEPON'); ?> placeholder="Masukan Nonmor Handphone" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Nomor Hamphone -->
						<div class="form-group row">
							<label for="ukuranBaju" class="inline-label">Ukuran Baju<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-1">
								<select class="bioFormInput" id="ukuranBaju" name="ukuranBaju" required>
									<option value="" selected hidden disabled>Pilih Ukuran</option>
									<option value="S">Small (S)</option>
									<option value="M">Medium (M)</option>
									<option value="L">Large (L)</option>
									<option value="XL">Extra Large (XL)</option>
									<option value="XXL">Extra Extra Large (XXL)</option>
								</select>
							</div>
						</div> <!-- Ukuran Baju -->
						<div class="form-group row">
							<label for="ketrampilan" class="inline-label">Ketrampilan<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-6">
								<span>
									<input class="bioFormInput" id="ketrampilan" name="ketrampilan" <?= input_value($biodata, 'KETRAMPILAN'); ?> placeholder="Masukan Ketrampilan Khusus Yang Dimiliki" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Keterampilan -->
						<div class="form-group row">
							<label for="organisasi" class="inline-label">Organisasi<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-6">
								<span>
									<input class="bioFormInput" id="organisasi" name="organisasi" <?= input_value($biodata, 'ORGANISASI'); ?> placeholder="Masukan Nama Organisasi Yang Diikuti" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Organisasi -->
						<div class="form-group row">
							<label for="alamatTinggal" class="inline-label">Alamat Tinggal<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-8">
								<span>
									<input class="bioFormInput" id="alamatTinggal" name="alamatTinggal" <?= input_value($biodata, 'ALAMATTINGGAL'); ?> placeholder="Masukan Alamat Tempat Anda Tinggal" type="text" required="required" />
								</span>
							</div>
						</div> <!-- alamatTinggal,  -->
						<div class="form-group row">
							<label for="Nama" class="inline-label">Name Orang Tua<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-4">
								<span>
									<input class="bioFormInput" id="namaAyah" name="namaAyah" <?= input_value($biodata, 'NAMAAYAH'); ?> placeholder="Masukan Nama Ayah" type="text" required="required" />
									<input class="bioFormInput" id="namaIbu" name="namaIbu" <?= input_value($biodata, 'NAMAIBU'); ?> placeholder="Masukan Nama Ibu" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Nama Orang Tua -->
						<div class="form-group row">
							<label for="noHpOrangTUA" class="inline-label">No. Hp. Orangtua<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-3">
								<span>
									<input class="bioFormInput" id="noHpOrangTUA" name="noHpOrangTUA" <?= input_value($biodata, 'NOHPORTU'); ?> placeholder="Masukan No. HP. Orangtua" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Nomor Hamphone Orang Tua -->
						<div class="form-group row">
							<label for="nameGenting" class="inline-label">Name Kontak Darurat<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-4">
								<span>
									<input class="bioFormInput" id="nameGenting" name="nameGenting" <?= input_value($biodata, 'NAMAGENTING'); ?> placeholder="Masukan Nama Kontak Darurat" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Nama Kontak Darurat -->
						<div class="form-group row">
							<label for="noHpGenting" class="inline-label">No. Hp. Kontak Darurat<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-3">
								<span>
									<input class="bioFormInput" id="noHpGenting" name="noHpGenting" <?= input_value($biodata, 'NAMAGENTING'); ?> placeholder="Masukan No. HP. Darurat" type="text" required="required" />
								</span>
							</div>
						</div> <!-- Nomor Hamphone Kontak Darurat -->
						<div class="form-group row">
							<label for="alamatAsal" class="inline-label">Alamat Rumah<span class="required">*</span></label>
							<div class="dot">:</div>
							<div class="col-md-8">
								<div class="col-md-A">
									<input class="bioFormInput" id="alamatAsal" name="alamatAsal" <?= input_value($biodata, 'ALAMATASAL'); ?> placeholder="Masukan Nama Jalan, No, RT/RW, atau Dusun" type="text" required="required" />
								</div>
								<div class="col-sm-B">
									<input class="bioFormInput" id="kecamatan" name="kecamatan" <?= input_value($biodata, 'KECAMATAN'); ?> placeholder="Masukan Kecamatan" type="text" required="required" />
								</div>
								<div class="col-sm-C">
									<input class="bioFormInput" id="kabupaten" name="kabupaten" <?= input_value($biodata, 'KABUPATEN'); ?> placeholder="Masukan Kabupaten" type="text" required="required" />
								</div>
								<div class="col-sm-D">
									<input class="bioFormInput" id="propinsi" name="propinsi" <?= input_value($biodata, 'PROPINSI'); ?> placeholder="Masukan Propinsi" type="text" required="required" />
								</div>
							</div>
						</div> <!-- alamatAsal, kecamatan, 	kabupaten,	propinsi,	password',  -->
					</form>
					<div class="form-group action-right">
						<button class="btn btn-medium btn-ok" type="submit" id="btnUpdate" style="display:none;">Update</button>
						<button class=" btn btn-medium btn-ok btn-edit" id="btnEdit">Edit</button>
						<button class="btn btn-medium btn-cancel" id="btnCancel" style="display:none;">Cancel</button>
					</div> <!-- edit console -->
				</div>
				<script>
					var save_btn = document.getElementById("btnUpdate");
					var edit_btn = document.getElementById("btnEdit");
					var cancel_btn = document.getElementById("btnCancel");

					save_btn.addEventListener("click", function(e) {
						e.preventDefault();
						let form = document.getElementById("formBiodata");
						form.submit();
					});
					edit_btn.addEventListener("click", function(e) {
						setBioFormInput(true);
						save_btn.style.display = "inline";
						edit_btn.style.display = "none";
						cancel_btn.style.display = "inline";
					});
					cancel_btn.addEventListener("click", function(e) {
						setBioFormInput(false);
						save_btn.style.display = "none";
						edit_btn.style.display = "inline";
						cancel_btn.style.display = "none";
					});

					function setBioFormInput(set) {
						let bioFormInput = document.getElementsByClassName("bioFormInput");
						for (let input = 0; input < bioFormInput.length; input++) {
							const element = bioFormInput[input];
							element.disabled = !set;
						}
					}
					setBioFormInput(false);
				</script>
			</div>
			<?php
			if ($set != 1) { ?>
				<script>
					(function() {
						document.getElementById("jurusan").value = "<?php echo form_imput_value($biodata, "JURUSAN"); ?>";
						document.getElementById("<?php echo strtolower(str_replace(" ", "_", form_imput_value($biodata, "JURUSAN"))); ?>").value = "<?php echo form_imput_value($biodata, "PROGRAMSTUDI"); ?>";
						if ("<?php echo strtolower(str_replace(" ", "_", form_imput_value($biodata, "JURUSAN"))); ?>" != "") {
							document.getElementById("prodyDummy").style.display = "none";
							document.getElementById("<?php echo strtolower(str_replace(" ", "_", form_imput_value($biodata, "JURUSAN"))); ?>").style.display = "block";
						}
						document.getElementById("jenisKelamin").value = "<?php echo form_imput_value($biodata, "JENISKELAMIN"); ?>";
						document.getElementById("Agama").value = "<?php echo form_imput_value($biodata, "AGAMA"); ?>";
						document.getElementById("ukuranBaju").value = "<?php echo form_imput_value($biodata, "UKURANBAJU"); ?>";
					})();
				</script>
			<?php
				$set = 1;
			} ?>
		</div>
	</section>
</content>