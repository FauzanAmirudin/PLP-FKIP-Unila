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
    public function mingguan($data)
    {
        $id = $this->getID($data, "Admin, Monitor, Operator, DPL");
        $this->data['enableregister'] = get_dbconfig('OPENREGISTER');
        
        $registration = $this->registrasi->data($id);

        $tahunDaftar = isset($registration["TAHUNDAFTAR"]) ? $registration["TAHUNDAFTAR"] : null;
        $periodeDaftar = isset($registration["PERIODEDAFTAR"]) ? $registration["PERIODEDAFTAR"] : null;
        
        $this->data['biodata_done'] = $this->mahasiswa->data_check($this->data['user']["ID"], $tahunDaftar, $periodeDaftar);
        $this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"], $tahunDaftar, $periodeDaftar);

        if ($this->data['biodata_done'] && $this->data['registration_done']) {
            $this->data['report'] = $this->report->data($registration['ID']);
        } else {
            $report = '<a>Pendaftaran anda belum disetujui.</a>';
            save_notification($report);
        }

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
            $file = $this->input->post('laporan');
            $timpa = $this->input->post('timpa');

            $data = $this->mahasiswa->data($id);
            $registration = $this->registrasi->data($id);

            $ID = $data['NPM'] . "(" . $id . ")";
            $section = $registration["TAHUNDAFTAR"] . "-files" . DIRECTORY_SEPARATOR . str_replace(" ", "_", $registration["PERIODEDAFTAR"]);
            $folder     =    'uploads' . DIRECTORY_SEPARATOR . 'berkas-laporan' . DIRECTORY_SEPARATOR . $section . DIRECTORY_SEPARATOR . $ID;
            $upload = $this->input->upload('file', $file, $folder, array("type" => 'doc, docx', "sizelimit" => '5000', "update" => !empty($timpa), "fileHash" => TRUE));
            if ($upload['status']) {
                $upload['data']['NPM'] = $data['NPM'];
                $this->report->save($id, $registration["BRKSKEY"], $upload['data'], $this->data['user']['USERID']);
            }
            $report = $upload['report'];
        } else $report = "Anda tidak memeiliki izin untuk meng upload file ini.";
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
        $this->data['form_link'] = "laporan/data/" . $id;

        $this->data['mahasiswa'] = $this->report->list($this->data['tahun'], $this->data['periode'], $id, $this->data['npm']);

        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/reportcheck", $this->data);
        $this->load->view("footer", $this->data);
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
