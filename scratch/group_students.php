<?php
/**
 * Script untuk mengelompokkan mahasiswa yang sudah ada ke dalam satu DPL.
 * Pastikan untuk menjalankan script ini dari root direktori project.
 */
define('GF_BASE_PATH', dirname(__DIR__));
require GF_BASE_PATH . '/application/config/db.php';

// Konfigurasi Database Manual
$host = $gf_db['default']['server'];
$user = $gf_db['default']['username'];
$pass = $gf_db['default']['password'];
$name = $gf_db['default']['database'];

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Ambil Data DPL yang tersedia (Ambil DPL pertama yang aktif)
$dplQuery = "SELECT USRKEY, NIPDOSEN, NAMADOSEN FROM dosen LIMIT 1";
$dplResult = $conn->query($dplQuery);

if ($dplResult->num_rows == 0) {
    die("Tidak ada data DPL di database.\n");
}
$dplData = $dplResult->fetch_assoc();

// 2. Data Penempatan Kelompok
$lokasiKabupaten = 'Bandar Lampung';
$lokasiKecamatan = 'Sukarame';
$lokasiDesa      = 'Sukarame';
$lokasiSekolah   = 'SMAN 1 Bandar Lampung';

// 3. Ambil Semua Data Mahasiswa yang memiliki NAMA (sudah melengkapi profil)
$mhsQuery = "SELECT USRKEY, NPM, NAMA FROM datamahasiswa WHERE NAMA IS NOT NULL AND NAMA != ''";
$mhsResult = $conn->query($mhsQuery);

if ($mhsResult->num_rows == 0) {
    die("Tidak ada data Mahasiswa aktif di database.\n");
}

echo "Memulai proses pengelompokan mahasiswa...\n";
echo "DPL Terpilih: " . $dplData['NAMADOSEN'] . " (NIP: " . $dplData['NIPDOSEN'] . ")\n";
echo "Lokasi: $lokasiSekolah, $lokasiDesa, $lokasiKecamatan, $lokasiKabupaten\n\n";

$count = 0;
while ($mhs = $mhsResult->fetch_assoc()) {
    $usrkey = $mhs['USRKEY'];
    $npm = $mhs['NPM'];
    $nama = $mhs['NAMA'];

    // Cek apakah mahasiswa sudah punya penempatan
    $checkQuery = "SELECT ID FROM datapenempatan WHERE USRKEY = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $usrkey);
    $stmtCheck->execute();
    $checkResult = $stmtCheck->get_result();

    if ($checkResult->num_rows > 0) {
        // Update penempatan
        $updateQuery = "UPDATE datapenempatan SET 
                            DPLUSRKEY = ?, 
                            NPMPESERTA = ?, 
                            NAMADPL = ?, 
                            NIPDPL = ?, 
                            LOKASIKABUPATEN = ?, 
                            LOKASIKECAMATAN = ?, 
                            LOKASIDESA = ?, 
                            LOKASISEKOLAH = ?, 
                            LOKASIPESERTA = ?
                        WHERE USRKEY = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param(
            "issssssssi", 
            $dplData['USRKEY'], 
            $npm, 
            $dplData['NAMADOSEN'], 
            $dplData['NIPDOSEN'], 
            $lokasiKabupaten, 
            $lokasiKecamatan, 
            $lokasiDesa, 
            $lokasiSekolah, 
            $lokasiSekolah,
            $usrkey
        );
        $stmtUpdate->execute();
        echo "[UPDATE] Mahasiswa '$nama' (NPM: $npm) berhasil di-update ke kelompok.\n";
    } else {
        // Insert penempatan
        $insertQuery = "INSERT INTO datapenempatan (
                            USRKEY, DPLUSRKEY, NPMPESERTA, NAMADPL, NIPDPL, 
                            LOKASIKABUPATEN, LOKASIKECAMATAN, LOKASIDESA, LOKASISEKOLAH, LOKASIPESERTA
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param(
            "iissssssss", 
            $usrkey, 
            $dplData['USRKEY'], 
            $npm, 
            $dplData['NAMADOSEN'], 
            $dplData['NIPDOSEN'], 
            $lokasiKabupaten, 
            $lokasiKecamatan, 
            $lokasiDesa, 
            $lokasiSekolah, 
            $lokasiSekolah
        );
        $stmtInsert->execute();
        echo "[INSERT] Mahasiswa '$nama' (NPM: $npm) berhasil ditambahkan ke kelompok.\n";
    }
    $count++;
}

echo "\nSelesai! Berhasil mengelompokkan $count mahasiswa di bawah bimbingan DPL " . $dplData['NAMADOSEN'] . ".\n";

$conn->close();
