<?php defined('GF_BASE_PATH') or exit('No direct script access allowed'); ?>

<div class="laporan-container">
    <?php if (!empty($notification)) { ?>
        <div id="toastServer" class="toast-notification toast-success">
            <div class="toast-content">
                <div class="toast-title">Notifikasi</div>
                <div class="toast-message"><?= str_replace(['<br>', '<br/>'], '', $notification) ?></div>
            </div>
            <button class="toast-close" onclick="document.getElementById('toastServer').classList.add('hide')">×</button>
        </div>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('toastServer');
                if(toast) toast.classList.add('hide');
            }, 6000);
        </script>
    <?php } ?>

    <div class="laporan-card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h1 class="card-title" style="margin: 0;">Buat Sesi Bimbingan Baru</h1>
            <a href="<?= set_url("bimbingan/index/" . $user['ID']) ?>" class="btn-cancel-modal" style="text-decoration: none; padding: 10px 20px;">&larr; Kembali</a>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
            <p style="margin: 0; color: #334155;">DPL Anda saat ini adalah: <strong style="color: #0f172a;"><?= htmlspecialchars($penempatan['NAMADOSEN']) ?></strong></p>
        </div>
        
        <div class="upload-panel-collapse" style="display: block; background: transparent; padding: 0; border: none; box-shadow: none;">
            <form action="<?= set_url("bimbingan/simpan/" . $user['ID']) ?>" method="POST" class="laporan-form">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group-modern" style="margin-bottom: 20px;">
                    <label for="topik">Topik Bimbingan <span class="required">*</span></label>
                    <input type="text" id="topik" name="topik" class="input-control" placeholder="Contoh: Rencana Pelaksanaan Pembelajaran (RPP)" required>
                </div>
                
                <div class="form-group-modern" style="margin-bottom: 20px;">
                    <label for="deskripsi">Deskripsi Detail <span class="required">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" class="input-control" placeholder="Tuliskan secara lengkap hal-hal yang ingin Anda diskusikan dengan DPL..." required style="resize: vertical; padding: 12px; height: 250px !important; min-height: 250px;"></textarea>
                </div>
                
                <div class="form-actions" style="margin-top: 30px;">
                    <button type="submit" class="btn-save" style="width: 100%; max-width: 200px;">Mulai Bimbingan</button>
                </div>
            </form>
        </div>
    </div>
</div>
