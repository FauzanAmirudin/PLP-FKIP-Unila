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
            <h1 class="card-title">Tambah User Baru</h1>
            <p class="card-subtitle">Daftarkan akun baru ke dalam sistem secara manual.</p>
        </div>

        <form class="form" method="post" action="<?= set_url('user/create_user') ?>" id="createUserForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="settings-form-group">
                    <label for="User">Username / NPM <span class="required">*</span></label>
                    <input <?= input_name('User'); ?> id="User" placeholder="Masukan Username atau NPM" type="text" required="required" />
                </div>
                
                <div class="settings-form-group">
                    <label for="Name">Nama Lengkap <span class="required">*</span></label>
                    <input <?= input_name('Name'); ?> id="Name" placeholder="Masukan Nama Lengkap" type="text" required="required" />
                </div>

                <div class="settings-form-group">
                    <label for="Password">Password <span class="required">*</span></label>
                    <input name="Password" id="Password" value="majuteruspltfkip" placeholder="Masukan Password" type="text" required="required" />
                </div>

                <div class="settings-form-group">
                    <label for="rePassword">Konfirmasi Password <span class="required">*</span></label>
                    <input name="rePassword" id="rePassword" value="majuteruspltfkip" placeholder="Masukan Kembali Password" type="text" required="required" />
                </div>

                <div class="settings-form-group" style="grid-column: 1 / -1;">
                    <label for="Type">Tipe Akun <span class="required">*</span></label>
                    <select name="Type" id="Type" required="required" style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fcfcfc;">
                        <?= select_option("Type", "Pilih Tipe Akun", FALSE) ?>
                        <?= select_option("Type", "Mahasiswa") ?>
                        <?= select_option("Type", "DPL") ?>
                        <?= select_option("Type", "Operator") ?>
                        <?= select_option("Type", "Monitor") ?>
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 25px;">
                <button type="submit" class="btn-save">Daftarkan Akun</button>
            </div>
        </form>
    </div>

    <div class="settings-card" style="margin-top: 30px;">
        <div class="card-header">
            <h1 class="card-title">Bulk Create User</h1>
            <p class="card-subtitle">Unggah berkas Excel untuk mendaftarkan banyak akun sekaligus.</p>
        </div>

        <form action="<?= set_url('api/upload/users') ?>" method="post" enctype="multipart/form-data" class="form">
            <div class="settings-form-group">
                <label>Pilih File Excel Users (.xlsx)</label>
                <div style="position: relative; border: 2px dashed #ddd; border-radius: 12px; padding: 30px; text-align: center; background: #fafafa; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#a805a8';" onmouseout="this.style.borderColor='#ddd';">
                    <input type="file" name="file" id="fileBulkUser" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2" style="margin-bottom: 10px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    <p style="margin: 0; font-size: 14px; color: #64748b;">Klik atau tarik file Excel users ke sini</p>
                    <span id="bulkFileNameDisplay" style="display: block; margin-top: 10px; font-weight: 600; color: #a805a8; font-size: 13px;"></span>
                </div>
                <span class="input-hint" style="color: #ef4444; margin-top: 10px;">* Hanya file .xlsx dengan ukuran maksimal 5 MB.</span>
            </div>

            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 25px;">
                <button type="submit" id="uploadUser" name="action" class="btn-save">Upload Users</button>
                <button type="reset" class="btn-save" style="background: #64748b; margin-left: 10px;" onclick="document.getElementById('bulkFileNameDisplay').innerText=''">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('fileBulkUser').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : '';
    document.getElementById('bulkFileNameDisplay').innerText = fileName ? "File terpilih: " + fileName : '';
});
</script>

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

