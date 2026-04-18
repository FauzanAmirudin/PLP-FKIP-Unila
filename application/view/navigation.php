<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
?>
<nav>
	<div id="logo">
		<h1 href="?page=home" title="Kembali Ke halaman awal">plt FKIP</h1>
		<h1 href="?page=home" title="Kembali Ke halaman awal">Universitas Lampung</h1>
	</div>
	<?php
	if (!is_login()) {
	?>
		<div id="modal" class="modal">
			<div class="modal-centered">
				<div class="content animate">
					<div class="container">
						<div class="title">
							<span class="action-right">
								<a class="btn btn-tiny btn-danger btn-close" id="close_modal" data-target="login, register" title="Close Modal"></a>
							</span>
						</div>
						<form id="login" action="<?php echo set_url('user/login'); ?>" method="post" class="form field hiden">
							<div class="imgcontainer">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="150px" height="100px" viewBox="0 0 311.541 311.541" style="enable-background:new 0 0 311.541 311.541;" xml:space="preserve">
									<g>
										<g>
											<path d="M155.771,26.331C69.74,26.331,0,96.071,0,182.102c0,37.488,13.25,71.883,35.314,98.761 c3.404-27.256,30.627-50.308,68.8-61.225c13.946,12.994,31.96,20.878,51.656,20.878c19.233,0,36.894-7.487,50.698-19.936 c38.503,11.871,65.141,36.27,66.017,64.63c24.284-27.472,39.056-63.555,39.056-103.108 C311.541,96.071,241.801,26.331,155.771,26.331z M155.771,222.069c-9.944,0-19.314-2.732-27.634-7.464 c-20.05-11.409-33.855-34.756-33.855-61.711c0-38.143,27.583-69.176,61.489-69.176c33.909,0,61.489,31.033,61.489,69.176	c0,27.369-14.237,51.004-34.786,62.215C174.379,219.523,165.346,222.069,155.771,222.069z" fill="#933EC5" />
								</svg>
							</div>
							<div class="form-group">
								<label><b>NPM:</b></label>
								<input type="text" name="username" placeholder="Masukan NPM / NIP" class="box" required <?= input_value($input, 'username'); ?> />
							</div>
							<div class="form-group">
								<label><b>Password:</b></label>
								<input type="password" name="password" placeholder="Masukan Password" class="box" required <?= input_value($input, 'password'); ?> />
							</div>
							<div class="form-group">
								<label><b>Captcha:</b></label>
								<img src="<?php echo set_url('captcha'); ?>" id="captcha_login" class="captcha" /><br>
								<!-- CHANGE TEXT LINK -->
								<a href="#" onclick="resetCaptcha('captcha_login')">Tak Terbaca? Ganti.</a>
							</div>
							<div class="form-group">
								<input type="text" name="captcha" autocomplete="off" required />
							</div>
							<div class="form-group action-right">
								<button type="submit" value="login" name="action" class="btn btn-medium btn-ok">Login</button>
							</div>
						</form>
						<form id="register" action="<?php echo set_url('user/registration'); ?>" method="post" class="field form hiden">
							<div class="center">
								<h1>DAFTAR</h1>
							</div>
							<div class="form-group">
								<label><b>Nama:</b></label>
								<input type="text" name="nama" placeholder="Nama Lengkap" class="box" required <?= input_value($input, 'name'); ?> />
							</div>
							<div class="form-group">
								<label><b>NPM:</b></label>
								<input type="text" name="npm" placeholder="NPM" class="box" required <?= input_value($input, 'npm'); ?> />
							</div>
							<div class="form-group row">
								<div class="input-group col-md-6">
									<label><b>Password:</b></label>
									<input type="Password" name="pass" placeholder="Password" class="box" required <?= input_value($input, 'pass'); ?> />
								</div>
								<div class="input-group col-md-6">
									<label><b>Re-password:</b></label>
									<input type="Password" name="npass" placeholder="Ulangi Password" class="box" required <?= input_value($input, 'npass'); ?> />
								</div>
							</div>
							<div class="form-group row">
								<div class="input-group col-md-6">
									<img src="<?php echo set_url('captcha'); ?>" id="captcha_register" class="captcha" />
									<!-- CHANGE TEXT LINK --><br>
									<a href="#" onclick="resetCaptcha('captcha_register')">Tak Terbaca? Ganti.</a>
								</div>
								<div class="input-group col-md-6">
									<label><b>Captcha:</b></label>
									<input type="text" name="captcha" autocomplete="off" />
								</div>
							</div>
							<div class="form-group action-right" style="text-align:right;">
								<button type="submit" value="register" name="action" class="btn btn-medium btn-ok">Daftar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		</div>
	<?php
	} ?>
	<div class="mobileMenu"> <?php
								if (is_login()) { ?>
			<!-- This search box is displayed only in mobile and tablet laouts and not in desktop layouts -->
			<button id="mnMobileON" onclick="document.getElementById('sidebar-wraper').style.height='100%';
				document.getElementById('mnMobileOFF').style.display='block';
				document.getElementById('mnMobileON').style.display='none'" style="width:auto; float:Right;" class="button">MENU</button>
			<button id="mnMobileOFF" onclick="document.getElementById('sidebar-wraper').style.height='0px';
				document.getElementById('mnMobileOFF').style.display='none';
				document.getElementById('mnMobileON').style.display='block'" style="width:auto; float:Right; display:none;" class="button">CLOSE</button><?php
																																						} else { ?>
			<a onclick="openModal('login', 300)" title="Login" class="button">LOGIN</a>
			<a onclick="openModal('register', 400)" title="register" class="button">DAFTAR</a> <?php
																																						} ?>
	</div>
	<div class="menu desktop">
		<a href="<?php echo set_url(); ?>" title="Kembali Ke halaman awal" class="button desktop">Home</a>
		<a href="<?php echo set_url('gallery'); ?>" title="Galley documentasi dari kegiatan PPK FKIP Unila" class="button desktop">Gallery</a><?php
																																				if (!is_login()) { ?>
			<a onclick="openModal('login', 300)" title="Login" class="button desktop">Login</a>
			<a onclick="openModal('register', 400)" title="Login" class="button desktop">Daftar</a><?php
																																				}
																																				if (is_login()) { ?>
			<a href="<?php echo set_url('loby'); ?>" title="Halaman User" class="button desktop">User</a> <?php
																																				} ?>
	</div>
</nav>