<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
?>
<content>
	<?php
	if (!is_login()) {
	?> <style>
			content #mainContent {
				width: 100%;
			}
		</style>
	<?php
	}	// site defaut
	if (is_login()) {
	?>
		<!--************************************************************************
		Sidebar starts here
		****************************************************************************-->
		<section id="sidebar-wraper">
			</style>
			<div class="Sidebar SidebarAvatar">
				<div>
					<a><strong>Informasi User</strong></a>
				</div>
				<table>
					<tr>
						<td width="80px">User</td>
						<td>: &nbsp;<?php echo  $user['USERID']; ?></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>: &nbsp;<?php echo  $user['NAME']; ?></td>

					</tr>
					<tr>
						<td>Status User </td>
						<td>: &nbsp;<?php echo  $user['LEVEL']; ?></td>
					</tr>
				</table>
			</div>
			<div class="Sidebar SidebarMenu">
				<div>
					<a href="<?php echo set_url("user/dashboard"); ?>" title="Halaman informasi dan berita terbaru kegiatan PLP">HALAMAN ANDA</a>
				</div>
				<div>
					<a href="<?php echo set_url("kegiatan/jadwal"); ?>" title="Jadwal keiatan PLP">JADWAL KEGIATAN</a>
				</div>
				<?php
				if (is_Level("Mahasiswa")) { ?>
					<div>
						<a href="<?php echo set_url("mahasiswa/data/" . $user['ID']); ?>" title="Lihat dan merubah data biadata">BIODATA</a>
					</div>
					<div>
						<a href="<?php echo set_url("mahasiswa/pendaftaran/" . $user['ID']); ?>" title="Download Formulir Pendaftaran">PENDAFTARAN</a>
					</div>
					<?php
					if (isset($registration_done) && $registration_done == TRUE) { ?>
						<div>
							<a href="<?php echo set_url("mahasiswa/penempatan/" . $user['ID']); ?>" title="Penempatan mahasiswa PLP">PENEMPATAN</a>
						</div>
						<div>
							<a href="<?php echo set_url("laporan/mingguan/" . $user['ID']); ?>" title="Upload Laporan PLP">LAPORAN</a>
						</div>
					<?php
					}
				}
				if (is_level("Admin, Monitor")) { ?>
					<div>
						<a href="<?php echo set_url("admin/monitor"); ?>" title="Data statistik peserta">MONITOR</a>
					</div>
				<?php }
				if (is_level("Admin, Operator, Monitor, DPL")) { ?>
					<div>
						<a href="<?php echo set_url("laporan/data/" . $user['ID']); ?>">LAPORAN MAHASISWA</a>
					</div>
				<?php }
				if (is_level("Admin, Monitor, Operator")) { ?>
					<div>
						<a href="<?php echo set_url("registration/data"); ?>" title="Data statistik peserta">DATA PESERTA</a>
					</div>
				<?php }
				if (is_level("Admin, Operator")) { ?>
					<div>
						<a href="<?php echo set_url("registration/validate"); ?>" title="Upload Laporan PLP">VALIDASI REGISTRASI</a>
					</div>
					<div>
						<a href="<?php echo set_url("site/settings"); ?>" title="Upload Laporan PLP">PEENGATURAN WEB</a>
					</div>
					<!-- <div>
							<a href="<?php echo set_url("site/addpenempatan"); ?>"  title="Upload Laporan PPK/KKN-KT">PENEMPATAN</a>
						</div> -->
					<!-- <div>
							<a href="<?php echo set_url("site/addjadwal"); ?>"  title="Upload Laporan PPK/KKN-KT">ATUR JADWAL</a>
						</div> -->
					<div>
						<a href="<?php echo set_url("user/password_reset"); ?>" title="Reset Password Peserta">RESET PASSWORD</a>
					</div>
				<?php }
				if (is_level("Admin")) { ?>
					<div>
						<a href="<?php echo set_url("registration/assignment"); ?>" title="Reset Password Peserta">ASSIGNMENT</a>
					</div>
					<div>
						<a href="<?php echo set_url("user/create_user"); ?>" title="Reset Password Peserta">CREATE USER</a>
					</div>
					<div>
						<a href="<?php echo set_url("admin/impersonate"); ?>" title="Impersonate User">IMPERSONATE</a>
					</div>
				<?php } ?>
				<div>
					<a href="<?php echo set_url("user/settings"); ?>" title="Pengaturan account">PENGATURAN</a>
				</div>
				<div>
					<a href="<?php echo set_url(session_get('IMPERSONATE') ? "admin/restore_impersonate" : "user/logout"); ?>" title="Keluar dari pelayangan PLP online">LOGOUT</a>
				</div>
				<div>
					<a href="<?php echo set_url("site/versionhistory"); ?>" title="Version History">APP VERSION</a>
				</div>
			</div>
		</section>
	<?php
	} // site Login
	?>
</content>