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
class system extends gf_controller
{
    function __construct()
    {
        // require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->load->model('registration_data', 'registrasi');
        $this->data['user'] = session_get();
    }
    public function index()
    {
        error_page('505');
    }
    public function devide($input)
    {
        // require_level("Admin");
        set_time_limit(5000);
        $tahun = 2020;
        $periode = "Ganjil";
        $prodi = NULL;
        $member = 60;

        $data['mahasiswa'] = $this->registrasi->list($tahun, $periode, "Disetujui", $prodi, NULL);
        $data['total'] = count($data['mahasiswa']);
        $data['group'] = (int)round($data['total'] / $member);
        $data['extra'] = (int)round($data['total'] / $data['group'] / $member, 0, PHP_ROUND_HALF_UP);
        $data['lengh'] = strlen($data['group']);

        $DAFTARKELOMPOK  = [];

        $beginGroup      = 1;
        $curentGroup     = 1;

        $curentAngota = 1;
        foreach ($data['mahasiswa'] as $mahasiswa) {
            $namaKelompok = "Kelompok_" . str_pad($curentGroup, $data['lengh'], "0", STR_PAD_LEFT);
            if (!isset($DAFTARKELOMPOK[$namaKelompok])) $DAFTARKELOMPOK[$namaKelompok] = [];
            $mahasiswa = array(
                'ID'            => $mahasiswa["ID"],
                'USRKEY'        => $mahasiswa["USRKEY"],
                'KELOMPOK'      => $namaKelompok,
                'JENISKELAMIN'  => $mahasiswa["JENISKELAMIN"]
            );
            array_push($DAFTARKELOMPOK[$namaKelompok], $mahasiswa);
            $curentGroup++;
            $angota = count($DAFTARKELOMPOK[$namaKelompok]);
            if ($curentAngota > $angota) {
                $curentGroup--;
            } else {
                $curentAngota = $angota;
            }
            if ($curentGroup > $data['group']) $curentGroup = $beginGroup;
        }
        
        var_dump(array_map(function ($item) {
           return count($item);
        }, $DAFTARKELOMPOK));
        // exit;
    }
}
