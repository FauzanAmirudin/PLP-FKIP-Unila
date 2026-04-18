<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
is_level("Admin, Monitor, DPL");
$dataAccess = new gf_sql(GF_DB['default']);
?>
<content>
	<section id="mainContent">
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
		<div class="content">
			<div class="header">
				<stong>Data Laporan Mingguan Mahasiswa <?= $tahun ?><?= isset($prodi) ? ", Program studi " . $prodi : "" ?></strong>
			</div>

			<div class="field">
				<h1>Load Laporan
					<span class="field-action action-right"> </span>
				</h1>
				<div>
					<form class="form" method="get" action="<?= set_url($form_link) ?>">
						<div class="form-group row">
							<div class="input-group col-md-1">
								<label for="tahun">Tahun<span class="required">*</span></label>
								<?php
								$input_option =  '<select name="tahun" type="text" require="required">';
								if (!empty($alltahun)) {
									$alltahun = array_map(function ($a) {
										return $a["TAHUNDAFTAR"] == NULL ? "Tidak Ada Data" : $a["TAHUNDAFTAR"];
									}, $alltahun);
									if (empty($tahun)) $tahun = current($alltahun);
									foreach ($alltahun as $key => $TAHUNDAFTAR) {
										if ($TAHUNDAFTAR == $tahun) {
											$input_option .= '<option value="' . $TAHUNDAFTAR . '" selected>' . $TAHUNDAFTAR . '</option>';
										} else {
											$input_option .= '<option value="' . $TAHUNDAFTAR . '">' . $TAHUNDAFTAR . '</option>';
										}
									}
								} else {
									$input_option .= '<option value="">Belum ada pendaftar</option>';
								}
								$input_option .=  '</select>';
								echo $input_option;
								?>
							</div>
							<div class="input-group col-md-3">
								<label for="periode">Periode</label>
								<?php
								$input_option =  '<select name="periode" type="text" require="required">';
								if (!empty($allperiode)) {
									$allperiode = array_map(function ($a) {
										return $a["PERIODEDAFTAR"] == NULL ? "Tidak Ada Data" : $a["PERIODEDAFTAR"];
									}, $allperiode);
									if (empty($periode)) $periode = current($allperiode);
									foreach ($allperiode as $key => $PERIODEDAFTAR) {
										if ($PERIODEDAFTAR == $periode) {
											$input_option .= '<option value="' . $PERIODEDAFTAR . '" selected>' . $PERIODEDAFTAR . '</option>';
										} else {
											$input_option .= '<option value="' . $PERIODEDAFTAR . '">' . $PERIODEDAFTAR . '</option>';
										}
									}
								} else {
									$input_option .= '<option value="">Belum ada pendaftar</option>';
								}
								$input_option .=  '</select>';
								echo $input_option;
								?>
							</div>
							<div class="input-group col-md-6">
								<label for="NPM">NPM</label>
								<input name="npm" value="<?php echo $npm; ?>" placeholder="Masukan NPM bila perlu." type="text" />
							</div>
						</div>
						<div class="form-group action-right">
							<button type="submit" value="Simpan" class="btn btn-medium btn-ok">Buka</button>
						</div>
					</form>
				</div>
			</div>
			<?php
			$report = '';
			$myDesa = FALSE;
			$n = 0;
			foreach ($mahasiswa as $key => $r) {
				if ($r['LOKASIDESA'] !== $myDesa) {
					if ($myDesa !== FALSE) $report .= '</table></div></div><br>';
					$title = '';
					$title .= !empty($r['LOKASIDESA']) ? 'Desa ' . $r['LOKASIDESA'] : "";
					$title .= is_level("Admin, Monitor") ? (!empty($r['NAMADOSEN']) ? (!empty($title) ? " / " : "") . "Di bimbing oleh: " . $r['NAMADOSEN'] : "") : "";
					$report .= '
					<div class="field">
					<h1>' . $title . '<span class="field-action action-right"></span></h1>
					<div class="table-view">
						<table width="100%">
							<tr class="thead">
								<td width="35px"><b>No</b></td>
								<td width="250px"><b>Nama</b></td>
								<td width="130px"><b>NPM</b></td>
								<td width="auto"><b>Program Studi</b></td>
								<td width="auto"><b>Sekolah</b></td>
								<td width="auto"><b>Laporan</b></td>
							</tr>';
					$n = 1;
				}
				$report .= '
							<tr class="trow">
								<td>' . $n . '</td>
								<td>' . $r["NAMA"] . '</td>
								<td>' . $r["NPM"] . '</td>
								<td>' . $r["PROGRAMSTUDI"] . '</td>
								<td>' . $r["LOKASISEKOLAH"] . '</td>
								<td>';
				$qw = null;
				$item = $r["NPM"];
				$file = $r["LAPORAN"];
				// echo $laporanAccess->last_query;
				$ln = 1;
				foreach ($file as $key => $l) {
					$namaLaporan = $l['FILENAME'];
					$fileext = $l['FILEEXT'];

					if (!empty($l['RESPONSE'])) {
						if ($l['RESPONSE'] == "Cukup") $respon = "btn-ok";
						else $respon = "btn-cancel";
					} else $respon = "btn-view";
					$filePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $l["FILEPATH"] . DIRECTORY_SEPARATOR . $namaLaporan . $fileext;
					if ($ln <= 9 && file_exists($filePath)) {
						$report .= '' .
							'<div class="laporan-set">' .
							'	<a href="' .  set_url($l['FILELINK']) . '" class="btn btn-tiny btn-download laporan-download">' . $ln . '</a><br>' .
							'	<a onclick="giveResponseLaporan(\'' . $l["ID"] . '\', \'' . str_replace("'", "\'", html_entity_decode(($r["NAMA"]), ENT_QUOTES | ENT_HTML5)) . '\', \'' . $l['NPM'] . '\', \'' . $namaLaporan . '\')" class="response-laporan btn btn-tiny laporan-response ' . $respon . '">R</a>' .
							'</div>';
					}
					$ln++;
					$qw += 1;
				}
				if (!isset($qw)) $report .= "Belum Ada";
				elseif (isset($qw) && $qw > 1) $report .= '<a href="' . set_url("downloads/reports/bundle/" . $r['ID']) . '" class="btn btn-tiny btn-download laporan-collected" target="_blank">Semua</a>';
				$report .= '</td>
						</tr>';
				$n++;
				$myDesa = $r["LOKASIDESA"];
			}
			$report .= '</table></div>';
			echo $report;
			?>
			<div id="modal" class="modal">
				<div class="modal-centered" style="width: 450px;">
					<div class="content animate">
						<div class="container" id="contain">
							<div class="title">
								<h1>Response Laporan
									<span class="action-right">
										<a onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right;"></a>
									</span>
								</h1>
								</h1>
							</div>
							<div class="field">
								<label id="res-ket" for="respons"></label>
								<div id="dta-mhs" class="banner"></div>
								<div id="dta-ket"></div>
								<div id="dta-lap"></div>
								<form action="" method="post" enctype="multipart/form-data" id="res-lap-form" class="form">
									<div class="form-group">
										<select id="res-lap" name="respons" onChange="displayKomentarLaporan(this.value)">
											<option value="Tidak Ada" hidden>Pilih Response</option>
											<option value="Cukup">Cukup</option>
											<option value="Kurang">Kurang</option>
										</select>
									</div>
									<div class="form-group" id="komentarLaporan" style="display:none">
										<label for="komentar"><b>Komentar</b></label>
										<textarea class="comment-text-area" id="komentar" name="komentar" placeholder="Tambahkan Komentar..." maxlength="250"></textarea>
									</div>
									<div class="form-group" style="text-align:right; color: black;">
										<button id="sendResponse-Laporan" type="submit" name="action" value="ResponseLaporan" class="btn btn-medium purple">Simpan</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				function giveResponseLaporan(id, nama, npm, brks) {
					document.getElementById('modal').style.display = "block";
					document.getElementById('dta-mhs').innerHTML = "<b>Nama</b><br><a id=\"nama\">" + nama + "</a><br><b>NPM</b><br><a id=\"npm\">" + npm + "</a>";
					document.getElementById('dta-lap').innerHTML = "<br><b>Respons " + brks + "</b>";
					document.getElementById('res-ket').innerHTML = "";
					document.getElementById('dta-ket').innerHTML = "";
					document.getElementById('res-lap-form').action = "<?php echo set_url("api/report/response/") ?>?id=" + id;
					document.getElementById('res-lap').selectedIndex = 0;
					document.getElementById('komentarLaporan').style.display = "none";
					readCommentLaporan(id, brks);
				}

				function readCommentLaporan(id, brks) {
					let aj_data = new gcAjax("POST", "<?php echo set_url("api/report/comment/") ?>?id=" + id)
						.setCallback(function(text, element) {
							if (text == '') {
								text = 'Tidak Ada.'
							}
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
	</section>
</content>