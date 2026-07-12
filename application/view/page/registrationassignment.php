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

        <form action="<?= set_url('api/upload/assignment') ?>" method="post" enctype="multipart/form-data" id="assignmentForm" class="form">
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

<script>
document.getElementById('fileAssignment').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : '';
    document.getElementById('fileNameDisplay').innerText = fileName ? "File terpilih: " + fileName : '';
});
</script>

    <script type="text/javascript">
      let form = document.getElementById("assignmentForm");
      form.addEventListener("submit", function(event) {
        event.preventDefault();
        let btnSubmit = document.getElementById("uploadUser");
        ajaxPOST(this, btnSubmit, false);
      }, false);

      function ajaxPOST(form, button, force) {
        let formData = new FormData(form);
        formData.append("force", force);
        
        let originalBtnText = button.innerHTML;
        button.innerHTML = 'Uploading...';
        button.disabled = true;

        fetch("<?= set_url('api/upload/assignment') ?>", {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(text => {
          try {
            if (text != null) {
              let jsonStart = text.indexOf('{');
              let jsonEnd = text.lastIndexOf('}');
              if (jsonStart !== -1 && jsonEnd !== -1) {
                let jsonStr = text.substring(jsonStart, jsonEnd + 1);
                var response = JSON.parse(jsonStr);
                
                Swal.fire({
                  title: response.status ? 'Sukses!' : 'Terjadi Kesalahan',
                  icon: response.status ? 'success' : 'error',
                  html: response.data || response.messege,
                  confirmButtonColor: '#a805a8',
                  confirmButtonText: 'Tutup'
                });
              } else {
                Swal.fire('Error', 'Format respons tidak valid.', 'error');
              }
            }
          } catch (error) {
            console.error("JSON Parse Error: ", error);
            Swal.fire('Error', 'Terjadi kesalahan saat memproses data.', 'error');
          }
          button.innerHTML = originalBtnText;
          button.disabled = false;
        })
        .catch(error => {
          console.error('Error:', error);
          button.innerHTML = originalBtnText;
          button.disabled = false;
          Swal.fire('Error', 'Terjadi kesalahan saat mengunggah file.', 'error');
        });
      }
    </script>

