<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *  Page untuk memvalidasi data berkas perserta
 */

require_level('Admin, Operator');
$tahun  = !empty($_POST['tahun'])  ? strip_tags($_POST['tahun'])   : NULL;
$prodi  = !empty($_POST['prodi'])  ? strip_tags($_POST['prodi'])   : NULL;
$berkas = !empty($_POST['status']) ? strip_tags($_POST['status'])  : NULL;
$npm    = !empty($_POST['npm'])    ? strip_tags($_POST['npm'])     : NULL;

if (!empty($npm)) { $prodi = NULL; $berkas = NULL; }

$dataAccess = clone $this->database('default', 'dbconfig', TRUE);
?>
<div class="validate-container">
  <?php if (isset($response) && $response != null) {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
  } ?>

  <div class="validate-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h1 class="card-title">Filter Peserta</h1>
    </div>
    <form class="filter-form" method="post" action="?page=validatepeserta&action=filterpeserta">
      <div class="form-row">
        <div class="form-group-modern col-md-5">
          <label for="prodi">Prodi</label>
          <select name="prodi" class="input-control">
            <?php
            if (!empty($npm)) echo '<option value="" selected hidden>Semua</option>';
            $allprodi = $dataAccess->reset()->distinct("PROGRAMSTUDI")->where("PROGRAMSTUDI IS NOT NULL")->where(["TAHUNDAFTAR"   => $config['CURENTYEAR']])->order("PROGRAMSTUDI")->result_array('datamahasiswa');
            if ($allprodi != FALSE && !empty($allprodi) && $allprodi[0]["PROGRAMSTUDI"] != NULL) {
              foreach ($allprodi as $key => $value) {
                if ($value["PROGRAMSTUDI"] == $prodi) {
                  echo '<option value="' . $value["PROGRAMSTUDI"] . '" selected>' . $value["PROGRAMSTUDI"] . '</option>';
                } else {
                  echo '<option value="' . $value["PROGRAMSTUDI"] . '">' . $value["PROGRAMSTUDI"] . '</option>';
                }
              }
            } else {
              echo '<option>Belum ada pendaftar</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group-modern col-md-3">
          <label for="status">Status Berkas</label>
          <select name="status" class="input-control">
            <option value="" default>Semua</option>
            <option value="Disetujui" <?php echo ($berkas == "Disetujui" ? "selected" : "") ?>>Disetujui</option>
            <option value="Ditolak" <?php echo ($berkas == "Ditolak" ? "selected" : "") ?>>Ditolak</option>
            <option value="Pengajuan" <?php echo ($berkas == "Pengajuan" ? "selected" : "") ?>>Pengajuan</option>
          </select>
        </div>
        <div class="form-group-modern col-md-3">
          <label for="npm">NPM</label>
          <input name="npm" value="<?php echo htmlspecialchars($npm); ?>" placeholder="Masukan NPM" class="input-control" type="text" />
        </div>
        <div class="form-group-modern col-md-2" style="justify-content: flex-end;">
          <button type="submit" value="Simpan" class="btn-save">Buka</button>
        </div>
      </div>
    </form>
  </div>

  <div class="validate-card">
    <div class="card-header">
      <h1 class="card-title">Daftar Mahasiswa</h1>
    </div>
    
      <?php
      $dataAccess->reset();
      $dataAccess->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY');
      
      $condition = [];
      if (!empty($npm)) {
          $condition["NPM"] = $npm;
      } else {
          $prodiValue = !empty($prodi) ? $prodi : (($allprodi != FALSE && !empty($allprodi) && $allprodi[0]["PROGRAMSTUDI"] != NULL) ? current($allprodi)["PROGRAMSTUDI"] : "*");
          if ($prodiValue != "*") {
              $condition["PROGRAMSTUDI"] = $prodiValue;
          }
      }
      
      $targetYear = isset($config['CURENTYEAR']) ? $config['CURENTYEAR'] : get_dbconfig('CURENTYEAR');
      if (!empty($targetYear)) {
          $condition["TAHUNDAFTAR"] = $targetYear;
      }

      $data = $dataAccess->where($condition)->result_array('datamahasiswa');
      if ($data !== FALSE && count($data) != 0) {
      ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th width="40px">No</th>
                <th width="250px">Nama</th>
                <th width="120px">NPM</th>
                <th width="200px">Program Studi</th>
                <th width="150px">Jenis Kelamin</th>
                <th width="150px">No Handphone</th>
                <th width="100px">Status</th>
                <th width="100px">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $n = 1;
            $berkasAccess = clone $this->database('default', 'dbconfig', TRUE);
            foreach ($data as $key => $r) {
              $result = $berkasAccess->reset()->where(["USRKEY" => $r["USRKEY"]])->result_row_array('statusberkas');
              if ($berkas !== NULL && $result["STATUSBERKAS"] != $berkas) continue;
              
              $statusTeks = ($result["STATUSBERKAS"] != FALSE ? $result["STATUSBERKAS"] : "Pengajuan");
              $badgeClass = 'badge-default';
              if ($statusTeks == 'Disetujui') $badgeClass = 'badge-success';
              else if ($statusTeks == 'Ditolak') $badgeClass = 'badge-danger';
              else if ($statusTeks == 'Pengajuan') $badgeClass = 'badge-warning';

              echo '
                  <tr>
                    <td>' . $n . '</td>
                    <td style="font-weight:600;">' . htmlspecialchars($r["NAMA"]) . '</td>
                    <td>' . htmlspecialchars($r["NPM"]) . '</td>
                    <td>' . htmlspecialchars($r["PROGRAMSTUDI"]) . '</td>
                    <td>' . htmlspecialchars($r["JENISKELAMIN"]) . '</td>
                    <td>' . htmlspecialchars($r["NOTELEPON"]) . '</td>
                    <td><span class="badge ' . $badgeClass . '">' . $statusTeks . '</span></td>
                    <td><button class="btn-action-view" onclick="validate(' . $r["USRKEY"] . ',\'' . str_replace("'", "\'", html_entity_decode(($r["NAMA"]), ENT_QUOTES | ENT_HTML5)) . '\',\'' . $r["NPM"] . '\')">Action</button></td>
                  </tr>';
              $n++;
            }
            ?>
            </tbody>
          </table>
        </div>
      <?php
      } else {
        echo '<div class="empty-state"><p>Data Pendaftar Tidak Ditemukan!</p></div>';
      }
      ?>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-centered" style="width: 500px;">
    <div class="content animate" style="background:#ffffff; border-radius:8px; overflow:hidden;">
      <div class="title" style="background:#f8f9fa; padding:15px 20px; border-bottom:1px solid #eaeaea; display:flex; justify-content:space-between; align-items:center;">
          <h1 style="margin:0; font-size:18px; color:#a805a8; font-weight:600;">Action Validation</h1>
          <a onclick="document.getElementById('modal').style.display='none'" class="btn-close" title="Close Modal" style="cursor:pointer; font-size:20px; color:#999; text-decoration:none;">&times;</a>
      </div>
      <div class="container" style="padding: 20px;">
          <div id="ajaxDiv"></div>
          <div style="margin-bottom:15px; font-size:14px; color:#555;">
              Berkas mahasiswa dengan data berikut:
              <div id="dta-mhs" style="background:#f3f4f6; padding:12px; border-radius:6px; margin-top:8px; font-weight:500;"></div>
          </div>
          <div style="font-size:14px; color:#555; margin-bottom:10px;">Akan ditandai dengan:</div>
          <form action="" method="post" enctype="multipart/form-data" id="res-lap-form">
            <input type="hidden" id="data-ID" name="idmahasiswa" value="aa">
            <input type="hidden" id="data-NPM" name="npmmahasiswa" value="aa">
            
            <div class="form-group">
              <label for="data-NOTE">Beri Catatan</label>
              <textarea id="data-NOTE" name="catatanberkas" placeholder="Tulis catatan jika ditolak atau arahan tambahan..."></textarea>
            </div>

            <div class="action-row">
              <button id="mhs-approve" type="submit" value="Simpan" class="btn-approve">Disetujui</button>
              <button id="mhs-reject" type="submit" value="Simpan" class="btn-reject">Ditolak</button>
              <button id="mhs-delete" type="submit" value="Simpan" class="btn-delete">Hapus</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function validate(id, nama, npm) {
    document.getElementById('modal').style.display = "block";
    document.getElementById('dta-mhs').innerHTML = "<div style='margin-bottom:6px;'><b style='color:#333; font-size:12px; text-transform:uppercase;'>Nama</b><br><span id='nama' style='font-size:15px; color:#a805a8;'>" + nama + "</span></div><div><b style='color:#333; font-size:12px; text-transform:uppercase;'>NPM</b><br><span id='npm'>" + npm + "</span></div>";
    document.getElementById('data-ID').value = id;
    document.getElementById('data-NPM').value = npm;
    document.getElementById('ajaxDiv').innerHTML = '';
  }

  function ajaxPOST(form, button, type) {
    let aj_data = new gcAjax(form, "index.php?ajax=berkas_validate");
    aj_data.addValue("status=" + type).setCallback(function(text, element) {
      let relodBtn = '<button class="btn-action-view" style="margin-top:10px; border:1px solid #4f46e5;" onClick="location.reload()">Perbaharui Daftar</button>';
      element.innerHTML = '<div style="padding:12px; background:#dcfce7; color:#166534; border-radius:6px; margin-bottom:15px;"><b>' + text + '</b><br>' + relodBtn + '</div>';
    }).send('ajaxDiv', button, '#a805a8');
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
