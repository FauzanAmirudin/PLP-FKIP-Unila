<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');

$art = isset($article) ? $article : null;
if (empty($art)) return;
?>

<style>
.detail-main-section .detail-container {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  max-width: 1200px;
  margin: 0 auto;
  align-items: flex-start;
}
.detail-main-section .detail-content-left {
  flex: 1 1 65%;
  min-width: 0 !important;
  max-width: 100%;
  word-wrap: break-word !important;
  word-break: break-word !important;
  overflow-wrap: anywhere !important;
}
.detail-main-section .detail-sidebar-right {
  flex: 0 0 30%;
  min-width: 280px;
}
.article-body {
  word-wrap: break-word !important;
  word-break: break-word !important;
  overflow-wrap: anywhere !important;
  color: #334155;
  font-size: 1.05rem;
  line-height: 1.8;
  width: 100%;
}
.article-body p {
  font-size: 1.05rem;
  line-height: 1.8;
  color: #334155;
  margin-bottom: 1.2em;
  word-break: break-word !important;
  overflow-wrap: anywhere !important;
}
.article-body h1, .article-body h2, .article-body h3, .article-body h4, .article-body h5, .article-body h6 {
  color: #1e293b;
  font-weight: 700;
  margin-top: 1.5em;
  margin-bottom: 0.6em;
  line-height: 1.3;
  word-break: break-word;
}
.article-body h1 { font-size: 1.8rem; }
.article-body h2 { font-size: 1.5rem; }
.article-body h3 { font-size: 1.3rem; }
.article-body ul, .article-body ol {
  margin-top: 0.5em;
  margin-bottom: 1.2em;
  padding-left: 28px;
}
.article-body ul { list-style-type: disc !important; }
.article-body ol { list-style-type: decimal !important; }
.article-body li { margin-bottom: 0.4em; line-height: 1.7; }
.article-body a {
  color: #a805a8;
  text-decoration: underline;
  word-break: break-all !important;
  overflow-wrap: anywhere !important;
}
.article-body a:hover { color: #7c047c; }
.article-body blockquote {
  border-left: 4px solid #a805a8;
  background: #fdf4ff;
  padding: 12px 20px;
  margin: 1.2em 0;
  font-style: italic;
  color: #475569;
  border-radius: 0 8px 8px 0;
}
.article-body img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 1.2em 0;
}
.article-body table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.2em 0;
}
.article-body th, .article-body td {
  border: 1px solid #e2e8f0;
  padding: 8px 12px;
}
</style>

<div class="detail-hero-section">
    <div class="hero-content">
        <h1 class="hero-title">DETAIL</h1>
    </div>
</div>

<div class="detail-main-section">
    <div class="detail-container">
        <div class="detail-content-left">
            
            <article class="full-article">
                <?php if (!empty($art['GAMBAR'])) { ?>
                <div class="article-hero-image article-hero-custom">
                    <img src="<?php echo $art['GAMBAR']; ?>" alt="<?php echo htmlspecialchars($art['JUDUL']); ?>" class="article-hero-img" />
                </div>
                <?php } ?>
                
                <div class="article-meta">
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <?php echo date("d M, Y", strtotime($art['TANGGAL'])); ?>
                    </span>
                    <span class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        oleh <?php echo htmlspecialchars($art['PENULIS']); ?>
                    </span>
                    <?php if (!empty($art['TAG'])) { ?>
                    <span class="meta-item meta-tag">
                        <?php echo htmlspecialchars($art['TAG']); ?>
                    </span>
                    <?php } ?>
                </div>
                
                <h1 class="article-title"><?php echo htmlspecialchars($art['JUDUL']); ?></h1>
                
                <div class="article-body">
                    <?php 
                    $content = htmlspecialchars_decode($art['INFORMASI']);
                    if ($content !== strip_tags($content)) {
                        $allowed_tags = '<p><br><strong><em><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><a><blockquote><span><div><style><img><table><thead><tbody><tr><th><td><figure>';
                        echo strip_tags($content, $allowed_tags);
                    } else {
                        $paragraphs = preg_split('/\r?\n\r?\n/', $content);
                        foreach ($paragraphs as $para) {
                            $para = trim($para);
                            if (!empty($para)) {
                                echo '<p>' . nl2br(htmlspecialchars($para)) . '</p>';
                            }
                        }
                    }
                    ?>
                </div>
            </article>

            <div class="back-link-wrapper">
                <a href="?page=informasi" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Informasi
                </a>
            </div>
        </div>
        
        <aside class="detail-sidebar-right">
            <div class="sidebar-widget">
                <h3 class="widget-title">POSTINGAN TERBARU</h3>
                <div class="recent-posts-list">
                    <?php if (!empty($recent)) { ?>
                        <?php foreach ($recent as $rec) { ?>
                        <div class="recent-item">
                            <?php if (!empty($rec['GAMBAR'])) { ?>
                            <div class="recent-thumb recent-thumb-bg" style="background-image: url('<?php echo $rec['GAMBAR']; ?>');"></div>
                            <?php } else { ?>
                            <div class="recent-thumb recent-thumb-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a805a8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            </div>
                            <?php } ?>
                            <div class="recent-info">
                                <span class="recent-meta">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?php echo date("d M, Y", strtotime($rec['TANGGAL'])); ?>
                                </span>
                                <h4 class="recent-title"><a href="?page=detailinformasi&id=<?php echo $rec['ID']; ?>" class="recent-link"><?php echo htmlspecialchars(mb_strimwidth($rec['JUDUL'], 0, 50, '...')); ?></a></h4>
                            </div>
                        </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="empty-info">Belum ada informasi.</p>
                    <?php } ?>
                </div>
            </div>

        </aside>

    </div>
</div>
