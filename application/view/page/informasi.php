<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
?>
<div class="info-hero-section">
    <div class="hero-content">
        <h1 class="hero-title">INFORMASI</h1>
    </div>
</div>

<div class="info-main-section">
    <div class="info-container">
        <div class="info-content-left">
            
            <?php if (!empty($articles)) { ?>
                <?php foreach ($articles as $article) { ?>
                <article class="post-card">
                    <div class="post-meta">
                        <span class="meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <?php echo date("d M, Y", strtotime($article['TANGGAL'])); ?>
                        </span>
                        <span class="meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            by <?php echo htmlspecialchars($article['PENULIS']); ?>
                        </span>
                        <?php if (!empty($article['TAG'])) { ?>
                        <span class="meta-item" style="background: #f0e3fc; color: #7c047c; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 500;">
                            <?php echo htmlspecialchars($article['TAG']); ?>
                        </span>
                        <?php } ?>
                    </div>
                    
                    <h2 class="post-title"><?php echo htmlspecialchars($article['JUDUL']); ?></h2>
                    
                    <p class="post-excerpt">
                        <?php echo htmlspecialchars(mb_strimwidth($article['INFORMASI'], 0, 200, '...')); ?>
                    </p>
                    
                    <a href="?page=detailinformasi&id=<?php echo $article['ID']; ?>" class="post-read-more">READ DETAILS &raquo;</a>
                </article>
                <?php } ?>

                <?php if ($total_pages > 1) { ?>
                <div class="info-pagination">
                    <?php if ($current_page > 1) { ?>
                        <a href="?page=informasi&pg=<?php echo $current_page - 1; ?>" class="page-link">&larr;</a>
                    <?php } ?>
                    <?php for ($p = 1; $p <= $total_pages; $p++) { ?>
                        <a href="?page=informasi&pg=<?php echo $p; ?>" class="page-link <?php echo ($p == $current_page) ? 'active' : ''; ?>"><?php echo $p; ?></a>
                    <?php } ?>
                    <?php if ($current_page < $total_pages) { ?>
                        <a href="?page=informasi&pg=<?php echo $current_page + 1; ?>" class="page-link next">&rarr;</a>
                    <?php } ?>
                </div>
                <?php } ?>

            <?php } else { ?>
                <!-- Empty State -->
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="width: 80px; height: 80px; background: #f0e3fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <h3 style="color: #475569; font-size: 18px; margin: 0 0 8px;">Belum Ada Informasi</h3>
                    <p style="color: #94a3b8; font-size: 14px; margin: 0;">Informasi terbaru akan ditampilkan di sini.</p>
                </div>
            <?php } ?>
            
        </div>
        
        <aside class="info-sidebar-right">
            
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
