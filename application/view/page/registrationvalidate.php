<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *  Page untuk memvalidasi data berkas perserta
 */
require_login();
require_level('Admin, Operator');
$dataAccess = new gf_sql(GF_DB['default']);
$dataAccess->tabel('datamahasiswa');
?>
<content>
  <section id="mainContent">
    <div class="content">
      <div class="header">
        <a>VALIDASI DATA</a>
      </div>
      <?php if (isset($notification) && $notification != null) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Load Data
          <span class="field-action action-right"> </span>
        </h1>
        <div>
          <form class="form" method="get" action="<?= set_url('registration/validate') ?>">
            <div class="form-group row">
              <div class="input-group col-md-6">
                <label for="prodi">Prodi<span class="required">*</span></label>
                <?php
                if (!empty($npm)) echo '<option value="" selected hidden>Semua</option>';
                $input_select = ' <select name="prodi" type="text">';
                if ($allprodi != FALSE && !empty($allprodi) && $allprodi[0]["PROGRAMSTUDI"] != NULL) {
                  foreach ($allprodi as $key => $value) {
                    if ($value["PROGRAMSTUDI"] == $prodi) {
                      $input_select .= '<option value="' . $value["PROGRAMSTUDI"] . '" selected>' . $value["PROGRAMSTUDI"] . '</option>';
                    } else {
                      $input_select .= '<option value="' . $value["PROGRAMSTUDI"] . '">' . $value["PROGRAMSTUDI"] . '</option>';
                    }
                  }
                } else {
                  $input_select .=  '<option>Belum ada pendaftar</option>';
                }
                $input_select .= '</select>';
                echo $input_select;
                ?>
              </div>
              <div class="input-group col-md-3">
                <label for="status">Status Berkas</label>
                <select name="status" type="text">
                  <option value="" default>Semua</option>
                  <option value="Disetujui" <?php echo ($berkas == "Disetujui" ? "selected" : "") ?>>Disetuji</option>
                  <option value="Ditolak" <?php echo ($berkas == "Ditolak" ? "selected" : "") ?>>Ditolak</option>
                  <option value="Pengajuan" <?php echo ($berkas == "Pengajuan" ? "selected" : "") ?>>Pengajuan</option>
                </select>
              </div>
              <div class="input-group col-md-3">
                <label for="NPM">NPM</label>
                <input name="npm" value="<?php echo $npm; ?>" placeholder="Masukan NPM bila perlu." type="text" />
              </div>
            </div>
            <div class="form-group action-right">
              <button type="submit" class="btn btn-medium btn-ok">Buka</button>
            </div>
          </form>
        </div>
      </div>
      <div class="field">
        <h1>Daftar Mahasiswa
          <span class="field-action action-right"> </span>
        </h1>
        <div class="penempatan">
          <?php
          $data = $registration_list;
          if ($data !== FALSE && count($data) != 0) {
          ?>
            <div class="table-view">
              <table>
                <tr class="thead">
                  <td width="25px"><b>No</b></td>
                  <td width="300px"><b>Nama</b></td>
                  <td width="100px"><b>NPM</b></td>
                  <td width="200px"><b>Program Studi</b></td>
                  <td width="250px"><b>Jenis Kelamin</b></td>
                  <td width="15px"><b>No Handphone</b></td>
                  <td width="15px"><b>Status</b></td>
                  <td width="15px"><b>Action</b></td>
                </tr>
                <?php
                $n = 1;
                $search = array(
                  chr(145),
                  chr(146),
                  chr(147),
                  chr(148),
                  chr(151)
                );

                $replace = array(
                  "'",
                  "'",
                  '"',
                  '"',
                  '-'
                );
                foreach ($data as $st_berkas) {
                  echo '
                  <tr class="trow">
                    <td>' . $n . '</td>
                    <td>' . html_entity_decode($st_berkas["NAMA"], ENT_QUOTES | ENT_HTML5) . '</td>
                    <td>' . $st_berkas["NPM"] . '</td>
                    <td>' . $st_berkas["PROGRAMSTUDI"] . '</td>
                    <td>' . $st_berkas["JENISKELAMIN"] . '</td>
                    <td>' . $st_berkas["NOTELEPON"] . '</td>
                    <td>' . ($st_berkas["STATUSBERKAS"] != FALSE ? $st_berkas["STATUSBERKAS"] : "Pengajuan") . '</td>
                    <td><button class="btn btn-small btn-download" onclick="validate(' . $st_berkas["ID"] . ', \'' . str_replace("&#39;", "\\'", html_entity_decode($st_berkas["NAMA"], ENT_QUOTES | ENT_HTML5)) . '\',\'' . $st_berkas["NPM"] . '\',' . (file_exists($st_berkas["BERKASDAFTAR"]) ? '\'' . set_url($st_berkas["BERKASDAFTAR"]) . '\'' : "null") . ')">Action</button></td>
                  </tr>';
                  $n++;
                }
                /* echo '
              <tr class="row row-even">
                <td>1</td>
                <td>Tidak Ada</td>
                <td>Tidak Ada</td>
                <td>Tidak Ada</td>
                <td>Tidak Ada</td>
                <td>Tidak Ada</td>
                <td></td>
                </tr>'; */
                ?>
              </table>
            </div>
          <?php
          } else {
            echo "<a>Data Tidak Ada!</a>";
          }
          ?>
        </div>
      </div>
    </div>

    <div id="modal" class="modal">
      <div class="modal-centered" style="width: 450px;">
        <div class="content animate">
          <div class="container">
            <div class="title title__color">
              <h1>Action
                <span class="action-right">
                  <a onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-danger btn-close" title="Close Modal" style="float: right;"></a>
                </span>
              </h1>
            </div>
            <div class="field">
              <div id="ajaxDiv"></div>
              <a>Berkas mahasiswa dengan data berikut:</a>
              <div id="dta-mhs" class="banner"></div>
              <a>akan ditandai dengan</a>
              <form class="form" action="" method="post" enctype="multipart/form-data" id="ajax-form">
                <input type="hidden" id="data-ID" name="idmahasiswa" value="">
                <input type="hidden" id="data-NPM" name="npmmahasiswa" value="">
                <div id="form-action" class="form-group action-right"></div>
                <div id="note" class="form-group">
                  <label for="catatanberkas">Beri Catatan</label>
                  <textarea id="data-NOTE" name="catatanberkas"></textarea>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function ajaxPOST(form, button, type) {
        let aj_data = new gcAjax(form, "<?= set_url('api/registration/validatioon') ?>");
        aj_data.addValue("status=" + type).setCallback(function(text, element) {
          let relodBtn = '<button class="btn btn-ok" onClick="location.reload()">Perbaharui Daftar</button></div>';
          element.innerHTML = '<div class="info info-danger"><a>' + text + '</a>';
        }).send('ajaxDiv', button, '#6424D9');
      }

      function validate(id, nama, npm, url) {
        document.getElementById('modal').style.display = "block";
        document.getElementById('dta-mhs').innerHTML = "<b>Nama</b><br><a id=\"nama\">" + nama + "</a><br><b>NPM</b><br><a id=\"npm\">" + npm + "</a>";
        document.getElementById('data-ID').value = id;
        document.getElementById('data-NPM').value = npm;
        var form = document.getElementById('ajax-form');
        var action = form.querySelector('#form-action');
        action.innerHTML = '';

        var btnDownload = document.createElement('a');
        btnDownload.id = 'data-URL';
        btnDownload.text = 'Download';
        btnDownload.style.marginRight = '12px';

        if (url != null) {
          btnDownload.classList.add("btn-ok");
          btnDownload.className = "btn btn-medium btn-ok";
          btnDownload.target = '_blank';
          btnDownload.href = url;
        } else {
          btnDownload.disabled = true;
          btnDownload.className = "btn btn-medium btn-disable";
        }
        action.appendChild(btnDownload);

        var btnApprove = document.createElement('button');
        btnApprove.type = "submit";
        btnApprove.className = "btn btn-medium btn-ok";
        btnApprove.innerHTML = "Diterima";
        action.appendChild(btnApprove);
        btnApprove.addEventListener("click", function(event) {
          event.preventDefault();
          console.log(event.target.form);
          console.log(this.form);
          ajaxPOST(this.form, this, 'approved');
        }, false);

        var btnReject = document.createElement('button');
        btnReject.type = "submit";
        btnReject.className = "btn btn-medium btn-cancel";
        btnReject.innerHTML = "Ditolak";
        action.appendChild(btnReject);
        btnReject.addEventListener("click", function(event) {
          event.preventDefault();
          ajaxPOST(this.form, this, 'rejected');
        }, false);

        var btnDelete = document.createElement('button');
        btnDelete.type = "submit";
        btnDelete.className = "btn btn-medium btn-alert";
        btnDelete.innerHTML = "Hapus";
        action.appendChild(btnDelete);
        btnDelete.addEventListener("click", function(event) {
          event.preventDefault();
          ajaxPOST(this.form, this, 'delete');
        }, false);


        let aj_data = new gcAjax('post', "<?= set_url('api/registration/comment?berkas=') ?>" + id)
          .setCallback(function(text, element) {
            if (text == '') {
              text = 'Tidak Ada.'
            }
            console.log(text);
            element.innerHTML = text;
          }).send('data-NOTE');
      }
    </script>
  </section>
</content>