<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| User Contorler
|--------------------------------------------------------------------------
|
| Controler aplications
|
*/
class admin extends gf_controller
{
    function __construct()
    {
        require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->load->model('user_data', 'user');
        $this->load->model('mahasiswa_data', 'mahasiswa');
        $this->load->model('registration_data', 'registrasi');
        $this->data['user'] = session_get();
    }
    public function index()
    {
        $this->monitor();
    }
    public function monitor()
    {
        require_login();
        require_level("Admin, Monitor, Operator");
        $this->data['user'] = session_get();
        $this->data['enableregister'] = get_dbconfig('OPENREGISTER');
        $this->data['curentyear'] = get_dbconfig('CURENTYEAR');
        $this->data['curentperiode'] = get_dbconfig('CURENTSEMESTER');
        $this->data['page'] = array(
            'notice' => $this->user->notice(),
            'scajule' => $this->user->scajule()
        );
        if (is_level('Mahasiswa')) {
            $this->data['page']['berkas'] = $this->registrasi->data($this->data['user']["ID"]);
            $this->data['registration_process'] = $this->registrasi->data($this->data['user']["ID"]);
        }
        $this->data['notification'] = implode("<br/>", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/monitor", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function impersonate()
    {
        require_login();
        require_level("Admin");
        $this->data['user'] = session_get();
        if (!empty($this->input->post())) {
            $username = $this->input->post('username');
            $user = $this->user->check($username);
            if (!empty($user)) {
                $d_user = $this->user->get_data($user['ID'], $user['STAT']);
                session_save("ORIGNIAL_USER", login_data());
                session_save("IMPERSONATE", TRUE);
                login($user['ID'], $user['STAT']);
                session_save('USERID', $user['USERID']);
                $name = hapus_gelar(isset($d_user['NAMA']) ? $d_user['NAMA'] : (isset($d_user['NAMADOSEN']) ? $d_user['NAMADOSEN'] : $d_user['NOTE']));
                session_save('FULLNAME', $name);
                session_save('NAME', perpendek_nama($name));
                $report = 'Anda masuk sebagai ' . $name;
                save_notification($report);
                redirect('user/dashboard');
            } else {
                $report = 'User ' . $username . ' Tidak ditemukan.';
                save_notification($report);
            }
        }
        $this->data['config'] = get_dbconfig();
        $this->alert = implode("\n", get_notification());
        $this->load->view("navigation", $this->data);
        $this->load->view("sidebar", $this->data);
        $this->load->view("page/siteimpersonate", $this->data);
        $this->load->view("footer", $this->data);
    }
    public function restore_impersonate()
    {
        $login = session_get("ORIGNIAL_USER");
        if ($login) {
            $user = $this->user->data($login);
            $d_user = $this->user->get_data($login['ID'], $login['LEVEL']);
            if (!empty($user)) {
                session_delete("IMPERSONATE");
                session_delete("ORIGNIAL_USER");
                login($login['ID'], $login['LEVEL']);
                session_save('USERID', $user['USERID']);
                $name = hapus_gelar(isset($d_user['NAMA']) ? $d_user['NAMA'] : (isset($d_user['NAMADOSEN']) ? $d_user['NAMADOSEN'] : $d_user['NOTE']));
                session_save('FULLNAME', $name);
                session_save('NAME', perpendek_nama($name));
                $report = 'Anda masuk sebagai ' . $name;
            } else $report = 'Login data anda telah tidak ditemukan.';
            save_notification($report);
        }
        redirect('user/dashboard');
    }
    private function getID($iid)
    {
        if (is_level("Admin, Monitor, Operator")) {
            $id = $iid;
            $this->permision = TRUE;
        } else {
            $id = $this->data['user']["ID"];
            if ($id == $iid) {
                $this->permision = TRUE;
            } else $this->permision = FALSE;
        }
        return $id;
    }
}
