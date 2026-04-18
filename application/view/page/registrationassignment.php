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
        <a>ASSIGNMENT USER</a>
      </div>
      <?php if (isset($notification) && $notification != null) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Bulk Assignment
          <span class="field-action action-right"> </span>
        </h1>
        <form action="<?= set_url('api/upload/users') ?>" method="post" enctype="multipart/form-data" class="form">
          <div class="form-group row">
            <label class="inline-label">Pilih file:</label>
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
      <div class="field" id="ajaxDiv" style="display: none;">
      </div>
    </div>

    <div id="modal" class="modal">
      <div class="modal-centered" style="max-width: 10rem; width: 90%;">
        <div class="content animate">
          <div class="container">
            <h1 style="padding: .2rem 1rem; text-align: center; font-size: 1.3rem; color: #8806D4;">Loading...</h1>
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
        let aj_data = new gcAjax(form, "<?= set_url('api/upload/assignment') ?>");
        aj_data.addValue("force=" + force)
          .setLoading(function(state, element) {
            if (state) {
              document.getElementById('modal').style.display = "block";
            } else {
              document.getElementById('modal').style.display = "none";
            }
          })
          .setCallback(function(text, element) {
            try {
              if (text != null) {
                element.style.display = "block";
                let html = '';
                var response = JSON.parse(text);
                if (response.messege != 'undefined' && response.messege != null) {
                  html += response.messege;
                }
                if (response.data != 'undefined' && response.data != null) {
                  html += '<div id="" class="table-view" style="overflow-x:auto; max-height: 90vh;">' + response.data + '</div>';
                }
                element.innerHTML = html;
              } else {

              }
            } catch (error) {
              element.innerHTML = text;
            }
          })
          .send('ajaxDiv', button, '#FFFFFF');
      }
    </script>
  </section>
</content>