<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Admin, Operator');
?>

<div class="settings-container">
    <?php 
    $msg = '';
    if (isset($response) && $response != null) {
        $msg = $response;
    } elseif (isset($alert) && $alert != null) {
        $msg = $alert;
    }
    
    if ($msg != '') { ?>
        <!-- Modern Toast Notification -->
        <div id="toastSuccess" class="toast-notification">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="toast-content">
                <div class="toast-title">Berhasil</div>
                <div class="toast-message"><?php echo str_replace('<br>', '', $msg); ?></div>
            </div>
            <button class="toast-close" onclick="closeToast()">×</button>
        </div>
        
        <script>
            setTimeout(function() {
                var toast = document.getElementById('toastSuccess');
                if(toast) toast.classList.add('hide');
            }, 5000);
            function closeToast() {
                document.getElementById('toastSuccess').classList.add('hide');
            }
        </script>
        
        <style>
            .toast-notification {
                position: fixed;
                top: 24px;
                right: 24px;
                background: #fff;
                border-left: 4px solid #10b981;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                padding: 16px 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 16px;
                z-index: 99999;
                min-width: 300px;
                animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            }
            .toast-notification.hide {
                animation: slideOutRight 0.4s ease-in forwards;
            }
            .toast-icon {
                background: #d1fae5;
                color: #10b981;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .toast-icon svg { width: 18px; height: 18px; }
            .toast-title { font-weight: 600; color: #1f2937; font-size: 15px; margin-bottom: 4px; }
            .toast-message { color: #6b7280; font-size: 13px; line-height: 1.4; }
            .toast-close { background: transparent; border: none; font-size: 22px; color: #9ca3af; cursor: pointer; margin-left: auto; padding: 0 4px; display:flex; align-items:center; }
            .toast-close:hover { color: #4b5563; }
            
            @keyframes slideInRight {
                0% { transform: translateX(120%); opacity: 0; }
                100% { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                0% { transform: translateX(0); opacity: 1; }
                100% { transform: translateX(120%); opacity: 0; }
            }
        </style>
    <?php } ?>
    
    <div class="settings-card">
        <div class="card-header">
            <h1 class="card-title">Pengaturan Aplikasi</h1>
            <p class="card-subtitle">Manajemen konfigurasi waktu aktif operasional dan status registrasi sistem Web PLT.</p>
        </div>
        
        <form class="form" method="post" action="?page=site/settings">
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

            <div class="settings-form-group" style="margin-top: 25px;">
                <label for="maxReport">Maksimal Laporan Mingguan<span class="required">*</span></label>
                <input id="maxReport" name="maxReport" value="<?php echo htmlspecialchars(isset($config['MAXREPORT']) ? $config['MAXREPORT'] : '8'); ?>" placeholder="Contoh: 8" type="number" min="1" required="required" style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc; transition: all 0.2s ease; outline:none;" onfocus="this.style.borderColor='#a805a8'; this.style.boxShadow='0 0 0 3px rgba(168, 5, 168, 0.1)';" onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none';" />
                <span style="font-size: 11px; color: #888; display: block; margin-top: 6px;">Menentukan jumlah maksimal laporan yang dapat diunggah oleh mahasiswa (muncul di pilihan dropdown).</span>
            </div>

            <div class="form-actions" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                <button type="submit" name="action" value="updatesitesetting" class="btn-save">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
