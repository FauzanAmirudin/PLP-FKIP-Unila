<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');

$id = isset($jadwal['ID']) ? $jadwal['ID'] : '';
$nama = isset($jadwal['JENISKEGIATAN']) ? $jadwal['JENISKEGIATAN'] : '';
$pelaksana = isset($jadwal['PELAKSANA']) ? $jadwal['PELAKSANA'] : '';
$mulai = isset($jadwal['WAKTUAWAL']) ? $jadwal['WAKTUAWAL'] : '';
$selesai = isset($jadwal['WAKTUAKHIR']) ? $jadwal['WAKTUAKHIR'] : '';
$keterangan = isset($jadwal['KETERANGAN']) ? $jadwal['KETERANGAN'] : '';
?>

<div class="settings-container">
  <div class="settings-card">
    <div class="card-header">
      <h1 class="card-title"><?php echo $edit_mode ? 'Edit Jadwal' : 'Tambah Jadwal Baru'; ?></h1>
      <p class="card-subtitle">Silakan isi formulir di bawah ini untuk <?php echo $edit_mode ? 'memperbaharui' : 'menambahkan'; ?> jadwal kegiatan.</p>
    </div>

    <form action="" method="post" class="settings-form mt-30">
      <div class="settings-form-group">
        <label for="nama_kegiatan">Nama Kegiatan <span class="asterisk">*</span></label>
        <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?php echo htmlspecialchars($nama); ?>" placeholder="Contoh: Pembekalan Mahasiswa PLP" required>
      </div>

      <div class="settings-form-group">
        <label for="pelaksana">Pelaksana / Penanggung Jawab</label>
        <input type="text" name="pelaksana" id="pelaksana" value="<?php echo htmlspecialchars($pelaksana); ?>" placeholder="Contoh: Panitia PLP / Jurusan">
      </div>

      <div class="form-grid-2">
        <div class="settings-form-group">
          <label for="tanggal_mulai">Tanggal Mulai <span class="asterisk">*</span></label>
          <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $mulai; ?>" required>
        </div>
        <div class="settings-form-group">
          <label for="tanggal_akhir">Tanggal Berakhir <span class="asterisk">*</span></label>
          <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?php echo $selesai; ?>" required>
        </div>
      </div>

      <div class="settings-form-group">
        <label for="deskripsi">Keterangan / Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Berikan detail singkat mengenai kegiatan ini..." class="form-textarea-custom"><?php echo htmlspecialchars($keterangan); ?></textarea>
      </div>

      <div class="form-actions-flex form-actions-end">
        <a href="<?php echo set_url("site/kelola_jadwal"); ?>" class="btn-action-cancel">Batal</a>
        <button type="submit" class="btn-action-submit">
          <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Jadwal'; ?>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelector('.settings-form').addEventListener('submit', function(e) {
  var tglMulai = document.getElementById('tanggal_mulai').value;
  var tglAkhir = document.getElementById('tanggal_akhir').value;
  
  if (tglMulai && tglAkhir) {
    if (new Date(tglAkhir) < new Date(tglMulai)) {
      e.preventDefault();
      alert("Kesalahan: Tanggal Berakhir tidak boleh lebih awal dari Tanggal Mulai!");
      document.getElementById('tanggal_akhir').focus();
    }
  }
});
</script>
