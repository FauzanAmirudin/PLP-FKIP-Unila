  <?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
/**
*	
*/

?>
  <style>
  .pasfoto {
    margin-right:10px;
    height: 3.8cm;
    float:left;
    padding: 3px;
    box-shadow: 5px 4px 5px rgba(0,0,0, 0.12), 0px 6px 20px rgba(0,0,0, 0.12);
  }
  .hero-section {
    background-color: transparent !important;
    position: relative;
  }
  .hero-section .hero-bg-diagonal {
    clip-path: none !important;
    height: 100% !important;
  }
  .hero-siger-bg {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80%;
    max-width: 600px;
    opacity: 0.3;
    z-index: 1;
    mix-blend-mode: screen;
    pointer-events: none;
  }
  .hero-content {
    position: relative;
    z-index: 2;
  }
  </style>
	<div class="hero-section">
		<div class="hero-bg-diagonal"></div>
		<img src="assets/images/siger.png" class="hero-siger-bg" alt="Siger Background">
		
		<div class="hero-content">
			<h1 class="hero-title-main">
				<span>PENGENALAN LAPANGAN PERSEKOLAHAN (PLP)</span>
				<br/> FKIP UNIVERSITAS LAMPUNG
			</h1>
			
			<!-- <div class="hero-actions">
				<a href="?page=loby" class="btn-primary">DASHBOARD PESERTA</a>
			</div> -->
		</div>
	</div>

	<div class="welcome-section">
		<div class="welcome-visual">
			<img src="assets/images/hero-asset.jpg" alt="Selamat Datang di PLP" onerror="this.src='https://placehold.co/600x800/e2e8f0/64748b?text=Gambar+Selamat+Datang'" />
		</div>
		<div class="welcome-content">
			<span class="welcome-badge">Selamat Datang</span>
			<h3 class="welcome-title">Selamat Datang di PLP FKIP<br />Universitas Lampung</h3>
			<p class="welcome-desc">
				Pengenalan Lapangan Persekolahan (PLP) Fakultas Keguruan dan Ilmu Pendidikan Universitas Lampung memiliki tanggung jawab yang besar dalam pelaksanaan tridharma perguruan tinggi baik unsur pendidikan, penelitian, maupun pengabdian kepada masyarakat yang diwujudkan secara profesional dan berkarakter.
			</p>
		</div>
	</div>

	<div class="info-section">
		<div class="info-header">
			<span class="info-badge">PANEL INFORMASI</span>
			<h3 class="info-title">INFORMASI TERBARU</h3>
			<div class="info-dots">
				<span></span><span></span><span></span><span></span>
			</div>
		</div>

		<div class="info-grid">
			<?php if (!empty($recent_info)) { ?>
				<?php foreach ($recent_info as $info) { ?>
				<div class="info-card">
					<h4 class="card-title"><?php echo htmlspecialchars(mb_strimwidth($info['JUDUL'], 0, 40, '...')); ?></h4>
					<p class="card-desc"><?php echo htmlspecialchars(mb_strimwidth(strip_tags(htmlspecialchars_decode($info['INFORMASI'])), 0, 100, '...')); ?></p>
					<a href="?page=detailinformasi&id=<?php echo (int)$info['ID']; ?>" class="card-link">BACA SELENGKAPNYA &mdash; &rarr;</a>
				</div>
				<?php } ?>
			<?php } else { ?>
				<div class="info-card" style="grid-column: 1 / -1; text-align: center;">
					<p class="card-desc">Belum ada informasi terbaru saat ini.</p>
				</div>
			<?php } ?>
		</div>
	</div>
