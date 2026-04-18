<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');


if (!function_exists("get_dbconfig")) {
    function get_dbconfig(string $tag = '')
    {
        global $gf_db;
        $configAccess = new gf_sql($gf_db['default']);
        foreach ($configAccess->reset()->select('settings') as $cnf) {
            $db_config[$cnf['ITEM']] = $cnf['VALUE'];
        }
        if(empty($tag)){
            return $db_config;
        } else {
            return $db_config[$tag];
        }
    }
}
if (!function_exists("save_dbconfig")) {
    function save_dbconfig($tag, $value)
    {
        global $gf_db;
        $data = array(
            "VALUE" => $value
        );
        $configAccess = new gf_sql($gf_db['default']);
        if (!empty($configAccess->reset()->where(array('ITEM'=>$tag))->select('settings'))) {
            $result = $configAccess->reset()->tabel('settings')->where(array('ITEM' => $tag))->update($data);
        } else {
            $result = $configAccess->reset()->tabel('settings')->where(array('ITEM' => $tag))->insert($data);
        }
        return $result;
    }
}
