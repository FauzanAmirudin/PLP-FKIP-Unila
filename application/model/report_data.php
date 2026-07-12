<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
| 
| 
| 
*/
class report_data extends gf_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default', 'dbAccess');
        $this->year = get_dbconfig('CURENTYEAR');
        $this->period = get_dbconfig('CURENTSEMESTER');
    }
    /**
     * save
     *
     * @param  mixed $id
     * @param  mixed $berkas
     * @param  mixed $data
     * @param  mixed $validator
     * @return void
     */
    function save($id, $berkas, $data, $validator)
    {
        $result = $this->dbAccess->reset(TRUE)
            ->tabel('laporan')
            ->where(["BRKSKEY" => $berkas, "FILENAME" => $data["FILENAME"]])
            ->result_row_array();
        $valid = array(
            "USRKEY"        => $id,
            "BRKSKEY"       => $berkas,
            "NPM"           => $data["NPM"],
            "FILELINK"      => $data["FILELINK"],
            "FILEPATH"      => $data["FILEPATH"],
            "FILENAME"      => $data["FILENAME"],
            "FILEEXT"       => $data["FILEEXT"],
            "FILEHASH"      => $data["FILEHASH"],
            "TIMESTAMP"     => $data["TIMESTAMP"],
            "VALIDATOR"        => $validator
        );
        $this->dbAccess->reset(TRUE);
        if (!empty($result)) {
            $result = $this->dbAccess->reset(TRUE)->tabel('laporan')->where([
                "BRKSKEY"       => $berkas,
                "FILENAME"      => $data["FILENAME"],
            ])->update($valid);
        } else {
            $this->dbAccess->reset(TRUE);
            $result = $this->dbAccess->reset(TRUE)->tabel('laporan')->insert($valid);
        }
        return $result;
    }
    function response($id, $response, $comment)
    {
        $report = $this->dbAccess->reset()
            ->tabel('laporan')
            ->where(["ID" => $id])
            ->update(["RESPONSE" => $response, "KRITIKSARAN" => $comment]);
        return $report;
    }
    /**
     * get_report Give report by ID report
     *
     * @param  int $id
     * @param  string $key
     * @return void
     */
    function get(int $id)
    {
        $report = $this->dbAccess->reset()->tabel('laporan')->where(array('ID' => $id))->result_row_array();
        return $report;
    }
    /**
     * report give list of report for berkas ID
     * 
     * @param  mixed $id
     * @return void
     */
    function data($id, $year = NULL, $periode = NULL, string $col = "USRKEY")
    {
        $this->dbAccess->reset();
        if (empty($year) && empty($periode)) $col = "BRKSKEY";
        if (!empty($year)) $this->dbAccess->where("`TAHUNDAFTAR` = " . $year);
        if (!empty($periode)) $this->dbAccess->where("`PERIODEDAFTAR` = '" . $periode . "'");
        $this->dbAccess->tabel('databerkas')
            ->join("laporan", "`laporan`.`BRKSKEY` = `databerkas`.`ID`")
            ->where("`laporan`.`" . $col . "` = " . $id)
            ->order('`FILENAME`', FALSE);
        $data = $this->dbAccess->result_array();
        return $data;
    }
    /**
     * direct - Get laporan directly by USRKEY without databerkas dependency
     * 
     * @param  int $usrkey
     * @return array
     */
    function direct(int $usrkey)
    {
        $this->dbAccess->reset();
        $data = $this->dbAccess
            ->tabel('laporan')
            ->where("`laporan`.`USRKEY` = " . $usrkey)
            ->order('`FILENAME`', TRUE)
            ->result_array();
        return $data;
    }
    /**
     * list
     *
     * @param  mixed $year
     * @param  mixed $periode
     * @param  mixed $dosen
     * @param  mixed $npm
     * @return void
     */
    function list($year = NULL, $periode = NULL, $dosen = NULL, $npm = NULL, $prodi = NULL)
    {
        $condition = array(
            "`datastatus`.`STATUSBERKAS`" => 'Disetujui'
        );
        if (!empty($year)) $condition["`databerkas`.`TAHUNDAFTAR`"] = $year;
        if (!empty($periode)) $condition["`databerkas`.`PERIODEDAFTAR`"] = $periode;
        if (!empty($dosen) && empty($npm)) $condition["`datapenempatan`.`DPLUSRKEY`"] = $dosen;
        if (!empty($prodi)) $condition["`datamahasiswa`.`PROGRAMSTUDI`"] = $prodi;
        // if (!empty($npm)) $condition["`datamahasiswa`.`NPM`"] = $npm; // Removed strict NPM check

        $this->dbAccess->reset();
        $suport = $this->dbAccess->suport_version('33.0.0');
        $this->dbAccess->tabel('datamahasiswa')
            ->column(array(
                '`databerkas`.`ID`',
                '`databerkas`.`USRKEY`',
                '`datamahasiswa`.`NAMA`',
                '`datamahasiswa`.`NPM`',
                '`datamahasiswa`.`PROGRAMSTUDI`',
                '`datamahasiswa`.`JENISKELAMIN`',
                '`datamahasiswa`.`NOTELEPON`',
                '`databerkas`.`TAHUNDAFTAR`',
                "`databerkas`.`ID` AS BERKASID",
                '`databerkas`.`PERIODEDAFTAR`',
                '`databerkas`.`BERKASDAFTAR`',
                '`datastatus`.`STATUSBERKAS`',
                '`datapenempatan`.`DPLUSRKEY` AS DOSEN',
                '`datapenempatan`.`LOKASIKABUPATEN`',
                '`datapenempatan`.`LOKASIKECAMATAN`',
                '`datapenempatan`.`LOKASIDESA`',
                '`datapenempatan`.`LOKASISEKOLAH`',
                '`dosen`.`NAMADOSEN`',
            ))
            ->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`', 'INNER')
            ->join('datastatus', '`datastatus`.`BRKSKEY` = `databerkas`.`ID`', 'LEFT')
            ->join('datapenempatan', '`datapenempatan`.`USRKEY` = `datamahasiswa`.`USRKEY`', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `datapenempatan`.`DPLUSRKEY`', 'LEFT')
            ->where($condition);

        if (!empty($npm)) {
            /* Improve security using real_escape_string instead of simple addslashes */
            $safe_npm = $this->dbAccess->mysql->real_escape_string($npm);
            $this->dbAccess->where("(`datamahasiswa`.`NPM` LIKE '%$safe_npm%' OR `datamahasiswa`.`NAMA` LIKE '%$safe_npm%')");
            if (!empty($dosen)) {
                $safe_dosen = $this->dbAccess->mysql->real_escape_string($dosen);
                $this->dbAccess->where("`datapenempatan`.`DPLUSRKEY` = '$safe_dosen'");
            }
        }

        $this->dbAccess->order("`databerkas`.`TAHUNDAFTAR`", 'DESC')
            ->order("`databerkas`.`PERIODEDAFTAR`", 'DESC')
            ->order("`datastatus`.`DATEVALID`", 'DESC')
            ->order("`datapenempatan`.`LOKASIDESA`", 'DESC')
            ->order("`datapenempatan`.`LOKASISEKOLAH`", 'DESC');
            
        if ($suport) {
            $this->dbAccess->column("row_number() over ( PARTITION BY datamahasiswa.USRKEY ORDER BY databerkas.TAHUNDAFTAR DESC, databerkas.PERIODEDAFTAR DESC, datastatus.DATEVALID DESC ) AS NUMRECORD", FALSE);
            $tabel = $this->dbAccess->query();
            $mahasiswa = $this->dbAccess->reset(TRUE)
                ->tabel("(" . $tabel . ") AS NUMRECORD", FALSE)
                ->where("NUMRECORD = 1")
                ->result_array();
        } else {
            $mahasiswa = $this->dbAccess->result_array();
            $unique_mahasiswa = [];
            $seen = [];
            foreach ($mahasiswa as $row) {
                if (!isset($seen[$row['USRKEY']])) {
                    $unique_mahasiswa[] = $row;
                    $seen[$row['USRKEY']] = true;
                }
            }
            $mahasiswa = $unique_mahasiswa;
        }
        // echo ($this->dbAccess->last_query);
        $result = [];
        if (!empty($mahasiswa)) {
            foreach ($mahasiswa as $key => $berkas) {
                // Fetch reports using USRKEY instead of databerkas ID to match the new save logic
                $report = $this->dbAccess->reset()->tabel('laporan')->where(array("USRKEY" => $berkas["USRKEY"]))->order('`FILENAME`', TRUE)->result_array();
                $mahasiswa[$key]['LAPORAN'] = $report;
                array_push($result, $mahasiswa[$key]);
            }
        }

        return $result;
    }
    /**
     * get_berkas_by_id - Fetch databerkas row by primary key ID
     *
     * @param  int $id
     * @return array
     */
    function get_berkas_by_id(int $id)
    {
        $this->dbAccess->reset();
        return $this->dbAccess->tabel('databerkas')->where(['ID' => $id])->result_row_array();
    }
    /**
     * direct_by_usrkey - Get all laporan by USRKEY
     *
     * @param  int $usrkey
     * @return array
     */
    function direct_by_usrkey(int $usrkey)
    {
        $this->dbAccess->reset();
        return $this->dbAccess->tabel('laporan')->where(['USRKEY' => $usrkey])->order('`FILENAME`', TRUE)->result_array();
    }
}
