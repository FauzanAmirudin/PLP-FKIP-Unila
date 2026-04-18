<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */	
echo 'Access Database Update Handler by Ghea Chandra S';
$DPLData	= new SQL('upload-dpl');
$datadpl 	= new SQL('datadpl');
$user		= new SQL('user');
$allDPLData = $DPLData->result_array();
// var_dump($allDPLData);
echo '<br>';
$error = '';
if ($allDPLData != FALSE) {
	echo '
		<table style="font-family: arial, sans-serif; border-collapse: collapse; width: 100%; border: 1px solid #dddddd; text-align: center; padding: 8px;"><tr><th>NIPDOSEN</th><th>NAMADOSEN</th><th>USER</th><th>DATA</th></tr>';
	foreach ($allDPLData as $row) {
		echo "<tr>";
		echo "<td>" . ($row['NIPDOSEN'] == '' ? '<a style="color:red;">NO DATA!</a>' : $row['NIPDOSEN']) . "</td>";
		echo "<td>" . ($row['NAMADOSEN'] == '' ? '<a style="color:red;">NO DATA!</a>' : $row['NAMADOSEN']) . "</td>";
		if ( $row["STAT"] == "DPL") {
		// USER
			$NIPDOSEN = str_replace(" ", "", trim($row['NIPDOSEN']));
			$NAMADOSEN = nameFIlter($row['NAMADOSEN']);
			$data = array(
				"USERID"		=> $NIPDOSEN,
				"STAT"			=> $row['STAT'],
				"NOTE"			=> $NAMADOSEN,
				"ACTIVE"		=> 1,
			);
			if ( $user->reset()->where( ["USERID" => $NIPDOSEN] )->count_rows() == 0 ) {
				$data["PASSWORD"] = str_encrypt('Majuteruspltfkip');
				$result_add_user 	= $user->reset()->insert($data);
				$error .= $user->last_error != '' ? ' "'.$user->last_error.'"<br>' : '';
				$result = 'Insert';
				$color = '#24b702';
			} else {
				$userDPL = $user->result_row_array();
				$update = FALSE;
				foreach ($data as $key => $value) {
					if (!isset($userDPL[$key]) || $userDPL[$key] != $value) {
						$update = TRUE;
					}
				}
				if ($update) {
					$result_add_user 	= $user->reset()->where( ["USERID" => $NIPDOSEN] )->update($data);
					$result = 'update';
					$color = '#24b702';
					$error .= $user->last_error != '' ? ' "'.$user->last_error.'"<br>' : '';
				} else {
					$result_add_user = TRUE;
					$result = 'Same data';
					$color = '#d2d245';
				}
			}
			if ( $result_add_user ) {
				echo  '<td><a style="color:'.$color.';">'.$result.'</a></td>';
			} else echo  '<td><a style="color:Red;">'.$result.' Fail!</a></td>';
		// DATA
			$USERDPL = $user->reset()->where( ["USERID" => $NIPDOSEN] )->result_row_array();
			$data = array(
				'USRKEY'			=> $USERDPL["ID"],
				'NAMADOSEN'			=> $NAMADOSEN,
				'NIPDOSEN'			=> $NIPDOSEN,
				'HANDPHPONEDOSEN'	=> $row["HANDPHPONEDOSEN"],
			);
			if ( $datadpl->reset()->where( ["USRKEY" => $USERDPL["ID"]] )->count_rows() == 0 ) {
				$result_add_user 	= $datadpl->reset()->insert($data);
				$error .= $datadpl->last_error != '' ? ' "'.$datadpl->last_error.'"<br>' : '';
				$result = 'Insert';
				$color = '#24b702';
			} else {
				$dataDPL = $datadpl->result_row_array();
				$update = FALSE;
				foreach ($data as $key => $value) {
					if (!isset($dataDPL[$key]) || $dataDPL[$key] != $value) {
						$update = TRUE;
					}
				}
				if ($update) {
					$result_add_user 	= $datadpl->reset()->where( ["USRKEY" => $USERDPL["ID"]] )->update($data);
					$result = 'update';
					$color = '#24b702';
					$error .= $datadpl->last_error != '' ? ' "'.$datadpl->last_error.'"<br>' : '';
				} else {
					$result_add_user = TRUE;
					$result = 'Same data';
					$color = '#d2d245';
				}
			}
			if ( $result_add_user ) {
				echo  '<td><a style="color:'.$color.';">'.$result.'</a></td>';
			} else echo  '<td><a style="color:Red;">'.$result.' Fail!</a></td>';
		} else echo '<td><a style="color:red;">DATA NOT DPL</a></td><td><a style="color:red;">DATA NOT DPL</a></td>';
		echo '</tr>';
	}
	echo "</table>";
} else echo ' <a style="color:red;">DATA NOT FOUND!</a>';
echo $error;