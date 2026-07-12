<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
require_level('Admin, Operator');

$id = isset($jadwal['ID']) ? $jadwal['ID'] : '';
$jenis_plp = isset($jadwal['JENIS_PLP']) ? $jadwal['JENIS_PLP'] : 'PLP1';
$minggu_ke = isset($jadwal['MINGGU_KE']) ? $jadwal['MINGGU_KE'] : '';
$nomor_kegiatan = isset($jadwal['NOMOR_KEGIATAN']) ? $jadwal['NOMOR_KEGIATAN'] : '';
$jp_hari = isset($jadwal['JP_HARI']) ? $jadwal['JP_HARI'] : '';
$rincian_kegiatan = isset($jadwal['JENISKEGIATAN']) ? $jadwal['JENISKEGIATAN'] : '';
?>

<div class="settings-container">
  <div class="settings-card">
    <div class="card-header">
      <h1 class="card-title"><?php echo $edit_mode ? 'Edit Jadwal' : 'Tambah Jadwal Baru'; ?></h1>
      <p class="card-subtitle">Silakan isi formulir di bawah ini untuk <?php echo $edit_mode ? 'memperbaharui' : 'menambahkan'; ?> jadwal kegiatan akademik.</p>
    </div>

    <form action="" method="post" class="settings-form mt-30">
      
      <div class="form-grid-2">
        <div class="settings-form-group">
          <label for="jenis_plp">Jenis PLP <span class="asterisk">*</span></label>
          <select name="jenis_plp" id="jenis_plp" required>
            <option value="PLP1" <?php echo $jenis_plp == 'PLP1' ? 'selected' : ''; ?>>PLP 1</option>
            <option value="PLP2" <?php echo $jenis_plp == 'PLP2' ? 'selected' : ''; ?>>PLP 2</option>
          </select>
        </div>
        <div class="settings-form-group">
          <label for="minggu_ke">Minggu Ke- <span class="asterisk">*</span></label>
          <input type="number" name="minggu_ke" id="minggu_ke" value="<?php echo htmlspecialchars($minggu_ke); ?>" min="1" max="20" placeholder="Contoh: 1" required>
        </div>
      </div>

      <div class="settings-form-group">
        <label for="rincian_kegiatan">Rincian Kegiatan <span class="asterisk">*</span></label>
        <textarea name="rincian_kegiatan" id="rincian_kegiatan" rows="3" placeholder="Masukkan rincian kegiatan..." required class="form-textarea-custom"><?php echo htmlspecialchars($rincian_kegiatan); ?></textarea>
      </div>

      <div class="form-grid-2">
        <div class="settings-form-group">
          <label for="nomor_kegiatan">Nomor Kegiatan <span class="asterisk">*</span></label>
          <input type="number" name="nomor_kegiatan" id="nomor_kegiatan" value="<?php echo htmlspecialchars($nomor_kegiatan); ?>" min="1" placeholder="Contoh: 1" required>
        </div>
        <div class="settings-form-group">
          <label for="jp_hari">Beban Belajar (JP/Hari) <span class="asterisk">*</span></label>
          <input type="number" name="jp_hari" id="jp_hari" value="<?php echo htmlspecialchars($jp_hari); ?>" step="0.1" min="0" placeholder="Contoh: 1.5" required>
        </div>
      </div>

      <div class="form-actions-flex form-actions-end mt-20">
        <a href="<?php echo set_url("site/kelola_jadwal"); ?>" class="btn-action-cancel">Batal</a>
        <button type="submit" class="btn-action-submit">
          <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Jadwal'; ?>
        </button>
      </div>
    </form>
  </div>
</div>
