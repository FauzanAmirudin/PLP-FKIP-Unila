<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level('Admin, Operator');
?>
<content>
  <section id="mainContent">
    <div class="content">
      <div class="header">
        <a>IMPERSONATE USER</a>
      </div>
      <?php if (isset($notification) && $notification != null) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Masuk Sebagai
          <span class="field-action action-right"> </span>
        </h1>
        <div>
          <form class="form" method="post" action="<?php set_url('site/impersonate') ?>">
            <div class="form-group row">
              <label for="statusPendaftaran" class="inline-label">Username<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input name="username" value="" placeholder="Masukan username" type="text" required="required" />
              </div>
            </div>
            <div class="form-group action-right">
              <button type="submit" class="btn btn-medium btn-ok">Inpersonate</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</content>