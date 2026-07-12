<?php defined('GF_BASE_PATH') or exit('No direct script access allowed'); ?>

<div class="laporan-container">
    <?php if (!empty($notification)) { ?>
        <div id="toastServer" class="toast-notification toast-success">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
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
            <h1 class="card-title" style="margin: 0;">Bimbingan DPL</h1>
            <div style="display: flex; gap: 10px;">
                <?php if ($role == 'Mahasiswa' && isset($can_bimbingan) && $can_bimbingan) { ?>
                    <a href="<?= set_url("bimbingan/download/" . $user['ID']) ?>" class="btn-save" style="background: #1e3a8a; text-decoration: none; padding: 10px 20px;">Unduh Buku Kendali (.docx)</a>
                    <a href="<?= set_url("bimbingan/buat/" . $user['ID']) ?>" class="btn-save" style="text-decoration: none; padding: 10px 20px;">+ Buat Bimbingan Baru</a>
                <?php } else if ($role == 'DPL') { ?>
                    <a href="<?= set_url("bimbingan/download_dpl/" . $user['ID']) ?>" class="btn-save" style="background: #1e3a8a; text-decoration: none; padding: 10px 20px;">Unduh Seluruh Buku Kendali (.docx)</a>
                <?php } ?>
            </div>
        </div>

        <?php if ($role == 'Mahasiswa' && isset($can_bimbingan) && !$can_bimbingan) { ?>
            <div class="alert-danger-modern" style="margin-bottom: 20px;">
                ⚠️ <?= $bimbingan_message ?>
            </div>
        <?php } ?>

        <div class="report-list">
            <?php if (empty($sesi_list)) { ?>
                <div class="empty-state">
                    <p>Belum ada sesi bimbingan.</p>
                </div>
            <?php } else { ?>
                <?php foreach ($sesi_list as $sesi) { 
                    if ($sesi['STATUS'] == 'Selesai') {
                        $statusBadge = '<span class="badge badge-read" style="background: #dcfce7; color: #16a34a;">Selesai</span>';
                    } elseif ($sesi['STATUS'] == 'Berlangsung') {
                        $statusBadge = '<span class="badge badge-unread" style="background: #dbeafe; color: #1e3a8a;">Berlangsung</span>';
                    } else {
                        $statusBadge = '<span class="badge badge-pending" style="background: #f1f5f9; color: #64748b;">Menunggu Dibalas</span>';
                    }
                ?>
                    <div class="report-item" style="display: flex; align-items: flex-start; gap: 15px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 15px; background: white; transition: all 0.3s ease;">
                        <div class="report-icon" style="flex-shrink: 0; width: 48px; height: 48px; border-radius: 12px; background: #fdf4ff; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#B33791" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        
                        <div class="report-info" style="flex-grow: 1;">
                            <div style="margin-bottom: 8px;">
                                <h3 class="report-filename" style="margin: 0; font-size: 1.1rem; color: #1e293b;"><?= htmlspecialchars($sesi['TOPIK']) ?></h3>
                            </div>
                            
                            <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 10px;">
                                <?php if ($role == 'Mahasiswa') { ?>
                                    <span><strong>DPL:</strong> <?= htmlspecialchars($sesi['NAMA_DPL']) ?></span>
                                <?php } else { ?>
                                    <span><strong>Mahasiswa:</strong> <?= htmlspecialchars($sesi['NAMA_MAHASISWA']) ?> (<?= htmlspecialchars($sesi['NPM']) ?>)</span>
                                <?php } ?>
                                <span style="margin: 0 8px;">•</span>
                                <span><?= date('d M Y, H:i', strtotime($sesi['CREATEDAT'])) ?></span>
                            </div>
                        </div>
                        
                        <div class="report-actions" style="flex-shrink: 0; align-self: center; display: flex; gap: 15px; align-items: center;">
                            <?= $statusBadge ?>
                            <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                                <a class="btn-action-laporan" href="<?= set_url("bimbingan/sesi/" . $sesi['ID']) ?>" style="background: white; border: 1px solid #B33791; color: #B33791; padding: 8px 16px; border-radius: 8px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s;">
                                    Buka Sesi &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
