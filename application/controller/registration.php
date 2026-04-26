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
class registration extends gf_controller
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
		$this->load->model('extra_data', 'extra');
		$this->data['user'] = session_get();
	}
	public function index()
	{
		$this->validate();
	}
	public function submit($data)
	{
		$id = $this->getID($data);
		if ($this->permision) {
			$file = $this->data['user']['USERID'] . "(" . $this->data['user']['ID'] . ")";
			$folder = 'uploads/berkas-pendaftaran/' . get_dbconfig("CURENTYEAR") . '-files/' . str_replace(" ", "_", get_dbconfig("CURENTSEMESTER")) . "/" . $file;
			$upload = $this->input->upload('file', $file, $folder, array("type" => 'zip', "sizelimit" => '1000', "update" => TRUE));
			if ($upload['status']) {
				$this->registrasi->save($id, $upload['data']["FILELINK"], $this->data['user']['USERID']);
			}
			$report = $upload['report'];
		} else $report = "Anda tidak memeiliki izin untuk meng upload file ini.";
		save_notification($report);
		redirect("mahasiswa/pendaftaran/" . $id);
	}
	public function validate()
	{
		require_level("Admin, Operator");
		$this->data['config'] = get_dbconfig();
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$tahun  = $this->input->get('tahun');
		$periode  = $this->input->get('periode');
		$prodi  = $this->input->get('prodi');
		$berkas = $this->input->get('status');
		$npm    = $this->input->get('npm');

		$this->data['tahun'] = !empty($tahun) ? $tahun : get_dbconfig('CURENTYEAR');
		$this->data['periode'] = !empty($periode) ? $periode : get_dbconfig('CURENTSEMESTER');

		$this->data['allprodi'] = $this->registrasi->register_prodi($this->data['tahun'], $this->data['periode']);
		$this->data['prodi'] = empty($npm) ? (!empty($prodi) ? $prodi : (!empty($this->data['allprodi']) ? current($this->data['allprodi'])["PROGRAMSTUDI"] : NULL)) : NULL;

		$this->data['berkas'] = !empty($berkas) ? $berkas : NULL;

		$this->data['npm'] = !empty($npm) ? $npm : NULL;

		$this->data['registration_list'] = $this->registrasi->list($this->data['tahun'], $this->data['periode'], $this->data['berkas'], $this->data['prodi'], $this->data['npm']);

		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationvalidate", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function data()
	{
		require_level("Admin, Monitor, Operator");
		$this->data['config'] = get_dbconfig();
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$tahun = $this->input->get('tahun');
		$periode = $this->input->get('periode');
		$npm = $this->input->get('npm');
		$prodi = $this->input->get('prodi');

		$this->data['alltahun'] = $this->registrasi->register_year();
		$this->data['tahun'] = !empty($tahun) ? $tahun : (!empty($this->data['alltahun']) ? current($this->data['alltahun'])['TAHUNDAFTAR'] : NULL);

		$this->data['allperiode'] = $this->registrasi->register_periode((int)$this->data['tahun']);
		$this->data['periode'] = !empty($periode) ? $periode : (!empty($this->data['allperiode']) ? current($this->data['allperiode'])['PERIODEDAFTAR'] : NULL);

		$this->data['allprodi'] = $this->registrasi->register_prodi((int)$this->data['tahun'], $this->data['periode']);
		$this->data['prodi'] = empty($npm) ? (!empty($prodi) ? $prodi : (!empty($this->data['allprodi']) ? current($this->data['allprodi'])["PROGRAMSTUDI"] : NULL)) : NULL;

		$this->data['npm'] = $npm;

		$this->data['mahasiswa'] = $this->registrasi->list($this->data['tahun'], $this->data['periode'], NULL, $this->data['prodi'], $this->data['npm']);

		$data = $this->registrasi->statistic();

		$labels  = $series = [];
		$barCount = 0;
		foreach ($data as $n => $d) {
			array_push($labels, $d["TITLE"] == NULL ? "Kumulatif Data Lampau" : $d["TITLE"] . " (" . $d["SUBTITLE"] . ")");
			array_push($series, $d["JUMLAHPESERTA"]);
			$barCount++;
		}

		$this->data['statistic'] = array(
			"labels" => $labels,
			"series" => $series
		);

		$this->data["barCount"] = $barCount;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationlist", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function assignment()
	{
		require_level("Admin");
		if (!empty($this->input->post())) {
			$Username = $this->input->post('User');
			$FullName = $this->input->post('Name');
			$Type = $this->input->post('Type');
			$Password = $this->input->post('Password');
			$rePassword = $this->input->post('rePassword');
			$this->data['UserData'] = array($Username, $FullName, $Type);
			if (!empty($Username) && !empty($FullName) && !empty($Type) && !empty($Password) && !empty($rePassword)) {
				$userCheck = $this->user->check($Username);
				if (empty($userCheck)) {
					if ($Password === $rePassword) {
						$result = $this->user->insert(array(
							"USERID"	=> $Username,
							"PASSWORD"	=> str_encrypt($Password),
							"STAT"		=> $Type,
							"NOTE"		=> $FullName,
							"ACTIVE"	=> 1,
						));
						if ($result == TRUE) {
							$report = 'User ' . $Type . ' dengan username ' . $Username . ' Berhasil dibuat dengan password ' . $Password;
						} else {
							$report = 'User ' . $Username . ' Password Gagal dibuat.';
						}
					} else $report = 'Password yang dimasukan tidak sama.';
				} else $report = 'User sudah terdaftar di dalam system.';
			} else $report = 'Data tidak lengkap mohon isi semua data yang diperlukan.';
			save_notification($report);
		}
		$this->data['config'] = get_dbconfig();
		$this->alert = implode("\n", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationassignment", $this->data);
		$this->load->view("footer", $this->data);
	}
	private function getID($data)
	{
		if (is_level("Admin, Operator")) {
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
