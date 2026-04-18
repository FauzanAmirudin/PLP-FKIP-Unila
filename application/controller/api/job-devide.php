<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

$jumlahPerKelompok = 10;
$YEAR = 2019;
// $sqlAccess = new SQL("datamahasiswa");
// echo $sqlAccess->join("group", ["USRKEY", "USRKEYGROUP"])
// 	->join("datapenempatan", ["USRKEY", "USRKEY"])
// 	->column(["datamahasiswa`.`USRKEY", "NPM", "NAMA", "PROGRAMSTUDI", "GROUP", "NAMADPL", "NIPDPL","LOKASIKABUPATEN","LOKASIKECAMATAN","LOKASIDESA","LOKASISEKOLAH","LOKASIPESERTA"])
// 	->where(["TAHUNDAFTAR" => $YEAR, "PROGRAMSTUDI" => "PGSD"])->order(["GROUP"])->order(["NPM"], "DESC")->query();
// 	exit;
function bagi_kelompok($peserta)
{
	global $DAFTARKELOMPOK;
	global $JUMLAHKELOMPOK;
	global $CURENTGRUP;
	global $BEGINEGRUP;
	global $LEADING;
	global $YEAR;
	$curentAngota = 1;
	foreach ($peserta as $mahasiswa) {
		$namaKelompok = "Kelompok_" . str_pad($CURENTGRUP, $LEADING, "0", STR_PAD_LEFT);
		if (!isset($DAFTARKELOMPOK[$namaKelompok])) $DAFTARKELOMPOK[$namaKelompok] = [];
		$data = array(
			'ID' 		=> $mahasiswa["ID"],
			'USRKEY' 		=> $mahasiswa["USRKEY"],
			'KELOMPOK'	=> $namaKelompok,
			'JENISKELAMIN' => $mahasiswa["JENISKELAMIN"]
		);
		array_push($DAFTARKELOMPOK[$namaKelompok], $data);
		$CURENTGRUP++;
		$angota = count($DAFTARKELOMPOK[$namaKelompok]);
		if ($curentAngota > $angota) {
			$CURENTGRUP--;
		} else {
			$curentAngota = $angota;
		}
		if ($CURENTGRUP > $JUMLAHKELOMPOK) $CURENTGRUP = $BEGINEGRUP;
	}
}
function hitung_peserta($kelompok)
{
	return array_map(function ($a) {
		$c = [];
		$c["Total"] = 0;
		foreach ($a as $b) {
			if (!isset($c[$b["JENISKELAMIN"]])) $c[$b["JENISKELAMIN"]] = 0;
			$c[$b["JENISKELAMIN"]]++;
			$c["Total"]++;
		}
		return $c;
	}, $kelompok);
}
$report	=  "<h4>Welcome to migrate encript password set.</h4>";
$userAccess = new SQL("datamahasiswa");

// PGSD
$LEADING = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR])->count_rows();
$TOTAL = round($LEADING / $jumlahPerKelompok);
$LEADING = strlen((string) round($TOTAL / $jumlahPerKelompok, 0, PHP_ROUND_HALF_UP));
$jumlahPesertaSD = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR, "PROGRAMSTUDI" => "PGSD"])->count_rows();
$jumlahKelompokSD = $jumlahPesertaSD / $jumlahPerKelompok;

$JUMLAHKELOMPOK 	= round($jumlahKelompokSD);
$DAFTARKELOMPOK 	= [];
$BEGINEGRUP		= 1;
$CURENTGRUP 		= 1;
$pesertaSD = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR, "PROGRAMSTUDI" => "PGSD", "JENISKELAMIN" => "Laki-Laki"])->result_array();
bagi_kelompok($pesertaSD);
$pesertaSD = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR, "PROGRAMSTUDI" => "PGSD", "JENISKELAMIN" => "Perempuan"])->result_array();
bagi_kelompok($pesertaSD);

$KELOMPOKSD = $DAFTARKELOMPOK;
// var_dump(hitung_peserta($KELOMPOKSD));

// NON PGSD

$jumlahPeserta = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR])->where(["PROGRAMSTUDI" => "PGSD"], FALSE)->count_rows();
$jumlahKelompok = round($jumlahPeserta / $jumlahPerKelompok);
$BEGINEGRUP		= $JUMLAHKELOMPOK + 1;
$CURENTGRUP 		= $JUMLAHKELOMPOK + 1;
$JUMLAHKELOMPOK 	= $JUMLAHKELOMPOK + $jumlahKelompok;
$DAFTARKELOMPOK 	= [];

$peserta = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR, "JENISKELAMIN" => "Laki-Laki"])->where(["PROGRAMSTUDI" => "PGSD"], FALSE)->result_array();
bagi_kelompok($peserta);

$peserta = $userAccess->reset()->where(["TAHUNDAFTAR" => $YEAR, "JENISKELAMIN" => "Perempuan"])->where(["PROGRAMSTUDI" => "PGSD"], FALSE)->result_array();
bagi_kelompok($peserta);
// echo $userAccess->last_query;
// var_dump(hitung_peserta($DAFTARKELOMPOK));

$KELOMPOK = array_merge($DAFTARKELOMPOK, $KELOMPOKSD);
ksort($KELOMPOK);
var_dump($KELOMPOK);
$grupAcess = new SQL("group");
foreach ($KELOMPOK as $grupName => $group) {
	foreach ($group as $mahasiswa) {
		$data["GROUP"] = $grupName;
		$data["USRKEYGROUP"] = $mahasiswa["USRKEY"];
		if ($grupAcess->reset()->where(["USRKEYGROUP" =>  $mahasiswa["USRKEY"]])->count_rows() == 0) {
			$grupAcess->reset()->insert($data);
		} else {
			$grupAcess->reset()->where(["USRKEYGROUP" =>  $mahasiswa["USRKEY"]])->update($data);
		}
	}
}
$KELOMPOK = hitung_peserta($KELOMPOK);
$grupList = new SQL("grouplist");
?>

<head>
	<meta charset="utf-8">
	<meta name="google-site-verification" content="WJXVnQJHS2MEgedF5Yw2RuhkMaf5rEL4ENRq_3KGSGY">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PLT FKIP UNILA</title>
	<meta name="description" content="Website administrasi PLT FKIP UNILA">
	<link href="assets/css/style.min.css" rel="stylesheet" type="text/css">
	<link rel="manifest" href="assets/manifest.json">
	<meta name="theme-color" content="#8806D4">
	<meta name="msapplication-TileColor" content="#8806D4">
	<meta name="msapplication-TileImage" content="./assets/assets/images/ms-icon-144x144.png">
	<link rel="shortcut icon" href="assets/favicon.ico">
</head>

<body>
	<div class="content">
		<div class="header">
			<a>JADWAL</a>
		</div>
		<div class="field">
			<h1>Daftar Kelompok / Jumlah <?= $TOTAL ?>
				<span class="field-action action-right"></span>
			</h1>
			<div id="table">
				<div class="row background-primare text-center">
					<a class=col-md-6>Nama Kelompok</a>
					<a class=col-md-1>Laki-Laki</a>
					<a class=col-md-1>Perempuan</a>
					<a class=col-md-1>Total</a>
				</div>
				<?php
				foreach ($KELOMPOK as $grupName => $grup) {
					# code...
					$row = ' 	<div class="row">';
					$row .= ' 		<a class="col-md-6 text-center">' . $grupName . '</a>';
					$row .= ' 		<a class="col-md-1 text-center">' . $grup["Laki-Laki"] . '</a>';
					$row .= ' 		<a class="col-md-1 text-center">' . $grup["Perempuan"] . '</a>';
					$row .= ' 		<a class="col-md-1 text-center">' . $grup["Total"] . '</a>';
					$row .= ' 	</div>';
					echo $row;

					$data = [];
					$data["GROUP"] = $grupName;
					$data["TOTAL"] = $grup["Total"];
					$data["L"] 	= $grup["Laki-Laki"];
					$data["P"] 	= $grup["Perempuan"];
					$data["YEAR"]	= $YEAR;
					if ($grupList->reset()->where(["GROUP" => $grupName])->count_rows() == 0) {
						$grupList->reset()->insert($data);
					} else {
						$grupList->reset()->where(["GROUP" => $grupName])->update($data);
					}
					echo $grupList->last_error;
				}
				?>
			</div>
		</div>
	</div>
</body>
<?php
// var_dump($KELOMPOK);
