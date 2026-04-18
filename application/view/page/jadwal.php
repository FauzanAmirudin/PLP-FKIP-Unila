<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
?>
<style>
  #table {
    padding: 5px 5px 5px 5px;
  }

  .row {
    width: 100%;
    border: 1px solid purple;
  }
</style>
<content>
  <section id="mainContent">

    <div class="content">
      <div class="header">
        <a>JADWAL</a>
      </div>
      <div class="field">
        <h1>Jadwal Kegiatan
          <span class="field-action action-right"></span>
        </h1>
        <?php
        if ($jadwals != FALSE) {
          echo '
        <div id="table">
          <div class="row background-primare text-center">
            <a class=col-md-4>Kegiatan</a><a class=col-md-1>Tanggal Mulai</a><a class=col-md-1>Tanggal Berahir</a><a class=col-md-4>Pelaksana</a>
          </div>';
          foreach ($jadwals as $jadwal) {
            echo '
          <div class="row">
            <a class=col-md-4>' . $jadwal['JENISKEGIATAN'] . '</a><a class=col-md-1>' . $jadwal['WAKTUAWAL'] . '</a><a class=col-md-1>' . $jadwal['WAKTUAKHIR'] . '</a><a class=col-md-4>' . $jadwal['KETERANGAN'] . '</a>
          </div>';
          }
          echo '
        </div>';
        } else {
          echo 'Jadwal tidak tersedia.';
        }
        ?>
      </div>
    </div>

  </section>
</content>