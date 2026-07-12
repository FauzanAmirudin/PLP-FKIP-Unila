<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class bimbingan extends gf_controller
{
    function __construct()
    {
        require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->load->model('user_data', 'user');
        $this->load->model('bimbingan_data', 'bimbingan');
        $this->load->model('penempatan_data', 'penempatan');
        $this->load->model('registration_data', 'registrasi');
        $this->data['user'] = session_get();
    }

    private function getID($data, $level)
    {
        if (is_level($level)) {
            $id = isset($data[0]) ? $data[0] : 0;
            $this->permision = TRUE;
        } else {
            $id = $this->data['user']["ID"];
            if (isset($data[0]) && $id == $data[0]) {
                $this->permision = TRUE;
            } else {
                $this->permision = FALSE;
            }
        }
        return $id;
    }

    public function index($data)
    {
        require_level("Mahasiswa, DPL");
        $id = $this->getID($data, "DPL");
        
        if (!$this->permision) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        }

        $role = is_level("Mahasiswa") ? "Mahasiswa" : "DPL";
        $this->data['role'] = $role;
        $this->data['can_bimbingan'] = true;
        $this->data['bimbingan_message'] = "";

        if ($role == 'Mahasiswa') {
            $registration = $this->registrasi->data($id);
            $tahunDaftar = isset($registration["TAHUNDAFTAR"]) ? $registration["TAHUNDAFTAR"] : null;
            $periodeDaftar = isset($registration["PERIODEDAFTAR"]) ? $registration["PERIODEDAFTAR"] : null;
            $registration_done = $this->registrasi->status_check($id, $tahunDaftar, $periodeDaftar);
            
            $penempatan = $this->penempatan->data($id);
            
            if (!$registration_done) {
                $this->data['can_bimbingan'] = false;
                $this->data['bimbingan_message'] = "Pendaftaran Anda belum disetujui. Anda tidak dapat membuat sesi bimbingan.";
            } else if (empty($penempatan) || empty($penempatan['DPLUSRKEY'])) {
                $this->data['can_bimbingan'] = false;
                $this->data['bimbingan_message'] = "Anda belum memiliki Dosen Pembimbing Lapangan (DPL).";
            }
        }
        $this->data['sesi_list'] = $this->bimbingan->get_sesi_list($id, $role);
        
        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/bimbingan_list", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function buat($data)
    {
        require_level("Mahasiswa");
        $id = $this->getID($data, "Admin"); // Only allowed for self
        
        if (!$this->permision) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        }

        $registration = $this->registrasi->data($id);
        $tahunDaftar = isset($registration["TAHUNDAFTAR"]) ? $registration["TAHUNDAFTAR"] : null;
        $periodeDaftar = isset($registration["PERIODEDAFTAR"]) ? $registration["PERIODEDAFTAR"] : null;
        $registration_done = $this->registrasi->status_check($id, $tahunDaftar, $periodeDaftar);
        
        if (!$registration_done) {
            save_notification("Pendaftaran Anda belum disetujui.");
            redirect("bimbingan/index/" . $id);
            exit;
        }

        // Cek apakah mahasiswa punya DPL
        $penempatan = $this->penempatan->data($id);
        if (empty($penempatan) || empty($penempatan['DPLUSRKEY'])) {
            save_notification("Anda belum memiliki Dosen Pembimbing Lapangan (DPL).");
            redirect("bimbingan/index/" . $id);
            exit;
        }

        // Fetch NAMADOSEN
        $dosen = $this->bimbingan->dbAccess->reset()->tabel('dosen')->where(array('USRKEY' => $penempatan['DPLUSRKEY']))->result_row_array();
        $penempatan['NAMADOSEN'] = isset($dosen['NAMADOSEN']) ? $dosen['NAMADOSEN'] : 'Dosen Tidak Ditemukan';

        $this->data['penempatan'] = $penempatan;

        // Generate CSRF token
        if (!session_get('csrf_token')) {
            session_save('csrf_token', md5(uniqid(rand(), true)));
        }
        $this->data['csrf_token'] = session_get('csrf_token');

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/bimbingan_buat", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function simpan($data)
    {
        require_level("Mahasiswa");
        $id = $this->getID($data, "Admin");
        
        if (!$this->permision) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        }

        // Validasi CSRF
        $csrf = $this->input->post('csrf_token');
        if (empty($csrf) || $csrf !== session_get('csrf_token')) {
            save_notification("Token keamanan tidak valid.");
            redirect("bimbingan/buat/" . $id);
            exit;
        }

        $topik = $this->input->post('topik');
        $deskripsi = $this->input->post('deskripsi');

        if (empty($topik) || empty($deskripsi)) {
            save_notification("Semua kolom harus diisi.");
            redirect("bimbingan/buat/" . $id);
            exit;
        }

        $penempatan = $this->penempatan->data($id);
        if (empty($penempatan) || empty($penempatan['DPLUSRKEY'])) {
            save_notification("DPL tidak ditemukan.");
            redirect("bimbingan/buat/" . $id);
            exit;
        }

        $dpl_usrkey = $penempatan['DPLUSRKEY'];

        $sesi_data = array(
            'USRKEY' => $id,
            'DPL_USRKEY' => $dpl_usrkey,
            'TOPIK' => $topik,
            'DESKRIPSI' => $deskripsi,
            'STATUS' => 'Menunggu'
        );

        $sesi_id = $this->bimbingan->create_sesi($sesi_data);
        if ($sesi_id) {
            save_notification("Sesi bimbingan berhasil dibuat.");
            redirect("bimbingan/sesi/" . $sesi_id);
        } else {
            save_notification("Gagal membuat sesi bimbingan.");
            redirect("bimbingan/buat/" . $id);
        }
    }

    public function sesi($data)
    {
        require_level("Mahasiswa, DPL");
        $sesi_id = isset($data[0]) ? (int)$data[0] : 0;
        
        if ($sesi_id <= 0) {
            save_notification("Sesi tidak valid.");
            redirect("user/dashboard");
            exit;
        }

        $sesi = $this->bimbingan->get_sesi($sesi_id);
        if (empty($sesi)) {
            save_notification("Sesi tidak ditemukan.");
            redirect("user/dashboard");
            exit;
        }

        // Validasi kepemilikan
        $userId = $this->data['user']['ID'];
        $role = is_level("Mahasiswa") ? "Mahasiswa" : "DPL";
        
        if ($role == "Mahasiswa" && $sesi['USRKEY'] != $userId) {
            save_notification("Anda tidak berhak melihat sesi ini.");
            redirect("bimbingan/index/" . $userId);
            exit;
        } else if ($role == "DPL" && $sesi['DPL_USRKEY'] != $userId) {
            save_notification("Anda tidak berhak melihat sesi ini.");
            redirect("bimbingan/index/" . $userId);
            exit;
        }

        // Kalau DPL buka dan status 'Menunggu', ubah ke 'Berlangsung'
        if ($role == "DPL" && $sesi['STATUS'] == 'Menunggu') {
            $this->bimbingan->update_status($sesi_id, 'Berlangsung');
            $sesi['STATUS'] = 'Berlangsung';
        }

        $this->data['role'] = $role;
        $this->data['sesi'] = $sesi;
        $this->data['pesan_list'] = $this->bimbingan->get_pesan($sesi_id);

        // Generate CSRF token
        if (!session_get('csrf_token')) {
            session_save('csrf_token', md5(uniqid(rand(), true)));
        }
        $this->data['csrf_token'] = session_get('csrf_token');

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/bimbingan_sesi", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function kirim($data)
    {
        require_level("Mahasiswa, DPL");
        $sesi_id = isset($data[0]) ? (int)$data[0] : 0;
        
        if ($sesi_id <= 0) {
            save_notification("Sesi tidak valid.");
            redirect("user/dashboard");
            exit;
        }

        $csrf = $this->input->post('csrf_token');
        if (empty($csrf) || $csrf !== session_get('csrf_token')) {
            save_notification("Token keamanan tidak valid.");
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        $pesan = $this->input->post('pesan');
        if (empty(trim($pesan))) {
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        $sesi = $this->bimbingan->get_sesi($sesi_id);
        $userId = $this->data['user']['ID'];
        $role = is_level("Mahasiswa") ? "Mahasiswa" : "DPL";
        
        // Validasi
        if ($role == "Mahasiswa" && $sesi['USRKEY'] != $userId) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        } else if ($role == "DPL" && $sesi['DPL_USRKEY'] != $userId) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        }

        if ($sesi['STATUS'] == 'Selesai') {
            save_notification("Sesi ini sudah selesai, tidak dapat membalas pesan.");
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        $pesan_data = array(
            'SESIKEY' => $sesi_id,
            'SENDER_USRKEY' => $userId,
            'SENDER_ROLE' => $role,
            'PESAN' => $pesan
        );

        $this->bimbingan->send_pesan($pesan_data);
        redirect("bimbingan/sesi/" . $sesi_id);
    }

    public function hapus($data)
    {
        require_level("Mahasiswa");
        $sesi_id = isset($data[0]) ? (int)$data[0] : 0;
        
        if ($sesi_id <= 0) {
            save_notification("Sesi tidak valid.");
            redirect("user/dashboard");
            exit;
        }

        $csrf = $this->input->post('csrf_token');
        if (empty($csrf) || $csrf !== session_get('csrf_token')) {
            save_notification("Token keamanan tidak valid.");
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        $sesi = $this->bimbingan->get_sesi($sesi_id);
        if (empty($sesi)) {
            save_notification("Sesi tidak ditemukan.");
            redirect("user/dashboard");
            exit;
        }

        $userId = $this->data['user']['ID'];
        
        // Cek kepemilikan dan status
        if ($sesi['USRKEY'] != $userId) {
            save_notification("Akses ditolak. Anda tidak berhak menghapus sesi ini.");
            redirect("user/dashboard");
            exit;
        }

        if ($sesi['STATUS'] !== 'Menunggu') {
            save_notification("Hanya sesi dengan status Menunggu yang dapat dihapus.");
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        // Eksekusi hapus
        if ($this->bimbingan->delete_sesi($sesi_id)) {
            save_notification("Sesi bimbingan berhasil dihapus.");
        } else {
            save_notification("Gagal menghapus sesi bimbingan.");
        }

        redirect("bimbingan/index/" . $userId);
    }

    public function download($data)
    {
        require_level("Mahasiswa, DPL");
        $mhs_usrkey = isset($data[0]) ? (int)$data[0] : 0;
        
        if ($mhs_usrkey <= 0) {
            save_notification("Mahasiswa tidak valid.");
            redirect("user/dashboard");
            exit;
        }

        $userId = $this->data['user']['ID'];
        $role = is_level("Mahasiswa") ? "Mahasiswa" : "DPL";
        
        // Ambil data sesi
        $sesi_list = $this->bimbingan->get_all_sesi_for_download($mhs_usrkey);
        
        // Validasi
        if ($role == 'Mahasiswa' && $mhs_usrkey != $userId) {
            save_notification("Anda hanya bisa mendownload data bimbingan Anda sendiri.");
            redirect("bimbingan/index/" . $userId);
            exit;
        }

        if ($role == 'DPL') {
            // Pastikan mahasiswa ini bimbingannya DPL tersebut
            $penempatan = $this->penempatan->data($mhs_usrkey);
            if (empty($penempatan) || $penempatan['DPLUSRKEY'] != $userId) {
                save_notification("Anda tidak memiliki akses ke data bimbingan mahasiswa ini.");
                redirect("bimbingan/index/" . $userId);
                exit;
            }
        }

        // Siapkan variabel untuk diisi
        $nama_mhs = "-";
        $npm_mhs = "-";
        $prodi_mhs = "-";
        $nama_dpl = "-";
        $sekolah = "-";

        if (!empty($sesi_list)) {
            $nama_mhs = $sesi_list[0]['NAMA_MAHASISWA'];
            $npm_mhs = $sesi_list[0]['NPM'];
            $prodi_mhs = $sesi_list[0]['PRODI'];
            $nama_dpl = $sesi_list[0]['NAMA_DPL'];
            $sekolah = isset($sesi_list[0]['SEKOLAH']) ? $sesi_list[0]['SEKOLAH'] : '-';
        } else {
            // Kalau sesi kosong, ambil data dari tabel terkait (karena joinnya return kosong)
            $this->load->model('mahasiswa_data', 'mhs');
            $mhs_data = $this->mhs->data($mhs_usrkey);
            if (!empty($mhs_data)) {
                $nama_mhs = isset($mhs_data['NAMA']) ? $mhs_data['NAMA'] : '-';
                $npm_mhs = isset($mhs_data['NPM']) ? $mhs_data['NPM'] : '-';
                $prodi_mhs = isset($mhs_data['PROGRAMSTUDI']) ? $mhs_data['PROGRAMSTUDI'] : '-';
            }
            
            $penempatan = $this->penempatan->data($mhs_usrkey);
            if (!empty($penempatan)) {
                $sekolah = isset($penempatan['LOKASISEKOLAH']) ? $penempatan['LOKASISEKOLAH'] : '-';
                if (!empty($penempatan['DPLUSRKEY'])) {
                    $dosen = $this->bimbingan->dbAccess->reset()->tabel('dosen')->where(array('USRKEY' => $penempatan['DPLUSRKEY']))->result_row_array();
                    $nama_dpl = isset($dosen['NAMADOSEN']) ? $dosen['NAMADOSEN'] : '-';
                }
            }
        }

        // Mencegah output PHP warning/error yang merusak file
        error_reporting(0);
        ini_set('display_errors', '0');
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $filename = "Buku_Kendali_" . str_replace(" ", "_", $npm_mhs) . "_" . str_replace(" ", "_", $nama_mhs) . ".doc";
        
        header("Content-Type: application/vnd.ms-word");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-disposition: attachment; filename=\"$filename\"");

        // Output HTML
        echo '<html xmlns:w="urn:schemas-microsoft-com:office:word"><head><meta charset="utf-8"><style>
            body { font-family: "Times New Roman", Times, serif; font-size: 12pt; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; }
            th, td { border: 1px solid black; padding: 8px; text-align: left; vertical-align: top; }
            th { text-align: center; font-weight: bold; }
        </style></head><body>';
        
        echo '<h3 style="text-align: center; text-transform: uppercase;">BUKU KENDALI PEMBIMBINGAN PENGENALAN LAPANGAN PERSEKOLAHAN (PLP)</h3>';
        echo '<br/>';
        
        echo '<table style="border: none; width: 100%;">';
        echo '<tr><td style="border: none; width: 30%; padding: 2px;">Nama</td><td style="border: none; width: 2%; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($nama_mhs) . '</td></tr>';
        echo '<tr><td style="border: none; padding: 2px;">NPM</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($npm_mhs) . '</td></tr>';
        echo '<tr><td style="border: none; padding: 2px;">Program Studi</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($prodi_mhs) . '</td></tr>';
        echo '<tr><td style="border: none; padding: 2px;">Dosen Pembimbing Lapangan</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($nama_dpl) . '</td></tr>';
        echo '<tr><td style="border: none; padding: 2px;">Sekolah Mitra</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($sekolah) . '</td></tr>';
        echo '</table>';
        
        echo '<br/>';
        
        echo '<table>';
        echo '<tr>';
        echo '<th style="width: 5%; text-align: center;">No.</th>';
        echo '<th style="width: 20%; text-align: center;">Hari/Tanggal/Tahun</th>';
        echo '<th style="text-align: center;">Catatan Pembimbingan</th>';
        echo '<th style="width: 15%; text-align: center;">Paraf DPL</th>';
        echo '</tr>';
        
        if (empty($sesi_list)) {
            echo '<tr><td colspan="4" style="text-align: center;">Belum ada sesi bimbingan.</td></tr>';
        } else {
            $no = 1;
            foreach ($sesi_list as $sesi) {
                // Hari/Tanggal/Tahun
                $time = strtotime($sesi['CREATEDAT']);
                $hari_array = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                $hari = $hari_array[date('w', $time)];
                $tanggal = date('d-m-Y', $time);
                $waktu_str = $hari . " / " . $tanggal;

                echo '<tr>';
                echo '<td style="text-align: center;">' . $no . '.</td>';
                echo '<td>' . htmlspecialchars($waktu_str) . '</td>';
                echo '<td>' . nl2br(htmlspecialchars($sesi['TOPIK'])) . '</td>';
                echo '<td></td>'; // Paraf
                echo '</tr>';
                
                $no++;
            }
        }
        
        echo '</table>';
        echo '</body></html>';
        exit;
    }

    public function download_dpl($data)
    {
        require_level("DPL");
        
        $userId = $this->data['user']['ID'];
        
        // Ambil semua data sesi yang dimiliki DPL
        $sesi_list_raw = $this->bimbingan->get_all_sesi_for_download_dpl($userId);
        
        // Kelompokkan berdasarkan mahasiswa
        $mhs_sessions = [];
        foreach ($sesi_list_raw as $sesi) {
            $mhs_sessions[$sesi['USRKEY']][] = $sesi;
        }

        // Mencegah output PHP warning/error yang merusak file
        error_reporting(0);
        ini_set('display_errors', '0');
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $filename = "Buku_Kendali_Seluruh_Mahasiswa_DPL.doc";
        
        header("Content-Type: application/vnd.ms-word");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-disposition: attachment; filename=\"$filename\"");

        // Output HTML
        echo '<html xmlns:w="urn:schemas-microsoft-com:office:word"><head><meta charset="utf-8"><style>
            body { font-family: "Times New Roman", Times, serif; font-size: 12pt; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; }
            th, td { border: 1px solid black; padding: 8px; text-align: left; vertical-align: top; }
            th { text-align: center; font-weight: bold; }
            .page-break { page-break-after: always; }
        </style></head><body>';

        if (empty($mhs_sessions)) {
            echo '<h3 style="text-align: center;">Belum ada data bimbingan mahasiswa.</h3>';
        } else {
            $mhs_count = count($mhs_sessions);
            $current_mhs = 0;
            foreach ($mhs_sessions as $usrkey => $sesi_list) {
                $current_mhs++;
                $nama_mhs = $sesi_list[0]['NAMA_MAHASISWA'];
                $npm_mhs = $sesi_list[0]['NPM'];
                $prodi_mhs = $sesi_list[0]['PRODI'];
                $nama_dpl = $sesi_list[0]['NAMA_DPL'];
                $sekolah = isset($sesi_list[0]['SEKOLAH']) ? $sesi_list[0]['SEKOLAH'] : '-';

                echo '<h3 style="text-align: center; text-transform: uppercase;">BUKU KENDALI PEMBIMBINGAN PENGENALAN LAPANGAN PERSEKOLAHAN (PLP)</h3>';
                echo '<br/>';
                
                echo '<table style="border: none; width: 100%;">';
                echo '<tr><td style="border: none; width: 30%; padding: 2px;">Nama</td><td style="border: none; width: 2%; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($nama_mhs) . '</td></tr>';
                echo '<tr><td style="border: none; padding: 2px;">NPM</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($npm_mhs) . '</td></tr>';
                echo '<tr><td style="border: none; padding: 2px;">Program Studi</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($prodi_mhs) . '</td></tr>';
                echo '<tr><td style="border: none; padding: 2px;">Dosen Pembimbing Lapangan</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($nama_dpl) . '</td></tr>';
                echo '<tr><td style="border: none; padding: 2px;">Sekolah Mitra</td><td style="border: none; padding: 2px;">:</td><td style="border: none; padding: 2px;">' . htmlspecialchars($sekolah) . '</td></tr>';
                echo '</table>';
                
                echo '<br/>';
                
                echo '<table>';
                echo '<tr>';
                echo '<th style="width: 5%; text-align: center;">No.</th>';
                echo '<th style="width: 20%; text-align: center;">Hari/Tanggal/Tahun</th>';
                echo '<th style="text-align: center;">Catatan Pembimbingan</th>';
                echo '<th style="width: 15%; text-align: center;">Paraf DPL</th>';
                echo '</tr>';

                $no = 1;
                foreach ($sesi_list as $sesi) {
                    $time = strtotime($sesi['CREATEDAT']);
                    $hari_array = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                    $hari = $hari_array[date('w', $time)];
                    $tanggal = date('d-m-Y', $time);
                    $waktu_str = $hari . " / " . $tanggal;

                    echo '<tr>';
                    echo '<td style="text-align: center;">' . $no . '.</td>';
                    echo '<td>' . htmlspecialchars($waktu_str) . '</td>';
                    echo '<td>' . nl2br(htmlspecialchars($sesi['TOPIK'])) . '</td>';
                    echo '<td></td>'; // Paraf
                    echo '</tr>';
                    
                    $no++;
                }
                echo '</table>';

                // Add page break if it's not the last student
                if ($current_mhs < $mhs_count) {
                    echo '<br clear="all" style="page-break-before:always" />';
                }
            }
        }
        
        echo '</body></html>';
        exit;
    }

    public function selesai($data)
    {
        require_level("DPL");
        $sesi_id = isset($data[0]) ? (int)$data[0] : 0;
        
        if ($sesi_id <= 0) {
            save_notification("Sesi tidak valid.");
            redirect("user/dashboard");
            exit;
        }

        $csrf = $this->input->post('csrf_token');
        if (empty($csrf) || $csrf !== session_get('csrf_token')) {
            save_notification("Token keamanan tidak valid.");
            redirect("bimbingan/sesi/" . $sesi_id);
            exit;
        }

        $sesi = $this->bimbingan->get_sesi($sesi_id);
        $userId = $this->data['user']['ID'];
        
        if ($sesi['DPL_USRKEY'] != $userId) {
            save_notification("Akses ditolak.");
            redirect("user/dashboard");
            exit;
        }

        $this->bimbingan->update_status($sesi_id, 'Selesai');
        save_notification("Sesi bimbingan telah ditandai Selesai.");
        redirect("bimbingan/sesi/" . $sesi_id);
    }
}
