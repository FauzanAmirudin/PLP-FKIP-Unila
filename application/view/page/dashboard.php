<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
require_login();
?>

<content>
  <section id="mainContent">
    <div class="content">
      <div class="header">
        <a>Dashboard</a>
      </div>
      <?php if (isset($notification) && !empty($notification)) {
        echo '
			<div class="note-Field"><h2 class="alert">' . $notification . '</h2></div>';
      } ?>
      <?php
      $dbAccess = $this->database('default', 'dbconfig', TRUE);
      if (is_level('Mahasiswa')) { ?>
        <?php
        if (isset($registration_process) && !empty($registration_process)) {
          echo '
          <div class="note-Field">
            <h1>Status Pendaftran!</h1>
            <h2>"' . $registration_process['STATUSBERKAS'] . '" ' . (!empty($registration_process["NOTEBERKAS"]) ? 'dengan catatan: ' . $registration_process["NOTEBERKAS"] : '') . '</h2>
          </div>';
        } ?>
        <div class="note-Field">
          <h1>Info!</h1>
          <h2>
            <?php
            $info = $dbAccess->reset()->where("`TANGGAL` <= CURDATE() ORDER BY `TANGGAL` DESC")->result_row_array('informasi');
            if ($info == FALSE) {
              echo '<a>Tidak ada informasi.</a>';
            } else { ?>
              <div>
                <?php
                $originalDate = $info['TANGGAL'];
                $info['WAKTU'] = date("d-m-Y", strtotime($originalDate));
                $tag = $info['TAG']; ?>
                <a><?php echo $info['INFORMASI']; ?></a>
              </div>
            <?php } ?>
          </h2>
        </div> <?php
              }
              if (is_level('DPL') || is_level('Admin')) {
              } else {
              }
                ?>
      <div class="note-Field">
        <h1>Jadwal</h1>
        <h2>
          <?php
          $jadwal = $dbAccess->reset()->where("`WAKTUAKHIR` >= CURDATE() ORDER BY `WAKTUAWAL` DESC")->result_row_array('jadwal');
          if ($jadwal == FALSE) {
            echo '<a>Jadwal tidak tersedia.</a>';
          } else { ?>
            <div>
              <?php
              $jadwal['WAKTUAWAL'] = date("d-m-Y", strtotime($jadwal['WAKTUAWAL']));
              $reminder = $jadwal['JENISKEGIATAN'];
              $reminder .= " " . $jadwal['KETERANGAN'];
              $reminder .= " dilaksanakan tangal " . $jadwal['WAKTUAWAL'];

              $end = $jadwal['WAKTUAKHIR'];
              if ($end != '' || $end != null) {
                $jadwal['WAKTUAKHIR'] = date("d-m-Y", strtotime($jadwal['WAKTUAKHIR']));
                $reminder .= " hingga " . $jadwal['WAKTUAKHIR'];
              } ?>
              <a><?php echo $reminder; ?>.</a>
            </div>
          <?php }
          ?>
        </h2>
      </div>
    </div>
  </section>
</content>