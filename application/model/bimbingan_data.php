<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class bimbingan_data extends gf_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default', 'dbAccess');
    }

    /**
     * Membuat sesi bimbingan baru
     */
    function create_sesi($data)
    {
        $this->dbAccess->reset();
        $result = $this->dbAccess->tabel('bimbingan_sesi')->insert($data);
        if ($result) {
            return $this->dbAccess->mysql->insert_id;
        }
        return false;
    }

    /**
     * Mengambil daftar sesi berdasarkan user
     * $role bisa 'Mahasiswa' atau 'DPL'
     */
    function get_sesi_list($usrkey, $role)
    {
        $this->dbAccess->reset();

        $this->dbAccess->tabel('bimbingan_sesi')
            ->column(array(
                '`bimbingan_sesi`.`ID`',
                '`bimbingan_sesi`.`USRKEY`',
                '`bimbingan_sesi`.`DPL_USRKEY`',
                '`bimbingan_sesi`.`TOPIK`',
                '`bimbingan_sesi`.`DESKRIPSI`',
                '`bimbingan_sesi`.`STATUS`',
                '`bimbingan_sesi`.`CREATEDAT`',
                '`bimbingan_sesi`.`UPDATEDAT`',
                '`datamahasiswa`.`NAMA` AS NAMA_MAHASISWA',
                '`datamahasiswa`.`NPM` AS NPM',
                '`dosen`.`NAMADOSEN` AS NAMA_DPL',
            ))
            ->join('datamahasiswa', '`datamahasiswa`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `bimbingan_sesi`.`DPL_USRKEY`', 'LEFT');

        if ($role == 'Mahasiswa') {
            $this->dbAccess->where(array('`bimbingan_sesi`.`USRKEY`' => $usrkey));
        } else {
            $this->dbAccess->where(array('`bimbingan_sesi`.`DPL_USRKEY`' => $usrkey));
        }

        $this->dbAccess->order('`bimbingan_sesi`.`UPDATEDAT`', 'DESC');

        return $this->dbAccess->result_array();
    }

    /**
     * Mengambil detail satu sesi
     */
    function get_sesi($id)
    {
        $this->dbAccess->reset();
        $this->dbAccess->tabel('bimbingan_sesi')
            ->column(array(
                '`bimbingan_sesi`.`ID`',
                '`bimbingan_sesi`.`USRKEY`',
                '`bimbingan_sesi`.`DPL_USRKEY`',
                '`bimbingan_sesi`.`TOPIK`',
                '`bimbingan_sesi`.`DESKRIPSI`',
                '`bimbingan_sesi`.`STATUS`',
                '`bimbingan_sesi`.`CREATEDAT`',
                '`bimbingan_sesi`.`UPDATEDAT`',
                '`datamahasiswa`.`NAMA` AS NAMA_MAHASISWA',
                '`datamahasiswa`.`NPM` AS NPM',
                '`dosen`.`NAMADOSEN` AS NAMA_DPL',
            ))
            ->join('datamahasiswa', '`datamahasiswa`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `bimbingan_sesi`.`DPL_USRKEY`', 'LEFT')
            ->where(array('`bimbingan_sesi`.`ID`' => $id));

        return $this->dbAccess->result_row_array();
    }

    /**
     * Update status sesi
     */
    function update_status($id, $status)
    {
        $this->dbAccess->reset();
        return $this->dbAccess->tabel('bimbingan_sesi')
            ->where(array('ID' => $id))
            ->update(array('STATUS' => $status));
    }

    /**
     * Mengirim pesan baru
     */
    function send_pesan($data)
    {
        $this->dbAccess->reset();
        $result = $this->dbAccess->tabel('bimbingan_pesan')->insert($data);

        // Update waktu sesi
        if ($result) {
            $this->dbAccess->reset();
            $this->dbAccess->tabel('bimbingan_sesi')
                ->where(array('ID' => $data['SESIKEY']))
                ->update(array('UPDATEDAT' => date('Y-m-d H:i:s')));
        }

        return $result;
    }

    /**
     * Mengambil semua pesan dalam satu sesi
     */
    function get_pesan($sesikey)
    {
        $this->dbAccess->reset();
        $this->dbAccess->tabel('bimbingan_pesan')
            ->column(array(
                '`bimbingan_pesan`.`ID`',
                '`bimbingan_pesan`.`SESIKEY`',
                '`bimbingan_pesan`.`SENDER_USRKEY`',
                '`bimbingan_pesan`.`SENDER_ROLE`',
                '`bimbingan_pesan`.`PESAN`',
                '`bimbingan_pesan`.`CREATEDAT`',
                '`datamahasiswa`.`NAMA` AS NAMA_MAHASISWA',
                '`dosen`.`NAMADOSEN` AS NAMA_DPL',
            ))
            ->join('datamahasiswa', '`datamahasiswa`.`USRKEY` = `bimbingan_pesan`.`SENDER_USRKEY` AND `bimbingan_pesan`.`SENDER_ROLE` = \'Mahasiswa\'', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `bimbingan_pesan`.`SENDER_USRKEY` AND `bimbingan_pesan`.`SENDER_ROLE` = \'DPL\'', 'LEFT')
            ->where(array('`bimbingan_pesan`.`SESIKEY`' => $sesikey))
            ->order('`bimbingan_pesan`.`CREATEDAT`', 'ASC');

        return $this->dbAccess->result_array();
    }

    /**
     * Menghapus sesi bimbingan dan semua pesannya
     */
    function delete_sesi($sesi_id)
    {
        $this->dbAccess->reset();
        // Hapus pesan-pesannya dulu
        $this->dbAccess->tabel('bimbingan_pesan')->where(array('SESIKEY' => $sesi_id))->delete();
        
        // Baru hapus sesi-nya
        $this->dbAccess->reset();
        return $this->dbAccess->tabel('bimbingan_sesi')->where(array('ID' => $sesi_id))->delete();
    }

    /**
     * Mendapatkan semua sesi bimbingan beserta informasi lengkap mahasiswa untuk diunduh (docx)
     */
    function get_all_sesi_for_download($usrkey)
    {
        $this->dbAccess->reset();

        $this->dbAccess->tabel('bimbingan_sesi')
            ->column(array(
                '`bimbingan_sesi`.`ID`',
                '`bimbingan_sesi`.`TOPIK`',
                '`bimbingan_sesi`.`DESKRIPSI`',
                '`bimbingan_sesi`.`CREATEDAT`',
                '`datamahasiswa`.`NAMA` AS NAMA_MAHASISWA',
                '`datamahasiswa`.`NPM` AS NPM',
                '`datamahasiswa`.`PROGRAMSTUDI` AS PRODI',
                '`dosen`.`NAMADOSEN` AS NAMA_DPL',
                '`datapenempatan`.`LOKASISEKOLAH` AS SEKOLAH'
            ))
            ->join('datamahasiswa', '`datamahasiswa`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `bimbingan_sesi`.`DPL_USRKEY`', 'LEFT')
            ->join('datapenempatan', '`datapenempatan`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->where(array('`bimbingan_sesi`.`USRKEY`' => $usrkey))
            ->order('`bimbingan_sesi`.`CREATEDAT`', 'ASC');

        return $this->dbAccess->result_array();
    }

    /**
     * Mendapatkan semua sesi bimbingan beserta informasi lengkap mahasiswa untuk diunduh oleh DPL (docx)
     */
    function get_all_sesi_for_download_dpl($dpl_usrkey)
    {
        $this->dbAccess->reset();

        $this->dbAccess->tabel('bimbingan_sesi')
            ->column(array(
                '`bimbingan_sesi`.`USRKEY`',
                '`bimbingan_sesi`.`ID`',
                '`bimbingan_sesi`.`TOPIK`',
                '`bimbingan_sesi`.`DESKRIPSI`',
                '`bimbingan_sesi`.`CREATEDAT`',
                '`datamahasiswa`.`NAMA` AS NAMA_MAHASISWA',
                '`datamahasiswa`.`NPM` AS NPM',
                '`datamahasiswa`.`PROGRAMSTUDI` AS PRODI',
                '`dosen`.`NAMADOSEN` AS NAMA_DPL',
                '`datapenempatan`.`LOKASISEKOLAH` AS SEKOLAH'
            ))
            ->join('datamahasiswa', '`datamahasiswa`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->join('dosen', '`dosen`.`USRKEY` = `bimbingan_sesi`.`DPL_USRKEY`', 'LEFT')
            ->join('datapenempatan', '`datapenempatan`.`USRKEY` = `bimbingan_sesi`.`USRKEY`', 'LEFT')
            ->where(array('`bimbingan_sesi`.`DPL_USRKEY`' => $dpl_usrkey))
            ->order('`datamahasiswa`.`NAMA`', 'ASC')
            ->order('`bimbingan_sesi`.`CREATEDAT`', 'ASC');

        return $this->dbAccess->result_array();
    }
}
