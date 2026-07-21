<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
class upload extends gf_controller
{
	// PHP 8.2+ membutuhkan deklarasi properti eksplisit
	private $exel_load_report = '';
	private $exel_load_memory = '';
    function __construct()
    {
        require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->load->model('user_data', 'user');
        $this->load->model('mahasiswa_data', 'mahasiswa');
        $this->load->model('penempatan_data', 'penempatan');
        $this->load->model('registration_data', 'registrasi');
        $this->load->model('kaprodi_data', 'kaprodi');
        $this->load->model('extra_data', 'extra');
        $this->data['user'] = session_get();
    }
    public function index()
    {
        error_page('505');
    }
    public function validatioon()
    {
        require_level("Admin, Operator");
        $this->data['enableregister'] = get_dbconfig('OPENREGISTER');
        $response = '';
        if (!empty($this->input->post())) {
            $berkasid = $this->input->post('idmahasiswa');
            $npm = $this->input->post('npmmahasiswa');
            $status = $this->input->post('status');
            $catatan = $this->input->post('catatanberkas');
            $catatan = strip_tags(!empty($catatan) ? $catatan : "Tidak ada.");
            $berkas = $this->registrasi->check_by($berkasid, "`datastatus`.`BRKSKEY`");
            $mahasiswa = $this->mahasiswa->check_by($npm, "NPM");
            if (!empty($berkas)) {
                switch ($status) {
                    case 'approved':
                        $state = 'Disetujui';
                        if ($state !== $berkas["STATUSBERKAS"]) {
                            $result = $this->registrasi->status_update($berkasid, $state, $catatan, session_get('USERID'), NULL, NULL);
                            if ($result) $response .= "Status berkas " . $mahasiswa["NAMA"] . " telah diubah menjadi <b>" . $result["STATUSBERKAS"] . "</b>.";
                        } else $response .= 'Status berkas ' . $mahasiswa["NAMA"] . ' tidak diubah, berkas sudah berstatus ' . $berkas["STATUSBERKAS"] . ' yang dilakukan oleh: ' . $berkas["VALIDATOR"] . ' pada tanggal ' . $berkas["DATEVALID"] . '.';
                        break;

                    case 'rejected':
                        $state = 'Ditolak';
                        $result = $this->registrasi->status_update($berkasid, $state, $catatan, session_get('USERID'), NULL, NULL);
                        if ($result) $response .= "Status berkas " . $mahasiswa["NAMA"] . " telah diubah menjadi <b>" . $result["STATUSBERKAS"] . "</b>.";
                        break;

                    case 'delete':
                        $response .= "Maaf, fitur delete belum tersedia.";
                        break;

                    default:
                        $response .= "Maaf, fitur ini belum tersedia.";
                        break;
                }
            } else $response .= 'Berkas tidak ditemukan!';
        } else $response .= "Maaf anda tidak memiliki izin untuk melakukan perubahan status.";
        echo $response;
    }
    public function users()
    {
        require_level("Admin");
        $folder     =    'uploads' . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'user';
        $upload = $this->input->upload('file', "Bulk_user_upload", $folder, array("type" => 'xlsx', "sizelimit" => '5000', "update" => TRUE, "fileHash" => TRUE));
        if ($upload["status"] === TRUE) {
            echo '<div class="info info-success"><a>' . $upload["report"] . '</a></div>';
            $error = '';
            $requiredField = ["user", "nama", "pass", "type"];

            $data = $this->load_exel_file(
                $upload["data"]["FILELINK"],
                $requiredField,
                $upload["data"]["FILEEXT"]
            );

            $num = 0;
            $successCount = 0;
            $failedCount = 0;
            foreach ($data['raw'] as $row) {
                if ($num == 0) {
                    foreach ($row as $key => $cell) {
                        $lcell = strtolower($cell);
                        $rKey = array_search($lcell, $requiredField);
                        if ($rKey !== FALSE) {
                            $requiredField[$requiredField[$rKey]] = $lcell == $requiredField[$rKey] ? $key : $requiredField[$rKey];
                            unset($requiredField[$rKey]);
                        }
                    }
                    foreach ($requiredField as $key) {
                        if (!is_int($key)) {
                            $error .= "Coloum " . strtoupper($key) . " tidak ditemukan di dalam tabel, mohon lengkapi kembali data user.";
                        }
                    }
                } else {
                    if ($error == '') {
                        foreach ($requiredField as $field => $key) {
                            $row[$key] = trim($row[$key]);
                            if ($field == 'user') {
                                $row[$key] = str_replace(" ", "", $row[$key]);
                            }
                        }

                        $Username   = $row[$requiredField["user"]];
                        $FullName   = $row[$requiredField["nama"]];
                        $Password   = $row[$requiredField["pass"]];
                        $Type       = $row[$requiredField["type"]];
                        if (!empty($Username) && !empty($FullName) && !empty($Type) && !empty($Password)) {
                            $userCheck = $this->user->check($Username);
                            if (empty($userCheck)) {
                                $result = $this->user->insert(array(
                                    "USERID"      => $Username,
                                    "PASSWORD"    => str_encrypt($Password),
                                    "STAT"        => $Type,
                                    "NOTE"        => $FullName,
                                    "ACTIVE"    => 1,
                                ));
                                if ($result) {
                                    $user = $this->user->check($Username);
                                    switch ($Type) {
                                        case 'DPL':
                                            $result = $this->user->insert_config(array(
                                                "USRKEY"        => $user['ID'],
                                                "NAMADOSEN"     => $FullName,
                                                "NIPDOSEN"      => $Username
                                            ), "DPL");
                                            break;
                                        case 'Mahasiswa':
                                            $result = $this->mahasiswa->insert(array(
                                                "NAMA"      => $FullName,
                                                "NPM"       => $Username,
                                                "USRKEY"    => $user['ID']
                                            ));
                                            break;
                                        default:
                                            break;
                                    }
                                    if ($result == TRUE) {
                                        $successCount++;
                                    } else {
                                        $failedCount++;
                                    }
                                } else $failedCount++;
                            } else $failedCount++;
                        } else $failedCount++;
                    }
                }
                $num++;
            }
            if ($error == '') {
                $totalData = $num - 1;
                $html = '<div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 25px; border-radius: 12px; text-align: center; font-family: sans-serif; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-top: 20px;">';
                $html .= '<h3 style="color: #166534; margin: 0 0 20px 0; font-size: 20px;">Upload Berhasil Diproses!</h3>';
                $html .= '<div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #3b82f6;"><span style="display:block; font-size: 28px; font-weight: bold; color: #1e3a8a;">'.$totalData.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Total Data</span></div>';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #22c55e;"><span style="display:block; font-size: 28px; font-weight: bold; color: #15803d;">'.$successCount.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Berhasil</span></div>';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #ef4444;"><span style="display:block; font-size: 28px; font-weight: bold; color: #b91c1c;">'.$failedCount.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Gagal/Dilewati</span></div>';
                $html .= '</div></div>';
                
                echo json_encode(array(
                    "status" => true,
                    "messege" => "",
                    "data" => $html
                ));
            } else {
                echo json_encode(array(
                    "status" => false,
                    "messege" => '<div class="info info-danger" style="margin-top: 20px;"><a>' . $error . '</a></div>',
                    "data" => ""
                ));
            }
        } else {
            echo json_encode(array(
                "status" => false,
                "messege" => '<div class="info info-danger"><a>' . $upload["report"] . '</a></div>',
                "data" => ""
            ));
        }
        exit;
    }
    public function assignment()
    {
        require_level("Admin");
        $folder     =    'uploads' . DIRECTORY_SEPARATOR . 'administrator';
        $upload = $this->input->upload('file', "Bulk_assignment_upload", $folder, array("type" => 'xlsx', "sizelimit" => '5000', "update" => TRUE, "fileHash" => TRUE));
        if ($upload["status"] === TRUE) {
            $requiredField = ["nama", "npm", "nama_dpl", "lokasi_kabupaten", "lokasi_kecamatan", "lokasi_desa", "lokasi_sekolah"];
            $result = $this->load_exel_file(
                $upload["data"]["FILELINK"],
                $requiredField
            );
            if ($result['status']) {
                $successCount = 0;
                $failedCount = 0;
                $totalCount = 0;
                foreach ($result['data']['row'] as $row) {
                    $totalCount++;
                    $fieldNotFound = $requiredField;
                    foreach ($row as $key => $value) {
                        $id = array_search(strtolower($key), $fieldNotFound);
                        if ($id !== FALSE) {
                            unset($fieldNotFound[$id]);
                        }
                    }
                    if (empty($fieldNotFound)) {
                        $dplName = nameFIlter($row['NAMA_DPL']);
                        $dplSearch = $this->user->search($dplName, "NAMADOSEN");
                        if (!empty($dplSearch)) {
                            if ($dplSearch['NAMADOSEN'] == $dplName) {
                                $dplCheck = $this->user->check($dplSearch['NIPDOSEN']);
                                if (!empty($dplSearch)) {
                                    $userCheck = $this->user->check($row['NPM']);
                                    if (!empty($userCheck)) {
                                        $data = array(
                                            'USRKEY'            => $userCheck["ID"],
                                            'NPMPESERTA'        => $row["NPM"],
                                            'DPLUSRKEY'         => $dplCheck["ID"],
                                            'NAMADPL'           => secureInput($dplName),
                                            'NIPDPL'            => $dplCheck["USERID"],
                                            'LOKASIKABUPATEN'   => secureInput($row['LOKASI_KABUPATEN']),
                                            'LOKASIKECAMATAN'   => secureInput($row['LOKASI_KECAMATAN']),
                                            'LOKASIDESA'        => secureInput($row['LOKASI_DESA']),
                                            'LOKASISEKOLAH'     => secureInput($row['LOKASI_SEKOLAH'])
                                        );
                                        $dataCheck = $this->penempatan->check($userCheck["ID"]);
                                        if (empty($dataCheck)) {
                                            $res = $this->penempatan->insert($data);
                                            if ($res == TRUE) $successCount++; else $failedCount++;
                                        } else {
                                            $res = $this->penempatan->update($userCheck["ID"], $data);
                                            if ($res == TRUE) $successCount++; else $failedCount++;
                                        }
                                    } else $failedCount++;
                                } else $failedCount++;
                            } else $failedCount++;
                        } else $failedCount++;
                    } else $failedCount++;
                }
                
                $html = '<div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 25px; border-radius: 12px; text-align: center; font-family: sans-serif; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-top: 20px;">';
                $html .= '<h3 style="color: #166534; margin: 0 0 20px 0; font-size: 20px;">Upload Penempatan Selesai!</h3>';
                $html .= '<div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #3b82f6;"><span style="display:block; font-size: 28px; font-weight: bold; color: #1e3a8a;">'.$totalCount.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Total Data</span></div>';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #22c55e;"><span style="display:block; font-size: 28px; font-weight: bold; color: #15803d;">'.$successCount.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Berhasil Disimpan</span></div>';
                $html .= '<div style="background: #ffffff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 3px solid #ef4444;"><span style="display:block; font-size: 28px; font-weight: bold; color: #b91c1c;">'.$failedCount.'</span><span style="font-size: 14px; color: #6b7280; font-weight: 500;">Gagal/Dilewati</span></div>';
                $html .= '</div></div>';

                echo json_encode(array(
                    "status" => true,
                    "messege" => "",
                    "data" => $html
                ));
            } else {
                echo json_encode(array(
                    "status" => false,
                    "messege" => '<div class="info info-danger"><a>' . $result['messege'] . '</a></div>',
                    "data" => null
                ));
            }
        } else {
            echo json_encode(array(
                "status" => false,
                "messege" => '<div class="info info-danger"><a>' . $upload["report"] . '</a></div>',
                "data" => null
            ));
        }
        exit;
    }
    private function load_exel_file($xlsxFile, $requiredField, $EXT = "xlsx")
    {
        if (file_exists($xlsxFile)) {
            try {
                $error = '';
                // Simpan pengaturan error saat ini
                $previous_error_reporting = error_reporting();
                // Matikan peringatan Deprecated untuk library usang ini
                error_reporting($previous_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

                switch (strtolower($EXT)) {
                    case 'xls':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                        break;
                    case 'xml':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xml();
                        break;
                    case 'ods':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
                        break;
                    case 'csv':
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        break;
                    case 'xlsx':
                    default:
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        break;
                }
                $reader->setReadDataOnly(true);

                /* Find the first worksheetname */
                $worksheetData = $reader->listWorksheetInfo($xlsxFile);
                $sheetName = $worksheetData[0]['worksheetName'];

                /* Set what worksheet to open */
                $reader->setLoadSheetsOnly($sheetName);

                /*  Load file to a Spreadsheet Object  */
                $spreadsheet = $reader->load($xlsxFile);

                /* read data from worksheet */
                $worksheet = $spreadsheet->getActiveSheet();

                $data = $worksheet->toArray();

                $num = 0;
                $dataHead = [];
                $preparedData = [];
                foreach ($data as $id => $row) {
                    if ($num == 0) {
                        foreach ($row as $key => $cell) {
                            $lcell = str_replace(" ", "_", strtolower($cell));
                            $rKey = array_search($lcell, $requiredField);
                            if ($rKey !== FALSE) {
                                $requiredField[$requiredField[$rKey]] = $lcell == $requiredField[$rKey] ? $key : $requiredField[$rKey];
                                unset($requiredField[$rKey]);
                            }
                        }
                        $missing_column = "";
                        foreach ($requiredField as $key) {
                            if (is_int($key)) {
                                array_push($dataHead, $row[$key]);
                            } else {
                                if ($missing_column != "") {
                                    $missing_column .= ", ";
                                }
                                $missing_column .= strtoupper($key);
                            }
                        }
                        if ($missing_column != "") {
                            $error .= "Coloum " . $missing_column . " tidak ditemukan di dalam tabel, mohon lengkapi kembali data user.";
                        }
                    } else {
                        if ($error == '') {
                            $rData = [];
                            if ($row[0] != NULL) {
                                $spesial_field = ['user', 'userid', 'nip_dpl', 'npm'];
                                foreach ($requiredField as $field => $key) {
                                    $row[$key] = trim($row[$key]);
                                    if (in_array($field, $spesial_field)) {
                                        $row[$key] = str_replace(" ", "", $row[$key]);
                                    }
                                    $field = strtoupper($field);
                                    $rData[$field] = $row[$key];
                                }
                                array_push($preparedData, $rData);
                            } else unset($data[$id]);
                        }
                    }
                    $num++;
                }
                error_reporting($previous_error_reporting);
                return array(
                    'status' => $error == '', TRUE, FALSE,
                    'messege' =>  $error,
                    'data' => array(
                        'head' => $dataHead,
                        'row' => $preparedData
                    ),
                    'raw' => $data
                );
            } catch (Exception $e) {
                error_reporting($previous_error_reporting);
                $this->exel_load_report .= $e->getMessage();
                exit;
            }
            $this->exel_load_memory = (memory_get_peak_usage(true) / 1024 / 1024) . "MB";
        } else $this->exel_load_report = "FIle not found!";
    }
}
