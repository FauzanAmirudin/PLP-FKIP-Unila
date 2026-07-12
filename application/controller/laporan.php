<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Contorler
|--------------------------------------------------------------------------
|
| Controler aplications
|
*/
class laporan extends gf_controller
{
    function __construct()
    {
        require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->load->model('user_data', 'user');
        $this->load->model('mahasiswa_data', 'mahasiswa');
        $this->load->model('registration_data', 'registrasi');
        $this->load->model('kaprodi_data', 'kaprodi');
        $this->load->model('report_data', 'report');
        $this->load->model('extra_data', 'extra');
        $this->data['user'] = session_get();
    }
    public function index()
    {
        $ajax = $this->input->get('ajax');
        if ($ajax == 'get_response') {
            $reportId = (int)$this->input->get('id');
            $res = $this->report->get($reportId);
            
            // Validasi kepemilikan: laporan harus milik user yang sedang login
            $userId = $this->data['user']['ID'];
            $userLevel = isset($this->data['user']['LEVEL']) ? $this->data['user']['LEVEL'] : '';
            $isAdmin = (stripos($userLevel, 'Admin') !== false || stripos($userLevel, 'DPL') !== false || stripos($userLevel, 'Monitor') !== false || stripos($userLevel, 'Operator') !== false);
            
            if (empty($res) || (!$isAdmin && $res['USRKEY'] != $userId)) {
                header('Content-Type: text/html; charset=utf-8');
                http_response_code(403);
                exit;
            }
            
            // Framework stores view in $this->html buffer — not suitable for AJAX.
            // We include directly so output goes to browser immediately.
            header('Content-Type: text/html; charset=utf-8');
            include(GF_APP_PATH . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . 'report_status.php');
            exit;
        }
    }
    public function mingguan($data)
    {
        $id = $this->getID($data, "Admin, Monitor, Operator, DPL");
        $this->data['enableregister'] = get_dbconfig('OPENREGISTER');
        $this->data['config'] = get_dbconfig();

        // Check registration status to enforce upload rules
        $registration = $this->registrasi->data($id);
        $tahunDaftar = isset($registration["TAHUNDAFTAR"]) ? $registration["TAHUNDAFTAR"] : null;
        $periodeDaftar = isset($registration["PERIODEDAFTAR"]) ? $registration["PERIODEDAFTAR"] : null;
        $this->data['registration_done'] = $this->registrasi->status_check($id, $tahunDaftar, $periodeDaftar);

        if (!$this->data['registration_done']) {
            save_notification("Pendaftaran Anda belum disetujui. Anda tidak dapat mengupload laporan.");
        }

        // Always load laporan directly by USRKEY — so they can still view past reports if any
        $this->data['report'] = $this->report->direct($id);

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/laporan", $this->data);
        $this->load->view("footer", $this->data);
    }
    public function simpan($data)
    {
        $id = $this->getID($data, "Admin, Monitor, Operator, DPL");
        if ($this->permision) {
            $file  = $this->input->post('laporan');
            $timpa = $this->input->post('timpa');

            $mahasiswaData = $this->mahasiswa->data($id);
            $registration = $this->registrasi->data($id);
            $tahunDaftar = isset($registration["TAHUNDAFTAR"]) ? $registration["TAHUNDAFTAR"] : null;
            $periodeDaftar = isset($registration["PERIODEDAFTAR"]) ? $registration["PERIODEDAFTAR"] : null;
            $registration_done = $this->registrasi->status_check($id, $tahunDaftar, $periodeDaftar);

            if (empty($mahasiswaData) || empty($mahasiswaData['NPM'])) {
                save_notification("Upload Gagal: Data mahasiswa tidak ditemukan.");
                redirect($this->controler_name . "/mingguan/" . $id);
                exit;
            }

            if (!$registration_done) {
                save_notification("Upload Gagal: Anda belum menyelesaikan pendaftaran atau pendaftaran belum disetujui.");
                redirect($this->controler_name . "/mingguan/" . $id);
                exit;
            }

            $npm    = $mahasiswaData['NPM'];
            $config = get_dbconfig();
            $tahun  = !empty($config['CURENTYEAR'])    ? $config['CURENTYEAR']    : date('Y');
            $periode = !empty($config['CURENTSEMESTER']) ? $config['CURENTSEMESTER'] : 'Periode_1';

            $ID      = $npm . "(" . $id . ")";
            $section = $tahun . "-files" . DIRECTORY_SEPARATOR . str_replace(" ", "_", $periode);
            $folder  = 'uploads' . DIRECTORY_SEPARATOR . 'berkas-laporan' . DIRECTORY_SEPARATOR . $section . DIRECTORY_SEPARATOR . $ID;

            $upload = $this->input->upload('file', $file, $folder, array(
                "type"      => 'doc, docx',
                "sizelimit" => '5000',
                "update"    => !empty($timpa),
                "fileHash"  => TRUE
            ));

            if ($upload['status']) {
                $upload['data']['NPM'] = $npm;
                // Pastikan BERKASID valid — blokir upload jika data registrasi tidak lengkap
                if (empty($registration['ID'])) {
                    save_notification("Upload Gagal: Data registrasi tidak ditemukan, tidak dapat menyimpan laporan.");
                    @unlink(GF_BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $upload['data']['FILELINK']));
                    redirect($this->controler_name . "/mingguan/" . $id);
                    exit;
                }
                $berkasId = (int)$registration['ID'];
                $db_result = $this->report->save($id, $berkasId, $upload['data'], $this->data['user']['USERID']);
                if ($db_result) {
                    $report = "Upload laporan berhasil!";
                } else {
                    $report = "Upload Gagal: Terjadi kesalahan saat menyimpan data ke database.";
                    @unlink(GF_BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $upload['data']['FILELINK']));
                }
            } else {
                $report = $upload['report'];
            }
        } else {
            $report = "Anda tidak memiliki izin untuk mengupload file ini.";
        }
        save_notification($report);
        redirect($this->controler_name . "/mingguan/" . $id);
    }
    public function data($data)
    {
        require_level("Admin, Monitor, Operator, DPL");
        $formSubmitted = isset($_GET['tahun']); // form selalu kirim tahun jika sudah di-submit
        $tahun = !empty($_GET['tahun']) ? (int)$this->input->get('tahun') : NULL;
        $npm = $this->input->get('npm');

        $this->data['config'] = get_dbconfig();
        $id = $this->getID($data, "Admin, Monitor, Operator");

        $this->data['alltahun'] = $this->registrasi->register_year();
        
        // Jika form belum disubmit, default ke tahun terbaru dari db
        if ($tahun === NULL && !$formSubmitted) {
            $tahun = !empty($this->data['alltahun']) ? (int)current($this->data['alltahun'])['TAHUNDAFTAR'] : NULL;
        }
        $this->data['tahun'] = $tahun;

        // Ambil periode dari tahun aktif
        $this->data['allperiode'] = $this->registrasi->register_periode((int)$tahun);

        // Jika disubmit, ambil periode apa adanya (bisa kosong untuk 'Semua Periode')
        // Jika belum disubmit, default ke periode pertama dari tahun aktif
        if ($formSubmitted) {
            $periode = isset($_GET['periode']) && $_GET['periode'] !== '' ? $_GET['periode'] : NULL;
        } else {
            $periode = !empty($this->data['allperiode']) ? current($this->data['allperiode'])['PERIODEDAFTAR'] : NULL;
        }
        $this->data['periode'] = $periode;
        $this->data['npm'] = $npm;
        $prodi = $this->input->get('prodi');
        $this->data['prodi'] = $prodi;

        $this->data['allprodi'] = $this->report->dbAccess->reset()->tabel('datamahasiswa')->distinct("PROGRAMSTUDI")->where("PROGRAMSTUDI IS NOT NULL")->where("PROGRAMSTUDI != ''")->order("PROGRAMSTUDI")->result_array();
        
        $this->data['form_link'] = "laporan/data/" . $id;

        $dosenFilter = is_level("Admin, Monitor, Operator") ? NULL : $id;

        $this->data['mahasiswa'] = $this->report->list($this->data['tahun'], $this->data['periode'], $dosenFilter, $this->data['npm'], $this->data['prodi']);

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/reportcheck", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function review_form($data)
    {
        require_level("Admin, Monitor, Operator, DPL");
        $reportId = isset($data[0]) ? (int)$data[0] : 0;
        $this->data['nama_mahasiswa'] = isset($data[1]) ? urldecode($data[1]) : 'Tidak Diketahui';
        
        if ($reportId <= 0) {
            save_notification("Data laporan tidak valid.");
            redirect("laporan/data/" . $this->data['user']['ID']);
            exit;
        }

        $this->data['res'] = $this->report->get($reportId);
        if (empty($this->data['res'])) {
            save_notification("Laporan tidak ditemukan di database.");
            redirect("laporan/data/" . $this->data['user']['ID']);
            exit;
        }

        $this->data['reportId'] = $reportId;
        $this->data['filename'] = $this->data['res']['FILENAME'];
        $this->data['npm'] = $this->data['res']['NPM'];

        // Generate CSRF token
        if (!session_get('csrf_token')) {
            session_save('csrf_token', md5(uniqid(rand(), true)));
        }
        $this->data['csrf_token'] = session_get('csrf_token');

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/reportreview", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function save_review($data)
    {
        require_level("Admin, Monitor, Operator, DPL");

        // Validasi CSRF token
        $csrf = $this->input->post('csrf_token');
        if (empty($csrf) || $csrf !== session_get('csrf_token')) {
            save_notification("Token keamanan tidak valid. Silakan coba lagi.");
            redirect("laporan/data/" . $this->data['user']['ID']);
            exit;
        }

        $reportId = (int)$this->input->post('reportId');
        $respons = $this->input->post('respons');
        $komentar = $this->input->post('komentar');
        $nama_mahasiswa = $this->input->post('nama_mahasiswa');

        if ($reportId <= 0 || empty($respons) || empty($komentar)) {
            save_notification("Harap lengkapi semua form respons dan komentar.");
            redirect("laporan/review_form/" . $reportId . "/" . urlencode($nama_mahasiswa));
            exit;
        }

        $res = $this->report->response($reportId, $respons, $komentar);
        if ($res) {
            save_notification("Respons Laporan berhasil disimpan.");
        } else {
            save_notification("Gagal menyimpan respons. Coba lagi.");
        }
        
        redirect("laporan/data/" . $this->data['user']['ID']);
    }
    public function download_massal($data)
    {
        require_level("Admin, Monitor, Operator, DPL");
        
        if (is_level("DPL")) {
            $id = $this->data['user']["ID"];
        } else {
            $id = $this->input->get('dpl_id');
            if (empty($id)) {
                save_notification("ID DPL tidak valid.");
                redirect("laporan/data/" . $this->data['user']["ID"]);
                exit;
            }
        }
        
        $tahun = !empty($_GET['tahun']) ? (int)$this->input->get('tahun') : NULL;
        $periode = !empty($_GET['periode']) ? $_GET['periode'] : NULL;
        $prodi = !empty($_GET['prodi']) ? $_GET['prodi'] : NULL;
        
        // Ambil daftar mahasiswa bimbingan DPL ini
        $mahasiswa_list = $this->report->list($tahun, $periode, $id, NULL, $prodi);

        if (empty($mahasiswa_list)) {
            save_notification("Tidak ada data mahasiswa atau laporan untuk diunduh.");
            redirect("laporan/data/" . $id);
            exit;
        }

        $zip = new ZipArchive();
        $zipFileName = "Laporan_Akhir_Massal_DPL_" . $id . ".zip";
        $zipFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            save_notification("Gagal membuat file ZIP.");
            redirect("laporan/data/" . $id);
            exit;
        }

        $files_added = 0;

        foreach ($mahasiswa_list as $mhs) {
            if (!empty($mhs['LAPORAN'])) {
                foreach ($mhs['LAPORAN'] as $lap) {
                    $judul_laporan = $lap['FILENAME'];
                    // Hanya memproses jika judulnya mengandung "Laporan Akhir PLP 1" atau "Laporan Akhir PLP 2"
                    if (stripos($judul_laporan, "Laporan Akhir PLP 1") !== false || stripos($judul_laporan, "Laporan Akhir PLP 2") !== false || stripos($judul_laporan, "Laporan Akhir") !== false) {
                        $file_link = GF_BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $lap['FILELINK']);
                        
                        if (file_exists($file_link)) {
                            $ext = pathinfo($file_link, PATHINFO_EXTENSION);
                            
                            // Ekstrak PLP 1 atau PLP 2 untuk penamaan
                            $tipe_plp = "Akhir";
                            if (stripos($judul_laporan, "PLP 1") !== false) $tipe_plp = "PLP_1";
                            if (stripos($judul_laporan, "PLP 2") !== false) $tipe_plp = "PLP_2";
                            
                            $nama_mhs_bersih = preg_replace('/[^a-zA-Z0-9]+/', '_', trim($mhs['NAMA']));
                            $npm = $mhs['NPM'];
                            
                            $newFileName = $npm . "_" . $nama_mhs_bersih . "_Laporan_" . $tipe_plp . "." . $ext;
                            
                            $zip->addFile($file_link, $newFileName);
                            $files_added++;
                        }
                    }
                }
            }
        }

        $zip->close();

        if ($files_added > 0) {
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $zipFileName);
            header('Content-Length: ' . filesize($zipFilePath));
            readfile($zipFilePath);
            unlink($zipFilePath);
            exit;
        } else {
            unlink($zipFilePath);
            save_notification("Tidak ada Laporan Akhir PLP 1 atau PLP 2 yang ditemukan dari mahasiswa bimbingan Anda.");
            redirect("laporan/data/" . $id);
            exit;
        }
    }

    private function getID($data, $level)
    {
        if (is_level($level)) {
            $id = $data[0];
            $this->permision = TRUE;
        } else {
            $id = $this->data['user']["ID"];
            if ($id == $data[0]) {
                $this->permision = TRUE;
            } else $this->permision = FALSE;
        }
        return $id;
    }
}
