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

    <div class="laporan-card" style="display: flex; flex-direction: column; height: calc(100vh - 200px); min-height: 600px; padding: 0; overflow: hidden;">
        
        <!-- Header Chat -->
        <div class="card-header" style="background: linear-gradient(135deg, #a805a8 0%, #bd0fc1 100%); border-bottom: none; box-shadow: 0 4px 15px rgba(168, 5, 168, 0.25); z-index: 10; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            
            <!-- Left: Back Button -->
            <div style="flex: 1; display: flex; justify-content: flex-start;">
                <a href="<?= set_url("bimbingan/index/" . $user['ID']) ?>" style="text-decoration: none; padding: 6px 12px; font-size: 0.8rem; border-radius: 4px; margin: 0; background: white; color: #a805a8; font-weight: 600; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">&larr; Kembali</a>
            </div>
            
            <!-- Center: Title and Info -->
            <div style="flex: 2; text-align: center;">
                <h1 class="card-title" style="margin: 0 0 5px 0; font-size: 1.25rem; color: #ffffff;"><?= htmlspecialchars($sesi['TOPIK']) ?></h1>
                <div style="color: #e2e8f0; font-size: 0.95rem;">
                    <?php if ($role == 'Mahasiswa') { ?>
                        <span><strong>DPL:</strong> <?= htmlspecialchars($sesi['NAMA_DPL']) ?></span>
                    <?php } else { ?>
                        <span><strong>Mahasiswa:</strong> <?= htmlspecialchars($sesi['NAMA_MAHASISWA']) ?> (<?= htmlspecialchars($sesi['NPM']) ?>)</span>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Right: Badges and Buttons -->
            <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                <?php 
                    if ($sesi['STATUS'] == 'Selesai') {
                        echo '<span class="badge badge-read" style="background: #dcfce7; color: #16a34a; font-size: 0.8rem; padding: 4px 10px; margin: 0;">Selesai</span>';
                    } elseif ($sesi['STATUS'] == 'Berlangsung') {
                        echo '<span class="badge badge-unread" style="background: #dbeafe; color: #1e3a8a; font-size: 0.8rem; padding: 4px 10px; margin: 0;">Berlangsung</span>';
                    } else {
                        echo '<span class="badge badge-pending" style="background: #f1f5f9; color: #64748b; font-size: 0.8rem; padding: 4px 10px; margin: 0;">Menunggu Dibalas</span>';
                    }
                ?>
                
                <?php if ($role == 'DPL' && $sesi['STATUS'] != 'Selesai') { ?>
                    <form action="<?= set_url("bimbingan/selesai/" . $sesi['ID']) ?>" method="POST" onsubmit="return confirm('Tandai sesi ini sebagai selesai?');" style="margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="btn-save" style="background: #16a34a; padding: 4px 10px; font-size: 0.8rem; border-radius: 4px; margin: 0;">Tandai Selesai</button>
                    </form>
                <?php } ?>
                
                <?php if ($role == 'Mahasiswa' && $sesi['STATUS'] == 'Menunggu') { ?>
                    <form action="<?= set_url("bimbingan/hapus/" . $sesi['ID']) ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus sesi bimbingan ini? Semua pesan di dalamnya akan ikut terhapus.');" style="margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="btn-save" style="background: #dc2626; padding: 4px 10px; font-size: 0.8rem; border-radius: 4px; margin: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); border: 1px solid #b91c1c;">Hapus Sesi</button>
                    </form>
                <?php } ?>
            </div>
        </div>

        <!-- Body Chat (Pesan-pesan) -->
        <div id="chatBody" style="flex-grow: 1; overflow-y: auto; padding: 20px; background: #fdfdfd;">
            <?php if (empty($pesan_list)) { ?>
                <div class="empty-state" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; border: none; background: transparent;">
                    <p style="color: #94a3b8; font-style: italic;">Belum ada pesan diskusi. Silakan mulai berdiskusi!</p>
                </div>
            <?php } else { ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- Bubble Topik / Deskripsi Awal -->
                    <?php
                        $isMeInitial = ($sesi['USRKEY'] == $user['ID']);
                    ?>
                    <div style="<?= $isMeInitial ? 'align-self: flex-end;' : 'align-self: flex-start;' ?> max-width: 85%;">
                        <div style="display: flex; <?= $isMeInitial ? 'justify-content: flex-end;' : '' ?> margin-bottom: 4px; font-size: 0.8rem; color: #64748b;">
                            <?php if ($isMeInitial) { ?>
                                <span><?= date('H:i, d M', strtotime($sesi['CREATEDAT'])) ?></span>
                                <span style="margin: 0 5px;">•</span>
                                <strong>Anda</strong>
                            <?php } else { ?>
                                <strong><?= htmlspecialchars($sesi['NAMA_MAHASISWA']) ?> (Mahasiswa)</strong>
                                <span style="margin: 0 5px;">•</span>
                                <span><?= date('H:i, d M', strtotime($sesi['CREATEDAT'])) ?></span>
                            <?php } ?>
                        </div>
                        <div style="background: #fefce8; color: #0f172a; padding: 15px; border-radius: <?= $isMeInitial ? '16px 16px 4px 16px' : '16px 16px 16px 4px' ?>; border: 1px solid #fef08a; box-shadow: 0 1px 2px rgba(0,0,0,0.05); line-height: 1.5;">
                            <div style="font-weight: 600; margin-bottom: 5px; color: #854d0e; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi Topik:</div>
                            <?= nl2br(htmlspecialchars($sesi['DESKRIPSI'])) ?>
                        </div>
                    </div>
                    <?php foreach ($pesan_list as $pesan) { 
                        $isMe = ($pesan['SENDER_USRKEY'] == $user['ID']);
                        $senderName = $pesan['SENDER_ROLE'] == 'Mahasiswa' ? $pesan['NAMA_MAHASISWA'] : $pesan['NAMA_DPL'];
                        
                        if ($isMe) {
                            // Bubble Kanan (Saya)
                            ?>
                            <div style="align-self: flex-end; max-width: 75%;">
                                <div style="display: flex; justify-content: flex-end; margin-bottom: 4px; font-size: 0.8rem; color: #64748b;">
                                    <span><?= date('H:i, d M', strtotime($pesan['CREATEDAT'])) ?></span>
                                    <span style="margin: 0 5px;">•</span>
                                    <strong>Anda</strong>
                                </div>
                                <div style="background: #a805a8; color: white; padding: 12px 16px; border-radius: 16px 16px 4px 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); line-height: 1.5;">
                                    <?= nl2br(htmlspecialchars($pesan['PESAN'])) ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            // Bubble Kiri (Orang Lain)
                            ?>
                            <div style="align-self: flex-start; max-width: 75%;">
                                <div style="display: flex; margin-bottom: 4px; font-size: 0.8rem; color: #64748b;">
                                    <strong><?= htmlspecialchars($senderName) ?> (<?= $pesan['SENDER_ROLE'] ?>)</strong>
                                    <span style="margin: 0 5px;">•</span>
                                    <span><?= date('H:i, d M', strtotime($pesan['CREATEDAT'])) ?></span>
                                </div>
                                <div style="background: #f1f5f9; color: #0f172a; padding: 12px 16px; border-radius: 16px 16px 16px 4px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05); line-height: 1.5;">
                                    <?= nl2br(htmlspecialchars($pesan['PESAN'])) ?>
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
            <?php } ?>
        </div>

        <!-- Footer Chat (Form Input) -->
        <div style="border-top: 1px solid #e2e8f0; background: white; padding: 15px 20px; flex-shrink: 0;">
            <?php if ($sesi['STATUS'] != 'Selesai') { ?>
                <form action="<?= set_url("bimbingan/kirim/" . $sesi['ID']) ?>" method="POST" style="display: flex; gap: 10px; margin: 0; align-items: flex-end;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <textarea name="pesan" class="input-control" placeholder="Ketik pesan Anda di sini..." required style="flex-grow: 1; resize: none; min-height: 50px; height: 50px; max-height: 120px; margin: 0; border-radius: 20px; padding: 12px 20px;"></textarea>
                    <button type="submit" class="btn-save" style="border-radius: 20px; padding: 12px 24px; height: 50px; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        Kirim
                    </button>
                </form>
            <?php } else { ?>
                <div class="alert-danger-modern" style="margin: 0; text-align: center; background: #f8fafc; border-color: #e2e8f0; color: #64748b;">
                    Sesi bimbingan ini telah ditandai selesai. Anda tidak dapat mengirim pesan baru.
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom of chat
    document.addEventListener("DOMContentLoaded", function() {
        var chatBody = document.getElementById("chatBody");
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    });

    // Auto resize textarea
    const tx = document.querySelector('textarea.input-control');
    if (tx) {
        tx.setAttribute('style', 'flex-grow: 1; resize: none; height: 50px; overflow-y: hidden; margin: 0; border-radius: 20px; padding: 12px 20px;');
        tx.addEventListener("input", OnInput, false);
    }

    function OnInput() {
        this.style.height = '50px';
        const scrollHeight = this.scrollHeight;
        if (scrollHeight > 50) {
            this.style.height = (scrollHeight < 120 ? scrollHeight : 120) + "px";
            if (scrollHeight >= 120) {
                this.style.overflowY = 'auto';
            } else {
                this.style.overflowY = 'hidden';
            }
        }
    }
</script>
