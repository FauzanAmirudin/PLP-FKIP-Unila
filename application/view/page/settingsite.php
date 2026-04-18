<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level('Admin, Operator');
?>
<content>
    <section id="mainContent">
        <div class="content">
            <div class="header">
                <a>PENGATURAN APLIKASI</a>
            </div>
            <?php if (isset($notification) && $notification != null) {
                echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
            } ?>
            <div class="field">
                <h1>Rubah Pengaturan
                    <a class="btnCircle" onclick="document.getElementById('form2').style.display='block'">[]</a>
                    <a class="btnCircle" onclick="document.getElementById('form2').style.display='none'"> _ </a>
                </h1>
                <div>
                    <form class="form" method="post" action="?page=sitesetting&action=updatesitesetting">
                        <div class="form-group row">
                            <label for="statusPendaftaran" class="inline-label">Status Pendaftaran<span class="required">*</span></label>
                            <div class="dot">:</div>
                            <div class="col-md-3">
                                <select id="statusPendaftaran" name="statusPendaftaran" type="text" required="required" />
                                <option value="1" <?php echo $config['OPENREGISTER'] == 1 ? 'selected' : '' ?>>Enable</option>
                                <option value="0" <?php echo $config['OPENREGISTER'] == 0 ? 'selected' : '' ?>>Disable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="waktuPembukaan" class="inline-label">Sesi Pendaftaran<span class="required">*</span></label>
                            <div class="dot">:</div>
                            <div class="col-md-4">
                                <select <?= select_name("tahunPendaftaram", $config['CURENTYEAR']) ?> type="text" required="required" />
                                <?php
                                echo select_option("tahunPendaftaram", "Pilih Tahun", FALSE);
                                $year = year();
                                $year++;
                                for ($i=0; $i < 4; $i++) {
                                    echo select_option("tahunPendaftaram", $year . '-' . ($year+1));
                                    $year--;
                                }
                                ?>
                                </select>
                                <input name="periodePendaftaran" value="<?php echo $config['CURENTSEMESTER'] ?>" placeholder="Masukan semester (ganjil/genap)" type="text" required="required" />
                            </div>
                        </div>
                        <div class="form-group action-right">
                            <button type="submit" name="action" value="updatesitesetting" class="btn btn-medium btn-ok">Simpan</button>
                        </div> <!-- edit console -->
                    </form>
                </div>
            </div>
        </div>
    </section>
</content>