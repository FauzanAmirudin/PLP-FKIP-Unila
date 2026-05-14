<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

?>
<link rel="stylesheet" href="./assets/css/chartist.min.css">


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
