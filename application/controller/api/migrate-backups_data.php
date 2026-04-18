<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */	
echo 'Access Database Backup Handler by Ghea Chan&apos';
$userNewAccess = new SQL('user@');
$dataMahasiswaNewAccess = new SQL("datamahasiswa@");
$berkasNewAccess = new sql('statusberkas@');
$dataPenempatanNewAccess = new sql('datapenempatan@');
$dataFilesNewAccess = new SQL('laporan@');
$dataDplNewAccess = new SQL('datadpl@');
$userAccess	= new SQL('userplt');
// $userAccess->where( ["USERID" => '1413023005'] )->or_where( ["USERID" => '1413034009'] )->or_where( ["USERID" => '1613051014'] );
// $userAccess->or_where( ["STAT" => 'DPL'] );
$allUser 		= $userAccess->result_array();
// var_dump($allUser);
echo '<br>';
foreach ($allUser as $row) {
	echo $row['USERID'];
	// CEK DATA
		$databaseArray = ['2017ganjil',  '2018ganjil', "2019ganjil"];
		foreach ($databaseArray as $tb) {
			$dataMahasiswaAccess = new SQL($tb."-peserta");
			$dataMahasiswa = $dataMahasiswaAccess->reset()->where( ["NPM" => $row["USERID"]] )->where( "JURUSAN IS NOT NULL" )->result_row_array();

			$dataPenempatanAccess = new SQL($tb."-penempatan");
			$dataPenempatanAccess->join($tb."-dpl", "`".$tb."-penempatan`.LOKASIKECAMATAN = `".$tb."-dpl`.LOKASIKECAMATAN");
			$dataPenempatan = $dataPenempatanAccess->reset()->where( ["NPMPESERTA" => $row["USERID"]] )->result_row_array();
			//echo $dataPenempatanAccess->last_query;
			$dataDPLAccess = new SQL($tb."-dpl");
			$dataFilesAccess = new SQL($tb."-files");

			$year = str_replace( 'ganjil', '', $tb );
			if ( $dataMahasiswa !== FALSE ) break;
		}
		
	if ( $dataMahasiswa !== FALSE && $dataPenempatan !== FALSE || $row["STAT"] == "DPL") {
	// USER
		$data = array(
			"USERID"		=> $row['USERID'],
			"PASS"		=> $row['PASS'],
			"STAT"		=> $row['STAT'],
			"CREATEDATE"	=> $row['CREATEDATE'],
			//"NOTE"		=> str_replace("'", "&apos;", $row['NAME']),
			"ACTIVE"		=> 1,
		);
		if ( $userNewAccess->reset()->where( ["USERID" => $row['USERID']] )->count_rows() == 0 ) {
			$result_add_user 	= $userNewAccess->reset()->insert($data);
			$result = ' insert';
		} else {
			$result_add_user 	= $userNewAccess->reset()->where( ["USERID" => $row['USERID']] )->update($data);
			$result = ' update';
		}
		if ( $result_add_user ) {
			echo $result . ' ,';
		} else echo ' FAIL ,';
	}
	if ( $dataMahasiswa !== FALSE && $dataPenempatan !== FALSE ) {
	// DATA
			$dataUser = $userNewAccess->reset()->where( ["USERID" => $row['USERID']] )->result_row_array();
			$data = array(
				'ID'				=> $dataUser['ID'],
				"NAMA"			=> str_replace("'", "&apos;", $dataMahasiswa["NAMA"]),
				"NPM"			=> str_replace("'", "&apos;", $dataMahasiswa["NPM"]),
				"JURUSAN" 		=> str_replace("'", "&apos;", $dataMahasiswa["JURUSAN"]), 
				"PROGRAMSTUDI" 	=> str_replace("'", "&apos;", $dataMahasiswa["PROGRAMSTUDI"]), 
				"SKS" 			=> str_replace("'", "&apos;", $dataMahasiswa["SKS"]), 
				"IPK" 			=> str_replace("'", "&apos;", $dataMahasiswa["IPK"]), 
				"JENISKELAMIN" 	=> str_replace("'", "&apos;", $dataMahasiswa["JENISKELAMIN"]), 
				"AGAMA" 			=> str_replace("'", "&apos;", $dataMahasiswa["AGAMA"]), 
				"NOTELEPON" 		=> str_replace("'", "&apos;", $dataMahasiswa["NOTELEPON"]), 
				"ALAMATTINGGAL" 	=> str_replace("'", "&apos;", $dataMahasiswa["ALAMATTINGGAL"]), 
				"UKURANBAJU" 		=> str_replace("'", "&apos;", $dataMahasiswa["UKURANBAJU"]), 
				"KETRAMPILAN" 		=> str_replace("'", "&apos;", $dataMahasiswa["KETRAMPILAN"]), 
				"ORGANISASI" 		=> str_replace("'", "&apos;", $dataMahasiswa["ORGANISASI"]), 
				"NAMAAYAH" 		=> str_replace("'", "&apos;", $dataMahasiswa["NAMAAYAH"]), 
				"NAMAIBU" 		=> str_replace("'", "&apos;", $dataMahasiswa["NAMAIBU"]), 
				"NOHPORTU" 		=> str_replace("'", "&apos;", $dataMahasiswa["NOHPORTU"]), 
				"NAMAGENTING"		=> $dataMahasiswa["NAMAGENTING"] == NULL ? "" : str_replace("'", "&apos;", $dataMahasiswa["NAMAGENTING"]),  
				"NOHPGENTING"		=> $dataMahasiswa["NOHPGENTING"] == NULL ? "" : str_replace("'", "&apos;", $dataMahasiswa["NOHPGENTING"]), 
				"ALAMATASAL" 		=> str_replace("'", "&apos;", $dataMahasiswa["ALAMATASAL"]), 
				"KECAMATAN" 		=> str_replace("'", "&apos;", $dataMahasiswa["KECAMATAN"]), 
				"KABUPATEN" 		=> str_replace("'", "&apos;", $dataMahasiswa["KABUPATEN"]), 
				"PROPINSI" 		=> str_replace("'", "&apos;", $dataMahasiswa["PROPINSI"]),
				"TAHUNDAFTAR"		=> $year,
				"SEMESTERDAFTAR"	=> "Ganji",
				"FTPROFIL"		=> "uploads/foto-profile/" . $year . "/" . $dataMahasiswa["NPM"] . ".jpg",
				'USRKEY'			=> $dataUser['ID'],
				'TIMEUPDATE'		=> $dataMahasiswa['TIMEUPDATE'],
			);
			if ( $dataMahasiswaNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->count_rows() == 0 ) {
				$result_add_dataMahasiswa = $dataMahasiswaNewAccess->reset()->insert($data);
				$result = ' insert';
			} else {
				$result_add_dataMahasiswa = $dataMahasiswaNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->update($data);
				$result = ' update';
			}
			if ( $result_add_dataMahasiswa ) {
				echo $result . ' ,';
			} else echo ' FAIL ,';

	// STATUS BERKAS
			$data = array(
				"USRKEY"		=> $dataUser['ID'],
				"NPM"		=> $dataMahasiswa["NPM"],
				"STATUSBERKAS"	=> 'Disetujui',
				"VALIDATOR"	=> "System_Backups",
				'DATEVALID'	=> $dataMahasiswa['TIMEUPDATE'],
			);
			if ( $berkasNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->count_rows() == 0 ) {
				$result_add_berkas = $berkasNewAccess->reset()->insert($data);
				$result = ' insert';
			} else {
				$result_add_berkas = $berkasNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->update($data);
				$result = ' update';
			}
			if ( $result_add_berkas ) {
				echo $result . ' ,';
			} else echo ' FAIL ,';
	// PENEMPATAN
			$DPL		= str_replace(["  ", " ,", "M. Pd.", "S Pd.", "Dr. Farida Aryani, M.Pd.", "Dr. Farida Ariyani, M.pd."], [" ", ",", "M.Pd.", "S.Pd.", "Dr. Farida Ariyani, M.Pd.", "Dr. Farida Ariyani, M.Pd."], $dataPenempatan["NAMEDPL"]);
			$USERDPL = $userNewAccess->reset()->where( ["NOTE" => $DPL] )->result_row_array();
			$data = array(	
				"USRKEY"			=> $dataUser['ID'],
				"DPLUSRKEY"		=> $USERDPL['ID'],
				"NPMPESERTA"		=> $dataMahasiswa["NPM"],
				"NAMADPL"			=> $DPL,
				"NIPDPL"			=> $dataPenempatan["NIPDPL"],
				"LOKASIKABUPATEN"	=> str_replace("'", "&apos;", $dataPenempatan["LOKASIKABUPATEN"]),
				"LOKASIKECAMATAN"	=> str_replace("'", "&apos;", $dataPenempatan["LOKASIKECAMATAN"]),
				"LOKASIDESA"		=> str_replace("'", "&apos;", $dataPenempatan["LOKASIDESA"]),
				"LOKASISEKOLAH"	=> str_replace("'", "&apos;", $dataPenempatan["LOKASISEKOLAH"]),
				"LOKASIPESERTA"	=> $dataPenempatan["LOKASIPESERTA"] == NULL ? "" : $dataPenempatan["LOKASIPESERTA"],
			);
			if ( $dataPenempatanNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->count_rows() == 0 ) {
				$result_add_dataPenempatan = $dataPenempatanNewAccess->reset()->insert($data);
				$result = ' insert';
			} else {
				$result_add_dataPenempatan = $dataPenempatanNewAccess->reset()->where( ["USRKEY" => $dataUser['ID']] )->update($data);
				$result = ' update';
			}
	// DPL			
			if ( $dataUser === FALSE) {
				$data = array(
					"USERID"		=> $dataPenempatan["NIPDPL"],
					"PASS"		=> $dataPenempatan["NIPDPL"],
					"STAT"		=> "DPL",
					// "CREATEDATE"	=> $row['CREATEDATE'],
					"NOTE"		=> $DPL,
					"ACTIVE"		=> 1,
				);
				$result_add_user 	= $userNewAccess->reset()->insert($data);
				$dataUser = $userNewAccess->reset()->where( ["NOTE" => $DPL] )->result_row_array();
			}
			//var_dump($dataPenempatan);
			$data = array(	
				'USRKEY'			=> $USERDPL["ID"],
				'NAMADOSEN'		=> $DPL,
				'NIPDOSEN'		=> $dataPenempatan["NIPDPL"],
				'HANDPHPONEDOSEN'	=> $dataPenempatan["HANDPHPONEDPL"],
			);
			if ( $dataDplNewAccess->reset()->where( ["NAMADOSEN" => $DPL] )->count_rows() == 0 ) {
				$result_add_dataPenempatan = $dataDplNewAccess->reset()->insert($data);
				$result = ' insert';
			} else {
				$result_add_dataPenempatan = $dataDplNewAccess->reset()->where( ["NAMADOSEN" => $DPL] )->update($data);
				$result = ' update';
			}
			if ( $result_add_dataPenempatan ) {
				echo $result . ' ,';
			} else echo ' FAIL ,';
	
	// FILES
			$dataFiles = $dataFilesAccess->reset()->where( ["NPM" => $row['USERID']] )->result_array();
			foreach ($dataFiles as $files) {
				$data = array(
					"USRKEY"		=> $dataUser['ID'],
					'NPM'		=> $row['USERID'], 
					'FILELINK'	=> "uploads/laporan/" . $year . "-files/" . $dataMahasiswa["NPM"] . "/" .$files["FILENAME"] . $files["FILEEXT"],
					'FILEPATH'	=> "uploads/laporan/" . $year . "-files/" . $dataMahasiswa["NPM"] . "/",
					'FILENAME'	=> $files["FILENAME"],
					'FILEEXT'		=> $files["FILEEXT"],
					'FILEHASH'	=> $files["FILEHASH"],
					'TIMESTAMP'	=> $files["TIMESTAMP"],
					'UPLOADTIME'	=> $files["UPLOADTIME"],
					'RESPONSE'	=> $files["RESPONSE"],
					'KRITIKSARAN'	=> $files["KRITIKSARAN"]
				);
				if ( $dataFilesNewAccess->reset()->where( ["USRKEY" => $dataUser['ID'], "FILENAME" => $files['FILENAME']] )->count_rows() == 0 ) {
					$result_add_dataFiles = $dataFilesNewAccess->reset()->insert($data);
					$result = ' insert';
				} else {
					$result_add_dataFiles = $dataFilesNewAccess->reset()->where( ["USRKEY" => $dataUser['ID'], "FILENAME" => $files['FILENAME']] )->update($data);
					$result = ' update';
				}
			}
			if ( $result_add_dataFiles ) {
				echo $result . ' ,';
			} else echo ' FAIL ,';

	} else echo " DATA NOT FOUND ,";
	if ( $dataMahasiswa === FALSE ) echo ' Missing data mahasiswa, ';
	if ( $dataPenempatan === FALSE ) echo ' Missing data penempatan, ';
	echo '<br>';
}