<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<link rel="stylesheet" href="./assets/css/chartist.min.css">
<style>
  /* Refined Institutional Purple Styling for Chartist */
  .ct-series-a .ct-bar { stroke: #a805a8 !important; stroke-linecap: round; }
  .ct-series-a .ct-slice-pie { fill: #a805a8 !important; }
  .ct-series-b .ct-slice-pie { fill: #c026d3 !important; }
  .ct-series-c .ct-slice-pie { fill: #e879f9 !important; }
  .ct-series-d .ct-slice-pie { fill: #f0e3fc !important; }
  .ct-series-e .ct-slice-pie { fill: #64748b !important; }
  .ct-series-f .ct-slice-pie { fill: #334155 !important; }
  .ct-series-g .ct-slice-pie { fill: #94a3b8 !important; }
  
  .ct-label { font-size: 12px; font-weight: 500; fill: #64748b; color: #64748b; }
  .ct-pie-label { fill: #fff; font-weight: 600; text-shadow: 0px 1px 2px rgba(0,0,0,0.4); font-size: 11px; }
  
  .monitor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
  }
</style>

<div class="settings-container">
  
  <?php if (isset($response) && $response != null) {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
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
        <h2 class="card-title" style="font-size: 16px;">Status Verifikasi Berkas</h2>
      </div>
      <div style="margin-top: 15px;">
        <div id="dataTerverifikasi" class="ct-pie-1" style="height: 250px;"></div>
      </div>
    </div>
    
    <div class="settings-card">
      <div class="card-header">
        <h2 class="card-title" style="font-size: 16px;">Jenis Kelamin</h2>
      </div>
      <div style="margin-top: 15px;">
        <div id="dataPeserta" class="ct-pie-1" style="height: 250px;"></div>
      </div>
    </div>
  </div>

  <div class="settings-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h2 class="card-title" style="font-size: 16px;">Jumlah Pendaftar per Program Studi</h2>
    </div>
    <div style="margin-top: 15px; padding-bottom: 10px;">
      <div class="chart-wraper" style="overflow-x: auto; overflow-y: hidden;">
        <div id="dataPendaftar" class="ct-chart" style="height:300px; min-width: 600px;"></div>
      </div>
    </div>
  </div>

  <div class="settings-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h2 class="card-title" style="font-size: 16px;">Persentase Pendaftar per Program Studi</h2>
    </div>
    <div style="margin-top: 15px;">
      <div id="presentasePendaftar" class="ct-pie" style="height: 350px;"></div>
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
