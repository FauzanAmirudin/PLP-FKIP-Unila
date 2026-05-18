<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<link rel="stylesheet" href="./assets/css/chartist.min.css">
<style type="text/css">
/* Crisp white borders between pie chart slices for modern clean UI */
.ct-slice-pie {
    stroke: #ffffff !important;
    stroke-width: 2px !important;
}

/* Ensure flat bar ends for all series to prevent overflowing layout and maintain consistency */
.ct-bar {
    stroke-linecap: butt !important;
}

/* Custom Chartist Modern Palette (A-Z) to prevent purple dominance */
.ct-series-a .ct-slice-pie, .ct-series-a .ct-area { fill: #6366f1 !important; }
.ct-series-a .ct-bar, .ct-series-a .ct-line, .ct-series-a .ct-point { stroke: #6366f1 !important; }

.ct-series-b .ct-slice-pie, .ct-series-b .ct-area { fill: #10b981 !important; }
.ct-series-b .ct-bar, .ct-series-b .ct-line, .ct-series-b .ct-point { stroke: #10b981 !important; }

.ct-series-c .ct-slice-pie, .ct-series-c .ct-area { fill: #f59e0b !important; }
.ct-series-c .ct-bar, .ct-series-c .ct-line, .ct-series-c .ct-point { stroke: #f59e0b !important; }

.ct-series-d .ct-slice-pie, .ct-series-d .ct-area { fill: #ef4444 !important; }
.ct-series-d .ct-bar, .ct-series-d .ct-line, .ct-series-d .ct-point { stroke: #ef4444 !important; }

.ct-series-e .ct-slice-pie, .ct-series-e .ct-area { fill: #06b6d4 !important; }
.ct-series-e .ct-bar, .ct-series-e .ct-line, .ct-series-e .ct-point { stroke: #06b6d4 !important; }

.ct-series-f .ct-slice-pie, .ct-series-f .ct-area { fill: #ec4899 !important; }
.ct-series-f .ct-bar, .ct-series-f .ct-line, .ct-series-f .ct-point { stroke: #ec4899 !important; }

.ct-series-g .ct-slice-pie, .ct-series-g .ct-area { fill: #8b5cf6 !important; }
.ct-series-g .ct-bar, .ct-series-g .ct-line, .ct-series-g .ct-point { stroke: #8b5cf6 !important; }

.ct-series-h .ct-slice-pie, .ct-series-h .ct-area { fill: #f97316 !important; }
.ct-series-h .ct-bar, .ct-series-h .ct-line, .ct-series-h .ct-point { stroke: #f97316 !important; }

.ct-series-i .ct-slice-pie, .ct-series-i .ct-area { fill: #14b8a6 !important; }
.ct-series-i .ct-bar, .ct-series-i .ct-line, .ct-series-i .ct-point { stroke: #14b8a6 !important; }

.ct-series-j .ct-slice-pie, .ct-series-j .ct-area { fill: #3b82f6 !important; }
.ct-series-j .ct-bar, .ct-series-j .ct-line, .ct-series-j .ct-point { stroke: #3b82f6 !important; }

.ct-series-k .ct-slice-pie, .ct-series-k .ct-area { fill: #a855f7 !important; }
.ct-series-k .ct-bar, .ct-series-k .ct-line, .ct-series-k .ct-point { stroke: #a855f7 !important; }

.ct-series-l .ct-slice-pie, .ct-series-l .ct-area { fill: #eab308 !important; }
.ct-series-l .ct-bar, .ct-series-l .ct-line, .ct-series-l .ct-point { stroke: #eab308 !important; }

.ct-series-m .ct-slice-pie, .ct-series-m .ct-area { fill: #d946ef !important; }
.ct-series-m .ct-bar, .ct-series-m .ct-line, .ct-series-m .ct-point { stroke: #d946ef !important; }

.ct-series-n .ct-slice-pie, .ct-series-n .ct-area { fill: #84cc16 !important; }
.ct-series-n .ct-bar, .ct-series-n .ct-line, .ct-series-n .ct-point { stroke: #84cc16 !important; }

.ct-series-o .ct-slice-pie, .ct-series-o .ct-area { fill: #22c55e !important; }
.ct-series-o .ct-bar, .ct-series-o .ct-line, .ct-series-o .ct-point { stroke: #22c55e !important; }

.ct-series-p .ct-slice-pie, .ct-series-p .ct-area { fill: #64748b !important; }
.ct-series-p .ct-bar, .ct-series-p .ct-line, .ct-series-p .ct-point { stroke: #64748b !important; }

.ct-series-q .ct-slice-pie, .ct-series-q .ct-area { fill: #a1a1aa !important; }
.ct-series-q .ct-bar, .ct-series-q .ct-line, .ct-series-q .ct-point { stroke: #a1a1aa !important; }

.ct-series-r .ct-slice-pie, .ct-series-r .ct-area { fill: #fb7185 !important; }
.ct-series-r .ct-bar, .ct-series-r .ct-line, .ct-series-r .ct-point { stroke: #fb7185 !important; }

.ct-series-s .ct-slice-pie, .ct-series-s .ct-area { fill: #38bdf8 !important; }
.ct-series-s .ct-bar, .ct-series-s .ct-line, .ct-series-s .ct-point { stroke: #38bdf8 !important; }

.ct-series-t .ct-slice-pie, .ct-series-t .ct-area { fill: #fb923c !important; }
.ct-series-t .ct-bar, .ct-series-t .ct-line, .ct-series-t .ct-point { stroke: #fb923c !important; }

.ct-series-u .ct-slice-pie, .ct-series-u .ct-area { fill: #2dd4bf !important; }
.ct-series-u .ct-bar, .ct-series-u .ct-line, .ct-series-u .ct-point { stroke: #2dd4bf !important; }

.ct-series-v .ct-slice-pie, .ct-series-v .ct-area { fill: #c084fc !important; }
.ct-series-v .ct-bar, .ct-series-v .ct-line, .ct-series-v .ct-point { stroke: #c084fc !important; }

.ct-series-w .ct-slice-pie, .ct-series-w .ct-area { fill: #818cf8 !important; }
.ct-series-w .ct-bar, .ct-series-w .ct-line, .ct-series-w .ct-point { stroke: #818cf8 !important; }

.ct-series-x .ct-slice-pie, .ct-series-x .ct-area { fill: #f472b6 !important; }
.ct-series-x .ct-bar, .ct-series-x .ct-line, .ct-series-x .ct-point { stroke: #f472b6 !important; }

.ct-series-y .ct-slice-pie, .ct-series-y .ct-area { fill: #fbbf24 !important; }
.ct-series-y .ct-bar, .ct-series-y .ct-line, .ct-series-y .ct-point { stroke: #fbbf24 !important; }

.ct-series-z .ct-slice-pie, .ct-series-z .ct-area { fill: #a3e635 !important; }
.ct-series-z .ct-bar, .ct-series-z .ct-line, .ct-series-z .ct-point { stroke: #a3e635 !important; }
</style>


<div class="settings-container">
  
  <?php if (isset($response) && $response != null) {
    echo '<div class="notif notif-primary-strong mb-20">' . $response . '</div>';
  } ?>

  <!-- <div class="settings-card" style="margin-bottom: 20px; background: linear-gradient(135deg, #a805a8, #7c047c); color: white;">
    <div style="padding: 5px 0;">
      <h1 style="font-size: 24px; font-weight: 700; margin: 0;">Dashboard Monitor Tahun <?php echo get_dbconfig('CURENTYEAR') ?></h1>
      <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Pantau statistik keseluruhan dari pendaftar dan status verifikasi secara langsung.</p>
    </div>
  </div> -->

  <div class="monitor-grid">
    <div class="settings-card">
      <div class="card-header">
        <h2 class="card-title card-title-sm">Status Verifikasi Berkas</h2>
      </div>
      <div class="mt-15">
        <div id="dataTerverifikasi" class="ct-pie-1 chart-h-250"></div>
      </div>
    </div>
    
    <div class="settings-card">
      <div class="card-header">
        <h2 class="card-title card-title-sm">Jenis Kelamin</h2>
      </div>
      <div class="mt-15">
        <div id="dataPeserta" class="ct-pie-1 chart-h-250"></div>
      </div>
    </div>
  </div>

  <div class="settings-card mb-20">
    <div class="card-header">
      <h2 class="card-title card-title-sm">Jumlah Pendaftar per Program Studi</h2>
    </div>
    <div class="mt-15 pb-10">
      <div class="chart-wraper">
        <div id="dataPendaftar" class="ct-chart chart-h-300 chart-min-w-600"></div>
      </div>
    </div>
  </div>

  <div class="settings-card mb-20">
    <div class="card-header">
      <h2 class="card-title card-title-sm">Persentase Pendaftar per Program Studi</h2>
    </div>
    <div class="mt-15">
      <div id="presentasePendaftar" class="ct-pie chart-h-350"></div>
    </div>
  </div>

</div>
<?php
$curentYear = get_dbconfig('CURENTYEAR');
$dbAccess = clone $this->database('default', 'dbconfig', TRUE);
$data = $dbAccess->reset()->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY')->column("PROGRAMSTUDI, COUNT(PROGRAMSTUDI) AS JUMLAHPESERTA", FALSE)->where(["TAHUNDAFTAR" => $curentYear])->group("PROGRAMSTUDI")->result_array('datamahasiswa');
/* echo $dbAccess->last_query; */
$label  = "";
$series = "";
foreach ($data as $n => $d) {
  if ($d["PROGRAMSTUDI"] == '' && $d["JUMLAHPESERTA"] = 0) continue;
  if ($n > 0) {
    $label .= ", ";
    $series .= ", ";
  }
  $label  .= "'" . $d["PROGRAMSTUDI"] . "'";
  $series .= $d["JUMLAHPESERTA"];
}
$data = $dbAccess->reset()->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY')->column("JENISKELAMIN, COUNT(NPM) AS JUMLAHPESERTA", FALSE)->where(["TAHUNDAFTAR" => $curentYear])->group("JENISKELAMIN")->result_array('datamahasiswa');
/* echo $dbAccess->last_query; */
$jnskllabel  = "";
$jnsklseries = "";
foreach ($data as $n => $d) {
  if ($d["JUMLAHPESERTA"] == 0) continue;
  if ($n > 0) {
    $jnskllabel .= ", ";
    $jnsklseries .= ", ";
  }
  if ($d["JENISKELAMIN"] == '') $d["JENISKELAMIN"] = 'Data Belum Lengkap';
  $jnskllabel  .= "'" . $d["JENISKELAMIN"] . "'";
  $jnsklseries .= $d["JUMLAHPESERTA"];
}
$data = $dbAccess->reset()->join('databerkas', 'datamahasiswa.USRKEY = databerkas.USRKEY')->column("( SELECT STATUSBERKAS FROM datastatus WHERE datastatus.USRKEY = datamahasiswa.USRKEY ORDER BY id DESC LIMIT 1 ) AS STATUSBERKAS ", FALSE)->where(["TAHUNDAFTAR" => $curentYear])->result_array('datamahasiswa');
$dataVerifiksi = [];
// echo $dbAccess->last_query;
foreach ($data as $d) {
  if ($d["STATUSBERKAS"] == '') $d["STATUSBERKAS"] = 'Pengajuan';
  if (!isset($dataVerifiksi[$d["STATUSBERKAS"]])) $dataVerifiksi[$d["STATUSBERKAS"]] = 0;
  $dataVerifiksi[$d["STATUSBERKAS"]]++;
}
$vrflabel   = "";
$vrfseries  = "";
$i = 0;
foreach ($dataVerifiksi as $n => $d) {
  if ($i > 0) {
    $vrflabel .= ", ";
    $vrfseries .= ", ";
  }
  $vrflabel   .= "'" . $n . "'";
  $vrfseries  .= $d;
  $i++;
}
?>
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
      scaleMinSpace: 30
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
        style: 'stroke-width: 55px'
      });
    }
  });


  options = {
    labelInterpolationFnc: function(label, index) {
      let sum = data.series.reduce(function(total, value) {
        return total + value
      });
      let val = data.series[index];
      let num = Math.round((val / sum) * 100) + '%';
      return num;
    }
  };
  var responsiveOptions = [
    ['screen and (min-width: 640px)', {
      chartPadding: 40,
      labelOffset: 1,
      labelDirection: 'explode',
      labelInterpolationFnc: function(label, index, a) {
        let sum = data.series.reduce(function(total, value) {
          return total + value
        });
        let val = data.series[index];
        let num = Math.round((val / sum) * 100) + '%';
        return label + ' - ' + num;
      }
    }],
    ['screen and (min-width: 1024px)', {
      labelOffset: 80,
      chartPadding: 20,
      labelInterpolationFnc: function(label, index, a) {
        let sum = data.series.reduce(function(total, value) {
          return total + value
        });
        let val = data.series[index];
        let num = Math.round((val / sum) * 100) + '%';
        return label + ' - ' + num;
      }
    }]
  ];
  new Chartist.Pie('#presentasePendaftar', data, options, responsiveOptions);


  var data2 = {
    labels: [<?php echo $vrflabel ?>],
    series: [<?php echo $vrfseries ?>]
  };
  options2 = {
    labelInterpolationFnc: function(label, index) {
      let sum = data2.series.reduce(function(total, value) {
        return total + value
      });
      let val = data2.series[index];
      let num = Math.round((val / sum) * 100) + '%';
      return label + ' - ' + num + "(" + val + ")";
    }
  };
  new Chartist.Pie('#dataTerverifikasi', data2, options2);


  var data3 = {
    labels: [<?php echo $jnskllabel ?>],
    series: [<?php echo $jnsklseries ?>]
  };
  options3 = {
    labelInterpolationFnc: function(label, index) {
      let sum = data3.series.reduce(function(total, value) {
        return total + value
      });
      let val = data3.series[index];
      let num = Math.round((val / sum) * 100) + '%';
      return label + ' - ' + num + "(" + val + ")";
    }
  };
  new Chartist.Pie('#dataPeserta', data3, options3);
</script>
