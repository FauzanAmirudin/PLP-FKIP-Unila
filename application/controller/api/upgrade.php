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
class upgrade extends gf_controller
{
    function __construct()
    {
        require_login();
        parent::__construct();
        $this->load->helper('dbconfig');
        $this->data['user'] = session_get();
    }
    public function index()
    {
        error_page('505');
    }
    public function init()
    {
        require_level("Admin");
        $userTabel = 'users';
        set_time_limit(5000);
        $mahasiswa_backups = new gf_sql(GF_DB['backups']);
        $mahasiswa_wroking = new gf_sql(GF_DB['default']);
        $backups = $mahasiswa_backups->tabel($userTabel)
            ->column(array(
                "`" . $userTabel . "`.`ID`",
                "`" . $userTabel . "`.`USERID`",
                "`" . $userTabel . "`.`PASSWORD`",
                "`" . $userTabel . "`.`STAT`",
                "`" . $userTabel . "`.`ACTIVE`",
                "`" . $userTabel . "`.`SETTING`",
                "`" . $userTabel . "`.`CREATEDATE`",
                "`" . $userTabel . "`.`NOTE`",
                "`datamahasiswa`.`NAMA`",
                "`datamahasiswa`.`NPM`",
                "`datamahasiswa`.`JURUSAN`",
                "`datamahasiswa`.`PROGRAMSTUDI`",
                "`datamahasiswa`.`SKS`",
                "`datamahasiswa`.`IPK`",
                "`datamahasiswa`.`JENISKELAMIN`",
                "`datamahasiswa`.`AGAMA`",
                "`datamahasiswa`.`NOTELEPON`",
                "`datamahasiswa`.`UKURANBAJU`",
                "`datamahasiswa`.`ALAMATTINGGAL`",
                "`datamahasiswa`.`KETRAMPILAN`",
                "`datamahasiswa`.`ORGANISASI`",
                "`datamahasiswa`.`NAMAAYAH`",
                "`datamahasiswa`.`NAMAIBU`",
                "`datamahasiswa`.`NOHPORTU`",
                "`datamahasiswa`.`ALAMATASAL`",
                "`datamahasiswa`.`KECAMATAN`",
                "`datamahasiswa`.`KABUPATEN`",
                "`datamahasiswa`.`PROPINSI`",
                "`datamahasiswa`.`NAMAGENTING`",
                "`datamahasiswa`.`NOHPGENTING`",
                "`datamahasiswa`.`FTPROFIL`",
                "`datamahasiswa`.`TAHUNDAFTAR`",
                "`datamahasiswa`.`SEMESTERDAFTAR`",
                "`datamahasiswa`.`TIMEUPDATE`",

                "`datapenempatan`.`DPLUSRKEY`",
                "`datapenempatan`.`NPMPESERTA`",
                "`datapenempatan`.`NAMADPL`",
                "`datapenempatan`.`NIPDPL`",
                "`datapenempatan`.`LOKASIKABUPATEN`",
                "`datapenempatan`.`LOKASIKECAMATAN`",
                "`datapenempatan`.`LOKASIDESA`",
                "`datapenempatan`.`LOKASISEKOLAH`",
                "`datapenempatan`.`LOKASIPESERTA`",

                "`aditionaldata`.`KAPRODI`",
                "`aditionaldata`.`PEMBIMBINGAKADEMIK`",
                "`aditionaldata`.`NIPPEMBIMBINGAKADEMIK`"
            ))
            ->join("datamahasiswa", "datamahasiswa.USRKEY = " . $userTabel . ".ID")
            ->join("aditionaldata", "aditionaldata.USRKEY = " . $userTabel . ".ID")
            ->join("datapenempatan", "datapenempatan.USRKEY = " . $userTabel . ".ID")
            ->where("" . $userTabel . ".STAT = 'Mahasiswa'")
            // ->where("" . $userTabel . ".ID = 3675")
            // ->where("" . $userTabel . ".ID < 600")
            // ->limit(200, 5000)
            ->result_array();
        // echo($mahasiswa_backups->last_query);
        // exit;
        $last_row['ID'] = 0;
        foreach ($backups as $backup) {
            if ($last_row['ID'] !== $backup['ID']) {
                $last_row['ID'] = $backup['ID'];

                $user = $mahasiswa_wroking->reset()->tabel($userTabel)->where(["USERID" => $backup["USERID"]])->result_row_array();

                if (!empty($user)) {
                    $datauser = array(
                        'USERID'         => $backup['USERID'],
                        'PASSWORD'       => $backup['PASSWORD'],
                        'STAT'           => $backup['STAT'],
                        'ACTIVE'         => $backup['ACTIVE'],
                        'SETTING'        => $backup['SETTING'],
                        'CREATEDATE'     => $backup['CREATEDATE'],
                        'NOTE'           => $backup['NOTE'],
                    );
                    // $mahasiswa_wroking->reset()->tabel($userTabel)->insert($datauser);

                    $datamahasiswa = array(
                        'ID'                => $backup['ID'],
                        'NAMA'              => $backup['NAMA'],
                        'NPM'               => !empty($backup['NPM']) ? $backup['NPM'] : $backup['USERID'],
                        'JURUSAN'           => $backup['JURUSAN'],
                        'PROGRAMSTUDI'      => $backup['PROGRAMSTUDI'],
                        'SKS'               => $backup['SKS'],
                        'IPK'               => $backup['IPK'],
                        'JENISKELAMIN'      => $backup['JENISKELAMIN'],
                        'AGAMA'             => $backup['AGAMA'],
                        'NOTELEPON'         => $backup['NOTELEPON'],
                        'UKURANBAJU'        => $backup['UKURANBAJU'],
                        'ALAMATTINGGAL'     => $backup['ALAMATTINGGAL'],
                        'KETRAMPILAN'       => $backup['KETRAMPILAN'],
                        'ORGANISASI'        => $backup['ORGANISASI'],
                        'NAMAAYAH'          => $backup['NAMAAYAH'],
                        'NAMAIBU'           => $backup['NAMAIBU'],
                        'NOHPORTU'          => $backup['NOHPORTU'],
                        'ALAMATASAL'        => $backup['ALAMATASAL'],
                        'KECAMATAN'         => $backup['KECAMATAN'],
                        'KABUPATEN'         => $backup['KABUPATEN'],
                        'PROPINSI'          => $backup['PROPINSI'],
                        'NAMAGENTING'       => $backup['NAMAGENTING'],
                        'NOHPGENTING'       => $backup['NOHPGENTING'],
                        'FTPROFIL'          => $backup['FTPROFIL'],
                        'USRKEY'            => $backup["ID"],
                        'TIMEUPDATE'        => $backup['TIMEUPDATE']
                    );
                    $mahasiswa_wroking->reset()->tabel('datamahasiswa')->insert($datamahasiswa);

                    $column = array('NPM', 'JURUSAN', 'NAMAGENTING', 'NOHPGENTING', 'PROGRAMSTUDI', 'SKS', 'IPK', 'JENISKELAMIN', 'AGAMA', 'NOTELEPON', 'UKURANBAJU', 'ALAMATTINGGAL', 'KETRAMPILAN', 'ORGANISASI', 'NAMAAYAH', 'NAMAIBU', 'NOHPORTU', 'ALAMATASAL', 'FTPROFIL');
                    $result = FALSE;
                    foreach ($column as $value) {
                        if ($backup[$value] !== null) {
                            $result = TRUE;
                        } else {
                            $result = FALSE;
                            break;
                        }
                    }

                    if ($result) {
                        $dataextra = array(
                            "USRKEY"                 => $backup["ID"],
                            "KAPRODI"                => $backup["KAPRODI"],
                            "PEMBIMBINGAKADEMIK"     => $backup["PEMBIMBINGAKADEMIK"],
                            "NIPPEMBIMBINGAKADEMIK"  => $backup["NIPPEMBIMBINGAKADEMIK"]
                        );
                        $mahasiswa_wroking->reset()->tabel('aditionaldata')->insert($dataextra);


                        $status = $mahasiswa_backups->reset()->tabel('statusberkas')->where(["USRKEY" => $backup["ID"]])->result_array();

                        $lastBerkas = end($status);

                        $databerkas = array(
                            "USRKEY"            => $backup["ID"],
                            "BERKASDAFTAR"      => isset($lastBerkas["BERKASDAFTAR"]) ? $lastBerkas["BERKASDAFTAR"] : NULL,
                            "TAHUNDAFTAR"       => isset($backup["TAHUNDAFTAR"])   && $backup["TAHUNDAFTAR"] != NULL     ? $backup["TAHUNDAFTAR"]      : (isset($lastBerkas["TAHUNDAFTAR"])   ? $lastBerkas["TAHUNDAFTAR"]   : NULL),
                            "PERIODEDAFTAR"     => isset($backup["SEMESTERDAFTAR"]) && $backup["SEMESTERDAFTAR"] != NULL ? $backup["SEMESTERDAFTAR"] : (isset($lastBerkas["PERIODEDAFTAR"]) && $lastBerkas["PERIODEDAFTAR"] !== 0 ? ($lastBerkas["PERIODEDAFTAR"] == '0' ? "Ganjil" :  $lastBerkas["PERIODEDAFTAR"]): "Ganjil"),
                            "DATEREQUEST"       => isset($lastBerkas['DATEVALID']) ? $lastBerkas['DATEVALID'] : $backup["TIMEUPDATE"]
                        );
                        $mahasiswa_wroking->reset()->tabel('databerkas')->insert($databerkas);

                        // var_dump($mahasiswa_wroking->last_query);
                        $berkas = $mahasiswa_wroking->reset()->tabel('databerkas')->where(["USRKEY" => $backup["ID"]])->result_row_array();

                        foreach ($status as $stat) {
                            $datastatus = array(
                                "BRKSKEY"           => $berkas["ID"],
                                "USRKEY"            => $backup["ID"],
                                "STATUSBERKAS"      => $stat["STATUSBERKAS"],
                                "NOTEBERKAS"        => $stat["NOTEBERKAS"],
                                "VALIDATOR"         => $stat["VALIDATOR"],
                                "DATEVALID"         => $stat["DATEVALID"]
                            );
                            $mahasiswa_wroking->reset()->tabel('datastatus')->insert($datastatus);
                        }

                        $laporans = $mahasiswa_backups->reset()->tabel('laporan')->where(["USRKEY" => $backup["ID"]])->result_array();
                        foreach ($laporans as $laporan) {
                            $datalaporan = array(
                                "BRKSKEY"           => $berkas["ID"],
                                "USRKEY"            => $laporan["USRKEY"],
                                "NPM"               => $laporan["NPM"],
                                "FILELINK"          => $laporan["FILELINK"],
                                "FILEPATH"          => $laporan["FILEPATH"],
                                "FILENAME"          => $laporan["FILENAME"],
                                "FILEEXT"           => $laporan["FILEEXT"],
                                "FILEHASH"          => $laporan["FILEHASH"],
                                "TIMESTAMP"         => $laporan["TIMESTAMP"],
                                "UPLOADTIME"        => $laporan["UPLOADTIME"],
                                "RESPONSE"          => $laporan["RESPONSE"],
                                "KRITIKSARAN"       => $laporan["KRITIKSARAN"]
                            );
                            $mahasiswa_wroking->reset()->tabel('laporan')->insert($datalaporan);
                        }

                        if ($backup["DPLUSRKEY"] != NULL) {
                            $datapenempatan = array(
                                "USRKEY"            => $backup["ID"],
                                "DPLUSRKEY"         => $backup["DPLUSRKEY"],
                                "NPMPESERTA"        => $backup["NPMPESERTA"],
                                "NAMADPL"           => $backup["NAMADPL"],
                                "NIPDPL"            => $backup["NIPDPL"],
                                "LOKASIKABUPATEN"   => $backup["LOKASIKABUPATEN"],
                                "LOKASIKECAMATAN"   => $backup["LOKASIKECAMATAN"],
                                "LOKASIDESA"        => $backup["LOKASIDESA"],
                                "LOKASISEKOLAH"     => $backup["LOKASISEKOLAH"],
                                "LOKASIPESERTA"     => $backup["LOKASIPESERTA"]
                            );
                            $mahasiswa_wroking->reset()->tabel('datapenempatan')->insert($datapenempatan);
                        }
                    } else echo "<br/>Data Not Complete: " . $backup['USERID'];
                } else echo "<br/>Not Found: " . $backup['USERID'];
            }
        }

        // var_dump($backups);
    }
}
