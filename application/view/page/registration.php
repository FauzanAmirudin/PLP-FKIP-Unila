<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
    <?php
    $dbAccess = clone $this->database('default', 'dbconfig', TRUE);
    $aditionaldata = $dbAccess->reset()->where("`USRID` = '" . session_get('USERID') . "'")->result_row_array('aditionaldata');
    ?>

    <div class="pendaftaran-wrapper" style="display: flex; flex-direction: column;">
        <?php if (isset($response) && $response != null) {
            echo '<div style="padding: 20px; margin-bottom: 20px; background: #fee2e2; color: #ef4444; border-radius: 8px; text-align: center; font-weight: 600;">' . $response . '</div>';
        } ?>

        <?php if (!empty($registration_process)) { ?>
            <!-- Status Informasi -->
            <div class="pendaftaran-container" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h1 class="card-title">Informasi Pendaftaran</h1>
                    <p class="card-subtitle">Status proses pendaftaran Anda saat ini</p>
                </div>
                <div class="form-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <div style="background: #f9fafb; padding: 12px; border-radius: 6px;">
                            <strong style="display:block; font-size:12px; color:#6b7280; text-transform:uppercase;">Status</strong>
                            <span style="font-weight:600; color:#111827;"><?= empty($registration_process["STATUSBERKAS"]) ? "Tidak ada" : $registration_process["STATUSBERKAS"] ?></span>
                        </div>
                        <div style="background: #f9fafb; padding: 12px; border-radius: 6px;">
                            <strong style="display:block; font-size:12px; color:#6b7280; text-transform:uppercase;">Tahun / Periode</strong>
                            <span style="font-weight:600; color:#111827;"><?= (empty($registration_process["TAHUNDAFTAR"]) ? "-" : $registration_process["TAHUNDAFTAR"]) . ' / ' . (empty($registration_process["PERIODEDAFTAR"]) ? "-" : $registration_process["PERIODEDAFTAR"]) ?></span>
                        </div>
                        <div style="background: #f9fafb; padding: 12px; border-radius: 6px;">
                            <strong style="display:block; font-size:12px; color:#6b7280; text-transform:uppercase;">Tanggal Pengajuan</strong>
                            <span style="font-weight:600; color:#111827;"><?= empty($registration_process["DATEREQUEST"]) ? "Tidak ada" : $registration_process["DATEREQUEST"] ?></span>
                        </div>
                        <div style="background: #f9fafb; padding: 12px; border-radius: 6px;">
                            <strong style="display:block; font-size:12px; color:#6b7280; text-transform:uppercase;">Berkas (PDF)</strong>
                            <span style="font-weight:600; color:#111827;"><?= empty($registration_process["BERKASDAFTAR"]) ? "Belum Upload" : '<a href="' . htmlspecialchars($registration_process["BERKASDAFTAR"]) . '" style="color:#B33791; text-decoration:none;">Download</a>' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (empty($registration_process) || $registration_process["STATUSBERKAS"] != "Disetujui") { ?>
            <?php if (!$registration_done && isset($downloadberkas) && $downloadberkas) { ?>
                <!-- Download Form -->
                <div class="pendaftaran-container">
                    <div class="card-header">
                        <h1 class="card-title">Download Form Pendaftaran</h1>
                        <p class="card-subtitle">Lengkapi data pembimbing untuk mengunduh form pendaftaran kosong</p>
                    </div>
                    <div class="form-body">
                        <form method="get" action="index.php" id="form2">
                            <input name="page" value="downloads/formulir/<?= session_get('ID') ?>" type="hidden" required="required" />
                            
                            <div class="form-group">
                                <label for="Kaprodi">Nama Koordinator Program Studi<span class="required">*</span></label>
                                <select class="input-control" id="Kaprodi" name="ketuaProdi" required="required">
                                    <option value="" hidden>Pilih Koordinator Program Studi</option>
                                    <?php
                                    $dataKaprodi = $dbAccess->reset()->order('PROGRAMSTUDI', 'ASC')->result_array('kaprodi');
                                    foreach ($dataKaprodi as $person) {
                                        $prodiLabel = !empty($person["PROGRAMSTUDI"]) ? ' - ' . $person["PROGRAMSTUDI"] : '';
                                        echo '<option value="' . $person["ID"] . '" ' . ((isset($aditionaldata['KAPRODI']) && $aditionaldata['KAPRODI'] == $person["ID"]) ? 'selected' : '') . '> ' . htmlspecialchars($person["NAMA"]) . $prodiLabel . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-text">Bila dosen anda tidak tersedia atau terdapat kesalahan hubungi PLT.</span>
                            </div>

                            <div class="form-group">
                                <label for="pembimbingakademik">Pembimbing Akademik<span class="required">*</span></label>
                                <input class="input-control" id="pembimbingakademik" name="pembimbingakademik" value="<?php echo isset($aditionaldata["PEMBIMBINGAKADEMIK"]) ? $aditionaldata["PEMBIMBINGAKADEMIK"] : ""; ?>" placeholder="Masukkan Pembimbing Akademik Anda" type="text" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="NipKaprodi">NIP Pembimbing Akademik<span class="required">*</span></label>
                                <input class="input-control" id="NipKaprodi" name="nippembimbingakademik" value="<?php echo isset($aditionaldata["NIPPEMBIMBINGAKADEMIK"]) ? $aditionaldata["NIPPEMBIMBINGAKADEMIK"] : ""; ?>" placeholder="Masukkan NIP Pembimbing Akademik Anda" type="text" minlength="10" maxlength="25" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required="required" />
                            </div>

                            <div class="form-actions">
                                <?php if (!$biodata_done) { ?>
                                    <button type="button" onclick="openBiodataAlertModal()" class="btn-save" style="background-color: #d1d5db; color: #4b5563; cursor: pointer;">Unduh Form Pendaftaran</button>
                                <?php } else { ?>
                                    <button type="submit" name="action" value="DownloadBerkas" class="btn-save">Unduh Form Pendaftaran</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Download Pakta Integritas -->
                <div class="pendaftaran-container" style="margin-top: 24px;">
                    <div class="card-header">
                        <h1 class="card-title">Download Form Pakta Integritas</h1>
                        <p class="card-subtitle">Unduh dokumen Form Pakta Integritas untuk dilengkapi dan ditandatangani</p>
                    </div>
                    <div class="form-body">
                        <div class="form-actions" style="margin-top: 10px;">
                            <?php if (!$biodata_done) { ?>
                                <button type="button" onclick="openBiodataAlertModal()" class="btn-save" style="background-color: #d1d5db; color: #4b5563; cursor: pointer; padding: 8px 16px; font-size: 13px; height: auto; line-height: 1.4;">Unduh Form Pakta Integritas</button>
                            <?php } else { ?>
                                <a href="<?= set_url('downloads/pakta_integritas') ?>" class="btn-save" style="text-decoration: none; display: inline-block; padding: 8px 16px; font-size: 13px; height: auto; line-height: 1.4;">Unduh Form Pakta Integritas</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (!$registration_done && isset($uploadberkas) && $uploadberkas) { ?>
                <!-- Upload Berkas -->
                <div class="pendaftaran-container" style="margin-top: 24px; margin-bottom: 24px;">
                    <div class="card-header">
                        <h1 class="card-title">Upload Berkas Pendaftaran</h1>
                        <p class="card-subtitle">Upload berkas formulir dan pakta integritas yang telah ditandatangani</p>
                    </div>
                    <div class="form-body">
                        <form method="post" action="?page=registration/submit/<?= session_get('ID') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Pilih file berkas (.pdf)<span class="required">*</span></label>
                                <input type="file" name="file" class="input-control" accept=".pdf,application/pdf" required style="padding: 6px; height: auto;">
                                <span class="help-text" style="color:#ef4444; margin-top: 6px; display: block;">* Harap scan dan jadikan satu file PDF antara Form Pendaftaran dan Pakta Integritas (jangan dipisah). Maksimum ukuran file 1 MB.</span>
                            </div>
                            <div class="form-actions" style="margin-top: 20px;">
                                <?php if (!$biodata_done) { ?>
                                    <button type="button" onclick="openBiodataAlertModal()" class="btn-save" style="background-color: #d1d5db; color: #4b5563; cursor: pointer;">Upload Berkas</button>
                                <?php } else { ?>
                                    <button type="submit" class="btn-save">Upload Berkas</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if (!empty($registration_history)) { ?>
            <!-- Riwayat Pendaftaran -->
            <div class="pendaftaran-container" style="margin-top: 24px; margin-bottom: 24px;">
                <div class="card-header" style="border-bottom: 2px solid #f0e3fc; padding-bottom: 15px; margin-bottom: 20px;">
                    <h1 class="card-title" style="color: #a805a8; font-weight: 700; font-size: 20px; margin: 0;">Riwayat Pendaftaran</h1>
                    <p class="card-subtitle" style="color: #777777; font-size: 13px; margin: 5px 0 0 0;">Daftar riwayat pendaftaran Anda di berbagai periode akademik</p>
                </div>
                <div class="form-body">
                    <div class="table-responsive" style="overflow-x: auto; border: 1px solid #eaeaea; border-radius: 6px; width: 100%;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; white-space: nowrap;">
                            <thead>
                                <tr style="background-color: #fcfcfc; border-bottom: 2px solid #eee; color: #555; font-weight: 700;">
                                    <th style="padding: 15px 16px;">Tahun / Periode</th>
                                    <th style="padding: 15px 16px;">Tanggal Pengajuan</th>
                                    <th style="padding: 15px 16px;">Berkas</th>
                                    <th style="padding: 15px 16px;">Status</th>
                                    <th style="padding: 15px 16px;">Catatan Validator</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registration_history as $hist) {
                                    $statusHist = $hist['STATUSBERKAS'];
                                    $badgeStyle = "background: #f3f4f6; color: #374151;"; // Default
                                    if ($statusHist == 'Disetujui') {
                                        $badgeStyle = "background: #dcfce7; color: #166534;";
                                    } elseif ($statusHist == 'Ditolak') {
                                        $badgeStyle = "background: #fee2e2; color: #991b1b;";
                                    } elseif ($statusHist == 'Pengajuan') {
                                        $badgeStyle = "background: #fef9c3; color: #854d0e;";
                                    } elseif ($statusHist == 'Mengundurkan Diri') {
                                        $badgeStyle = "background: #ffedd5; color: #9a3412;";
                                    }
                                ?>
                                    <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.15s ease;">
                                        <td style="padding: 15px 16px; font-weight: 600; color: #111827;"><?= htmlspecialchars($hist['TAHUNDAFTAR'] . ' / ' . $hist['PERIODEDAFTAR']) ?></td>
                                        <td style="padding: 15px 16px;"><?= htmlspecialchars($hist['DATEREQUEST'] ?? '-') ?></td>
                                        <td style="padding: 15px 16px;">
                                            <?php if (!empty($hist['BERKASDAFTAR'])) { ?>
                                                <a href="<?= htmlspecialchars($hist['BERKASDAFTAR']) ?>" style="color:#B33791; text-decoration:none; font-weight: 600;">Download PDF</a>
                                            <?php } else { echo '-'; } ?>
                                        </td>
                                        <td style="padding: 15px 16px;">
                                            <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; <?= $badgeStyle ?>">
                                                <?= htmlspecialchars($statusHist) ?>
                                            </span>
                                        </td>
                                        <td style="padding: 15px 16px; font-style: italic; font-size: 13px; max-width: 300px; white-space: normal; word-wrap: break-word;">
                                            <?= !empty($hist['NOTEBERKAS']) ? htmlspecialchars($hist['NOTEBERKAS']) : '<span style="color:#9ca3af;">Tidak ada catatan.</span>' ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

<!-- ===================== MODAL BIODATA ALERT ===================== -->
<div id="biodata-alert-modal" class="modal" style="display: none; z-index: 99999;">
	<div class="modal-centered">
		<div class="content animate" style="max-width: 350px; border-radius: 12px; overflow: hidden;">
			<div class="login-card" style="padding: 30px 20px; text-align: center; margin: 0; box-shadow: none;">
				<div style="background: #fff5f5; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#d93025" stroke-width="2.5" width="28" height="28">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
				</div>
				<h3 style="font-family: 'Poppins', sans-serif; color: #1e293b; margin-bottom: 8px; font-weight: 600; font-size: 18px;">Biodata Belum Lengkap</h3>
				<p style="font-family: 'Poppins', sans-serif; color: #64748b; font-size: 13px; margin-bottom: 25px;">Anda tidak dapat mengunduh atau mengunggah form pendaftaran sebelum melengkapi biodata Anda.</p>
				<div style="display: flex; gap: 10px; justify-content: center;">
					<button onclick="closeBiodataAlertModal()" style="padding: 10px; border: 1px solid #e2e8f0; background: #fff; border-radius: 8px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 500; color: #475569; flex: 1; transition: background 0.2s;">Tutup</button>
					<a href="<?= set_url("mahasiswa/data/" . session_get('ID')) ?>" style="padding: 10px; background: #B33791; color: #fff; border-radius: 8px; text-decoration: none; font-family: 'Poppins', sans-serif; font-weight: 500; display: inline-block; flex: 1; transition: background 0.2s;">Lengkapi Data</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function openBiodataAlertModal() {
    document.getElementById('biodata-alert-modal').style.display = 'block';
}
function closeBiodataAlertModal() {
    document.getElementById('biodata-alert-modal').style.display = 'none';
}
</script>
