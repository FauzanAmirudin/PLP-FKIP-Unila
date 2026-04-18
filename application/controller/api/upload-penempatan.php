<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */	
echo 'Access Database Update Handler by Ghea Chandra S';
$PenempatanData		= new SQL('upload-penempatan');
$datapenempatan 	= new SQL('datapenempatan');
$dpl				= new SQL('datadpl');
$mahasiswa			= new SQL('datamahasiswa');
$allPenempatanData = $PenempatanData->result_array();
// var_dump($allPenempatanData);
echo '<br>';
if ($allPenempatanData != FALSE) {
	echo '
		<table style="font-family: arial, sans-serif; border-collapse: collapse; width: 100%; border: 1px solid #dddddd; padding: 8px;">
			<tr>
				<th>NAMA</th>
				<th>NPM</th>
				<th>NAMA/NIP DOSEN</th>
				<th>LOKASI</th>
			</tr>';
	foreach ($allPenempatanData as $row) {
		echo '<tr>';
	// Data Mahasiswa Check
		$NAMAMAHASISWA = trim($row['NAMA']);
		$NPMMAHASISWA = trim($row['NPM']);
		if ( $mahasiswa->reset()->where( ["NPM" => $NPMMAHASISWA] )->count_rows() == 0 ) {
			echo '<td><a style="color:Red;">Mahasiswa Not Found!</a></td>';
			echo '<td><a style="color:Red;">Mahasiswa Not Found!</a></td>';
			$dtmahasiswa = false;
		} else {
			$dtmahasiswa = $mahasiswa->result_row_array();
			echo '<td>' . 
			($NAMAMAHASISWA == '' 
				? '<a style="color:red;">NO DATA!</a>' 
				: ($NAMAMAHASISWA == $dtmahasiswa['NAMA'] 
					? '<a>' . $dtmahasiswa['NAMA'] . '</a>' 
					: '<a style="color:#d2bf01;">"' . $NAMAMAHASISWA . '" | "' . $dtmahasiswa['NAMA'] . '"</a>'
				) 
			) .  '</td>';
			echo '<td>' . 
			($NPMMAHASISWA == '' 
				? '<a style="color:red;">NO DATA!</a>' 
				: '<a>'.$dtmahasiswa['NPM'].'</a>'
			) . '</td>';
		}
	// Data Dosen Check
		$NAMADOSEN = nameFIlter($row['DPL']);
		if ( $dpl->reset()->where( ["NAMADOSEN" => $NAMADOSEN] )->count_rows() == 0 ) {
			echo '<td><a style="color:Red;">"'.$NAMADOSEN.'" Not Found!</a></td>';
			$dtDPL = false;
		} else {
			$dtDPL = $dpl->result_row_array();
			echo '<td>' . 
			($dtDPL['NIPDOSEN'] == '' 
				? '<a style="color:red;">NO DATA!</a>' 
				: ($NAMADOSEN == $dtDPL['NAMADOSEN'] 
					? '<a>' . $NAMADOSEN . '/' . $dtDPL['NIPDOSEN'] . '</a>' 
					: '<a style="color:#d2bf01;">"' . $NAMADOSEN. '" | "' . $dtDPL['NAMADOSEN'] . '"</a>'
				) 
			) .  '</td>';
		}
	// DATA
		if ($dtmahasiswa != FALSE && $dtDPL != FALSE) {
			$error = '';
			$data = array(
				'USRKEY'			=> $dtmahasiswa["USRKEY"],
				'NPMPESERTA'		=> $dtmahasiswa["NPM"],
				'DPLUSRKEY'			=> $dtDPL["USRKEY"],
				'NAMADPL'			=> secureInput($dtDPL["NAMADOSEN"]),
				'NIPDPL'			=> $dtDPL["NIPDOSEN"],
				'LOKASIKABUPATEN'	=> secureInput($row['LOKASIKABUPATEN']),
				'LOKASIKECAMATAN'	=> secureInput($row['LOKASIKECAMATAN']),
				'LOKASIDESA'		=> secureInput($row['LOKASIDESA']),
				'LOKASISEKOLAH'		=> secureInput($row['LOKASISEKOLAH'])
			);
			if ( $datapenempatan->reset()->where( ["USRKEY" => $dtmahasiswa["USRKEY"]] )->count_rows() == 0 ) {
				$result_add_penempatan = $datapenempatan->reset()->insert($data);
				$error .= $datapenempatan->last_error != '' ? ' "'.$datapenempatan->last_error.'"<br>' : '';
				$result = 'Insert';
				$color = '#24b702';
			} else {
				$penempatan = $datapenempatan->result_row_array();
				$update = FALSE;
				foreach ($data as $key => $value) {
					if (!isset($penempatan[$key]) || $penempatan[$key] != $value) {
						$update = TRUE;
					}
				}
				if ($update) {
					$result_add_penempatan = $datapenempatan->reset()->where( ["USRKEY" => $dtmahasiswa["USRKEY"]] )->update($data);
					$result = 'Update';
					$color = '#24b702';
					$error .= $datapenempatan->last_error != '' ? ' "'.$datapenempatan->last_error.'"<br>' : '';
				} else {
					$result_add_penempatan = TRUE;
					$result = 'Same data';
					$color = '#d2d245';
				}
			}
			if ( $result_add_penempatan ) {
				echo  '<td><a style="color:'.$color.';">'.$result.'</a></td>';
			} else echo  '<td><a style="color:Red;">'.$result.' Fail! ' . $error . '</a></td>';
		} else echo '<td><a style="color:Red;">Failed, require data missing!</a></td>';
		echo '</tr>';
	}
	echo "</table>";
} else echo ' <a style="color:red;">DATA NOT FOUND!</a>';
echo $error;