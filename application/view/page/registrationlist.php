<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	  Page untuk memvalidasi data berkas perserta
 */
require_login();
require_level('Admin, Monitor');

$dataAccess = new gf_sql(GF_DB['default']);

?>
<content>
  <section id="mainContent">
    <style>
      @media print {

        @page {
          margin: 20mm;
          size: A4 landscape;
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

        #mainContent>div.content>div:nth-child(3),
        .header {
          display: none;
        }
      }
    </style>
    <link rel="stylesheet" href="<?= set_url('assets/css/chartist.min.css') ?>">
    <div class="content">
      <div class="header">
        <a>BERKAS DATA</a>
      </div>
      <div class="field">
        <h1>Data Peserta
          <span class="field-action action-right"> </span>
        </h1>
        <div class="content">
          <div style="overflow-x: scroll; overflow-y: hidden;">
            <div id="dataPendaftar" class="ct-chart" style="min-width: <?php print($barCount  * 120) ?>px; height:300px"></div>
          </div>
        </div>
      </div>
      <div class="field">
        <h1>Load Data
          <span class="field-action action-right"> </span>
        </h1>
        <div>
          <form class="form" method="get" action="<?= set_url('registration/data') ?>">
            <div class="form-group row">
              <div class="input-group col-md-1">
                <label for="tahun">Tahun<span class="required">*</span></label>
                <?php
                $input_option =  '<select name="tahun" type="text" require="required">';
                if (!empty($alltahun)) {
                  $alltahun = array_map(function ($a) {
                    return $a["TAHUNDAFTAR"] == NULL ? "Tidak Ada Data" : $a["TAHUNDAFTAR"];
                  }, $alltahun);
                  if (empty($tahun)) $tahun = current($alltahun);
                  foreach ($alltahun as $key => $TAHUNDAFTAR) {
                    if ($TAHUNDAFTAR == $tahun) {
                      $input_option .= '<option value="' . $TAHUNDAFTAR . '" selected>' . $TAHUNDAFTAR . '</option>';
                    } else {
                      $input_option .= '<option value="' . $TAHUNDAFTAR . '">' . $TAHUNDAFTAR . '</option>';
                    }
                  }
                } else {
                  $input_option .= '<option>Belum ada pendaftar</option>';
                }
                $input_option .=  '</select>';
                echo $input_option;
                ?>
              </div>
              <div class="input-group col-md-3">
                <label for="periode">Periode</label>
                <?php
                $input_option =  '<select name="periode" type="text" require="required">';
                if (!empty($allperiode)) {
                  $allperiode = array_map(function ($a) {
                    return $a["PERIODEDAFTAR"] == NULL ? "Tidak Ada Data" : $a["PERIODEDAFTAR"];
                  }, $allperiode);
                  if (empty($periode)) $periode = current($allperiode);
                  foreach ($allperiode as $key => $PERIODEDAFTAR) {
                    if ($PERIODEDAFTAR == $periode) {
                      $input_option .= '<option value="' . $PERIODEDAFTAR . '" selected>' . $PERIODEDAFTAR . '</option>';
                    } else {
                      $input_option .= '<option value="' . $PERIODEDAFTAR . '">' . $PERIODEDAFTAR . '</option>';
                    }
                  }
                } else {
                  $input_option .= '<option>Belum ada pendaftar</option>';
                }
                $input_option .=  '</select>';
                echo $input_option;
                ?>
              </div>
              <div class="input-group col-md-4">
                <label for="prodi">Prodi<span class="required">*</span></label>
                <?php
                $input_option = '<select name="prodi" type="text">';
                if (!empty($allprodi)) {
                  $allprodi = array_map(function ($a) {
                    return $a["PROGRAMSTUDI"] == NULL ? "Tidak Ada Data" : $a["PROGRAMSTUDI"];
                  }, $allprodi);
                  if (empty($prodi)) $prodi = current($allprodi);
                  foreach ($allprodi as $PROGRAMSTUDI) {
                    if ($PROGRAMSTUDI == $prodi) {
                      $input_option .= '<option value="' . $PROGRAMSTUDI . '" selected>' . $PROGRAMSTUDI . '</option>';
                    } else {
                      $input_option .= '<option value="' . $PROGRAMSTUDI . '">' . $PROGRAMSTUDI . '</option>';
                    }
                  }
                  if (!empty($npm)) echo '<option value="" selected>Semua</option>';
                } else {
                  $input_option .= '<option>Belum ada pendaftar</option>';
                }
                $input_option .= '</select>';
                echo $input_option;
                ?>
              </div>
              <div class="input-group col-md-3">
                <label for="NPM">NPM</label>
                <input name="npm" value="<?php echo $npm; ?>" placeholder="Masukan NPM bila perlu." type="text" />
              </div>
            </div>
            <div class="form-group action-right">
              <button type="submit" value="Simpan" class="btn btn-medium btn-ok">Buka</button>
            </div>
          </form>
        </div>
      </div>
      <div class="field">
        <h1>Daftar Mahasiswa Tahun <?= $tahun ?><?= isset($prodi) ? ", Program studi " . $prodi : "" ?>
          <span class="field-action action-right"> </span>
        </h1>
        <div class="penempatan">
          <?php
          if (isset($mahasiswa) && !empty($mahasiswa)) {
            $tabel =  "        <div class=\"table-view\">\n";
            $tabel .= "          <table>\n";
            $tabel .= "            <tr class=\"thead\">\n";
            $tabel .= "              <td width=\"25px\"><b>No</b></td>\n";
            $tabel .= "              <td width=\"300px\"><b>Nama</b></td>\n";
            $tabel .= "              <td width=\"100px\"><b>NPM</b></td>\n";
            $tabel .= "              <td width=\"200px\"><b>Program Studi</b></td>\n";
            $tabel .= "              <td width=\"250px\"><b>Jenis Kelamin</b></td>\n";
            $tabel .= "              <td width=\"15px\"><b>No Handphone</b></td>\n";
            $tabel .= "              <td width=\"15px\"><b>Status</b></td>\n";
            $tabel .= "              <td width=\"15px\"><b>Action</b></td>\n";
            $tabel .= "            </tr>\n";
            $n = 1;
            foreach ($mahasiswa as $key => $row) {
              $result = $dataAccess->reset()->tabel('datastatus')->where(["BRKSKEY" => $row["BERKASID"]])->result_row_array();
              // if ($berkas !== NULL && $result["STATUSBERKAS"] != $berkas) continue;
              $tabel .= "            <tr class=\"trow\">\n";
              $tabel .= "              <td>" . $n . "</td>\n";
              $tabel .= "              <td>" . $row["NAMA"] . "</td>\n";
              $tabel .= "              <td>" . $row["NPM"] . "</td>\n";
              $tabel .= "              <td>" . $row["PROGRAMSTUDI"] . "</td>\n";
              $tabel .= "              <td>" . $row["JENISKELAMIN"] . "</td>\n";
              $tabel .= "              <td>" . $row["NOTELEPON"] . "</td>\n";
              $tabel .= "              <td>" . ($result["STATUSBERKAS"] != FALSE ? $result["STATUSBERKAS"] : "Pengajuan") . "</td>\n";
              $tabel .= "              <td><a class=\"btn btn-small btn-download\" href=\"" . set_url('mahasiswa/data/' . $row['USRKEY']) . "\">Detail</a></td>\n";
              $tabel .= "            </tr>\n";
              $n++;
            }
            $tabel .= "          </table>\n";
            $tabel .= "        </div>\n";
          } else {
            $tabel = "<a>Data Tidak Ada!</a>";
          }
          echo "     <div class=\"penempatan\">" . $tabel . "     </div>\n";
          ?>
        </div>
      </div>

      <div id="modal" class="modal">
        <div class="modal-centered" style="width: 450px;">
          <div class="content animate">
            <div class="container">
              <div class="title">
                <h1>Detail
                  <span onclick="document.getElementById('modal').style.display='none'" class="btn btn-tiny btn-alert" title="Close Modal" style="float:right;">&times;</span>
                </h1>
              </div>
              <div class="field">
                Veiw
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="<?= set_url('assets/js/chartist.min.js') ?>" type="text/javascript"></script>
      <script src="<?= set_url('assets/js/chartist.bar.labels.js') ?>" type="text/javascript"></script>
      <script language="javascript" type="text/javascript">
        var data = <?php echo json_encode($statistic) ?>;
        var options = {
          distributeSeries: true,
          axisX: {
            offset: 60
          },
          axisY: {
            offset: 80,
            labelInterpolationFnc: function(value) {
              return value + ' Orang'
            },
            scaleMinSpace: 10
          },
          plugins: [
            Chartist.plugins.ctBarLabels({
              labelOffset: {
                y: 7
              },
              labelInterpolationFnc: function(text) {
                return text
              }
            })
          ]
        };
        new Chartist.Bar('#dataPendaftar', data, options).on('draw', function(data) {
          if (data.type === 'bar') {
            data.element.attr({
              style: 'stroke-width: 60px'
            });
          }
        });
      </script>
  </section>
</content>