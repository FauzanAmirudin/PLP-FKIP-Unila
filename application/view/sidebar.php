<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
	$page = isset($_GET['page']) ? $_GET['page'] : "frontpage";
	$page = str_replace(".html", "", $page);
	$fullpage = ['home', 'frontpage', 'about', 'contact', 'gallery', 'informasi', 'detailinformasi', 'captcha'];
	$is_dashboard = (is_login() && !in_array($page, $fullpage));
	
	// Helper untuk active menu state
	$current_ctrl = explode("/", $page)[0];
	$current_func = isset(explode("/", $page)[1]) ? explode("/", $page)[1] : '';
?>
<content class="<?php echo $is_dashboard ? 'dashboard-container' : ''; ?>">
	<?php
	if (!$is_dashboard) {
	?> <style>
			content #mainContent {
				width: 100%;
			}
		</style>
	<?php
	}	// site defaut
	if ($is_dashboard) {
	?>
		<!-- Mobile Header -->
		<div class="dashboard-mobile-header">
			<button id="mobile-sidebar-toggle" class="burger-btn" aria-label="Toggle Menu">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
			</button>
			<span class="mobile-header-title">Dashboard User</span>
		</div>
		<div id="sidebar-backdrop" class="sidebar-backdrop"></div>

		<!--************************************************************************
		Sidebar starts here
		****************************************************************************-->
		<section id="sidebar-wraper" class="dashboard-sidebar-wrapper">
			<!-- Info User Singkat -->
			<!-- <div class="SidebarAvatar" style="padding: 20px 20px 10px; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px;">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #aeb5cc; margin-bottom: 5px;">Selamat Datang</div>
				<div style="font-weight: 600; font-size: 15px;"><?php echo isset($user['NAME']) ? $user['NAME'] : ''; ?></div>
				<div style="font-size: 12px; opacity: 0.8; margin-top: 2px;">Role: <?php echo isset($user['LEVEL']) ? $user['LEVEL'] : ''; ?></div>
			</div> -->

			<ul class="dashboard-sidebar-menu">
				<li class="sidebar-item <?php echo ($current_ctrl == 'user' && $current_func == 'dashboard') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("user/dashboard"); ?>" title="Halaman Anda">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
						<span class="sidebar-label">Dashboard</span>
					</a>
				</li>
				
				<li class="sidebar-item <?php echo ($current_ctrl == 'kegiatan' && $current_func == 'jadwal') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("kegiatan/jadwal"); ?>" title="Jadwal Kegiatan">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
						<span class="sidebar-label">Jadwal Kegiatan</span>
					</a>
				</li>

				<?php if (is_Level("Mahasiswa")) { ?>
				<li class="sidebar-item <?php echo ($current_ctrl == 'mahasiswa' && $current_func == 'data') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("mahasiswa/data/" . $user['ID']); ?>" title="Biodata">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
						<span class="sidebar-label">Biodata Mahasiswa</span>
					</a>
				</li>
				<li class="sidebar-item <?php echo ($current_ctrl == 'mahasiswa' && $current_func == 'pendaftaran') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("mahasiswa/pendaftaran/" . $user['ID']); ?>" title="Pendaftaran">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
						<span class="sidebar-label">Pendaftaran</span>
					</a>
				</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'mahasiswa' && $current_func == 'penempatan') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("mahasiswa/penempatan/" . $user['ID']); ?>" title="Penempatan">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
							<span class="sidebar-label">Penempatan</span>
						</a>
					</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'laporan' && $current_func == 'mingguan') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("laporan/mingguan/" . $user['ID']); ?>" title="Laporan">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
							<span class="sidebar-label">Upload Laporan</span>
						</a>
					</li>
				<?php } ?>

				<?php if (is_level("Admin, Monitor, Operator")) { ?>
					<li class="sidebar-item <?php echo ($current_ctrl == 'registration' && $current_func == 'data') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("registration/data"); ?>" title="Data Peserta">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
							<span class="sidebar-label">Data Peserta</span>
						</a>
					</li>
				<?php } ?>

				<?php if (is_level("Admin, Monitor, Operator, DPL")) { ?>
					<li class="sidebar-item <?php echo ($current_ctrl == 'laporan' && $current_func == 'data') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("laporan/data/" . $user['ID']); ?>">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
							<span class="sidebar-label">Laporan Mahasiswa</span>
						</a>
					</li>
				<?php } ?>

				<?php if (is_level("Admin, Monitor")) { ?>
					<li class="sidebar-item <?php echo ($current_ctrl == 'admin' && $current_func == 'monitor') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("admin/monitor"); ?>" title="Monitor">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
							<span class="sidebar-label">Monitor</span>
						</a>
					</li>
				<?php } ?>

				<?php if (is_level("Admin, Operator")) { ?>
					<li class="sidebar-item <?php echo ($current_ctrl == 'registration' && $current_func == 'validate') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("registration/validate"); ?>" title="Validasi Registrasi">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
							<span class="sidebar-label">Validasi Registrasi</span>
						</a>
					</li>
				<li class="sidebar-item <?php echo ($current_ctrl == 'site' && in_array($current_func, ['informasi', 'informasi_create', 'informasi_edit'])) ? 'active' : ''; ?>">
					<a href="<?php echo set_url("site/informasi"); ?>" title="Kelola Informasi">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg>
						<span class="sidebar-label">Kelola Informasi</span>
					</a>
				</li>
				<li class="sidebar-item <?php echo ($current_ctrl == 'site' && in_array($current_func, ['gallery', 'gallery_create', 'gallery_edit'])) ? 'active' : ''; ?>">
					<a href="<?php echo set_url("site/gallery"); ?>" title="Kelola Gallery">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
						<span class="sidebar-label">Kelola Gallery</span>
					</a>
				</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'site' && $current_func == 'settings') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("site/settings"); ?>" title="Pengaturan Web">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
							<span class="sidebar-label">Pengaturan Web</span>
						</a>
					</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'user' && $current_func == 'password_reset') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("user/password_reset"); ?>" title="Reset Password Peserta">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
							<span class="sidebar-label">Reset Password</span>
						</a>
					</li>
				<?php } ?>

				<?php if (is_level("Admin")) { ?>
					<li class="sidebar-item <?php echo ($current_ctrl == 'registration' && $current_func == 'assignment') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("registration/assignment"); ?>">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
							<span class="sidebar-label">Assignment</span>
						</a>
					</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'user' && $current_func == 'create_user') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("user/create_user"); ?>">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
							<span class="sidebar-label">Create User</span>
						</a>
					</li>
					<li class="sidebar-item <?php echo ($current_ctrl == 'admin' && $current_func == 'impersonate') ? 'active' : ''; ?>">
						<a href="<?php echo set_url("admin/impersonate"); ?>">
							<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
							<span class="sidebar-label">Impersonate</span>
						</a>
					</li>
				<?php } ?>

				<li class="sidebar-item <?php echo ($current_ctrl == 'user' && $current_func == 'settings') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("user/settings"); ?>" title="Pengaturan">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
						<span class="sidebar-label">Setting</span>
					</a>
				</li>
				<li class="sidebar-item <?php echo ($current_ctrl == 'site' && $current_func == 'versionhistory') ? 'active' : ''; ?>">
					<a href="<?php echo set_url("site/versionhistory"); ?>">
						<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
						<span class="sidebar-label">App Version</span>
					</a>
				</li>
			</ul>
			<div class="dashboard-sidebar-footer">
				<a href="<?php echo set_url(session_get('IMPERSONATE') ? "admin/restore_impersonate" : "user/logout"); ?>" class="dashboard-logout-btn" title="Keluar">
					<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
					<span class="sidebar-label">Logout</span>
				</a>
			</div>
		</section>
		<script>
		document.addEventListener("DOMContentLoaded", function() {
			var toggleBtn = document.getElementById('mobile-sidebar-toggle');
			var sidebar = document.getElementById('sidebar-wraper');
			var backdrop = document.getElementById('sidebar-backdrop');
			
			if (toggleBtn && sidebar && backdrop) {
				function toggleMenu() {
					sidebar.classList.toggle('open');
					backdrop.classList.toggle('open');
				}
				toggleBtn.addEventListener('click', toggleMenu);
				backdrop.addEventListener('click', toggleMenu);
			}
		});
		</script>
	<?php
	} // site Login
	?>
	<section id="mainContent" class="<?php echo ($is_dashboard) ? 'dashboard-main-content' : ''; ?>">
		<!--************************************************************************
		Main content starts here
		************************************'****************************************-->