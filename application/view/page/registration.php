<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
?><content>
    <section id="mainContent">
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
            }

            .penempatan td {
                border: thin solid grey;
                margin: 0;
                padding: 0;
            }

            b {
                font-weight: bold;
            }

            .label {
                width: 150px;
                display: inline-block;
            }

            .text {
                display: inline-block;
            }
        </style>
        <div class="content">
            <div class="header">
                <a>PENDAFTARAN</a>
            </div>
            <?php if (isset($notification) && $notification != null) {
                echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
            }
            if (!empty($registration_process)) { ?>
                <div class="field">
                    <h1>Informasi Lokasi Peserta
                        <span class="field-action action-right"></span>
                    </h1>
                    <div class="penempatan">
                        <?php
                        echo '
                        <div class="label">Status</div>
                        <div class="text">: ' . (empty($registration_process["STATUSBERKAS"]) ? "Tidak ada" : $registration_process["STATUSBERKAS"]) . '</div><br>

                        <div class="label">Tahun</div>
                        <div class="text">: ' . (empty($registration_process["TAHUNDAFTAR"]) ? "Tidak ada" : $registration_process["TAHUNDAFTAR"]) . '</div><br>

                        <div class="label">Periode</div>
                        <div class="text">: ' . (empty($registration_process["PERIODEDAFTAR"]) ? "Tidak ada" : $registration_process["PERIODEDAFTAR"]) . '</div><br>

                        <div class="label">Tanggal Pengajuan</div>
                        <div class="text">: ' . (empty($registration_process["DATEREQUEST"]) ? "Tidak ada" : $registration_process["DATEREQUEST"]) . '</div><br>

                        <div class="label">Tanggal Validasi</div>
                        <div class="text">: ' . (empty($registration_process["DATEVALID"]) ? "Tidak ada" : $registration_process["DATEVALID"]) . '</div><br>

                        <div class="label">Validator</div>
                        <div class="text">: ' . (empty($registration_process["VALIDATOR"]) ? "Tidak ada" : $registration_process["VALIDATOR"]) . '</div><br>

                        <div class="label">Berkas</div>
                        <div class="text">: ' . (empty($registration_process["BERKASDAFTAR"]) ? "Tidak ada." : '<a href="' . set_url($registration_process["BERKASDAFTAR"]) . '">Download</a>') . '</div><br>
                    ';
                        ?>
                    </div>
                </div>
                <?php
            }
            if (empty($registration_process) || $registration_process["STATUSBERKAS"] != "Disetujui") {
                if (!$registration_done && $uploadberkas) { ?>
                    <div class="field">
                        <h1>Upload Berkas
                            <span class="field-action action-right"> </span>
                        </h1>
                        <div>
                            <form class="form" method="post" action="<?php echo set_url("registration/submit/" . $user['ID']); ?>" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="inline-label">Pilih file berkas:</label>
                                    <div class="col-md-6">
                                        <input type="file" name="file"><br />
                                        <a class="help-block"><span style="color:#ff0000;">*</span> Berkas harus telah ditandatangani dan di bundle kedalam zip file. System hanya menerima file .zip dengan ukuran maximum 1 MB.</a>
                                    </div>
                                </div>
                                <div class="form-group action-right">
                                    <button type="submit" class="btn btn-medium btn-ok">Upload</button>
                                </div> <!-- edit console -->
                            </form>
                        </div>
                    </div>
                <?php
                }
                if (!$registration_done && $downloadberkas) { ?>
                    <div class="field">
                        <h1>Download Form
                            <span class="field-action action-right"> </span>
                        </h1>
                        <div>
                            <form class="form" method="get" action="<?php echo set_url("downloads/formulir/" . $user['ID']); ?>" id=form2>
                                <div class="form-group">
                                    <label for="ketuaProdi">Nama Kaprodi<span class="required">*</span></label>
                                    <div class="dot">:</div>
                                    <div class="col-md-12">
                                        <select class="bioFormInput" id="Kaprodi" name="ketuaProdi" type="text" required="required" />
                                        <option value="" hidden>Pilih Kaprodi</option>
                                        <?php
                                        foreach ($dataKaprodi as $person) {
                                            echo '<option value="' . $person["ID"] . '" ' . ((isset($aditionaldata['KAPRODI']) && $aditionaldata['KAPRODI'] == $person["ID"]) ? 'selected' : '') . '> ' . $person["NAMA"] . '</option>';
                                        }
                                        ?>
                                        </select>
                                        <a style="font-size: small;">Bila dosen anda tidak tersedia atau terdapat kesalahan hubungi PLT.</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pembimbingakademik">Pembimbing Akademik<span class="required">*</span></label>
                                    <div class="dot">:</div>
                                    <div class="col-md-12">
                                        <input name="pembimbingakademik" value="<?php echo isset($aditionaldata["PEMBIMBINGAKADEMIK"]) ? $aditionaldata["PEMBIMBINGAKADEMIK"] : ""; ?>" placeholder="Masukan Pembimbing Akademik Anda" type="text" required="required" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nippembimbingakademik">NIP Pembimbing Akademik<span class="required">*</span></label>
                                    <div class="dot">:</div>
                                    <div class="col-md-12">
                                        <input id="NipKaprodi" name="nippembimbingakademik" value="<?php echo isset($aditionaldata["NIPPEMBIMBINGAKADEMIK"]) ? $aditionaldata["NIPPEMBIMBINGAKADEMIK"] : ""; ?>" placeholder="Masukan NIP Pembimbing Akademik Anda" type="text" required="required" />
                                    </div>
                                </div>
                                <div class="form-group action-right">
                                    <button type="submit" class="btn btn-medium btn-ok">Download</button>
                                </div> <!-- edit console -->
                            </form>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </section>
</content>