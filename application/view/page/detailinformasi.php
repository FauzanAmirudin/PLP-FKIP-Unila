<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');

$art = isset($article) ? $article : null;
if (empty($art)) return;
?>

<div class="detail-hero-section">
    <div class="hero-content">
        <h1 class="hero-title">DETAILS</h1>
    </div>
</div>

<div class="detail-main-section">
    <div class="detail-container">
        <div class="detail-content-left">
            
            <article class="full-article">
                <?php if (!empty($art['GAMBAR'])) { ?>
                <div class="article-hero-image" style="background: none; padding: 0; margin-bottom: 24px;">
                    <img src="<?php echo $art['GAMBAR']; ?>" alt="<?php echo htmlspecialchars($art['JUDUL']); ?>" style="width: 100%; max-height: 450px; object-fit: cover; border-radius: 12px; display: block;" />
                </div>
                <?php } ?>
                
                <div class="article-meta">
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <?php echo date("d M, Y", strtotime($art['TANGGAL'])); ?>
                    </span>
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        by <?php echo htmlspecialchars($art['PENULIS']); ?>
                    </span>
                    <?php if (!empty($art['TAG'])) { ?>
                    <span class="meta-item" style="background: #f0e3fc; color: #7c047c; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 500;">
                        <?php echo htmlspecialchars($art['TAG']); ?>
                    </span>
                    <?php } ?>
                </div>
                
                <h1 class="article-title"><?php echo htmlspecialchars($art['JUDUL']); ?></h1>
                
                <div class="article-body">
                    <?php 
                    $paragraphs = preg_split('/\r?\n\r?\n/', $art['INFORMASI']);
                    foreach ($paragraphs as $para) {
                        $para = trim($para);
                        if (!empty($para)) {
                            echo '<p>' . nl2br(htmlspecialchars($para)) . '</p>';
                        }
                    }
                    ?>
                </div>
            </article>

            <div style="margin-top: 30px;">
                <a href="?page=informasi" style="display: inline-flex; align-items: center; gap: 6px; color: #a805a8; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.gap='10px'" onmouseout="this.style.gap='6px'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Informasi
                </a>
            </div>
        </div>
        
        <aside class="detail-sidebar-right">
            <div class="sidebar-widget">
                <h3 class="widget-title">RECENT POSTS</h3>
                <div class="recent-posts-list">
                    <?php if (!empty($recent)) { ?>
                        <?php foreach ($recent as $rec) { ?>
                        <div class="recent-item">
                            <?php if (!empty($rec['GAMBAR'])) { ?>
                            <div class="recent-thumb" style="background-image: url('<?php echo $rec['GAMBAR']; ?>'); background-size: cover; background-position: center;"></div>
                            <?php } else { ?>
                            <div class="recent-thumb" style="background: #f0e3fc; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            </div>
                            <?php } ?>
                            <div class="recent-info">
                                <span class="recent-meta">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?php echo date("d M, Y", strtotime($rec['TANGGAL'])); ?>
                                </span>
                                <h4 class="recent-title"><a href="?page=detailinformasi&id=<?php echo $rec['ID']; ?>" style="text-decoration: none; color: inherit;"><?php echo htmlspecialchars(mb_strimwidth($rec['JUDUL'], 0, 50, '...')); ?></a></h4>
                            </div>
                        </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p style="color: #94a3b8; font-size: 13px;">Belum ada informasi.</p>
                    <?php } ?>
                </div>
            </div>

        </aside>

    </div>
</div>
