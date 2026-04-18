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
class penempatan_data extends gf_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default', 'dbAccess');
    }
    function check(int $id)
    {
        $result = $this->check_by($id, 'USRKEY');
        return $result;
    }
    function check_by(int $id, $key = 'USRKEY')
    {
        $this->dbAccess->reset();
        $this->dbAccess->where(array($key => $id));
        $result = $this->dbAccess->tabel('datapenempatan')->result_row_array();
        return $result;
    }
    function data(int $id)
    {
        $this->dbAccess->reset();
        $data = $this->dbAccess->tabel('datapenempatan')->where(array("datapenempatan`.`USRKEY" => $id))->result_row_array();
        return $data;
    }
    function list(int $id)
    {
        $this->dbAccess->reset();
        $result = $this->dbAccess->tabel('datapenempatan')->result_array();
        return $result;
    }
    function insert($data)
    {
        $this->dbAccess->reset();
        $result = $this->dbAccess->tabel('datapenempatan')->insert($data);
        return $result;
    }
    function update(int $id, $data)
    {
        $this->dbAccess->reset();
        $result = $this->dbAccess->tabel('datapenempatan')->where(array("USRKEY" => $id))->update($data);
        return $result;
    }
}
