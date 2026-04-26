<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *  Page untuk memvalidasi data berkas perserta
 */
require_login();
require_level('Admin, Operator');
$tahun  = !empty($_GET['tahun'])  ? strip_tags($_GET['tahun'])   : NULL;
$prodi  = !empty($_GET['prodi'])  ? strip_tags($_GET['prodi'])   : NULL;
$berkas = !empty($_GET['status']) ? strip_tags($_GET['status'])  : NULL;
$npm    = !empty($_GET['npm'])    ? strip_tags($_GET['npm'])     : NULL;

if (!empty($npm)) {
  $prodi = NULL;
  $berkas = NULL;
}

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
          $dataAccess->reset(FALSE);
          // $dataAccess->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY');
          // $dataAccess->column('datamahasiswa.USRKEY, NAMA, datamahasiswa.NPM, PROGRAMSTUDI, JENISKELAMIN, NOTELEPON, databerkas.STATUSBERKAS');
          if (!empty($npm)) $condition["NPM"] = $npm;
          else $condition["PROGRAMSTUDI"] = !empty($prodi) ? $prodi : (($allprodi != FALSE && !empty($allprodi) && $allprodi[0]["PROGRAMSTUDI"] != NULL) ? current($allprodi)["PROGRAMSTUDI"] : "*");
          $condition["databerkas`.`TAHUNDAFTAR"] = get_dbconfig('CURENTYEAR');
          $condition["databerkas`.`PERIODEDAFTAR"] = get_dbconfig('CURENTSEMESTER');
          $data = $dataAccess->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`', 'INNER')->order("`databerkas`.`DATEVALID`", 'DESC')->where($condition)->result_array();
          // var_dump($dataAccess->last_query);
          if (!empty($data)) {
            $n = 1;
            $last_row['USRKEY'] = 0;;
            ob_start();
            foreach ($data as $key => $row) {
              if ($last_row['USRKEY'] == $row['USRKEY']) continue;
              $last_row = $row;
              $result = $dataAccess->reset()->tabel('databerkas')->where(["USRKEY" => $row["USRKEY"]])->result_row_array();
              if ($berkas !== NULL && $result["STATUSBERKAS"] != $berkas) continue;
              echo '
                    <tr class="trow">
                      <td>' . $n . '</td>
                      <td>' . $row["NAMA"] . '</td>
                      <td>' . $row["NPM"] . '</td>
                      <td>' . $row["PROGRAMSTUDI"] . '</td>
                      <td>' . $row["JENISKELAMIN"] . '</td>
                      <td>' . $row["NOTELEPON"] . '</td>
                      <td>' . ($result["STATUSBERKAS"] != FALSE ? $result["STATUSBERKAS"] : "Pengajuan") . '</td>
                      <td><button class="btn btn-small btn-download" onclick="validate(' . $row["USRKEY"] . ',\'' . str_replace("'", "\'", html_entity_decode(($row["NAMA"]), ENT_QUOTES | ENT_HTML5)) . '\',\'' . $row["NPM"] . '\',\'' . set_url($result["BERKASDAFTAR"]) . '\')">Action</button></td>
                    </tr>';
              $n++;
            }
            $tabel = ob_get_clean();
            if (!empty($tabel)) { ?>
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
                  <?php echo $tabel; ?>
                  <!-- <tr class="trow">
                    <td>1</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                    <td>Tidak Ada</td>
                  </tr>'; -->
                </table>
              </div>
            <?php
            } else {
              echo "<a>Data Tidak Ada!</a>";
            }
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
              <form class="form" action="" method="post" enctype="multipart/form-data" id="res-lap-form">
                <input type="hidden" id="data-ID" name="idmahasiswa" value="aa">
                <input type="hidden" id="data-NPM" name="npmmahasiswa" value="aa">
                <div class="form-group action-right">
                  <a id="data-URL" type="submit" value="Simpan" target="blank" class="btn btn-medium btn-ok">Download</a>
                  <button id="mhs-approve" type="submit" value="Simpan" class="btn btn-medium btn-ok">Disetujui</button>
                  <button id="mhs-reject" type="submit" value="Simpan" class="btn btn-medium btn-cancel">Ditolak</button>
                  <button id="mhs-delete" type="submit" value="Simpan" class="btn btn-medium btn-alert">Hapus</button>
                </div>
                <div class="form-group">
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
      function validate(id, nama, npm, url) {
        document.getElementById('modal').style.display = "block";
        document.getElementById('dta-mhs').innerHTML = "<b>Nama</b><br><a id=\"nama\">" + nama + "</a><br><b>NPM</b><br><a id=\"npm\">" + npm + "</a>";
        document.getElementById('data-ID').value = id;
        document.getElementById('data-NPM').value = npm;
        document.getElementById('data-URL').href = url;
      }

      function ajaxPOST(form, button, type) {
        let aj_data = new gcAjax(form, "<?= set_url('api/registration/validatioon') ?>");
        aj_data.addValue("status=" + type).setCallback(function(text, element) {
          let relodBtn = '<button class="btn btn-ok" onClick="location.reload()">Perbaharui Daftar</button></div>';
          element.innerHTML = '<div class="info info-danger"><a>' + text + '</a>';
        }).send('ajaxDiv', button, '#6424D9');
      }
      document.querySelector("#mhs-approve").addEventListener("click", function(event) {
        event.preventDefault();
        ajaxPOST(this.form, this, 'approved');
      }, false);
      document.querySelector("#mhs-reject").addEventListener("click", function(event) {
        event.preventDefault();
        ajaxPOST(this.form, this, 'rejected');
      }, false);
      document.querySelector("#mhs-delete").addEventListener("click", function(event) {
        event.preventDefault();
        ajaxPOST(this.form, this, 'delete');
      }, false);
    </script>
  </section>
</content>
