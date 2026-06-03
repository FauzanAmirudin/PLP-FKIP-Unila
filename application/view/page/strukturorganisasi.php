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
                <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23B33791'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Penanggung Jawab">
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
                gap: 5px;
                padding: 10px 0;
                flex-wrap: nowrap;
            }
            .org-pengarah-group .org-card {
                flex: 1 1 0;
                min-width: 0;
                padding: 10px 5px !important;
            }
            .org-staff-group {
                gap: 10px !important;
                flex-wrap: nowrap;
            }
            .org-staff-group .org-card {
                padding: 10px 5px !important;
            }
            .modern-org-chart .org-card {
                max-width: none;
                width: auto;
                padding: 10px 5px;
            }
            .modern-org-chart .org-card .org-avatar {
                width: 40px !important;
                height: 40px !important;
                margin: -25px auto 10px !important;
                padding: 2px !important;
            }
            .modern-org-chart .org-card .org-avatar-small {
                width: 35px !important;
                height: 35px !important;
                margin: -20px auto 8px !important;
                padding: 2px !important;
            }
            .modern-org-chart .org-card .org-details .org-name {
                font-size: 0.65rem !important;
                margin-bottom: 4px !important;
            }
            .modern-org-chart .org-card .org-details .org-role {
                font-size: 0.55rem !important;
                padding: 3px 6px !important;
            }
            /* Connectors adjustment */
            .modern-org-chart .org-connector {
                height: 20px !important;
            }
            .modern-org-chart .org-staff-group .org-card.staff::before {
                top: -15px !important;
                height: 15px !important;
            }
            .modern-org-chart .org-staff-group .org-card.staff::after {
                top: -15px !important;
            }
        }
        </style>

        <div class="org-pengarah-group">
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23C562AF'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Tim Pengarah">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Dr. Riswandi, M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23C562AF'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Tim Pengarah">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Bambang Riandi, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23C562AF'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Tim Pengarah">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Hermi Yanzi, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
            <div class="org-card secondary">
                <div class="org-avatar-small">
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23C562AF'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Tim Pengarah">
                </div>
                <div class="org-details">
                    <h4 class="org-name" style="font-size: 1rem;">Didi Sudarmasyah, S.Pd., M.Pd.</h4>
                    <span class="org-role" style="background: rgba(197, 98, 175, 0.1); color: #C562AF;">Tim Pengarah</span>
                </div>
            </div>
        </div>

        <div class="org-connector"></div>

        <div class="org-card tertiary">
            <div class="org-avatar">
                <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23DB8DD0'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Ketua">
            </div>
            <div class="org-details">
                <h4 class="org-name">Drs. Maskun, M.H</h4>
                <span class="org-role" style="background: rgba(219, 141, 208, 0.15); color: #B33791;">Ketua</span>
            </div>
        </div>

        <div class="org-connector"></div>

        <div class="org-card quaternary">
            <div class="org-avatar">
                <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23B33791'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Sekretaris">
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
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Staff">
                </div>
                <div class="org-details">
                    <h4 class="org-name">Siti Alfiyah, S.Pd., M.Pd.</h4>
                    <span class="org-role staff-role">Staff Administrasi</span>
                </div>
            </div>
            
            <div class="org-card staff">
                <div class="org-avatar-small">
                    <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Staff">
                </div>
                <div class="org-details">
                    <h4 class="org-name">Iskandar, S.Pd.</h4>
                    <span class="org-role staff-role">Staff Administrasi</span>
                </div>
            </div>
        </div>
    </div>
</div>
