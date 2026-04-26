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
class user extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('dbconfig');
		$this->load->model('user_data', 'user');
		$this->load->model('mahasiswa_data', 'mahasiswa');
		$this->load->model('registration_data', 'registrasi');
	}
	public function registration()
	{
		$report = '';
		if (!empty($this->input->post())) {
			if (get_dbconfig('OPENREGISTER')) {
				$this->captcha->session_var = 'secretword';
				/** Validate captcha */
				if ($this->captcha->validate($this->input->post('captcha'))) {
					$name = ucwords(strtolower($this->input->post('name')));
					$npm = $this->input->post('npm');
					$pass = $this->input->post('pass');
					$npass = $this->input->post('npass');
					$user = $this->user->check($npm);
					// If result matched $myusername and $mypassword, table row must be 1 row
					if (empty($user)) {
						if ($pass == $npass) {
							$result_user = $this->user->insert(array(
								"USERID"	=> $npm,
								"PASSWORD"	=> str_encrypt($pass),
								"STAT"		=> 'Mahasiswa',
								"ACTIVE"	=> 1,
							));
							$dataUser = $this->user->check($npm);
							$result_dataMahasiswa = $this->mahasiswa->insert(array(
								"NAMA"		=> $name,
								"NPM"		=> $npm,
								'USRKEY'	=> $dataUser['ID'],
							));
							if ($result_user == true && $result_dataMahasiswa == true) {
								$report .= 'SELAMAT!';
								$report .= '\n';
								$report .= 'Anda sudah terdaftar silahkan login untuk melengkapi biodata dan mencetak formulir pendaftaran.';
							}
						} else {
							$report .= 'GAGAL!';
							$report .= '\n';
							$report	.= 'Password yang anda masukan tidak sama silahkan ulangi kembali dan pastikan kedua password yang anda masukan sama.';
						}
					} else {
						$report .= 'GAGAL! \n';
						$report .= '\n';
						$report	.= 'NPM yang anda masukan sudah terdaftar sebagai peserta periksa kembani data yang anda masukan. Apabila anda merasa belum pernah mendaftar silahkan hubungi sekretariat PLT.';
					}
				} else {
					$report .= '\n - Captcha yang anda masukan salah';
				}
			} else {
				$report .= 'GAGAL! \n';
				$report .= '\n';
				$report	.= 'Pendaftaran PLP sedang ditutup untuk informasi lebih lanjut silahkan hubungi sekretariat PLT.';
			}
			save_notification($report);
			redirect();
		} else redirect();
	}
	public function login()
	{
		$report = '';
		if (!empty($this->input->post())) {
			$this->captcha->session_var = 'secretword';
			/** Validate captcha */
			if ($this->captcha->validate($this->input->post('captcha'))) {
				$mypassword = $this->input->post('password');
				$myusername = $this->input->post('username');
				$user = $this->user->check($myusername);
				// If result matched $myusername and $mypassword, table row must be 1 row
				if (!empty($user)) {
					if ($user["PASSWORD"] == str_encrypt($mypassword)) {
						login($user['ID'], $user['STAT']);
						$userData = $this->user->get_data($user['ID'], $user['STAT']);
						$fullName = hapus_gelar(isset($userData['NAMA']) ? $userData['NAMA'] : (isset($userData['NAMADOSEN']) ? $userData['NAMADOSEN'] : $userData['NOTE']));
						$shortName = perpendek_nama($fullName);
						session_save('USERID', $user['USERID']);
						session_save('FULLNAME', $fullName);
						session_save('NAME', $shortName);
						redirect('user/dashboard');
					} else {
						$report	.= 'Login gagal:';
						$report .= '\n - Password yang anda masukan salah';
						$report .= '\n\nSilahkan coba kembali atau menghubungi admin di Sekretariat PLT FKIP UNILA';
						save_notification($report);
						redirect();
					}
				} else {
					$report	.= 'Login gagal:';
					$report .= '\n - Anda Belum terdaftar sebagai User';
					$report .= '\n\nSilahkan mendaftar atau menghubungi admin di Sekretariat PLT FKIP UNILA';
					save_notification($report);
					redirect();
				}
			} else {
				$report .= '\n - Captcha anda salah';
				save_notification($report);
				redirect();
			}
		} else {
			redirect();
		}
	}
	public function logout()
	{
		logout();
	}
	public function index()
	{
		$this->dashboard();
	}
	public function dashboard()
	{
		require_login();
		$this->data['user'] = session_get();
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$this->data['page'] = array(
			'notice' => $this->user->notice(),
			'scajule' => $this->user->scajule()
		);
		if (is_level('Mahasiswa')) {
			$this->data['registration_process'] = $this->registrasi->data($this->data['user']["ID"]);
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		}
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/dashboard", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function settings()
	{
		require_login();
		$this->data['user'] = session_get();
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		}
		if (!empty($this->input->post())) {
			$mypassword = $this->input->post('passwordOld');
			$passNew1	= $this->input->post('passwordNew1');
			$passNew2	= $this->input->post('passwordNew2');
			if ($passNew1 == $passNew2) {
				$user = $this->user->data(login_data());
				if ($user["PASSWORD"] == str_encrypt($mypassword)) {
					$update = $this->user->update($user['ID'], array("PASSWORD" => str_encrypt($passNew1)));
					if ($update) {
						$report	= "Password anda berhasil dirubah";
					} else $report = "Error! tidak diketahui, hubungi pengembang.";
				} else {
					$report = "Password yang anda masukan salah";
				}
			} else {
				$report = "Password baru yang anda masukan berbeda, pastikan keduanya sama";
			}
			save_notification($report);
		}
		$this->data['notification'] = implode("<br/>", get_notification());;
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/settingaccount", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function password_reset()
	{
		require_login();
		require_level("Admin, Operator");
		$this->data['user'] = session_get();
		if (!empty($this->input->post())) {
			$NPM = $this->input->post('NPM');
			$user = $this->user->check($NPM);
			if (!empty($user)) {
				$result = $this->user->update($user['ID'], array('PASSWORD' => str_encrypt("majuteruspltfkip")));
				if ($result == TRUE) {
					$report = $NPM . ' Password Berhasil Direset Menjadi "majuteruspltfkip"';
				} else {
					$report = $NPM . ' Password Gagal Direset';
				}
			} else $report = 'User ' . $NPM . ' Tidak ditemukan.';
			save_notification($report);
		}
		$this->data['config'] = get_dbconfig();
		$this->alert = implode("\n", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/resetpassword", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function create_user()
	{
		require_login();
		require_level("Admin");
		$this->data['user'] = session_get();
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
		$this->load->view("page/createuser", $this->data);
		$this->load->view("footer", $this->data);
	}
}
