<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
?>
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
							<input type="password" name="password" class="login-input" placeholder="Password" required />
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
							<input type="password" name="pass" class="login-input" placeholder="Password" required />
						</div>
						<div class="login-input-wrapper">
							<svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							<input type="password" name="npass" class="login-input" placeholder="Ulangi Password" required />
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
			<a href="?page=frontpage" class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'frontpage') ? 'nav-link-active' : ''; ?>">HOME</a>
			<a href="?page=about" class="nav-link <?php echo (isset($page) && $page == 'about') ? 'nav-link-active' : ''; ?>">ABOUT</a>
			<a href="?page=informasi" class="nav-link <?php echo (isset($page) && in_array($page, ['informasi', 'detailinformasi'])) ? 'nav-link-active' : ''; ?>">INFORMASI</a>
			<a href="?page=gallery" class="nav-link <?php echo (isset($page) && $page == 'gallery') ? 'nav-link-active' : ''; ?>">GALLERY</a>
			<a href="?page=contact" class="nav-link <?php echo (isset($page) && $page == 'contact') ? 'nav-link-active' : ''; ?>">CONTACT</a>
		</div>

		<!-- KANAN: Tombol Login / User -->
		<div class="nav-actions">
			<?php if (!is_login()) { ?>
				<a onclick="openModal('login', 460)" title="Login" class="nav-btn-login">LOGIN &nbsp;&#8594;</a>
			<?php } else { ?>
				<a href="?page=user/dashboard" class="nav-btn-login">HALAMAN ANDA &nbsp;&#8594;</a>
			<?php } ?>
		</div>

		<!-- Mobile Toggle -->
		<div class="nav-mobile-toggle">
			<button id="navMobileMenuON" onclick="openMobileMenu()" class="nav-mobile-btn">&#9776; MENU</button>
			<button id="navMobileMenuOFF" onclick="closeMobileMenu()" style="display:none;" class="nav-mobile-btn">&#10005; CLOSE</button>
		</div>

	</div><!-- /nav-inner -->

	<!-- ===================== MOBILE NAV OVERLAY ===================== -->
	<div id="mobile-menu-overlay" class="mobile-menu-overlay">
		<div class="mobile-menu-content">
			<a href="?page=frontpage" class="mobile-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'frontpage') ? 'active' : ''; ?>">HOME</a>
			<a href="?page=about" class="mobile-link <?php echo (isset($page) && $page == 'about') ? 'active' : ''; ?>">ABOUT</a>
			<a href="?page=informasi" class="mobile-link <?php echo (isset($page) && in_array($page, ['informasi', 'detailinformasi'])) ? 'active' : ''; ?>">INFORMASI</a>
			<a href="?page=gallery" class="mobile-link <?php echo (isset($page) && $page == 'gallery') ? 'active' : ''; ?>">GALLERY</a>
			<a href="?page=contact" class="mobile-link <?php echo (isset($page) && $page == 'contact') ? 'active' : ''; ?>">CONTACT</a>
			
			<div class="mobile-menu-divider"></div>

			<?php if (!is_login()) { ?>
				<a onclick="closeMobileMenu(); openModal('login', 460)" class="mobile-btn login-btn">LOGIN</a>
				<a onclick="closeMobileMenu(); openModal('register', 460)" class="mobile-btn register-btn">DAFTAR AKUN</a>
			<?php } else { ?>
				<a href="?page=user/dashboard" class="mobile-btn login-btn">HALAMAN ANDA &rarr;</a>
			<?php } ?>
		</div>
	</div>

</nav>

<script>
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