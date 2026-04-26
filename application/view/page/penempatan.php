<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

require_level('Mahasiswa');
?>
<div class="placement-container">
  
  <div class="placement-card placement-info-card">
    <h1 class="card-title">Informasi Lokasi Peserta</h1>
    
    <div class="info-grid">
      <?php
      $dataMahasiswaAccess = clone $this->database('default', 'dbconfig', TRUE);
      $dataMahasiswaAccess->join('datapenempatan', 'datamahasiswa.NPM = datapenempatan.NPMPESERTA');
      $dataMahasiswaAccess->join('dosen', 'datapenempatan.NIPDPL = dosen.NIPDOSEN');
      $dataMahasiswa = $dataMahasiswaAccess->reset()->where(["NPM" => session_get('USERID')])->result_row_array('datamahasiswa');
      
      $lokasiDesa = isset($dataMahasiswa["LOKASIDESA"]) ? $dataMahasiswa["LOKASIDESA"] : "";
      
      $fieldsMapping = [
        "Nama" => isset($dataMahasiswa["NAMA"]) ? $dataMahasiswa["NAMA"] : "-",
        "NPM" => isset($dataMahasiswa["NPM"]) ? $dataMahasiswa["NPM"] : "-",
        "Program Studi" => isset($dataMahasiswa["PROGRAMSTUDI"]) ? $dataMahasiswa["PROGRAMSTUDI"] : "-",
        "Kabupaten" => isset($dataMahasiswa["LOKASIKABUPATEN"]) ? $dataMahasiswa["LOKASIKABUPATEN"] : "Belum ditempatkan",
        "Kecamatan" => isset($dataMahasiswa["LOKASIKECAMATAN"]) ? $dataMahasiswa["LOKASIKECAMATAN"] : "Belum ditempatkan",
        "Desa" => $lokasiDesa !== "" ? $lokasiDesa : "Belum ditempatkan",
        "Sekolah" => isset($dataMahasiswa["LOKASISEKOLAH"]) ? $dataMahasiswa["LOKASISEKOLAH"] : "Belum ditempatkan",
        "Dosen Pembimbing" => isset($dataMahasiswa["NAMADOSEN"]) ? $dataMahasiswa["NAMADOSEN"] : "Belum ditentukan",
        "Contact DPL" => isset($dataMahasiswa["HANDPHPONEDOSEN"]) ? $dataMahasiswa["HANDPHPONEDOSEN"] : "Belum ditentukan"
      ];

      foreach ($fieldsMapping as $label => $val) { ?>
         <div class="info-group">
            <span class="info-label"><?= $label ?></span>
            <span class="info-value"><?= htmlspecialchars($val) ?></span>
         </div>
      <?php } ?>
    </div>
  </div>

  <div class="placement-card placement-team-card">
    <h1 class="card-title">Anggota Kelompok</h1>
    
    <?php
    $dataMahasiswaTeam = (!empty($lokasiDesa)) ? $dataMahasiswaAccess->reset()->where("`LOKASIDESA` = '" . $lokasiDesa . "'")->order('`LOKASISEKOLAH` ASC, `NPM` ASC')->result_array('datamahasiswa') : FALSE;
    
    if ($dataMahasiswaTeam != FALSE && !empty($dataMahasiswaTeam)) {
    ?>
      <div class="table-responsive">
        <table class="modern-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th style="width: 250px;">Nama</th>
              <th style="width: 120px;">NPM</th>
              <th style="width: 180px;">Program Studi</th>
              <th style="width: 300px;">Lokasi Sekolah</th>
              <th style="width: 150px;">No Handphone</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $n = 1;
          foreach ($dataMahasiswaTeam as $key => $r) { ?>
              <tr>
                <td><?= $n ?></td>
                <td><?= htmlspecialchars($r["NAMA"]) ?></td>
                <td><?= htmlspecialchars($r["NPM"]) ?></td>
                <td><?= htmlspecialchars($r["PROGRAMSTUDI"]) ?></td>
                <td><?= htmlspecialchars($r["LOKASISEKOLAH"]) ?></td>
                <td><?= htmlspecialchars($r["NOTELEPON"]) ?></td>
              </tr>
          <?php $n++; } ?>
          </tbody>
        </table>
      </div>
    <?php
    } else {
        echo '<div style="text-align:center; padding: 20px; color:#888;">Anggota kelompok tidak tersedia.</div>';
    }
    ?>
  </div>
</div>
<?php /*
<button style="position: absolute; top: 0px; left: 0; z-index: 200; background: rgba(1, 1, 1, 0.44); color: white; padding: 3px; padding-left: 8px; padding-right: 8px; font-size: 12pt; font-style: normal; font-weight: lighter; cursor: pointer; border: none;" onclick="document.getElementById('upload-foto').style.display='block'" >edit</button>'

<div id="upload-foto" class="modal">
  <div class="modal-content centered">
  <form action="" method="post" enctype="multipart/form-data" id="form1" class="content animate">
    <div class="field" style="margin: 0 0 0px;">
      <h1>Lokasi Penempatan<span onclick="document.getElementById('upload-foto').style.display='none'" class="btn btn-small btnCancel" title="Close Modal">&times;</span></h1>
      <div class="form-Field">

        <form class="content animate" action="" method = "post">
           <div id="stripHeader">
              <a><stong></stong></a>
            </div>
                <div class="container">
                  <label for="kabupaten">Kabupaten<span class="required">*</span></label>
                  <div class="dot">:</div>
                    <div class="col-sm-1" style="">
                      <select id="kabupaten" name="kabupaten" required>
                      <option value="" hidden>Pilih Kabupaten</option>
                      <?php
                      $GetLokasi = mysqli_query($db, "SELECT `KABUPATEN` FROM `lokasi-Kabupaten` ORDER BY `KABUPATEN` ASC");
                      while($lokasiKabupaten = mysqli_fetch_array($GetLokasi)){;
                        $key = 0;
                        if (isset($lokasiKabupaten[$key])) {
                          echo '<option value="'.$lokasiKabupaten[$key].'">'.$lokasiKabupaten[$key].'</option>';
                        }
                      }
                      ?>
                      </select>
                  </div>
                </div>
                <div class="container">
                  <label for="kecamatan">Kecamatan<span class="required">*</span></label>
                  <div class="dot">:</div>
                    <div class="col-sm-1" style="">
                      <select id="kecamatan" name="kecamatan" required>
                      <option value="" hidden>Pilih Kecamatan</option>
                      <?php
                      $GetLokasi = mysqli_query($db, "SELECT `KECAMATAN` FROM `lokasi-".$Lokasi[0]."-kecamatan` ORDER BY `KECAMATAN` ASC");
                      while($lokasiKabupaten = mysqli_fetch_array($GetLokasi)){;
                        $key = 0;
                        if (isset($lokasiKabupaten[$key])) {
                          echo '<option value="'.$lokasiKabupaten[$key].'">'.$lokasiKabupaten[$key].'</option>';
                        }    
                      }
                      ?>
                      </select>
                  </div>
                </div>
                <div class="container">
                  <label for="desa">Desa<span class="required">*</span></label>
                  <div class="dot">:</div>
                    <div class="col-sm-1" style="">
                      <select id="desa" name="desa" required>
                      <option value="" hidden>Pilih Desa</option>
                      <?php
                      $GetLokasi = mysqli_query($db, "SELECT `DESA` FROM `lokasi-".$Lokasi[0]."-desa` ORDER BY `DESA` ASC");
                      while($lokasiKabupaten = mysqli_fetch_array($GetLokasi)){;
                        $key = 0;
                        if (isset($lokasiKabupaten[$key])) {
                          echo '<option value="'.$lokasiKabupaten[$key].'">'.$lokasiKabupaten[$key].'</option>';
                        }
                      }
                      ?>
                      </select>
                  </div>
                </div>
            <div class="container" style="text-align:right; color: black;" >
              <button type="submit" name="action" value = "updatePenempatan" class="btn btn-medium purple"/>Kirim</button><br />
            </div>
          </form>

      </div>
    </div>
  </form>
  </div>
  </div>
*/
