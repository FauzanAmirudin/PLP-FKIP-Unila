<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level('Admin, Operator');
?>

<div class="settings-container">
    <?php if (isset($notification) && $notification != null) {
        echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $notification . '</div>';
    } ?>

    <div class="settings-card">
        <div class="card-header">
            <h1 class="card-title">Impersonate User</h1>
            <p class="card-subtitle">Masuk ke sistem seolah-olah Anda adalah user lain untuk keperluan bantuan teknis atau audit.</p>
        </div>

        <form class="form" method="post" action="<?php set_url('site/impersonate') ?>">
            <div class="settings-form-group">
                <label for="username">Username / NPM Target <span class="required">*</span></label>
                <input name="username" id="username" placeholder="Masukan username atau NPM target" type="text" required="required" autofocus />
                <span class="input-hint">Anda akan dialihkan ke dashboard user tersebut setelah berhasil masuk.</span>
            </div>

            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 25px;">
                <button type="submit" class="btn-save">Mulai Impersonate</button>
            </div>
        </form>
    </div>
</div>
</div>
