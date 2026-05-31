<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	  Page untuk memvalidasi data berkas perserta
 */

require_level('Admin, Monitor, Operator');
/* Use data from controller as primary source */
$tahun  = isset($tahun) ? $tahun : NULL;
$periode = isset($periode) ? $periode : NULL;
$npm    = isset($npm) ? $npm : NULL;
$prodi  = isset($prodi) ? $prodi : NULL;
$berkas = isset($berkas) ? $berkas : NULL;
$dataAccess = clone $this->database('default', 'dbconfig', TRUE);

// Data per tahun untuk grafik
$chartData = $dataAccess->reset()->column("TAHUNDAFTAR, COUNT(TAHUNDAFTAR) AS JUMLAHPESERTA", FALSE)->group("TAHUNDAFTAR")->order("TAHUNDAFTAR", "ASC")->result_array('databerkas');

$chartLabels = [];
$chartValues = [];
$totalAll    = 0;

foreach ($chartData as $d) {
    // Pastikan tahun tidak kosong dan jumlahnya valid
    if (empty($d["TAHUNDAFTAR"])) continue;
    
    $chartLabels[] = $d["TAHUNDAFTAR"];
    $chartValues[] = (int)$d["JUMLAHPESERTA"];
    $totalAll += (int)$d["JUMLAHPESERTA"];
}

// Total tahun aktif
$totalTahun = count($chartLabels);
$rentangTahun = $totalTahun > 1 ? min($chartLabels) . ' - ' . max($chartLabels) : ($totalTahun == 1 ? $chartLabels[0] : '-');

// Tahun tertinggi pendaftar
$maxVal  = !empty($chartValues) ? max($chartValues) : 0;
$maxIdx  = !empty($chartValues) ? array_search($maxVal, $chartValues) : 0;
$tahunTertinggi = !empty($chartLabels) ? $chartLabels[$maxIdx] : '-';

// Rata-rata per tahun
$rataRata = $totalTahun > 0 ? round($totalAll / $totalTahun) : 0;

// Encode ke JSON untuk Chart.js
$jsLabels = json_encode($chartLabels);
$jsValues = json_encode($chartValues);
?>

<style type="text/css">
/* Container styling to match validate-container and laporan-container */
.schedule-container {
  padding: 10px 0 !important;
  max-width: 1200px !important;
  margin: 0 auto !important;
  width: 100% !important;
  box-sizing: border-box !important;
  overflow-x: hidden !important;
}

/* Card layout safety */
.schedule-container .schedule-card {
  width: 100% !important;
  box-sizing: border-box !important;
  margin-bottom: 25px !important;
  background: #ffffff !important;
  border-radius: 12px !important;
  padding: 30px !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
  border: 1px solid #f1f5f9 !important;
}

@media screen and (max-width: 768px) {
  .schedule-container .schedule-card {
    padding: 20px !important;
  }
}

/* Stat Summary Grid - fully responsive */
.schedule-container .stat-summary-grid {
  display: grid !important;
  grid-template-columns: repeat(4, 1fr) !important;
  gap: 16px !important;
  margin-bottom: 24px !important;
  width: 100% !important;
  box-sizing: border-box !important;
}

@media screen and (max-width: 992px) {
  .schedule-container .stat-summary-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}

@media screen and (max-width: 576px) {
  .schedule-container .stat-summary-grid {
    grid-template-columns: 1fr !important;
    gap: 12px !important;
  }
}

/* Filter Form Grid - fully responsive */
.schedule-container .filter-form-grid {
  display: grid !important;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important;
  gap: 15px !important;
  align-items: end !important;
  margin-top: 20px !important;
  width: 100% !important;
  box-sizing: border-box !important;
}

@media screen and (max-width: 576px) {
  .schedule-container .filter-form-grid {
    grid-template-columns: 1fr !important;
    gap: 12px !important;
  }
}

/* Table Responsiveness - completely eliminates horizontal scroll of the body */
.schedule-container .table-responsive {
  width: 100% !important;
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch !important;
  border-radius: 8px !important;
  border: 1px solid #e2e8f0 !important;
  margin-top: 15px !important;
  box-sizing: border-box !important;
}

.schedule-container .modern-table {
  width: 100% !important;
  min-width: 850px !important; /* ensures the table scrolls nicely inside the wrapper, rather than squishing layout */
  border-collapse: collapse !important;
}

/* Chart container responsive design */
.schedule-container .chart-canvas-wrapper {
  position: relative !important;
  width: 100% !important;
  height: 320px !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
}

.schedule-container #dataPendaftar {
  width: 100% !important;
  height: 100% !important;
}
</style>

<div class="schedule-container">
  
  <div class="schedule-card">
    <div class="card-header">
      <h1 class="card-title">Statistik Pendaftar</h1>
      <p class="card-subtitle">Ringkasan dan grafik perbandingan jumlah pendaftar setiap tahunnya.</p>
    </div>

    <div class="card-content-wrapper">

      <!-- Stat Summary Cards -->
      <div class="stat-summary-grid">
        <div class="stat-card">
          <div class="stat-icon stat-icon--total">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <div class="stat-info">
            <div class="stat-value"><?= number_format($totalAll) ?></div>
            <div class="stat-label">Total Pendaftar</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon stat-icon--year">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          </div>
          <div class="stat-info">
            <div class="stat-value"><?= $rentangTahun ?></div>
            <div class="stat-label">Periode Akademik (<?= $totalTahun ?> Thn)</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon stat-icon--peak">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
          </div>
          <div class="stat-info">
            <div class="stat-value"><?= $tahunTertinggi ?></div>
            <div class="stat-label">Tahun Tertinggi (<?= $maxVal ?> orang)</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon stat-icon--avg">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
          </div>
          <div class="stat-info">
            <div class="stat-value"><?= $rataRata ?></div>
            <div class="stat-label">Rata-rata / Tahun</div>
          </div>
        </div>
      </div>

      <!-- Chart Canvas -->
      <div class="chart-canvas-wrapper">
        <canvas id="dataPendaftar"></canvas>
      </div>

    </div>
  </div>

  <div class="schedule-card">
    <div class="card-header">
      <h1 class="card-title">Pencarian & Filter Data</h1>
      <p class="card-subtitle">Gunakan filter di bawah ini untuk menampilkan spesifik data peserta.</p>
    </div>

    <div class="card-content-wrapper">
      <form class="form" method="get" action="">
        <input type="hidden" name="page" value="registration/data">
        <div class="filter-form-grid">
          <div class="input-group">
            <label for="tahun">Tahun<span class="required">*</span></label>
            <select name="tahun" require="required">
              <?php
              $alltahun = $dataAccess->reset()->distinct("TAHUNDAFTAR")->order("TAHUNDAFTAR", "DESC")->result_array('databerkas');
              if ($alltahun != FALSE || $alltahun[1]["TAHUNDAFTAR"] != NULL) {
                $alltahun = array_map(function ($a) {
                  return $a["TAHUNDAFTAR"];
                }, $alltahun);
                foreach ($alltahun as $key => $TAHUNDAFTAR_VAL) {
                  if (empty($TAHUNDAFTAR_VAL)) continue; // Lewati jika kosong/null
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
            <label for="periode">Periode</label>
            <select name="periode" id="periode">
              <?php
              $formSubmittedView = isset($_GET['tahun']);
              if (!empty($allperiode)) {
                $semuaSelected = ($formSubmittedView && $periode === NULL) ? 'selected' : '';
                echo '<option value="" ' . $semuaSelected . '>Semua Periode</option>';
                foreach ($allperiode as $p) {
                  if (empty($p['PERIODEDAFTAR'])) continue; // Lewati jika kosong/null
                  $isSelected = ($p['PERIODEDAFTAR'] === $periode) ? 'selected' : '';
                  echo '<option value="' . htmlspecialchars($p['PERIODEDAFTAR'] ?? '') . '" ' . $isSelected . '>' . htmlspecialchars($p['PERIODEDAFTAR'] ?? '') . '</option>';
                }
              } else {
                echo '<option>Belum ada periode</option>';
              }
              ?>
            </select>
          </div>
          
          <div class="input-group">
            <label for="prodi">Prodi<span class="required">*</span></label>
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
            <input name="npm" value="<?php echo htmlspecialchars($npm ?? ''); ?>" placeholder="Masukkan NPM..." type="text" />
          </div>
          
          <div class="input-group action-group">
            <button type="submit" value="Simpan" class="btn btn-medium btn-ok btn-tampilkan">Tampilkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php
  /* Use data prepared by controller */
  $data = isset($mahasiswa) ? $mahasiswa : [];
  $TAHUNDAFTAR  = !empty($tahun)   ? $tahun   : "Seluruh Tahun";
  $PERIODEDAFTAR = ($periode !== NULL) ? " ($periode)" : " (Semua Periode)";
  $PROGRAMSTUDI = !empty($prodi)   ? $prodi   : "Seluruh Program Studi";
  ?>
  <div class="schedule-card">
    <div class="card-header">
      <h1 class="card-title">
        <span>Daftar Mahasiswa Tahun <?= $TAHUNDAFTAR ?><?= $PERIODEDAFTAR ?><?= isset($PROGRAMSTUDI) && $PROGRAMSTUDI !== '*' && $PROGRAMSTUDI !== 'Seluruh Program Studi' ? ", Program studi " . htmlspecialchars($PROGRAMSTUDI ?? '') : "" ?></span>
        <?php if ($data !== FALSE && count($data) != 0) { ?>
          <a href="?page=registration/export_excel&tahun=<?= urlencode($tahun ?? '') ?>&periode=<?= urlencode($periode ?? '') ?>&prodi=<?= urlencode($prodi ?? '') ?>&npm=<?= urlencode($npm ?? '') ?>&status=<?= urlencode($berkas ?? '') ?>" title="Export ke Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export
          </a>
        <?php } ?>
      </h1>
      <p class="card-subtitle">Berikut adalah data pendaftar yang sesuai dengan kriteria yang dipilih.</p>
    </div>
    <div class="card-content-wrapper">
      <?php
      if ($data !== FALSE && count($data) != 0) { ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th width="60px" class="text-center">No</th>
                <th>Mahasiswa</th>
                <th>Program Studi</th>
                <th>Kontak</th>
                <th width="120px" class="text-center">Status</th>
                <th width="100px" class="text-center">Action</th>
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
                  <td class="text-center text-muted font-medium"><?= $n ?></td>
                  <td>
                    <div class="mahasiswa-info">
                      <div class="name"><?= htmlspecialchars($r["NAMA"] ?? '') ?></div>
                      <div class="npm"><?= htmlspecialchars($r["NPM"] ?? '') ?></div>
                    </div>
                  </td>
                  <td><div class="prodi-text"><?= htmlspecialchars($r["PROGRAMSTUDI"] ?? '') ?></div></td>
                  <td>
                    <div class="kontak-info">
                      <div class="phone"><?= htmlspecialchars($r["NOTELEPON"] ?? '') ?></div>
                      <div class="gender"><?= htmlspecialchars($r["JENISKELAMIN"] ?? '') ?></div>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="status-badge" style="background: <?= $statusBg ?>; color: <?= $statusColor ?>; border-color: <?= $statusColor ?>33;">
                      <?= strtoupper(htmlspecialchars($statusBadge ?? '')) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-tiny btn-view btn-view-detail" href="?page=mahasiswa/data/<?= $r["USRKEY"] ?>&NPM=<?= $r["NPM"] ?>">Detail</a>
                  </td>
                </tr>
                <?php
                $n++;
              } ?>
            </tbody>
          </table>
        </div>
      <?php } else { ?>
        <div class="empty-state">
          <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <h3>Data Tidak Ditemukan</h3>
          <p>Belum ada pendaftar yang cocok dengan kriteria pencarian Anda.</p>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<div id="modal" class="modal">
  <div class="modal-centered modal-detail">
    <div class="content animate">
      <div class="container">
        <div class="title">
          <h1>
            Detail Mahasiswa
            <span class="close" onclick="document.getElementById('modal').style.display='none'">&times;</span>
          </h1>
        </div>
        <div class="field">
          View
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function bindFilterForm() {
  const form = document.querySelector('.schedule-container form.form');
  if (!form) return;
  
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData).toString();
    const url = '?' + params;
    
    const cards = document.querySelectorAll('.schedule-container .schedule-card');
    if (cards.length >= 3) {
      cards[1].style.opacity = '0.5';
      cards[2].style.opacity = '0.5';
      cards[1].style.transition = 'opacity 0.2s';
      cards[2].style.transition = 'opacity 0.2s';
    }
    
    fetch(url)
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const currentCards = document.querySelectorAll('.schedule-container .schedule-card');
        const newCards = doc.querySelectorAll('.schedule-container .schedule-card');
        
        if (currentCards.length >= 3 && newCards.length >= 3) {
          currentCards[1].innerHTML = newCards[1].innerHTML;
          currentCards[2].innerHTML = newCards[2].innerHTML;
          
          currentCards[1].style.opacity = '1';
          currentCards[2].style.opacity = '1';
          
          bindFilterForm();
        }
        
        history.pushState(null, '', url);
      })
      .catch(err => {
        console.error(err);
        if (cards.length >= 3) {
          cards[1].style.opacity = '1';
          cards[2].style.opacity = '1';
        }
      });
  });
}

document.addEventListener('DOMContentLoaded', bindFilterForm);
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script language="javascript" type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var labels = <?= $jsLabels ?>;
  var values = <?= $jsValues ?>;

  if (!labels.length) return;

  var chartEl = document.getElementById('dataPendaftar');
  if (!chartEl) return;
  
  var ctx = chartEl.getContext('2d');

  var gradient = ctx.createLinearGradient(0, 0, 0, 350);
  gradient.addColorStop(0, 'rgba(168, 5, 168, 0.85)');
  gradient.addColorStop(1, 'rgba(189, 15, 193, 0.25)');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Pendaftar',
        data: values,
        backgroundColor: gradient,
        borderColor: '#a805a8',
        borderWidth: 0,
        borderRadius: 8,
        borderSkipped: false,
        hoverBackgroundColor: 'rgba(168, 5, 168, 1)',
        maxBarThickness: 65,
        barPercentage: 0.8,
        categoryPercentage: 0.9
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      layout: {
        padding: { top: 15, bottom: 5, left: 10, right: 10 }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          enabled: true,
          position: 'average',
          backgroundColor: '#fff',
          titleColor: '#1e293b',
          bodyColor: '#475569',
          borderColor: '#e2e8f0',
          borderWidth: 1,
          padding: 12,
          cornerRadius: 10,
          displayColors: false,
          callbacks: {
            label: function(ctx) {
              return ' ' + ctx.parsed.y + ' Orang';
            }
          }
        },
        datalabels: false
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: {
            color: '#64748b',
            font: { size: 12, weight: '600' }
          },
          border: { display: false }
        },
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(100, 116, 139, 0.08)',
            drawBorder: false
          },
          ticks: {
            color: '#94a3b8',
            font: { size: 11 },
            stepSize: 1,
            callback: function(value) {
              if (Number.isInteger(value)) return value + ' Org';
            }
          },
          border: { display: false, dash: [4,4] }
        }
      },
      animation: {
        duration: 800,
        easing: 'easeOutQuart'
      }
    }
  });
});
</script>
