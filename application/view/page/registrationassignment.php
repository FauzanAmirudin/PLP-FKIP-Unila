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
            <h1 class="card-title">Bulk Assignment User</h1>
            <p class="card-subtitle">Unggah berkas Excel untuk melakukan penempatan atau assignment user secara massal ke dalam sistem.</p>
        </div>

        <form action="<?= set_url('api/upload/users') ?>" method="post" enctype="multipart/form-data" class="form">
            <div class="settings-form-group">
                <label>Pilih File Excel (.xlsx)</label>
                <div style="position: relative; border: 2px dashed #ddd; border-radius: 12px; padding: 30px; text-align: center; background: #fafafa; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#a805a8'; this.style.background='#fff';" onmouseout="this.style.borderColor='#ddd'; this.style.background='#fafafa';">
                    <input type="file" name="file" id="fileAssignment" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <p style="margin: 0; font-size: 14px; color: #64748b;">Klik atau tarik file Excel ke sini</p>
                    <span id="fileNameDisplay" style="display: block; margin-top: 10px; font-weight: 600; color: #a805a8; font-size: 13px;"></span>
                </div>
                <span class="input-hint" style="color: #ef4444; margin-top: 10px;">* Hanya file .xlsx dengan ukuran maksimal 5 MB.</span>
            </div>

            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 25px;">
                <button type="submit" id="uploadUser" name="action" class="btn-save">Mulai Upload & Proses</button>
                <button type="reset" class="btn-save" style="background: #64748b; margin-left: 10px;" onclick="document.getElementById('fileNameDisplay').innerText=''">Reset</button>
            </div>
        </form>
    </div>

    <div id="ajaxDiv" style="margin-top: 30px; display: none;">
        <!-- Hasil AJAX akan muncul di sini -->
    </div>
</div>

<script>
document.getElementById('fileAssignment').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : '';
    document.getElementById('fileNameDisplay').innerText = fileName ? "File terpilih: " + fileName : '';
});
</script>

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
        let formData = new FormData(form);
        formData.append("force", force);
        
        document.getElementById('modal').style.display = "block";
        let originalBtnText = button.innerHTML;
        button.innerHTML = 'Uploading...';
        button.disabled = true;

        fetch("<?= set_url('api/upload/assignment') ?>", {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(text => {
          document.getElementById('modal').style.display = "none";
          let element = document.getElementById('ajaxDiv');
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
            }
          } catch (error) {
            element.innerHTML = text;
            element.style.display = "block";
          }
          button.innerHTML = originalBtnText;
          button.disabled = false;
        })
        .catch(error => {
          document.getElementById('modal').style.display = "none";
          console.error('Error:', error);
          button.innerHTML = originalBtnText;
          button.disabled = false;
          alert("Terjadi kesalahan saat mengunggah file.");
        });
      }
    </script>

