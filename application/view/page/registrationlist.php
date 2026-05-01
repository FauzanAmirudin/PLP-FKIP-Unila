<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	  Page untuk memvalidasi data berkas perserta
 */

require_level('Admin, Monitor, Operator');
/* Use data from controller as primary source */
$tahun  = isset($tahun) ? $tahun : NULL;
$npm    = isset($npm) ? $npm : NULL;
$prodi  = isset($prodi) ? $prodi : NULL;
$berkas = isset($berkas) ? $berkas : NULL;
$dataAccess = clone $this->database('default', 'dbconfig', TRUE);
$data = $dataAccess->reset()->column("TAHUNDAFTAR, COUNT(TAHUNDAFTAR) AS JUMLAHPESERTA", FALSE)->group("TAHUNDAFTAR")->result_array('databerkas');
$label  = "";
$series = "";
$bar = 0;
foreach ($data as $n => $d) {
  if ($n == 0) continue;
  if ($n > 1) {
    $label .= ", ";
    $series .= ", ";
  }
  $label  .= "'" . $d["TAHUNDAFTAR"] . "'";
  $series .= $d["JUMLAHPESERTA"];
  $bar++;
}
?>
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
<link rel="stylesheet" href="./assets/css/chartist.min.css">
<style>
  .ct-series-a .ct-bar {
    stroke: #a805a8 !important;
    stroke-linecap: round;
  }
  .ct-label {
    font-size: 12px;
    font-weight: 500;
    fill: #64748b;
    color: #64748b;
  }
  .filter-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    align-items: end;
    margin-top: 20px;
  }
  .filter-form-grid .input-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 100%;
  }
  .filter-form-grid select, .filter-form-grid input {
    width: 100%;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
    background: #fff;
    color: #334155;
    box-sizing: border-box;
  }
  .filter-form-grid label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
  }
</style>
<div class="schedule-container">
  
  <div class="schedule-card" style="margin-bottom: 25px;">
    <div class="card-header">
      <h1 class="card-title">Statistik Pendaftar</h1>
      <p class="card-subtitle">Pantau grafik perbandingan jumlah pendaftar setiap tahunnya.</p>
    </div>
    
    <div style="margin-top: 20px;">
      <div style="overflow-x: auto; overflow-y: hidden; padding-bottom: 10px;">
        <div id="dataPendaftar" class="ct-chart" style="min-width: <?php print($bar  * 120) ?>px; height:300px"></div>
      </div>
    </div>
  </div>

  <div class="schedule-card" style="margin-bottom: 25px;">
    <div class="card-header">
      <h1 class="card-title">Pencarian & Filter Data</h1>
      <p class="card-subtitle">Gunakan filter di bawah ini untuk menampilkan spesifik data peserta.</p>
    </div>

    <div style="margin-top: 20px;">
      <form class="form" method="get" action="">
        <input type="hidden" name="page" value="registration/data">
        <div class="filter-form-grid">
          <div class="input-group">
            <label for="tahun">Tahun<span class="required" style="color: #ef4444; margin-left: 2px;">*</span></label>
            <select name="tahun" require="required">
              <?php
              $alltahun = $dataAccess->reset()->distinct("TAHUNDAFTAR")->order("TAHUNDAFTAR", "DESC")->result_array('databerkas');
              if ($alltahun != FALSE || $alltahun[1]["TAHUNDAFTAR"] != NULL) {
                $alltahun = array_map(function ($a) {
                  return $a["TAHUNDAFTAR"];
                }, $alltahun);
                foreach ($alltahun as $key => $TAHUNDAFTAR_VAL) {
                  $isSelected = ($TAHUNDAFTAR_VAL == $tahun) ? 'selected' : '';
                  echo '<option value="' . $TAHUNDAFTAR_VAL . '" ' . $isSelected . '>' . $TAHUNDAFTAR_VAL . '</option>';
                }
              } else {
                echo '<option>Belum ada pendaftar</option>';
              }
              ?>
            </select>
          </div>
          
          <div class="input-group">
            <label for="prodi">Prodi<span class="required" style="color: #ef4444; margin-left: 2px;">*</span></label>
            <select name="prodi">
              <?php
              $dataAccess->reset();
              $dataAccess->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY');
              if (!empty($tahun)) $dataAccess->where(["TAHUNDAFTAR" => $tahun]);
              $allprodi = $dataAccess->distinct("PROGRAMSTUDI")->order("PROGRAMSTUDI")->result_array('datamahasiswa');
              if ($allprodi != FALSE  || $allprodi[1]["PROGRAMSTUDI"] != NULL) {
                $allprodi = array_map(function ($a) {
                  return $a["PROGRAMSTUDI"];
                }, $allprodi);
                echo '<option value="" ' . (empty($prodi) ? 'selected' : '') . '>Semua Program Studi</option>';
                foreach ($allprodi as $PROGRAMSTUDI_VAL) {
                  $isSelected = ($PROGRAMSTUDI_VAL == $prodi) ? 'selected' : '';
                  echo '<option value="' . $PROGRAMSTUDI_VAL . '" ' . $isSelected . '>' . $PROGRAMSTUDI_VAL . '</option>';
                }
              } else {
                echo '<option>Belum ada pendaftar</option>';
              }
              ?>
            </select>
          </div>
          
          <div class="input-group">
            <label for="status">Status Berkas</label>
            <select name="status">
              <option value="" default>Semua</option>
              <option value="Disetujui" <?php echo ($berkas == "Disetujui" ? "selected" : "") ?>>Disetujui</option>
              <option value="Ditolak" <?php echo ($berkas == "Ditolak" ? "selected" : "") ?>>Ditolak</option>
            </select>
          </div>
          
          <div class="input-group">
            <label for="NPM">NPM / Keyword</label>
            <input name="npm" value="<?php echo htmlspecialchars($npm); ?>" placeholder="Masukkan NPM..." type="text" />
          </div>
          
          <div class="input-group" style="align-items: flex-start; justify-content: flex-end;">
            <button type="submit" value="Simpan" class="btn btn-medium btn-ok" style="width: 100%; height: 38px; display: flex; align-items: center; justify-content: center; background: #a805a8; border: none; border-radius: 6px; color: white; font-weight: 600; cursor: pointer;">Tampilkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php
  /* Use data prepared by controller */
  $data = isset($mahasiswa) ? $mahasiswa : [];
  $TAHUNDAFTAR = !empty($tahun) ? $tahun : "Seluruh Tahun";
  $PROGRAMSTUDI = !empty($prodi) ? $prodi : "Seluruh Program Studi";
  ?>
  <div class="schedule-card">
    <div class="card-header">
      <h1 class="card-title">Daftar Mahasiswa Tahun <?= $TAHUNDAFTAR ?><?= isset($PROGRAMSTUDI) && $PROGRAMSTUDI !== '*' ? ", Program studi " . htmlspecialchars($PROGRAMSTUDI) : "" ?></h1>
      <p class="card-subtitle">Berikut adalah data pendaftar yang sesuai dengan kriteria yang dipilih.</p>
    </div>
    
    <div style="margin-top: 20px;">
      <?php
      if ($data !== FALSE && count($data) != 0) { ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th width="60px" style="text-align: center;">No</th>
                <th>Mahasiswa</th>
                <th>Program Studi</th>
                <th>Kontak</th>
                <th width="120px" style="text-align: center;">Status</th>
                <th width="100px" style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $n = 1;
              $berkasAccess = clone $this->database('default', 'dbconfig', TRUE);
              foreach ($data as $key => $r) {
                /* Menggunakan status yang sudah diambil secara akurat oleh Controller melalui Model */
                $statusBadge = (isset($r["STATUSBERKAS"]) && $r["STATUSBERKAS"] != FALSE) ? $r["STATUSBERKAS"] : "Pengajuan";
                
                if ($berkas !== NULL && $statusBadge != $berkas) continue;
                
                $statusColor = ($statusBadge == "Disetujui") ? "#10b981" : (($statusBadge == "Ditolak") ? "#ef4444" : "#f59e0b");
                $statusBg = ($statusBadge == "Disetujui") ? "#dcfce7" : (($statusBadge == "Ditolak") ? "#fee2e2" : "#fef3c7");
                ?>
                <tr>
                  <td style="text-align: center; color: #64748b; font-weight: 500;"><?= $n ?></td>
                  <td>
                    <div style="font-weight: 700; color: #1e293b; font-size: 14px;"><?= htmlspecialchars($r["NAMA"]) ?></div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;"><?= htmlspecialchars($r["NPM"]) ?></div>
                  </td>
                  <td style="color: #475569; font-size: 13px;"><?= htmlspecialchars($r["PROGRAMSTUDI"]) ?></td>
                  <td>
                    <div style="font-size: 13px; color: #1e293b;"><?= htmlspecialchars($r["NOTELEPON"]) ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($r["JENISKELAMIN"]) ?></div>
                  </td>
                  <td style="text-align: center;">
                    <span style="background: <?= $statusBg ?>; color: <?= $statusColor ?>; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid <?= $statusColor ?>33;">
                      <?= strtoupper(htmlspecialchars($statusBadge)) ?>
                    </span>
                  </td>
                  <td style="text-align: center;">
                    <a class="btn btn-tiny btn-view" style="background: #a805a8; color: white; border: none; border-radius: 8px; padding: 6px 14px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-block;" href="?page=mahasiswa/data/<?= $r["USRKEY"] ?>&NPM=<?= $r["NPM"] ?>">Detail</a>
                  </td>
                </tr>
                <?php
                $n++;
              } ?>
            </tbody>
          </table>
        </div>
      <?php } else { ?>
        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
          <div style="background: #f0e3fc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <h3 style="color: #333; font-weight: 700; margin-bottom: 10px;">Data Tidak Ditemukan</h3>
          <p style="color: #64748b; max-width: 400px; margin: 0 auto;">Belum ada pendaftar yang cocok dengan kriteria pencarian Anda.</p>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-centered" style="max-width: 450px; width: 90%;">
    <div class="content animate" style="border-radius: 16px; border: none; overflow: hidden;">
      <div class="container" style="padding: 0;">
        <div class="title" style="background: #a805a8; color: white; padding: 20px; border-radius: 16px 16px 0 0;">
          <h1 style="font-size: 18px; font-weight: 600; margin: 0; display: flex; justify-content: space-between; align-items: center;">
            Detail Mahasiswa
            <span onclick="document.getElementById('modal').style.display='none'" style="cursor: pointer; font-size: 24px; line-height: 1;">&times;</span>
          </h1>
        </div>
        <div class="field" style="padding: 20px; background: white;">
          View
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

</script>
<script src="./assets/js/chartist.min.js" type="text/javascript"></script>
<script src="./assets/js/chartist.bar.labels.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
  var data = {
    labels: [<?php echo $label ?>],
    series: [<?php echo $series ?>]
  };
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
