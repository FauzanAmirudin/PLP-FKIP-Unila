<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
?>
<content>
  <section id="mainContent">
    <div class="content">
      <div class="header">
        <a>PENGATURAN</a>
      </div>
      <?php if (isset($notification) && !empty($notification)) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Rubah Password
          <span class="field-action action-right"> </span>
        </h1>
        <div>
          <form class="form" method="post" action="<?php set_url('user/password_change') ?>">
            <div class="form-group row">
              <label for="passwordLama" class="inline-label">Password Lama<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input name="passwordOld" value="" placeholder="Masukan Password Lama Anda" type="password" required="required" />
              </div>
            </div>
            <div class="form-group row">
              <label for="passwordBaru" class="inline-label">Password Baru<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input name="passwordNew1" value="" placeholder="Masukan Password Baru Anda" type="password" required="required" />
                <input name="passwordNew2" value="" placeholder="Masukan Kembali Password Baru Anda" type="password" required="required" />
              </div>
            </div>
            <div class="form-group action-right">
              <button type="submit" name="action" value="rubahpassword" class="btn btn-medium btn-ok">Simpan</button>
            </div>
        </div> <!-- edit console -->
        </form>
      </div>
    </div>
  </section>
</content>