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
            $reportId = $this->input->get('id');
            $filename = $this->input->get('object');
            $this->data['filename'] = $filename;
            $this->data['res'] = $this->report->get((int)$reportId);
            
            // Framework stores view in $this->html buffer — not suitable for AJAX.
            // We extract vars and include directly so output goes to browser immediately.
            $res = $this->data['res'];
            header('Content-Type: text/html; charset=utf-8');
            include(GF_APP_PATH . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . 'report_status.php');
            exit;
        }

        if ($ajax == 'balas_laporan') {
            // Baca dari POST (dikirim via hidden input) dengan fallback ke GET
            $npm      = $this->input->post('id')      ?: $this->input->get('id');
            $filename = $this->input->post('object')  ?: $this->input->get('object');
            $response = $this->input->post('respons');
            $comment  = $this->input->post('komentar');

            $res = $this->report->save_response($npm, $filename, $response, $comment);
            if ($res) {
                $this->data['success'] = true;
            } else {
                $this->data['error'] = 'Gagal menyimpan respons.';
            }
            $this->load->view('ajax/report_message', $this->data);
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
        $tahun = $this->input->get('tahun');
        $periode = $this->input->get('periode');
        $npm = $this->input->get('npm');

        $this->data['config'] = get_dbconfig();
        $id = $this->getID($data, "Admin, Monitor, Operator");

        $this->data['alltahun'] = $this->registrasi->register_year();
        $this->data['tahun'] = empty($tahun) ? (!empty($this->data['alltahun']) ? current($this->data['alltahun'])['TAHUNDAFTAR'] : NULL) : $tahun;
        $this->data['allperiode'] = $this->registrasi->register_periode();
        $this->data['periode'] = empty($periode) ? (!empty($this->data['allperiode']) ? current($this->data['allperiode'])['PERIODEDAFTAR'] : NULL) : $periode;
        $this->data['npm'] = $npm;
        $id = $this->getID($data, "Admin, Monitor, Operator");
        $this->data['form_link'] = "laporan/data/" . $id;

        $dosenFilter = is_level("Admin, Monitor, Operator") ? NULL : $id;

        $this->data['mahasiswa'] = $this->report->list($this->data['tahun'], $this->data['periode'], $dosenFilter, $this->data['npm']);

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/reportcheck", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function review_form($data)
    {
        require_level("Admin, Monitor, Operator, DPL");
        $reportId = isset($data[0]) ? $data[0] : '';
        $this->data['nama_mahasiswa'] = isset($data[1]) ? urldecode($data[1]) : 'Tidak Diketahui';
        
        if (empty($reportId)) {
            save_notification("Data laporan tidak valid.");
            redirect("laporan/data/" . $this->data['user']['ID']);
            exit;
        }

        $this->data['res'] = $this->report->get((int)$reportId);
        if (empty($this->data['res'])) {
            save_notification("Laporan tidak ditemukan di database.");
            redirect("laporan/data/" . $this->data['user']['ID']);
            exit;
        }

        $this->data['reportId'] = $reportId;
        $this->data['filename'] = $this->data['res']['FILENAME'];
        $this->data['npm'] = $this->data['res']['NPM'];

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/reportreview", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function save_review($data)
    {
        require_level("Admin, Monitor, Operator, DPL");
        $reportId = $this->input->post('reportId');
        $respons = $this->input->post('respons');
        $komentar = $this->input->post('komentar');
        $nama_mahasiswa = $this->input->post('nama_mahasiswa');

        if (empty($reportId) || empty($respons) || empty($komentar)) {
            save_notification("Harap lengkapi semua form respons dan komentar.");
            redirect("laporan/review_form/" . urlencode($reportId) . "/" . urlencode($nama_mahasiswa));
            exit;
        }

        $res = $this->report->response((int)$reportId, $respons, $komentar);
        if ($res) {
            save_notification("Respons Laporan berhasil disimpan.");
        } else {
            save_notification("Gagal menyimpan respons. Coba lagi.");
        }
        
        redirect("laporan/data/" . $this->data['user']['ID']);
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
