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

    <style>
      /* Force date input colors to be plain black and white */
      input[type="date"] {
        background-color: #ffffff !important;
        color: #000000 !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
      }
      
      /* Reset specific fields for Webkit browsers (Chrome, Edge, etc) */
      input[type="date"]::-webkit-datetime-edit-text, 
      input[type="date"]::-webkit-datetime-edit-month-field, 
      input[type="date"]::-webkit-datetime-edit-day-field, 
      input[type="date"]::-webkit-datetime-edit-year-field { 
        color: #000000 !important; 
      }

      /* Fix for placeholder/empty state colors */
      input[type="date"]:invalid {
        color: #000000 !important;
      }
    </style>

    <form action="" method="post" class="settings-form" style="margin-top: 30px;">
      <div class="settings-form-group">
        <label for="nama_kegiatan">Nama Kegiatan <span style="color: #ef4444;">*</span></label>
        <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?php echo htmlspecialchars($nama); ?>" placeholder="Contoh: Pembekalan Mahasiswa PLP" required>
      </div>

      <div class="settings-form-group">
        <label for="pelaksana">Pelaksana / Penanggung Jawab</label>
        <input type="text" name="pelaksana" id="pelaksana" value="<?php echo htmlspecialchars($pelaksana); ?>" placeholder="Contoh: Panitia PLP / Jurusan">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="settings-form-group">
          <label for="tanggal_mulai">Tanggal Mulai <span style="color: #ef4444;">*</span></label>
          <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo $mulai; ?>" style="color: #000000;" required>
        </div>
        <div class="settings-form-group">
          <label for="tanggal_akhir">Tanggal Berakhir <span style="color: #ef4444;">*</span></label>
          <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?php echo $selesai; ?>" style="color: #000000;" required>
        </div>
      </div>

      <div class="settings-form-group">
        <label for="deskripsi">Keterangan / Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Berikan detail singkat mengenai kegiatan ini..." style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?php echo htmlspecialchars($keterangan); ?></textarea>
      </div>

      <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 40px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
        <a href="<?php echo set_url("site/kelola_jadwal"); ?>" style="padding: 12px 25px; border-radius: 8px; text-decoration: none; color: #64748b; font-weight: 600; font-size: 14px; background: #f8fafc; border: 1px solid #e2e8f0;">Batal</a>
        <button type="submit" class="btn-save" style="padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; font-size: 14px; background: #a805a8; color: white; cursor: pointer; transition: all 0.2s;">
          <?php echo $edit_mode ? 'Simpan Perubahan' : 'Tambah Jadwal'; ?>
        </button>
      </div>
    </form>
  </div>
</div>
