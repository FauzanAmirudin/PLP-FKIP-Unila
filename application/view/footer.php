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
    color: #000000 !important;
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
                <img src="assets/images/fkip.png" alt="Logo FKIP" />
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
                    <svg viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                </a>
                <a href="https://www.instagram.com/official_fkipunila/" class="social-icon" title="Instagram" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                </a>
                <a href="https://www.youtube.com/@fkipunila" class="social-icon" title="YouTube" target="_blank" rel="noopener noreferrer">
                    <svg viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
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
                Email: fkip@unila.ac.id
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
            <span>Developed by Fauzan Amirudin Basith</span>
        </div>
    </div>
</footer>

<!-- Include SweetAlert2 globally for modern alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
