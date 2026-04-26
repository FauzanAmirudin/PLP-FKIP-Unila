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

    <div class="pendaftaran-wrapper">
        <div class="pendaftaran-container">
            <?php if (isset($response) && $response != null) {
                echo '<div style="padding: 20px; background: #fee2e2; color: #ef4444; text-align: center; font-weight: 600;">' . $response . '</div>';
            } ?>
            
            <div class="card-header">
                <h1 class="card-title">Form Pendaftaran</h1>
                <p class="card-subtitle">Silakan lengkapi data pembimbing untuk mengunduh berkas</p>
            </div>
            
            <div class="form-body">
                <form method="get" action="index.php" id="form2">
                    <input name="page" value="berkas" type="hidden" required="required" />
                    <input name="access" value="downloads" type="hidden" required="required" />
                    <input name="file" value="formpendaftaran" type="hidden" required="required" />
                    
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
                        <button type="submit" name="action" value="DownloadBerkas" class="btn-save">Unduh Berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
