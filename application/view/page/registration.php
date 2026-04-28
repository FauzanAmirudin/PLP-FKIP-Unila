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
                            <strong style="display:block; font-size:12px; color:#6b7280; text-transform:uppercase;">Berkas (ZIP)</strong>
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
                                <label for="Kaprodi">Nama Kaprodi<span class="required">*</span></label>
                                <select class="input-control" id="Kaprodi" name="ketuaProdi" required="required">
                                    <option value="" hidden>Pilih Kaprodi</option>
                                    <?php
                                    $dataKaprodi = $dbAccess->reset()->result_array('kaprodi');
                                    foreach ($dataKaprodi as $person) {
                                        echo '<option value="' . $person["ID"] . '" ' . ((isset($aditionaldata['KAPRODI']) && $aditionaldata['KAPRODI'] == $person["ID"]) ? 'selected' : '') . '> ' . $person["NAMA"] . '</option>';
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
                                <input class="input-control" id="NipKaprodi" name="nippembimbingakademik" value="<?php echo isset($aditionaldata["NIPPEMBIMBINGAKADEMIK"]) ? $aditionaldata["NIPPEMBIMBINGAKADEMIK"] : ""; ?>" placeholder="Masukkan NIP Pembimbing Akademik Anda" type="text" required="required" />
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="action" value="DownloadBerkas" class="btn-save">Unduh Form Pendaftaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <?php if (!$registration_done && isset($uploadberkas) && $uploadberkas) { ?>
                <!-- Upload Berkas -->
                <div class="pendaftaran-container" style="margin-top: 24px; margin-bottom: 24px;">
                    <div class="card-header">
                        <h1 class="card-title">Upload Berkas Pendaftaran</h1>
                        <p class="card-subtitle">Upload berkas formulir yang telah ditandatangani</p>
                    </div>
                    <div class="form-body">
                        <form method="post" action="?page=registration/submit/<?= session_get('ID') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Pilih file berkas (.zip)<span class="required">*</span></label>
                                <input type="file" name="file" class="input-control" accept=".zip,application/zip" required style="padding: 6px; height: auto;">
                                <span class="help-text" style="color:#ef4444; margin-top: 6px; display: block;">* Berkas harus ditandatangani dan dibundel ke dalam file .zip. Maksimum ukuran file 1 MB.</span>
                            </div>
                            <div class="form-actions" style="margin-top: 20px;">
                                <button type="submit" class="btn-save">Upload Berkas</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
