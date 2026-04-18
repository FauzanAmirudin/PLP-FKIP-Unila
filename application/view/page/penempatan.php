<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level('Mahasiswa');
?>
<content>
  <section id="mainContent">
    <style>
      @media print {

        @page {
          margin: 20mm;
          size: A4 portrait;
        }

        body {
          width: 240mm;
          height: 297mm;
        }

        content .content .header {
          font-size: 14pt;
          margin-top: 5mm;
          page-break-after: avoid;
        }

        content .content .field {
          page-break-after: avoid;
        }
      }

      .penempatan td {
        border: thin solid grey;
        margin: 0;
        padding: 0;
      }

      b {
        font-weight: bold;
      }

      .label {
        width: 150px;
        display: inline-block;
      }

      .text {
        display: inline-block;
      }
    </style>
    <div class="content">
      <div class="header">
        <a>PENEMPATAN</a>
      </div>
      <div class="field">
        <h1>Informasi Lokasi Peserta
          <span class="field-action action-right"></span>
        </h1>
        <div class="penempatan">
          <?php
          echo '
            <div class="label">Nama</div>
            <div class="text">: ' . (empty($placement["NAMA"]) ? "Tidak ada" : $placement["NAMA"]) . '</div><br>

            <div class="label">NPM</div>
            <div class="text">: ' . (empty($placement["NPM"]) ? "Tidak ada" : $placement["NPM"]) . '</div><br>

            <div class="label">Program Studi</div>
            <div class="text">: ' . (empty($placement["PROGRAMSTUDI"]) ? "Tidak ada" : $placement["PROGRAMSTUDI"]) . '</div><br>

            <div class="label">Kabupaten</div>
            <div class="text">: ' . (empty($placement["LOKASIKABUPATEN"]) ? "Tidak ada" : $placement["LOKASIKABUPATEN"]) . '</div><br>

            <div class="label">Kecamatan</div>
            <div class="text">: ' . (empty($placement["LOKASIKECAMATAN"]) ? "Tidak ada" : $placement["LOKASIKECAMATAN"]) . '</div><br>

            <div class="label">Desa</div>
            <div class="text">: ' . (empty($placement["LOKASIDESA"]) ? "Tidak ada" : $placement["LOKASIDESA"]) . '</div><br>

            <div class="label">Sekolah</div>
            <div class="text">: ' . (empty($placement["LOKASISEKOLAH"]) ? "Tidak ada" : $placement["LOKASISEKOLAH"]) . '</div><br>

            <div class="label">Dosen Pembimbing</div>
            <div class="text">: ' . (empty($placement["NAMADOSEN"]) ? "Tidak ada" : $placement["NAMADOSEN"]) . '</div><br>

            <div class="label">Contact DPL</div>
            <div class="text">: ' . (empty($placement["HANDPHPONEDOSEN"]) ? "Tidak ada" : $placement["HANDPHPONEDOSEN"]) . '</div><br>
          ';
          ?>
        </div>
      </div>

      <div class="field">
        <h1>Anggota Kelompok
          <span class="field-action action-right"></span>
        </h1>
        <div class="penempatan">
          <?php
          $n = 1;
          if (!empty($group)) {
          ?>
            <div class="table-view">
              <table>
                <tr class="thead">
                  <td width="25px"><b>No</b></td>
                  <td width="300px"><b>Nama</b></td>
                  <td width="100px"><b>NPM</b></td>
                  <td width="200px"><b>Program Studi</b></td>
                  <td width="250px"><b>Lokasi Sekolah</b></td>
                  <td width="15px"><b>No Handphone</b></td>
                </tr>
                <?php
                foreach ($group as $key => $row) {
                  echo '
                  <tr class="trow row-even">
                    <td>' . $n . '</td>
                    <td>' . $row["NAMA"] . '</td>
                    <td>' . $row["NPM"] . '</td>
                    <td>' . $row["PROGRAMSTUDI"] . '</td>
                    <td>' . $row["LOKASISEKOLAH"] . '</td>
                    <td>' . $row["NOTELEPON"] . '</td>
                  </tr>';
                  $n++;
                } ?>
              </table>
            </div>
          <?php
          } else {
            echo 'Anggota kelompok tidak tersedia.';
          }
          ?>
        </div>
      </div>
    </div>
  </section>
</content>

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
