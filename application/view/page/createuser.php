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
        <a>CREATE USER</a>
      </div>
      <?php if (isset($notification) && $notification != null) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Create User
          <span class="field-action action-right"> </span>
        </h1>
        <div>
          <form class="form" method="post" action="<?php set_url('user/create_user') ?>" id="createUserForm" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="User" class="inline-label">User<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input <?= input_name('User'); ?> placeholder="Masukan User" type="text" required="required" />
              </div>
            </div>
            <div class="form-group row">
              <label for="Name" class="inline-label">Nama<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input <?= input_name('Name'); ?> placeholder="Masukan Nama" type="text" required="required" />
              </div>
            </div>
            <div class="form-group row">
              <label for="Password" class="inline-label">Password<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input name="Password" value="majuteruspltfkip" placeholder="Masukan Password" type="text" required="required" />
              </div>
            </div>
            <div class="form-group row">
              <label for="rePassword" class="inline-label">Password<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <input name="rePassword" value="majuteruspltfkip" placeholder="Masukan Kembali Password" type="text" required="required" />
              </div>
            </div>
            <div class="form-group row">
              <label for="Type" class="inline-label">Type<span class="required">*</span></label>
              <div class="dot">:</div>
              <div class="col-md-4">
                <select name="Type" type="text" required="required" />
                <?= select_option("Type", "Pilih Type", FALSE) ?>
                <?= select_option("Type", "Mahasiswa") ?>
                <?= select_option("Type", "DPL") ?>
                </select>
              </div>
            </div>
            <div class="form-group action-right">
              <button type="submit" class="btn btn-medium btn-ok">Create User</button>
            </div>
          </form>
        </div>
      </div>
      <div class="field">
        <h1>Bulk Create User
          <span class="field-action action-right"> </span>
        </h1>
        <form action="<?= set_url('api/upload/users') ?>" method="post" enctype="multipart/form-data" class="form">
          <div class="form-group row">
            <label class="inline-label">Pilih file users:</label>
            <div class="col-md-6">
              <input type="file" name="file">
              <a class="help-block"><a style="color:#ff0000;">*</a> hanya file .xlsx atau file Excel Document dengan ukuran maximum 5 MB.</a>
            </div>
          </div>
          <div class="form-group action-right">
            <button type="submit" id="uploadUser" name="action" class="btn btn-medium btn-ok">Upload File</button>
            <button type="reset" class="btn btn-medium btn-cancel">Reset</button>
          </div>
        </form>
      </div>
    </div>

    <div id="modal" class="modal">
      <div class="modal-centered" style="max-width: 60rem; width: 90%;">
        <div class="content animate">
          <div class="container">
            <div class="title title__color">
              <h1>Result
                <span class="action-right">
                  <a onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right;"></a>
                </span>
              </h1>
            </div>
            <div class="field">
              <div id="ajaxDiv" class="table-view" style="overflow-x:scroll; max-height: 90vh;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      btnSubmit = document.getElementById("uploadUser");
      btnSubmit.addEventListener("click", function(event) {
        event.preventDefault();
        console.log(event.target.form);
        console.log(this.form);
        ajaxPOST(this.form, this, false);
      }, false);

      function ajaxPOST(form, button, force) {
        let aj_data = new gcAjax(form, "<?= set_url('api/upload/users') ?>");
        aj_data.setMethod('post').addValue("force=" + force).setCallback(function(text, element) {
          let relodBtn = '<button class="btn btn-ok" onClick="location.reload()">Perbaharui Daftar</button></div>';
          element.innerHTML = text;
          document.getElementById('modal').style.display = "block";
        }).send('ajaxDiv', button, '#FFFFFF');
      }
    </script>
  </section>
</content>