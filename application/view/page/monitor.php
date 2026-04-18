<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
require_level("Admin, Monitor, Operator");
?>
<link rel="stylesheet" href="./assets/css/chartist.min.css">

<content>
  <section id="mainContent">
    <div class="content">
      <div class="header">
        <stong>Data Peserta Tahun <?= $curentyear ?> <?= $curentperiode ?></strong>
      </div>
      <?php if (isset($notification) && $notification != null) {
        echo '<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <div class="field">
        <h1>Data Terverifikasi
          <span class="field-action action-right"> </span>
        </h1>
        <div class="content">
          <div class="row">
            <div class="col-md-6">
              <div id="dataTerverifikasi" class="ct-pie-1"></div>
            </div>
            <div class="col-md-6">
              <div id="dataPeserta" class="ct-pie-1"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="field">
        <h1>Jumlah Pendaftar
          <span class="field-action action-right"> </span>
        </h1>
        <div class="content">
          <div class="chart-wraper">
            <div id="dataPendaftar" class="ct-chart"></div>
          </div>
        </div>
      </div>
      <div class="field">
        <h1>Presentase Pendaftar
          <span class="field-action action-right"> </span>
        </h1>
        <div class="content">
          <div id="presentasePendaftar" class="ct-pie"></div>
        </div>
      </div>
    </div>
  </section>
</content>
<?php
$dataMahasiswaAccess = new gf_sql(GF_DB['default']);
$dataMahasiswaAccess->tabel('datamahasiswa');
$data = $dataMahasiswaAccess->reset()
  ->column("PROGRAMSTUDI, COUNT(PROGRAMSTUDI) AS JUMLAHPESERTA")
  ->where("TAHUNDAFTAR = " . $curentyear)
  ->group("PROGRAMSTUDI")
  ->result_array();
/* echo $dataMahasiswaAccess->last_query; */
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
$data = $dataMahasiswaAccess->reset()
  ->column("JENISKELAMIN, COUNT(NPM) AS JUMLAHPESERTA")
  ->where("TAHUNDAFTAR = " . $curentyear)
  ->group("JENISKELAMIN")
  ->result_array();
/* echo $dataMahasiswaAccess->last_query; */
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
$dataMahasiswaAccess = new gf_sql(GF_DB['default']);
$data = $dataMahasiswaAccess->reset()
  ->tabel('statusberkas')
  ->column("( SELECT STATUSBERKAS FROM statusberkas WHERE statusberkas.USRKEY = datamahasiswa.USRKEY ORDER BY id DESC LIMIT 1 ) AS STATUSBERKAS ")
  ->where("TAHUNDAFTAR = " . $curentyear)
  ->result_array();
$dataVerifiksi = [];
// echo $dataMahasiswaAccess->last_query;
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