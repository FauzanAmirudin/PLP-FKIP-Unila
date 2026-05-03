<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Admin, Operator');
?>
<style>
</style>
<div class="settings-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
    } ?>

    <div class="settings-card">
        <div class="card-header">
            <h1 class="card-title">Reset Password User</h1>
            <p class="card-subtitle">Gunakan fitur ini untuk mereset kata sandi mahasiswa/user ke pengaturan default sistem.</p>
        </div>

        <form class="form" method="post" action="index.php?page=resetpassword&action=resetpassword" id="form2">
            <div class="settings-form-group">
                <label for="NPM">NPM / NIP User <span class="required">*</span></label>
                <input name="NPM" id="NPM" placeholder="Masukan NPM atau NIP yang akan di-reset" type="text" required="required" autofocus />
                <span class="input-hint">Password akan diatur ulang menjadi "majuteruspltfkip" secara otomatis.</span>
            </div>

            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 25px;">
                <button type="submit" name="action" value="resetpassword" class="btn-save">Reset Password Sekarang</button>
            </div>
        </form>
    </div>
</div>
