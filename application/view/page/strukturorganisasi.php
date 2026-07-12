<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
?>
<div class="about-hero-section">
    <div class="hero-content">
        <h1 class="hero-title">STRUKTUR ORGANISASI</h1>
    </div>
</div>

<div class="about-org-section">
    <div class="section-header">
        <span class="badge">STRUKTUR ORGANISASI</span>
        <h2 class="title">STRUKTUR ORGANISASI</h2>
        <div class="dots-separator">
            <span></span><span></span><span></span><span></span>
        </div>
    </div>

    <div class="modern-org-chart">
        <div class="org-card primary">
            <div class="org-avatar">
                <img src="assets/images/albet.jpg" alt="Penanggung Jawab" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="org-details">
                <h4 class="org-name">Dr. Albet Maydiantoro, S.Pd, M.Pd.</h4>
                <span class="org-role">Penanggung Jawab</span>
            </div>
        </div>

        <div class="org-connector"></div>

        <style>
        .org-pengarah-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            width: 100%;
            padding: 20px 0;
            position: relative;
        }
        .org-pengarah-group .org-card {
            flex: 1 1 calc(25% - 20px);
            min-width: 200px;
            max-width: 250px;
            margin: 0;
        }
        @media screen and (max-width: 768px) {
            .org-pengarah-group {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                padding: 15px 10px;
                width: 100%;
            }
            .org-staff-group {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px !important;
                padding: 15px 10px;
                width: 100%;
            }
            .modern-org-chart .org-card {
                width: 100% !important;
                max-width: 150px !important;
                min-width: 140px !important;
                padding: 15px 10px !important;
                margin: 0 auto !important;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                height: 100%;
            }
            .modern-org-chart .org-card .org-avatar,
            .modern-org-chart .org-card .org-avatar-small {
                width: 45px !important;
                height: 45px !important;
                margin: -25px auto 10px !important;
                padding: 3px !important;
            }
            .modern-org-chart .org-card .org-details .org-name {
                font-size: 0.75rem !important;
                margin-bottom: 5px !important;
                line-height: 1.3;
            }
            .modern-org-chart .org-card .org-details .org-role {
                font-size: 0.65rem !important;
                padding: 4px 8px !important;
                margin-top: auto;
            }
            /* Connectors adjustment */
            .modern-org-chart .org-connector {
                height: 25px !important;
            }
            .modern-org-chart .org-staff-group .org-card.staff::before,
            .modern-org-chart .org-staff-group .org-card.staff::after {
                display: none !important; /* Hide complex pseudo connectors for grid layout */
            }
        }
        </style>

        <div class="org-pengarah-group">
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="assets/images/riswandi.jpg" alt="Tim Pengarah" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Dr. Riswandi, M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="assets/images/bambang.jpg" alt="Tim Pengarah" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Bambang Riandi, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="assets/images/hermi.jpg" alt="Tim Pengarah" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Hermi Yanzi, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="assets/images/didi.jpeg" alt="Tim Pengarah" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Didi Sudarmansyah, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
        </div>

        <div class="org-connector"></div>

        <div class="org-card tertiary">
            <div class="org-avatar">
                <img src="assets/images/maskun.png" alt="Ketua" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="org-details">
                <h4 class="org-name">Drs. Maskun, M.H</h4>
                <span class="org-role" style="background: rgba(219, 141, 208, 0.15); color: #B33791;">Ketua</span>
            </div>
        </div>

        <div class="org-connector"></div>

        <div class="org-card quaternary">
            <div class="org-avatar">
                <img src="assets/images/fitriyadi.png" alt="Sekretaris" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="org-details">
                <h4 class="org-name">Dr. Fitriadi, S.Pd,. M.Pd.</h4>
                <span class="org-role" style="background: rgba(254, 197, 246, 0.3); color: #B33791;">Sekretaris</span>
            </div>
        </div>

        <div class="org-connector"></div>

        <div class="org-staff-group">
            <div class="org-card staff">
                <div class="org-avatar-small">
                    <img src="assets/images/siti.png" alt="Siti Alfiyah" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name">Siti Alfiyah, S.Pd., M.Pd.</h4>
                    <span class="org-role staff-role">Staff Administrasi</span>
                </div>
            </div>
            
            <div class="org-card staff">
                <div class="org-avatar-small">
                    <img src="assets/images/iskandar.png" alt="Iskandar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="org-details">
                    <h4 class="org-name">Iskandar, S.Pd.</h4>
                    <span class="org-role staff-role">Staff Administrasi</span>
                </div>
            </div>
        </div>
    </div>
</div>
