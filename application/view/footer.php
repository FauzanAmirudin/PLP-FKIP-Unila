<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
?>
	</section> <!-- close mainContent -->
</content> <!-- close content -->

<style type="text/css">
/* Bulletproof consistent styling for site-footer on all pages (public & dashboard) */
.site-footer {
    width: 100% !important;
    background-color: #B33791 !important; /* Beautiful purple matching navbar */
    color: #ffffff !important;
    font-family: 'Poppins', sans-serif !important;
    padding: 80px 5% 40px !important;
    box-sizing: border-box !important;
    position: relative !important;
    z-index: 10 !important;
    clear: both !important;
}

.site-footer * {
    box-sizing: border-box !important;
}

.site-footer .footer-grid {
    display: grid !important;
    grid-template-columns: 2fr 1.5fr 2fr !important;
    gap: 40px !important;
    max-width: 1200px !important;
    margin: 0 auto 60px !important;
    width: 100% !important;
}

.site-footer .footer-col {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
}

.site-footer .footer-col .footer-brand {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    margin-bottom: 24px !important;
}

.site-footer .footer-col .footer-brand img {
    height: 40px !important;
    width: 40px !important;
    object-fit: contain !important;
}

.site-footer .footer-col .footer-brand span {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.5px !important;
    color: #ffffff !important;
}

.site-footer .footer-col .footer-address {
    font-size: 0.85rem !important;
    line-height: 1.8 !important;
    color: rgba(255,255,255,0.8) !important;
    margin-bottom: 24px !important;
    max-width: 260px !important;
    text-align: left !important;
}

.site-footer .footer-col .footer-socials {
    display: flex !important;
    gap: 12px !important;
}

.site-footer .footer-col .footer-socials .social-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    background-color: #ffffff !important;
    color: #72307e !important;
    text-decoration: none !important;
    transition: transform 0.2s ease, background 0.2s ease !important;
}

.site-footer .footer-col .footer-socials .social-icon:hover {
    transform: translateY(-3px) !important;
    background-color: #FEC5F6 !important;
}

.site-footer .footer-col .footer-socials .social-icon svg {
    width: 16px !important;
    height: 16px !important;
    fill: currentColor !important;
}

.site-footer .footer-col .footer-heading {
    font-size: 0.95rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    margin: 0 0 24px !important;
    color: #ffffff !important;
}

.site-footer .footer-col .footer-links {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    text-align: left !important;
}

.site-footer .footer-col .footer-links li {
    margin-bottom: 12px !important;
}

.site-footer .footer-col .footer-links li a {
    color: rgba(255,255,255,0.8) !important;
    text-decoration: none !important;
    font-size: 0.85rem !important;
    transition: color 0.2s ease !important;
}

.site-footer .footer-col .footer-links li a:hover {
    color: #ffffff !important;
    text-decoration: underline !important;
}

.site-footer .footer-col .footer-text {
    font-size: 0.85rem !important;
    line-height: 1.6 !important;
    color: rgba(255,255,255,0.8) !important;
    text-align: left !important;
}

.site-footer .footer-bottom {
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding-top: 30px !important;
    border-top: 1px solid rgba(255,255,255,0.2) !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    font-size: 0.75rem !important;
    color: rgba(255,255,255,0.6) !important;
    width: 100% !important;
}

.site-footer .footer-bottom .footer-policies {
    display: flex !important;
    gap: 20px !important;
}

.site-footer .footer-bottom .footer-policies a {
    color: rgba(255,255,255,0.6) !important;
    text-decoration: none !important;
}

.site-footer .footer-bottom .footer-policies a:hover {
    color: #ffffff !important;
}

/* Responsive Rules */
@media screen and (max-width: 900px) {
    .site-footer .footer-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}

@media screen and (max-width: 600px) {
    .site-footer {
        padding: 60px 20px 30px !important;
    }
    .site-footer .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 40px !important;
    }
    .site-footer .footer-bottom {
        flex-direction: column !important;
        gap: 16px !important;
        text-align: center !important;
    }
}
</style>

<footer class="site-footer">
    <div class="footer-grid">
        
        <!-- Kolom 1: Brand & Alamat -->
        <div class="footer-col">
            <div class="footer-brand">
                <img src="assets/images/logo.png" alt="Logo Unila" />
                <span>PLP FKIP UNILA</span>
            </div>
            <div class="footer-address">
                Gedung Sekretariat PLT FKIP Unila,<br />
                Jl. Sumantri Brojonegoro No.1<br />
                Kampus Gedung Meneng,<br />
                Bandar Lampung 35145
            </div>
            <div class="footer-socials">
                <a href="https://www.facebook.com/eduspottv" class="social-icon" title="Facebook" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="https://www.instagram.com/official_fkipunila/" class="social-icon" title="Instagram" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
                <a href="https://www.youtube.com/@fkipunila" class="social-icon" title="YouTube" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                </a>
            </div>
        </div>
        
        <!-- Kolom 2: Links -->
        <div class="footer-col">
            <h4 class="footer-heading">TAUTAN</h4>
            <ul class="footer-links">
                <li><a href="?page=frontpage">BERANDA</a></li>
                <li><a href="?page=about">TENTANG</a></li>
                <li><a href="?page=informasi">INFORMASI</a></li>
                <li><a href="?page=gallery">GALERI</a></li>
                <li><a href="?page=contact">KONTAK</a></li>
            </ul>
        </div>
        
        <!-- Kolom 3: Newsletter & Contact -->
        <div class="footer-col">
            <h4 class="footer-heading">KONTAK</h4>
            <p class="footer-text">
                Telp/Fax: (0721) 704624<br />
                Email: pltunila@gmail.com
            </p>
        </div>
        
    </div>
    
    <!-- Bagian Bawah: Copyright & Nav tambahan -->
    <div class="footer-bottom">
        <div class="footer-copyright">
            Hak Cipta Dilindungi <?php echo date('Y'); ?>. Universitas Lampung
            | <a href="?page=versionhistory" style="color:inherit; text-decoration:none;">v2.4</a>
        </div>
        <div class="footer-policies">
            <span>Develope by Fauzan Amirudin</span>
        </div>
    </div>
</footer>

<!-- Include SweetAlert2 globally for modern alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
