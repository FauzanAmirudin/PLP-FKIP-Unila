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
class downloads extends gf_controller
{
    function __construct()
    {
        parent::__construct();
        require_login();
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
        error_page('505');
    }
    public function formulir($data)
    {
        error_reporting(0);
        ini_set('display_errors', 1);
        $id = $this->getID($data);
        if (isset($_GET['pembimbingakademik']) && isset($_GET['nippembimbingakademik']) && isset($_GET['ketuaProdi'])) {
            $downloadfile = 'formpendaftaran_2020';
            $pembimbingakademik = strip_tags(urldecode($_GET['pembimbingakademik']));
            $nippembimbingakademik = strip_tags(urldecode($_GET['nippembimbingakademik']));
            $kaprodiID = strip_tags(urldecode($_GET['ketuaProdi']));
            if (empty($this->extra->data($id))) {
                $result = $this->extra->insert([
                    'USRKEY'                 => $_SESSION['ID'],
                    'USRID'                  => $_SESSION['USERID'],
                    'KAPRODI'                => $kaprodiID,
                    'PEMBIMBINGAKADEMIK'     => $pembimbingakademik,
                    'NIPPEMBIMBINGAKADEMIK'  => $nippembimbingakademik,
                ]);
            } else {
                $result = $this->extra->update($id, array(
                    'KAPRODI'                => $kaprodiID,
                    'PEMBIMBINGAKADEMIK'     => $pembimbingakademik,
                    'NIPPEMBIMBINGAKADEMIK'  => $nippembimbingakademik,
                ));
            }
            $data_berkas = $this->mahasiswa->data($id);

            $kaprodi = $this->kaprodi->data($kaprodiID);

            $data_berkas['KETUAPRODI'] = $kaprodi["NAMA"];
            $data_berkas['NIPKETUAPRODI'] = $kaprodi["NIP"];
            $data_berkas['PEMBIMBINGAKADEMIK'] = $pembimbingakademik;
            $data_berkas['NIPPEMBIMBINGAKADEMIK'] = $nippembimbingakademik;
            // $data['KETUAPRODI'] = ".....................................";
            // $data['NIPKETUAPRODI'] = " ";

            printtoPDF($downloadfile, "tempelatePDF/Form Pendaftaran " . $_SESSION['USERID'], $data_berkas);
        } else {
            echo "Maaf tidak ada file yang dapat anda downloads";
            exit;
        }
    }

    public function reports($data)
    {
        if (isset($data[0]) && strtolower($data[0]) == 'bundle') {
            if (is_level("Admin, Monitor, Operator, DPL")) {
                if (isset($data[1])) {

                    $berkas = $this->report->data($data[1]);
                    $registration = $this->registrasi->get($data[1]);
                    $data = $this->mahasiswa->data($registration['USRKEY']);

                    $files = array_map(function ($item) {
                        return GF_BASE_PATH . DIRECTORY_SEPARATOR . $item['FILEPATH'] . DIRECTORY_SEPARATOR . $item['FILENAME'] . $item['FILEEXT'];
                    }, $berkas);

                    /* Archive Name */
                    $archive = 'tmp' . DIRECTORY_SEPARATOR . 'Laporan ' . $data["NAMA"] . ' (' . $data["NPM"] . ').zip';

                    /*zip and download */
                    zipFilesAndDownload($files, $archive);
                } else echo "Maaf file id file tidak ada.";
            } else error403();
        } else error404();
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
