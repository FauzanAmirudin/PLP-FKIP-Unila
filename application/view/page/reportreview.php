<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

is_level("Admin, Monitor, Operator, DPL");

// Mengambil data dari variabel yang diset di controller
$reportId = isset($reportId) ? $reportId : '';
$filename = isset($filename) ? $filename : '';
$npm = isset($npm) ? $npm : '';
$nama_mahasiswa = isset($nama_mahasiswa) ? $nama_mahasiswa : 'Tidak Diketahui';
$userId = isset($user['ID']) ? $user['ID'] : '';
$csrfToken = isset($csrf_token) ? $csrf_token : '';

// Mengambil data response jika sudah pernah direview
$existing_respons = isset($res['RESPONSE']) ? $res['RESPONSE'] : '';
$existing_komentar = isset($res['KRITIKSARAN']) ? $res['KRITIKSARAN'] : '';

?>
<div class="schedule-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong">' . $notification . '</div>';
    } ?>

    <div class="schedule-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header" style="border-bottom: 1px solid #e2e8f0; margin-bottom: 20px; padding-bottom: 15px;">
            <h1 class="card-title">Respons Laporan Mingguan</h1>
            <p class="card-subtitle">Berikan evaluasi dan catatan untuk laporan mahasiswa.</p>
        </div>

        <div class="student-info-box" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
            <div style="margin-bottom: 10px;">
                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Mahasiswa</span>
                <div style="font-size: 16px; font-weight: 500; color: #0f172a;"><?= htmlspecialchars($nama_mahasiswa) ?></div>
                <div style="font-size: 14px; color: #475569;"><?= htmlspecialchars($npm) ?></div>
            </div>
            <div>
                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">File Laporan</span>
                <div style="font-size: 14px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    <?= htmlspecialchars($filename) ?>
                </div>
            </div>
        </div>

        <form action="<?= set_url('laporan/save_review') ?>" method="post">
            <input type="hidden" name="reportId" value="<?= htmlspecialchars($reportId) ?>">
            <input type="hidden" name="nama_mahasiswa" value="<?= htmlspecialchars($nama_mahasiswa) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="respons" style="display: block; font-weight: 500; color: #334155; margin-bottom: 8px;">Status Respons <span style="color: #ef4444;">*</span></label>
                <select name="respons" id="respons" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; background-color: #fff; font-size: 14px; color: #0f172a; appearance: auto;">
                    <option value="" hidden>Pilih Response</option>
                    <option value="Cukup" <?= ($existing_respons == 'Cukup') ? 'selected' : '' ?>>Cukup (Diterima)</option>
                    <option value="Kurang" <?= ($existing_respons == 'Kurang') ? 'selected' : '' ?>>Kurang (Butuh Revisi)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="komentar" style="display: block; font-weight: 500; color: #334155; margin-bottom: 8px;">Komentar / Catatan Review <span style="color: #ef4444;">*</span></label>
                <textarea name="komentar" id="komentar" required rows="5" placeholder="Komentar atau catatan revisi wajib diisi..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; font-size: 14px; color: #0f172a; resize: vertical;"><?= htmlspecialchars($existing_komentar) ?></textarea>
                <p style="font-size: 12px; color: #64748b; margin-top: 6px; margin-bottom: 0;">Catatan ini akan dilihat oleh mahasiswa bersangkutan di halaman mereka.</p>
            </div>

            <div class="form-actions" style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 10px;">
                <a href="<?= set_url('laporan/data/' . $userId) ?>" class="btn-cancel-modal" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                <button type="submit" class="btn-save" style="display: inline-flex; align-items: center; justify-content: center;">Simpan Respons</button>
            </div>
        </form>

    </div>
</div>
