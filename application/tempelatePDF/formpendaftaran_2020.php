<?php
	$foto = $data["FTPROFIL"];
	$nama=$data["NAMA"];
	$npm=$data["NPM"];
	$prodi=$data["PROGRAMSTUDI"];
	$sks=$data["SKS"];
	$ipk= $data["IPK"];
	$kelamin=$data["JENISKELAMIN"];
	$agama=$data["AGAMA"];
	$nohp=$data["NOTELEPON"];
	$kaos=$data["SEMESTERPERIODE"];
	$alamat=$data["ALAMATTINGGAL"];
	$ketrampilan=$data["KETRAMPILAN"];
	$organisasi=$data["ORGANISASI"];
	$namaWali=$data["NAMAAYAH"];
	$noWali=$data["NOHPORTU"];
	$rumah=$data["ALAMATASAL"].", ".$data["KECAMATAN"].", ".$data["KABUPATEN"].", ".$data["PROPINSI"].".";
	$darurat=$data["NAMAGENTING"] . " / " . $data["NOHPGENTING"];
	$semester="Ganjil";
	$tahundaftar=$data["TAHUNDAFTAR"];
	$tahunSemester=$tahundaftar . "/" . ((int)$tahundaftar + 1);
	$ketuaPLT="Tasviri Efkar";

	$nipKetuaPLT="195810041987031001";
	$ketuaProdi=$data['KETUAPRODI'];
	$nipKetuaPRODI=$data['NIPKETUAPRODI'];
	$PembimbingAkademik = $data['PEMBIMBINGAKADEMIK'];
	$nipPembimbingAkademik = $data['NIPPEMBIMBINGAKADEMIK'];
	$year = date("Y");
	//exit;
?>
<!doctype html>
<html>
	<head>
		<title>Form Pendaftaran</title>
		<style type="text/css">
			body {
			font-family: "Times New Roman", Times, serif;
			display: inline-block;
			font-weight: normal;
			}
			h1 {
				font-size: 12pt;
				font-weight: normal;
				margin: 0pt;
				padding: 0pt;
			}
			h2 {
				font-size: 12pt;
				font-weight: bold;
				margin: 0pt;
				padding: 0pt;
			}
			h3 {
				font-size: 11pt;
				font-weight: bold;
				margin:0px;
				padding:0px;
			}
			h4 {
				font-size: 10pt;
				font-weight: normal;
				margin:0pt;
				padding:0pt;
			}
			h5 {
				font-size: 8pt;
				font-weight: normal;
				margin:0pt;
				padding:0pt;
			}
			a, p {
				font-size: 11pt;
			}
			.logo-left {
				float:left;
				width: 60pt;
				height: 60pt;
				display:inline-block;
			}
			.logo-right {
				float:right;
				width: 60pt;
				height: 60pt;
				display:inline-block;
			}
			.pass-foto {
				text-align: center;
				float:right;
				width: 100px;
				height: 125px;
				border-color: black;
				border-style: dotted;
				border-width: 2px;
				display:inline-block;
			}
			.kepala {
				font-size:12pt;
				text-align: center;
				display:inline-block;
			}
			.judul {
				text-align: center;
				padding-top: 15pt;
				margin-bottom: 15pt;
				border-top-color: black;
				border-top-style: double;
				border-top-width: thick;
			}
			.isi {
				display:inline-block;
				font-size:12pt;
				padding-left:1cm;
				padding-right:1cm;
			}
			.isi .biodata{
				float:left;
			}
			.isi .col-1{
				float:left;
				width: 130pt;
			}
			.isi .col-2{
				float:left;
				width: 15pt;
			}
			.isi .col-3{
				float:left;
				width: 240pt;
			}

			.isi table {
				text-align:left;
				font-size:12pt;
				}
			.isi:after, .biodata:after, .text:before, .text:after, .text, .sign:after {
				content:'';
				clear:both;
				display:block;
				width: 100%;
			}
			.isi .sign-left {
				float:left;
				width:40%;
			
			}
			.isi .sign-right {
				float:right;
				width:40%;
			
			}
			.materai {
				font-size:8pt;
				transform:translate(-10pt);
			}
			.isi .sign-left, .isi .sign-right {
				margin-bottom: 15pt;
			}
			.isi .sign-left p, .isi .sign-right p {
				margin-bottom: 45pt;
			}
			.isi .lines {
				width:100%;
				text-align:center;		
			}
		</style>
	</head>
	<body>
		<div class="logo-left">
			<img src="assets/images/logo.png" width="120px">
		</div>
		<div class="logo-right">
		</div>
		<div class="kepala" style="font-size:5pt;">
		<h1>KEMENTERIAN PENDIDIDIKAN DAN KEBUDAYAAN<br>UNIVERSITAS LAMPUNG<br>FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN</h1>
			<h2>PRAKTIK LAPANGAN TERPADU</h2>
		</div>
		<div class="kepala">
			<h4>Jl. Prof. Dr. Sumantri Brojonegoro No.1 Bandarlampung 35415, http://plt.fkip.unila.ac.id</h4>
		</div>
		<div class="judul">
			<h2>PENDAFTARAN PLP 1 DAN PLP 2 FKIP UNIVERSITAS LAMPUNG<br>
			SEMESTER GANJIL <?php if(isset($tahunSemester)){echo $tahunSemester;}else{$n=1; while($n<=20){echo ".";$n++;}} ?>
			</h2>
		</div>
		<div class="isi">
			<div class="pass-foto">
				<?php if(isset($npm)){echo '<img src="'. $foto.'" width="100px" height="125px">';}else{echo '<br><br><a> Pass Foto </a><br><a> 3 cm x 4 cm</a>';} ?>
			</div>
			<div class="biodata">
				<a>Dengan ini saya:</a>
				<div class="row">
					<div class="col-1">
						<a>Nama</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($nama)){echo $nama;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>NPM</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($npm)){echo $npm;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Program Studi</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($prodi)){echo $prodi;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Jumlah SKS</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($sks)){echo $sks;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>IPK</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($ipk)){echo $ipk;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Jenis Kelamin</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($kelamin)){echo $kelamin;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Agama</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($agama)){echo $agama;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div> 
				<div class="row">
					<div class="col-1">
						<a>Nomor HP</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($nohp)){echo $nohp;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Hobi/bakat/keahlian </a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($ketrampilan)){echo $ketrampilan;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Alamat</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($alamat)){echo $alamat;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<a>Orang Tua:</a>
				<div class="row">
					<div class="col-1">
						<a>Nama (ayah/ibu/wali)</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($namaWali)){echo $namaWali;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>No HP (ayah/ibu/wali)</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($noWali)){echo $noWali;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Alamat</a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($rumah)){echo $rumah;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
						<a>Kontak darurat </a>
					</div>
					<div class="col-3">
						<a>:  <?php if(isset($darurat)){echo $darurat;}else{$n=1; while($n<=65){echo ".";$n++;}} ?></a>
					</div>
				</div>
			</div>

			<div class="text">
				<p>Mendaftar sebagai peserta PLP (PLP 1 dan PLP 2) semester ganjil <?php if(isset($tahunSemester)){echo $tahunSemester;}else{$n=1; while($n<=20){echo ".";$n++;}} ?>. Saya bersedia ditempatkan dimana saja, dan akan mengikuti tata tertib sebagai mahasiswa PLP, sebagaimana yang tercantum dalam Panduan Pengenalan Lapangan Persekolahan (PLP) FKIP Universitas Lampung tahun 2020. Demikian permohonan ini disampaikan dan dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mustinya.</p>
			</div>
			<div class="sign">
				<div class="sign-right">
					<b class="title">Pendaftar</b><br><br><br>
					<h5 class="materai">materai 6000</h5><br>
					<a><?php if(isset($nama)){echo $nama;}else{$n=1; while($n<=35){echo ".";$n++;}} ?></a>
					<br><a>NPM. <?php if(isset($npm)){echo $npm;} ?></a>
				</div>
			</div>
			<div class="lines"><h3>Mengetahui/Menyetujui</h3></div>
			<div class="sign">
				<div class="sign-left">
					<p><b>Koordinator Program Studi</b></p><a><?php if(isset($ketuaProdi)){echo $ketuaProdi;}else{$n=1; while($n<=35){echo ".";$n++;}} ?></a><br><a>NIP. <?php if(isset($nipKetuaPRODI)){echo $nipKetuaPRODI;} ?></a>
				</div>
				<div class="sign-right">
					<p><b>Dosen PA</b></p><a><?php if(isset($PembimbingAkademik)){echo $PembimbingAkademik;}else{$n=1; while($n<=35){echo ".";$n++;}} ?></a><br><a>NIP. <?php if(isset($nipPembimbingAkademik)){echo $nipPembimbingAkademik;} ?></a>
				</div>
			</div>
		</div>
	</body>
</html>
