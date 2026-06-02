<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
?>
<style>
.nav-profile-dropdown-container {
    position: relative;
    display: inline-block;
}
.nav-profile-trigger {
    background: none;
    border: 2px solid #fff;
    padding: 0;
    cursor: pointer;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.nav-profile-trigger:hover {
    transform: scale(1.05);
    border-color: #fec5f6;
}
.nav-profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.nav-profile-initials {
    width: 100%;
    height: 100%;
    background-color: #a805a8;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
}
.nav-profile-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    width: 200px;
    padding: 8px 0;
    display: none;
    flex-direction: column;
    z-index: 1000;
    border: 1px solid #eaeaea;
    animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.nav-profile-dropdown.show {
    display: flex;
}
@keyframes dropdownFadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.dropdown-header {
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    text-align: left;
}
.dropdown-name {
    font-weight: 600;
    font-size: 14px;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Poppins', sans-serif;
}
.dropdown-role {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
}
.dropdown-divider {
    height: 1px;
    background-color: #f1f5f9;
    margin: 6px 0;
}
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: #334155;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    transition: background-color 0.15s ease, color 0.15s ease;
    text-align: left;
}
.dropdown-item:hover {
    background-color: #f8fafc;
    color: #a805a8;
}
.dropdown-item.logout {
    color: #d93025;
}
.dropdown-item.logout:hover {
    background-color: #fce8e6;
    color: #d93025;
}
.dropdown-icon {
    width: 16px;
    height: 16px;
    color: currentColor;
}
</style>
<nav id="navbar">
	<!-- ===================== MODAL LOGIN & REGISTER (logika tidak diubah) ===================== -->
	<?php if (!is_login()) { ?>
	<div id="modal" class="modal">
		<div class="modal-centered">
			<div class="content animate">

				<!-- ======= FORM LOGIN ======= -->
				<form id="login" action="?page=user/login" method="post" class="login-card hiden">

					<!-- Close Button -->
					<button type="button" class="login-close-btn" id="close_modal" data-target="login, register" title="Tutup">&#10005;</button>

					<!-- Header -->
					<div class="login-header">
						<div class="login-logo">
							<img src="assets/images/logo.png" alt="Logo" />
						</div>
						<h2 class="login-title">Masuk Akun</h2>
						<p class="login-subtitle">Sistem PLP FKIP Universitas Lampung</p>
					</div>

					<!-- NPM -->
					<div class="login-input-group">
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
							<input type="text" name="username" class="login-input" placeholder="NPM / NIP" required <?php if (isset($_SESSION['tmp_user']) && $_SESSION['tmp_user'] != null) { echo ('value="' . $_SESSION['tmp_user'] . '"'); } ?> />
						</div>
					</div>

					<!-- Password -->
					<div class="login-input-group">
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							<input type="password" name="password" class="login-input" placeholder="Kata Sandi" required />
						</div>
					</div>

					<!-- Captcha -->
					<div class="login-input-group">
						<div class="login-captcha-row">
							<img src="?page=captcha" id="captcha_login" class="login-captcha-img" />
							<a href="javascript:void(0)" onclick="resetCaptcha('captcha_login')" class="login-captcha-refresh">&#8635; Ganti</a>
						</div>
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
							<input type="text" name="captcha" class="login-input" placeholder="Masukkan kode captcha" autocomplete="off" required />
						</div>
					</div>

					<!-- Submit -->
					<button type="submit" value="login" name="action" class="login-submit-btn">
						<span class="login-btn-glow"></span>
						Masuk
					</button>

					<!-- Footer ke Register -->
					<p class="login-footer-text">
						Belum punya akun?
						<a class="login-switch-link" onclick="switchModal('login','register')" title="Daftar akun baru">Daftar di sini</a>
					</p>

				</form>

				<!-- ======= FORM REGISTER ======= -->
				<form id="register" action="?page=user/registration" method="post" class="login-card hiden">

					<!-- Close Button -->
					<button type="button" class="login-close-btn" id="close_modal_reg" data-target="login, register" title="Tutup">&#10005;</button>

					<!-- Header -->
					<div class="login-header">
						<div class="login-logo">
							<img src="assets/images/logo.png" alt="Logo" />
						</div>
						<h2 class="login-title">Buat Akun</h2>
						<p class="login-subtitle">Daftar sebagai peserta PLP FKIP Unila</p>
					</div>

					<!-- Nama -->
					<div class="login-input-group">
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
							<input type="text" name="nama" class="login-input" placeholder="Nama Lengkap" required <?php if (isset($_SESSION['tmp_nama']) && $_SESSION['tmp_nama'] != null) { echo ('value="' . $_SESSION['tmp_nama'] . '"'); } ?> />
						</div>
					</div>

					<!-- NPM -->
					<div class="login-input-group">
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
							<input type="text" name="npm" class="login-input" placeholder="NPM" required <?php if (isset($_SESSION['tmp_npm']) && $_SESSION['tmp_nama'] != null) { echo ('value="' . $_SESSION['tmp_npm'] . '"'); } ?> />
						</div>
					</div>

					<!-- Password -->
					<div class="login-input-group login-row">
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							<input type="password" name="pass" class="login-input" placeholder="Kata Sandi" required />
						</div>
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							<input type="password" name="npass" class="login-input" placeholder="Ulangi Kata Sandi" required />
						</div>
					</div>

					<!-- Captcha -->
					<div class="login-input-group">
						<div class="login-captcha-row">
							<img src="?page=captcha" id="captcha_register" class="login-captcha-img" />
							<a href="javascript:void(0)" onclick="resetCaptcha('captcha_register')" class="login-captcha-refresh">&#8635; Ganti</a>
						</div>
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
							<input type="text" name="captcha" class="login-input" placeholder="Masukkan kode captcha" autocomplete="off" />
						</div>
					</div>

					<!-- Submit -->
					<button type="submit" value="register" name="action" class="login-submit-btn">
						<span class="login-btn-glow"></span>
						Buat Akun
					</button>

					<!-- Footer ke Login -->
					<p class="login-footer-text">
						Sudah punya akun?
						<a class="login-switch-link" onclick="switchModal('register','login')" title="Masuk">Masuk di sini</a>
					</p>

				</form>

			</div>
		</div>
	</div>
	<?php } ?>


	<!-- ===================== NAVBAR INNER ===================== -->
	<div class="nav-inner">

		<!-- KIRI: Logo + Nama -->
		<div class="nav-brand">
			<a href="<?= GF_CONFIG['base_url'] ?>/" class="nav-brand-link">
				<img src="assets/images/logo.png" alt="Logo PLP FKIP Unila" class="nav-logo-img" />
				<span class="nav-brand-text">PLP FKIP UNILA</span>
			</a>
		</div>

		<!-- TENGAH: Menu Desktop -->
		<div class="nav-menu-center">
			<a href="?page=frontpage" class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'frontpage') ? 'nav-link-active' : ''; ?>">BERANDA</a>
			<a href="?page=about" class="nav-link <?php echo (isset($page) && $page == 'about') ? 'nav-link-active' : ''; ?>">TENTANG</a>
			<a href="?page=informasi" class="nav-link <?php echo (isset($page) && in_array($page, ['informasi', 'detailinformasi'])) ? 'nav-link-active' : ''; ?>">INFORMASI</a>
			<a href="?page=gallery" class="nav-link <?php echo (isset($page) && $page == 'gallery') ? 'nav-link-active' : ''; ?>">GALERI</a>
			<a href="?page=contact" class="nav-link <?php echo (isset($page) && $page == 'contact') ? 'nav-link-active' : ''; ?>">KONTAK</a>
		</div>

		<!-- KANAN: Tombol Login / User -->
		<div class="nav-actions">
			<?php if (!is_login()) { ?>
				<a onclick="openModal('login', 460)" title="Masuk" class="nav-btn-login">MASUK &nbsp;&#8594;</a>
			<?php } else { 
				$avatarUrl = '';
				if (strtolower(session_get('LEVEL')) == 'mahasiswa') {
					$dbAccess = clone $this->database('default', 'dbconfig', TRUE);
					$mahasiswa = $dbAccess->reset()->where("`USRKEY` = " . session_get('ID'))->result_row_array('datamahasiswa');
					if (!empty($mahasiswa['FTPROFIL'])) {
						$avatarUrl = $mahasiswa['FTPROFIL'];
					}
				}
				$initials = strtoupper(substr(session_get('NAME') ?: session_get('FULLNAME') ?: 'U', 0, 1));
			?>
				<div class="nav-profile-dropdown-container">
					<button class="nav-profile-trigger" id="profileDropdownTrigger" onclick="toggleProfileDropdown(event)" aria-haspopup="true" aria-expanded="false">
						<?php if (!empty($avatarUrl)) { ?>
							<img src="<?= $avatarUrl ?>" alt="Profile" class="nav-profile-img">
						<?php } else { ?>
							<div class="nav-profile-initials"><?= $initials ?></div>
						<?php } ?>
					</button>
					<div class="nav-profile-dropdown" id="profileDropdown">
						<div class="dropdown-header">
							<span class="dropdown-name"><?= htmlspecialchars(session_get('NAME') ?: session_get('FULLNAME')) ?></span>
							<span class="dropdown-role"><?= htmlspecialchars(session_get('LEVEL')) ?></span>
						</div>
						<div class="dropdown-divider"></div>
						<a href="?page=user/dashboard" class="dropdown-item">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
							Dashboard
						</a>
						<a href="<?php echo set_url(session_get('IMPERSONATE') ? "admin/restore_impersonate" : "user/logout"); ?>" class="dropdown-item logout">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
							Keluar
						</a>
					</div>
				</div>
			<?php } ?>
		</div>

		<!-- Mobile Toggle -->
		<div class="nav-mobile-toggle">
			<button id="navMobileMenuON" onclick="openMobileMenu()" class="nav-mobile-btn">&#9776; MENU</button>
			<button id="navMobileMenuOFF" onclick="closeMobileMenu()" style="display:none;" class="nav-mobile-btn">&#10005; TUTUP</button>
		</div>

	</div><!-- /nav-inner -->

	<!-- ===================== MOBILE NAV OVERLAY ===================== -->
	<div id="mobile-menu-overlay" class="mobile-menu-overlay">
		<div class="mobile-menu-content">
			<a href="?page=frontpage" class="mobile-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'frontpage') ? 'active' : ''; ?>">BERANDA</a>
			<a href="?page=about" class="mobile-link <?php echo (isset($page) && $page == 'about') ? 'active' : ''; ?>">TENTANG</a>
			<a href="?page=informasi" class="mobile-link <?php echo (isset($page) && in_array($page, ['informasi', 'detailinformasi'])) ? 'active' : ''; ?>">INFORMASI</a>
			<a href="?page=gallery" class="mobile-link <?php echo (isset($page) && $page == 'gallery') ? 'active' : ''; ?>">GALERI</a>
			<a href="?page=contact" class="mobile-link <?php echo (isset($page) && $page == 'contact') ? 'active' : ''; ?>">KONTAK</a>
			
			<div class="mobile-menu-divider"></div>

			<?php if (!is_login()) { ?>
				<a onclick="closeMobileMenu(); openModal('login', 460)" class="mobile-btn login-btn">MASUK</a>
				<a onclick="closeMobileMenu(); openModal('register', 460)" class="mobile-btn register-btn">DAFTAR AKUN</a>
			<?php } else { ?>
				<a href="?page=user/dashboard" class="mobile-btn login-btn">DASHBOARD &rarr;</a>
				<a href="<?php echo set_url(session_get('IMPERSONATE') ? "admin/restore_impersonate" : "user/logout"); ?>" class="mobile-btn logout-btn" style="background-color: #fce8e6; color: #d93025; margin-top: 10px;">KELUAR</a>
			<?php } ?>
		</div>
	</div>

</nav>

<script>
function toggleProfileDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.getElementById('profileDropdownTrigger');
    if (!dropdown || !trigger) return;
    const isShown = dropdown.classList.contains('show');
    
    if (isShown) {
        dropdown.classList.remove('show');
        trigger.setAttribute('aria-expanded', 'false');
    } else {
        dropdown.classList.add('show');
        trigger.setAttribute('aria-expanded', 'true');
    }
}

window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.getElementById('profileDropdownTrigger');
    if (dropdown && dropdown.classList.contains('show')) {
        if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
            trigger.setAttribute('aria-expanded', 'false');
        }
    }
});

function resetCaptcha(imgId) {
    document.getElementById(imgId).src = "?page=captcha&t=" + new Date().getTime();
}

let isMobileMenuOpen = false;

function openMobileMenu() {
    isMobileMenuOpen = true;
    document.getElementById('navMobileMenuON').style.display = 'none';
    document.getElementById('navMobileMenuOFF').style.display = 'block';
    
    let overlay = document.getElementById('mobile-menu-overlay');
    if (overlay) {
        overlay.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    isMobileMenuOpen = false;
    document.getElementById('navMobileMenuON').style.display = 'block';
    document.getElementById('navMobileMenuOFF').style.display = 'none';
    
    let overlay = document.getElementById('mobile-menu-overlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
    document.body.style.overflow = 'auto';
}

/* Auto-hide Navbar on Scroll */
let lastScrollTop = 0;
const navbar = document.querySelector('nav');

window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (isMobileMenuOpen) return;
    
    /* Jangan sembunyikan jika berada di paling atas */
    if (scrollTop <= 55) {
        navbar.style.top = '0';
        return;
    }
    
    if (scrollTop > lastScrollTop) {
        /* Scroll ke bawah -> Sembunyikan navbar */
        navbar.style.top = '-80px'; 
    } else {
        /* Scroll ke atas -> Tampilkan navbar */
        navbar.style.top = '0';
    }
    
    lastScrollTop = scrollTop;
});
</script>