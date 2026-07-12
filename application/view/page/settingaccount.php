<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<div class="settings-container">
  <?php if (isset($response) && $response != null) {
      echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
  } ?>
  
  <div class="settings-card">
    <div class="card-header">
      <h1 class="card-title">Ubah Password</h1>
    </div>
    
    <form class="form" method="post" action="<?php echo set_url('user/settings'); ?>" id="form2">
      <div class="settings-form-group">
        <label for="passwordNew1">Password Baru<span class="required">*</span></label>
        <input name="passwordNew1" id="passwordNew1" placeholder="Masukan password baru Anda" type="password" required="required" autofocus />
        <span class="input-hint">Pilih kata sandi yang kuat namun mudah Anda ingat.</span>
      </div>

      <div class="settings-form-group">
        <label for="passwordNew2">Konfirmasi Password Baru<span class="required">*</span></label>
        <input name="passwordNew2" id="passwordNew2" placeholder="Masukan kembali password baru" type="password" required="required" />
      </div>

      <div class="settings-form-group" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 25px;">
        <label for="passwordOld">Password Lama<span class="required">*</span></label>
        <input name="passwordOld" id="passwordOld" placeholder="Masukan password aktif Anda" type="password" required="required" />
        <span class="input-hint">Sebagai lapis keamanan tambahan, otentikasi bahwa ini adalah Anda.</span>
      </div>

      <div class="form-actions">
        <button type="submit" name="action" value="rubahpassword" class="btn-save">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
