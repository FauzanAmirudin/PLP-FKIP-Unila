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
        <div class="gallery-item-wrapper" onclick="openLightbox('<?php echo $photo['GAMBAR']; ?>', '<?php echo htmlspecialchars(addslashes($photo['KETERANGAN']), ENT_QUOTES); ?>')" style="cursor: pointer;">
            <img src="<?php echo $photo['GAMBAR']; ?>" alt="<?php echo htmlspecialchars($photo['KETERANGAN']); ?>" class="gallery-real-img" onerror="this.closest('.gallery-item-wrapper').style.display='none'" />
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
    <div class="info-pagination" style="margin-top: 40px;">
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
    <div style="text-align: center; padding: 80px 20px;">
        <div style="width: 90px; height: 90px; background: #f0e3fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        </div>
        <h3 style="color: #475569; font-size: 18px; margin: 0 0 8px;">Belum Ada Foto</h3>
        <p style="color: #94a3b8; font-size: 14px; margin: 0;">Foto kegiatan akan ditampilkan di sini.</p>
    </div>
    <?php } ?>
</div>

<!-- ===================== LIGHTBOX ===================== -->
<div id="galleryLightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.88); z-index: 99999; align-items: center; justify-content: center; flex-direction: column; padding: 20px; box-sizing: border-box;" onclick="if(event.target===this) closeLightbox()">
    <!-- Close Button -->
    <button onclick="closeLightbox()" style="position: fixed; top: 16px; right: 20px; background: rgba(255,255,255,0.15); border: none; color: white; width: 42px; height: 42px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s; backdrop-filter: blur(4px); z-index: 100000;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <!-- Foto -->
    <div style="max-width: 90vw; max-height: 80vh; display: flex; align-items: center; justify-content: center;">
        <img id="lightboxImg" src="" alt="Gallery" style="max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); animation: lbFadeIn 0.25s ease;" />
    </div>
    <!-- Keterangan -->
    <p id="lightboxCaption" style="color: rgba(255,255,255,0.85); font-size: 14px; text-align: center; margin: 16px 0 0; max-width: 600px; line-height: 1.6; padding: 0 16px;"></p>
</div>

<style>
/* Gallery item dengan gambar asli */
.gallery-item-wrapper {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    background: #f0e3fc;
}
.gallery-real-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}
.gallery-item-wrapper:hover .gallery-real-img {
    transform: scale(1.06);
}
.gallery-overlay {
    position: absolute;
    inset: 0;
    background: rgba(168, 5, 168, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-item-wrapper:hover .gallery-overlay {
    opacity: 1;
}
.view-btn svg {
    width: 36px;
    height: 36px;
    color: white;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
}

/* Lightbox animation */
@keyframes lbFadeIn {
    from { opacity: 0; transform: scale(0.94); }
    to   { opacity: 1; transform: scale(1); }
}
</style>

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
