<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *  Page untuk memvalidasi data berkas perserta
 */

require_level('Admin, Operator');

// Data is already prepared and filtered by the Controller
$dataAccess = clone $this->database('default', 'dbconfig', TRUE);
?>
<div class="validate-container">
  <?php if (isset($response) && $response != null) {
    echo '<div class="notif notif-primary-strong" style="margin-bottom: 20px;">' . $response . '</div>';
  } ?>

  <div class="validate-card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h1 class="card-title">Filter Peserta</h1>
    </div>
    <form class="filter-form" method="get" action="">
      <input type="hidden" name="page" value="registration/validate">
      <div class="form-row">
        <div class="form-group-modern col-md-5">
          <label for="prodi">Prodi</label>
          <select name="prodi" class="input-control">
            <?php
            /* Use $allprodi prepared by the Controller (filtered by current tahun + periode) */
            echo '<option value=""' . (empty($npm) && empty($prodi) ? ' selected' : '') . '>Semua Prodi</option>';
            if (!empty($allprodi)) {
              foreach ($allprodi as $value) {
                $sel = ($value["PROGRAMSTUDI"] == $prodi) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($value["PROGRAMSTUDI"]) . '" ' . $sel . '>' . htmlspecialchars($value["PROGRAMSTUDI"]) . '</option>';
              }
            } else {
              echo '<option disabled>Belum ada pendaftar</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group-modern col-md-3">
          <label for="status">Status Berkas</label>
          <select name="status" class="input-control">
            <option value="">Semua</option>
            <option value="Disetujui" <?php echo ($berkas == "Disetujui" ? "selected" : "") ?>>Disetujui</option>
            <option value="Ditolak" <?php echo ($berkas == "Ditolak" ? "selected" : "") ?>>Ditolak</option>
            <option value="Pengajuan" <?php echo ($berkas == "Pengajuan" ? "selected" : "") ?>>Pengajuan</option>
          </select>
        </div>
        <div class="form-group-modern col-md-3">
          <label for="npm">Cari NPM / Nama</label>
          <input name="npm" value="<?php echo htmlspecialchars($npm ?? ''); ?>" placeholder="Masukan NPM atau Nama" class="input-control" type="text" />
        </div>
        <div class="form-group-modern col-md-2" style="justify-content: flex-end;">
          <button type="submit" class="btn-save">Filter</button>
        </div>
      </div>
    </form>
  </div>

  <div class="validate-card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
      <h1 class="card-title">Daftar Mahasiswa</h1>
      <span id="pagination-info" style="font-size:13px; color:#64748b;"></span>
    </div>
    
      <?php
      $data = isset($registration_list) ? array_values($registration_list) : [];
      $totalData = count($data);
      if ($totalData > 0) {
      ?>
        <div class="table-responsive">
          <table class="modern-table" id="validate-table">
            <thead>
              <tr>
                <th width="40px" style="text-align:center;">No</th>
                <th>Nama</th>
                <th width="120px">NPM</th>
                <th>Program Studi</th>
                <th width="120px">Jenis Kelamin</th>
                <th width="130px">No Handphone</th>
                <th width="100px" style="text-align:center;">Status</th>
                <th width="80px" style="text-align:center;">Action</th>
              </tr>
            </thead>
            <tbody id="validate-tbody">
            <?php
            $n = 1;
            foreach ($data as $key => $r) {
              $statusTeks = (isset($r["STATUSBERKAS"]) && $r["STATUSBERKAS"] != FALSE ? $r["STATUSBERKAS"] : "Pengajuan");
              $badgeClass = 'badge-default';
              if ($statusTeks == 'Disetujui') $badgeClass = 'badge-success';
              elseif ($statusTeks == 'Ditolak') $badgeClass = 'badge-danger';
              elseif ($statusTeks == 'Pengajuan') $badgeClass = 'badge-warning';
              $berkasId = isset($r["ID"]) ? $r["ID"] : (isset($r["BERKASID"]) ? $r["BERKASID"] : $r["USRKEY"]);
              $namaSafe = str_replace("'", "\\'", html_entity_decode($r["NAMA"], ENT_QUOTES | ENT_HTML5));
              $berkasSafe = empty($r["BERKASDAFTAR"]) ? '' : htmlspecialchars($r["BERKASDAFTAR"]);
              ?>
              <tr class="validate-row" data-index="<?= $key ?>">
                <td style="text-align:center; color:#64748b; font-weight:500;"><?= $n ?></td>
                <td style="font-weight:600; color:#1e293b; font-size:14px;"><?= htmlspecialchars($r["NAMA"]) ?></td>
                <td style="font-size:13px; color:#475569;"><?= htmlspecialchars($r["NPM"]) ?></td>
                <td style="font-size:13px; color:#475569;"><?= htmlspecialchars($r["PROGRAMSTUDI"]) ?></td>
                <td style="font-size:13px; color:#64748b;"><?= htmlspecialchars($r["JENISKELAMIN"]) ?></td>
                <td style="font-size:13px; color:#64748b;"><?= htmlspecialchars($r["NOTELEPON"]) ?></td>
                <td style="text-align:center;"><span class="badge <?= $badgeClass ?>"><?= $statusTeks ?></span></td>
                <td style="text-align:center;"><button class="btn-action-view" onclick="validate(<?= $berkasId ?>,'<?= $namaSafe ?>','<?= $r['NPM'] ?>','<?= $berkasSafe ?>')">Action</button></td>
              </tr>
              <?php
              $n++;
            }
            ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div id="pagination-nav" style="display:flex; justify-content:center; align-items:center; gap:6px; margin-top:20px; flex-wrap:wrap;"></div>

      <?php
      } else {
        echo '<div class="empty-state" style="text-align:center; padding:60px 20px;">
          <div style="background:#f0e3fc; width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <h3 style="color:#1e293b; font-weight:700; font-size:18px; margin-bottom:8px;">Data Tidak Ditemukan</h3>
          <p style="color:#64748b; font-size:14px;">Tidak ada peserta yang sesuai dengan filter yang dipilih.</p>
        </div>';
      }
      ?>
  </div>
</div>

<style>
#pagination-nav button {
  min-width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #e2e8f0;
  background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;
  transition: all 0.2s; padding: 0 8px;
}
#pagination-nav button:hover { background: #f1f5f9; border-color: #a805a8; color: #a805a8; }
#pagination-nav button.active { background: #a805a8; color: #fff; border-color: #a805a8; }
#pagination-nav button:disabled { opacity: 0.4; cursor: default; }
</style>

<script>
(function() {
  const ROWS_PER_PAGE = 15;
  const rows = Array.from(document.querySelectorAll('#validate-tbody .validate-row'));
  const total = rows.length;
  const totalPages = Math.ceil(total / ROWS_PER_PAGE);
  let currentPage = 1;

  function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * ROWS_PER_PAGE;
    const end   = start + ROWS_PER_PAGE;
    rows.forEach(function(row, i) {
      row.style.display = (i >= start && i < end) ? '' : 'none';
    });
    document.getElementById('pagination-info').textContent =
      'Menampilkan ' + (Math.min(start + 1, total)) + '–' + Math.min(end, total) + ' dari ' + total + ' mahasiswa';
    renderNav();
  }

  function renderNav() {
    const nav = document.getElementById('pagination-nav');
    if (!nav || totalPages <= 1) return;
    nav.innerHTML = '';

    // Prev button
    const prev = document.createElement('button');
    prev.textContent = '← Prev';
    prev.disabled = (currentPage === 1);
    prev.addEventListener('click', function() { renderPage(currentPage - 1); });
    nav.appendChild(prev);

    // Page numbers
    for (let p = 1; p <= totalPages; p++) {
      // Show first, last, current ± 2
      if (p > 1 && p < totalPages && Math.abs(p - currentPage) > 2) {
        if (p === 2 || p === totalPages - 1) {
          const dots = document.createElement('span');
          dots.textContent = '…';
          dots.style.cssText = 'padding:0 4px; color:#94a3b8; font-size:14px; line-height:36px;';
          nav.appendChild(dots);
        }
        continue;
      }
      const btn = document.createElement('button');
      btn.textContent = p;
      if (p === currentPage) btn.classList.add('active');
      btn.addEventListener('click', (function(pg) { return function() { renderPage(pg); }; })(p));
      nav.appendChild(btn);
    }

    // Next button
    const next = document.createElement('button');
    next.textContent = 'Next →';
    next.disabled = (currentPage === totalPages);
    next.addEventListener('click', function() { renderPage(currentPage + 1); });
    nav.appendChild(next);
  }

  if (total > 0) renderPage(1);
})();
</script>

<style>
/* Modern Modal Styles */
.modal-overlay {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    overflow: auto;
    padding: 20px;
    box-sizing: border-box;
}
.modal-modern-card {
    background-color: #fff;
    margin: 5vh auto;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 500px;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}
@keyframes modalSlideIn {
    from { transform: translateY(-20px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}
.modal-modern-header {
    background: #f8fafc;
    padding: 16px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-modern-title {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #0f172a;
}
.modal-modern-close {
    font-size: 24px;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}
.modal-modern-close:hover {
    color: #ef4444;
}
.modal-modern-body {
    padding: 24px;
}
.modal-info-box {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}
.btn-val-approve { background-color: #10b981; color: white; padding: 12px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; transition: background 0.2s; }
.btn-val-approve:hover { background-color: #059669; }
.btn-val-reject { background-color: #f59e0b; color: white; padding: 12px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; transition: background 0.2s; }
.btn-val-reject:hover { background-color: #d97706; }
.btn-val-delete { background-color: #ef4444; color: white; padding: 12px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; transition: background 0.2s; }
.btn-val-delete:hover { background-color: #dc2626; }
.modal-action-row {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
@media (max-width: 480px) {
    .modal-action-row { flex-direction: column; }
    .modal-modern-card { margin: 10vh auto; }
}
</style>

<div id="modal" class="modal-overlay">
  <div class="modal-modern-card">
      <div class="modal-modern-header">
          <h1 class="modal-modern-title">Validasi Pendaftaran</h1>
          <span onclick="document.getElementById('modal').style.display='none'" class="modal-modern-close" title="Tutup">&times;</span>
      </div>
      <div class="modal-modern-body">
          <div id="ajaxDiv"></div>
          
          <div class="modal-info-box">
              <span style="font-size:12px; color:#64748b; font-weight:600; letter-spacing:0.5px;">DATA MAHASISWA</span>
              <div id="dta-mhs" style="margin-top:12px;"></div>
          </div>
          
          <form action="" method="post" enctype="multipart/form-data" id="res-lap-form">
            <input type="hidden" id="data-ID" name="idmahasiswa" value="">
            <input type="hidden" id="data-NPM" name="npmmahasiswa" value="">
            
            <div class="form-group-modern" style="margin-bottom:0;">
              <label for="data-NOTE" style="font-weight:600; color:#334155; margin-bottom:8px; display:block; font-size:14px;">Catatan Validator</label>
              <textarea id="data-NOTE" name="catatanberkas" class="input-control" placeholder="Tuliskan alasan penolakan atau instruksi perbaikan..." style="width:100%; min-height:90px; padding:12px; resize:vertical; font-family:inherit;"></textarea>
            </div>

            <div class="modal-action-row">
              <button id="mhs-approve" type="submit" value="Simpan" class="btn-val-approve">Setujui</button>
              <button id="mhs-reject" type="submit" value="Simpan" class="btn-val-reject">Tolak</button>
              <button id="mhs-delete" type="submit" value="Simpan" class="btn-val-delete">Hapus Data</button>
            </div>
          </form>
      </div>
  </div>
</div>

<script type="text/javascript">
  function validate(id, nama, npm, berkas) {
    document.getElementById('modal').style.display = "block";
    let linkBerkas = (berkas !== '') ? "<a href='" + berkas + "' target='_blank' style='color:#B33791; text-decoration:none; font-weight:600; background:#fdf2f8; padding:6px 12px; border-radius:6px; display:inline-block; border:1px solid #fbcfe8;' title='Klik untuk mendownload file ZIP'>⬇️ Download File .zip</a>" : "<span style='color:#ef4444; font-weight:500; background:#fee2e2; padding:6px 12px; border-radius:6px; display:inline-block; border:1px solid #fecaca;'>❌ Belum Upload Berkas</span>";
    document.getElementById('dta-mhs').innerHTML = "<div style='margin-bottom:12px;'><span style='color:#64748b; font-size:13px; display:block; margin-bottom:2px;'>Nama Lengkap</span><strong style='font-size:16px; color:#0f172a;'>" + nama + "</strong></div><div style='margin-bottom:12px;'><span style='color:#64748b; font-size:13px; display:block; margin-bottom:2px;'>NPM</span><strong style='font-size:16px; color:#0f172a;'>" + npm + "</strong></div><div><span style='color:#64748b; font-size:13px; display:block; margin-bottom:6px;'>Berkas Pendaftaran</span>" + linkBerkas + "</div>";
    document.getElementById('data-ID').value = id;
    document.getElementById('data-NPM').value = npm;
    document.getElementById('ajaxDiv').innerHTML = '';
  }

  function ajaxPOST(form, button, type) {
    let aj_data = new gcAjax(form, "?page=api/registration/validatioon");
    aj_data.addValue("status=" + type).setCallback(function(text, element) {
      let relodBtn = '<button class="btn-action-view" style="margin-top:10px; border:1px solid #4f46e5;" onClick="location.reload()">Perbaharui Daftar</button>';
      element.innerHTML = '<div style="padding:12px; background:#dcfce7; color:#166534; border-radius:6px; margin-bottom:15px;"><b>' + text + '</b><br>' + relodBtn + '</div>';
    }).send('ajaxDiv', button, '#a805a8');
  }
  
  document.querySelector("#mhs-approve").addEventListener("click", function(event) {
    event.preventDefault();
    ajaxPOST(this.form, this, 'approved');
  }, false);
  document.querySelector("#mhs-reject").addEventListener("click", function(event) {
    event.preventDefault();
    ajaxPOST(this.form, this, 'rejected');
  }, false);
  document.querySelector("#mhs-delete").addEventListener("click", function(event) {
    event.preventDefault();
    ajaxPOST(this.form, this, 'delete');
  }, false);
</script>
