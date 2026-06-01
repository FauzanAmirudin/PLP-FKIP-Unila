<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
?>

<div class="gallery-hero-section">
    <div class="hero-content">
        <h1 class="hero-title">GALLERY PAGE</h1>
    </div>
</div>

<div class="gallery-main-section">
    <div class="section-header">
        <span class="badge">GALERI KEGIATAN</span>
        <h2 class="title">GALLERY POSTS</h2>
        <div class="dots-separator">
            <span></span><span></span><span></span><span></span>
        </div>
    </div>

    <?php if (!empty($photos)) { ?>

    <div class="gallery-grid">
        <?php foreach ($photos as $photo) { ?>
        <div class="gallery-item-wrapper clickable-gallery-item" onclick="openLightbox('<?php echo $photo['GAMBAR']; ?>', '<?php echo htmlspecialchars(addslashes((string)$photo['KETERANGAN']), ENT_QUOTES); ?>')">
            <img src="<?php echo $photo['GAMBAR']; ?>" alt="<?php echo htmlspecialchars((string)$photo['KETERANGAN']); ?>" class="gallery-real-img" onerror="this.closest('.gallery-item-wrapper').style.display='none'" />
            <div class="gallery-overlay">
                <span class="view-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Paginasi — hanya tampil jika total_pages > 1 -->
    <?php if (isset($total_pages) && $total_pages > 1) { ?>
    <div class="info-pagination pagination-margin-top">
        <?php if ($current_page > 1) { ?>
            <a href="?page=gallery&pg=<?php echo $current_page - 1; ?>" class="page-link">&larr;</a>
        <?php } ?>
        <?php for ($p = 1; $p <= $total_pages; $p++) { ?>
            <a href="?page=gallery&pg=<?php echo $p; ?>" class="page-link <?php echo ($p == $current_page) ? 'active' : ''; ?>"><?php echo $p; ?></a>
        <?php } ?>
        <?php if ($current_page < $total_pages) { ?>
            <a href="?page=gallery&pg=<?php echo $current_page + 1; ?>" class="page-link next">&rarr;</a>
        <?php } ?>
    </div>
    <?php } ?>

    <?php } else { ?>
    <!-- Empty State -->
    <div class="empty-gallery-state">
        <div class="empty-gallery-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        </div>
        <h3 class="empty-gallery-title">Belum Ada Foto</h3>
        <p class="empty-gallery-desc">Foto kegiatan akan ditampilkan di sini.</p>
    </div>
    <?php } ?>
</div>

<!-- ===================== LIGHTBOX ===================== -->
<div id="galleryLightbox" class="gallery-lightbox-overlay" style="display: none;" onclick="if(event.target===this) closeLightbox()">
    <!-- Close Button -->
    <button onclick="closeLightbox()" class="gallery-lightbox-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <!-- Foto -->
    <div class="gallery-lightbox-img-wrapper">
        <img id="lightboxImg" src="" alt="Gallery" class="gallery-lightbox-img" />
    </div>
    <!-- Keterangan -->
    <p id="lightboxCaption" class="gallery-lightbox-caption"></p>
</div>



<script>
function openLightbox(src, caption) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxCaption').textContent = caption || '';
    var lb = document.getElementById('galleryLightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('galleryLightbox').style.display = 'none';
    document.getElementById('lightboxImg').src = '';
    document.body.style.overflow = '';
}
// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
