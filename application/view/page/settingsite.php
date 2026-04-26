<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Admin, Operator');
?>

<div class="settings-container">
    <?php if (isset($response) && $response != null) {
        echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
    } ?>
    
    <div class="settings-card">
        <div class="card-header">
            <h1 class="card-title">Pengaturan Aplikasi</h1>
            <p class="card-subtitle">Manajemen konfigurasi waktu aktif operasional dan status registrasi sistem Web PLT.</p>
        </div>
        
        <form class="form" method="post" action="?page=sitesetting&action=updatesitesetting">
            <div class="settings-form-group">
                <label for="statusPendaftaran">Status Pendaftaran<span class="required">*</span></label>
                <select id="statusPendaftaran" name="statusPendaftaran" required="required" style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; transition: all 0.2s ease; outline:none;" onfocus="this.style.borderColor='#a805a8'; this.style.boxShadow='0 0 0 3px rgba(168, 5, 168, 0.1)';" onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none';">
                    <option value="1" <?php echo (isset($config['OPENREGISTER']) && $config['OPENREGISTER'] == 1) ? 'selected' : '' ?>>Enable (Sistem Pendaftaran Terbuka)</option>
                    <option value="0" <?php echo (isset($config['OPENREGISTER']) && $config['OPENREGISTER'] == 0) ? 'selected' : '' ?>>Disable (Sistem Pendaftaran Tertutup)</option>
                </select>
            </div>

            <div class="settings-form-group" style="margin-top: 25px;">
                <label>Konfigurasi Waktu Pembukaan<span class="required">*</span></label>
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <input name="tahunPendaftaram" value="<?php echo htmlspecialchars(isset($config['CURENTYEAR']) ? $config['CURENTYEAR'] : ''); ?>" placeholder="Masukan tahun sekarang (Contoh: 2026)" type="text" required="required" style="width:100%;" />
                        <span style="font-size: 11px; color: #888; display: block; margin-top: 6px;">Tahun Akademik</span>
                    </div>
                    
                    <div style="flex: 1; min-width: 200px;">
                        <input name="periodePendaftaran" value="<?php echo htmlspecialchars(isset($config['CURENTSEMESTER']) ? $config['CURENTSEMESTER'] : ''); ?>" placeholder="Masukan semester (ganjil/genap)" type="text" required="required" style="width:100%;" />
                        <span style="font-size: 11px; color: #888; display: block; margin-top: 6px;">Semester</span>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                <button type="submit" name="action" value="updatesitesetting" class="btn-save">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
