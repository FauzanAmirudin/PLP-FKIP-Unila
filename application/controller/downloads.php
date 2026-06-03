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
                    'USRKEY'                 => $this->data['user']['ID'],
                    'USRID'                  => $this->data['user']['USERID'],
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

            printtoPDF($downloadfile, "tempelatePDF/Form Pendaftaran " . $this->data['user']['USERID'], $data_berkas);
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
                    $berkasId = (int) $data[1];

                    // Ambil USRKEY langsung dari databerkas via model
                    $berkasRow = $this->report->get_berkas_by_id($berkasId);

                    if (empty($berkasRow) || empty($berkasRow['USRKEY'])) {
                        echo "Maaf, data berkas tidak ditemukan.";
                        return;
                    }

                    $usrkey = (int) $berkasRow['USRKEY'];

                    // Ambil data laporan berdasarkan USRKEY via model
                    $berkas = $this->report->direct_by_usrkey($usrkey);

                    if (empty($berkas)) {
                        echo "Maaf, tidak ada laporan untuk mahasiswa ini.";
                        return;
                    }

                    // Ambil data mahasiswa untuk nama arsip
                    $mahasiswaData = $this->mahasiswa->data($usrkey);

                    $files = array_values(array_filter(array_map(function ($item) {
                        $relative_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $item['FILELINK']);
                        $path = GF_BASE_PATH . DIRECTORY_SEPARATOR . $relative_path;
                        return file_exists($path) ? $path : null;
                    }, $berkas)));

                    if (empty($files)) {
                        echo "Maaf, file laporan tidak ditemukan di server.";
                        return;
                    }

                    /* Archive Name */
                    $nama    = !empty($mahasiswaData['NAMA']) ? $mahasiswaData['NAMA'] : 'Mahasiswa';
                    $npm     = !empty($mahasiswaData['NPM'])  ? $mahasiswaData['NPM']  : $usrkey;
                    $archive = 'tmp' . DIRECTORY_SEPARATOR . 'Laporan ' . $nama . ' (' . $npm . ').zip';

                    /* zip and download */
                    zipFilesAndDownload($files, $archive);
                } else echo "Maaf, id file tidak ada.";
            } else error403();
        } elseif (isset($data[0]) && strtolower($data[0]) == 'file') {
            if (is_level("Admin, Monitor, Operator, DPL, Mahasiswa")) {
                if (isset($data[1])) {
                    $reportId = (int) $data[1];
                    $reportRow = $this->report->get($reportId);
                    
                    if (empty($reportRow) || empty($reportRow['FILELINK'])) {
                        echo "Maaf, data laporan tidak ditemukan.";
                        return;
                    }

                    if (is_level("Mahasiswa") && $reportRow['USRKEY'] != $this->data['user']['ID']) {
                        echo "Maaf, Anda tidak memiliki akses ke file ini.";
                        return;
                    }

                    $relative_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $reportRow['FILELINK']);
                    $path = GF_BASE_PATH . DIRECTORY_SEPARATOR . $relative_path;

                    if (!file_exists($path)) {
                        echo "Maaf, file fisik laporan tidak ditemukan di server.";
                        return;
                    }

                    $filename = !empty($reportRow['FILENAME']) ? $reportRow['FILENAME'] : basename($path);
                    if (strpos($filename, '.') === false && !empty($reportRow['FILEEXT'])) {
                        $filename .= '.' . $reportRow['FILEEXT'];
                    }

                    while (ob_get_level()) {
                        ob_end_clean();
                    }

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($path));
                    
                    readfile($path);
                    exit;
                } else {
                    echo "Maaf, id laporan tidak valid.";
                }
            } else {
                error403();
            }
        } else {
            error404();
        }
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
