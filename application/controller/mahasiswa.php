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
class mahasiswa extends gf_controller
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
	public function data($data)
	{
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$id = $this->getID($data);
		$this->data['biodata'] = $this->mahasiswa->data($id);

		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
			if ($this->mahasiswa->data_check($id) && !$this->data['registration_done']) {
				$report = '<a>Terimakasih telah melengkapi biodata, anda dapat membuka menu pendaftaran untuk doewnload dan mencetak formulir melalui link berikut.</a><br/>';
				$report .= '<a href="' . set_url($this->controler_name . "/pendaftaran/" . $id) . '">Download Formuir</a>';
				save_notification($report);
			}
		}
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/biodatamahasiswa", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function pendaftaran($data)
	{
		$id = $this->getID($data);
		$currentYear = get_dbconfig('CURENTYEAR');
		$currentSemester = get_dbconfig('CURENTSEMESTER');
		$this->data['registration_process'] = $this->registrasi->check($this->data['user']["ID"], $currentYear, $currentSemester);
		$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"], $currentYear, $currentSemester);
		$this->data['registration_history'] = $this->registrasi->history($this->data['user']["ID"]);
		
		if (!$this->data['registration_done']) {
			$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
			$this->data['biodata_done'] = $this->mahasiswa->data_check($this->data['user']["ID"]);
			if (!$this->data['biodata_done'] || !$this->data['enableregister']) {
				if (!$this->data['enableregister']) {
					$this->data['field'] = FALSE;
					$report = empty($this->data['registration_process']) ? '<a>Anda belum diperkenankan untuk mendaftar, pendaftaran telah ditutup.</a><br/>' : '';
				} else if (!$this->data['biodata_done']) {
					$report = '<a>Anda belum diperkenankan untuk mendaftar, silahkan lengkapi biodata ada terlebih dahulu.</a><br/>';
					$report .= '<a href="' . set_url($this->controler_name . "/data/" . $id) . '">Lengkapi Data</a>';
				}
				save_notification($report);
			}
			$this->data['aditionaldata'] = $this->extra->data($id);
			$this->data['dataKaprodi'] = $this->kaprodi->list();
			$this->data['uploadberkas'] = TRUE;
			$this->data['downloadberkas'] = TRUE;
		}

		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registration", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function penempatan($data)
	{
		$id = $this->getID($data);
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		$this->data['biodata_done'] = $this->mahasiswa->data_check($this->data['user']["ID"]);
		if ($this->data['biodata_done'] && $this->data['registration_done']) {
			$this->data['placement'] = $this->mahasiswa->placement($id);
			if (isset($this->data['placement']) && !empty($this->data['placement']['DPLUSRKEY'])) {
				$this->data['group'] = $this->mahasiswa->group($this->data['placement']['DPLUSRKEY']);
			} else $this->data['group'] = NULL;
		} else {
			$report = '<a>Pendaftaran anda belum disetujui.</a>';
			save_notification($report);
		}

		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/penempatan", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function simpan_biodata($data)
	{
		$id = $this->getID($data);
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
			if ($this->data['registration_done']) {
				$report	= "Registrasi anda telah disetujui oleh karena itu anda tidak dapat lagi merubah data.";
				save_notification($report);
				redirect($this->controler_name . "/data/" . $id);
			}
		}
		$myName 		 = setToProper($this->input->post('nama'));
		// $myNpm 			 = ucwords(strip_tags($_POST['npm']));
		$myJurusan 		 = $this->input->post('jurusan');
		$myProgram1 	 = $this->input->post('programStudy1');
		$myProgram2 	 = $this->input->post('programStudy2');
		$myProgram3 	 = $this->input->post('programStudy3');
		$myProgram4 	 = $this->input->post('programStudy4');
		$myProgramSTUDI	 = str_replace("'", "&apos;", "$myProgram1$myProgram2$myProgram3$myProgram4");
		$mySks 			 = $this->input->post('sks');
		$myIpk 			 = $this->input->post('ipk');
		$myJenisKELAMIN	 = $this->input->post('jenisKelamin');
		$myAgama 		 = $this->input->post('agama');
		$myUkuranBAJU 	 = $this->input->post('ukuranBaju');
		$myNoHP 		 = $this->input->post('noHp');
		$myKetrampilan 	 = $this->input->post('ketrampilan');
		$myOrganisasi 	 = $this->input->post('organisasi');
		$myAlamatTINGGAL = setToProper($this->input->post('alamatTinggal'));
		$myNamaAYAH 	 = setToProper($this->input->post('namaAyah'));
		$myNamaIBU 		 = setToProper($this->input->post('namaIbu'));
		$myNoHpOrangTUA  = $this->input->post('noHpOrangTUA');
		$myNamaGenting 	 = setToProper($this->input->post('nameGenting'));
		$myNoHpGenting 	 = $this->input->post('noHpGenting');
		$myAlamatASAL 	 = setToProper($this->input->post('alamatAsal'));
		$myKecamatan 	 = setToProper($this->input->post('kecamatan'));
		$myKabupaten 	 = setToProper($this->input->post('kabupaten'));
		$myPropinsi 	 = setToProper($this->input->post('propinsi'));
		$biodata = array(
			"NAMA" 			=> $myName,
			"JURUSAN" 		=> $myJurusan,
			"PROGRAMSTUDI" 	=> $myProgramSTUDI,
			"SKS" 			=> $mySks,
			"IPK" 			=> $myIpk,
			"JENISKELAMIN" 	=> $myJenisKELAMIN,
			"AGAMA" 		=> $myAgama,
			"NOTELEPON" 	=> $myNoHP,
			"ALAMATTINGGAL" => $myAlamatTINGGAL,
			"UKURANBAJU" 	=> $myUkuranBAJU,
			"KETRAMPILAN" 	=> $myKetrampilan,
			"ORGANISASI" 	=> $myOrganisasi,
			"NAMAAYAH" 		=> $myNamaAYAH,
			"NAMAIBU" 		=> $myNamaIBU,
			"NOHPORTU" 		=> $myNoHpOrangTUA,
			"NAMAGENTING"	=> $myNamaGenting,
			"NOHPGENTING"	=> $myNoHpGenting,
			"ALAMATASAL" 	=> $myAlamatASAL,
			"KECAMATAN" 	=> $myKecamatan,
			"KABUPATEN" 	=> $myKabupaten,
			"PROPINSI" 		=> $myPropinsi
		);
		if ($this->permision) {
			$user_data = $this->mahasiswa->data($id);
			// If result matched row table will fetch
			if (empty($user_data)) {
				$user = $this->user->check($id, 'ID');
				$biodata['USRKEY']	= $id;
				$biodata['NPM']		= $user['USERID'];
				$result = $this->mahasiswa->insert($biodata);
			} else {
				$result = $this->mahasiswa->update($id, $biodata);
			}
			if ($result === TRUE) {
				$report	= "Biodata berhasil di perbaharui.";
			} else $report = "Error! pembaharuan biodata gagal.";
		} else $report	= "Anda tidak memiliki izin untuk merubah biodata.";
		save_notification($report);
		redirect($this->controler_name . "/data/" . $id);
	}
	public function simpan_foto($data)
	{
		$id = $this->getID($data);
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
			if ($this->data['registration_done']) {
				$report	= "Registrasi anda telah disetujui oleh karena itu anda tidak dapat lagi merubah data.";
				save_notification($report);
				redirect($this->controler_name . "/data/" . $id);
			}
		}
		if ($this->permision) {
			$data = $this->mahasiswa->data($this->data['user']['ID']);
			$folder = 'uploads' . DIRECTORY_SEPARATOR . 'berkas-foto' . DIRECTORY_SEPARATOR . get_dbconfig("CURENTYEAR") . '-files' . DIRECTORY_SEPARATOR . str_replace(" ", "_", get_dbconfig("CURENTSEMESTER"));
			$file = $data['NPM'] . "(" . $this->data['user']['ID'] . ")";
			$upload = $this->input->upload('file', $file, $folder, array("type" => 'jpg', "sizelimit" => '100', "update" => TRUE));
			if ($upload['status']) {
				$result = $this->mahasiswa->update($id, array(
					'FTPROFIL' => $upload['data']["FILELINK"]
				));
			}
			$report = $upload['report'];
		} else $report = "Anda tidak memeiliki izin untuk meng upload file ini.";
		save_notification($report);
		redirect($this->controler_name . "/data/" . $id);
	}
	private function getID($data)
	{
		if (is_level("Admin, Monitor, Operator, DPL")) {
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
