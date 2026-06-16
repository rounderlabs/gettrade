<template>
    <section class="section-b-space profile-card-section pt-3 pb-4">
        <div class="custom-container">
            <div class="elite-profile-wrapper">
                <!-- Ambient Background Glow -->
                <div class="ambient-glow"></div>

                <div class="elite-profile-card">
                    <!-- Cyberpunk Corner Accents -->
                    <div class="cyber-corner top-left"></div>
                    <div class="cyber-corner top-right"></div>
                    <div class="cyber-corner bottom-left"></div>
                    <div class="cyber-corner bottom-right"></div>

                    <!-- Premium Scanline Effect -->
                    <div class="scanline"></div>

                    <!-- Profile Header -->
                    <div class="d-flex align-items-center gap-3 gap-sm-4 mb-4 position-relative z-1 flex-wrap flex-sm-nowrap">
                        <!-- Avatar with Animated Energy Ring -->
                        <div class="elite-avatar-container">
                            <div class="avatar-energy-ring"></div>
                            <div class="elite-avatar-inner">
                                <span class="avatar-text">{{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}</span>
                            </div>
                            <span class="elite-status-dot pulse-animation"></span>
                        </div>

                        <!-- User Info & Badges -->
                        <div class="elite-header-info">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h2 class="elite-name text-white mb-0">{{ user.name }}</h2>
                                <div class="elite-badge-verified">
                                    <vue-feather type="shield" size="12" class="me-1"></vue-feather>
                                    VERIFIED
                                </div>
                            </div>
                            <div class="elite-id-tag mt-1">
                                <span class="tag-label">SYS_ID //</span>
                                <span class="tag-value">{{ user.username }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Data Readout Grid -->
                    <div class="elite-details-grid position-relative z-1">
                        <div class="elite-data-panel">
                            <div class="panel-icon-box glow-cyan">
                                <vue-feather type="mail" size="16" />
                            </div>
                            <div class="panel-content">
                                <div class="panel-label">SECURE_COMMS</div>
                                <div class="panel-value truncate text-white">{{ user.email }}</div>
                            </div>
                        </div>

                        <div class="elite-data-panel">
                            <div class="panel-icon-box glow-emerald">
                                <vue-feather type="smartphone" size="16" />
                            </div>
                            <div class="panel-content">
                                <div class="panel-label">MOBILE_LINK</div>
                                <div class="panel-value text-white">{{ user.mobile || 'UNLINKED' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "UserLoginDetailsComponent",
    components: {Link, VueFeather},
    props: ['user'],
}
</script>

<style scoped>
.elite-profile-wrapper {
    position: relative;
    width: 100%;
}

/* Ambient Background Glow */
.ambient-glow {
    position: absolute;
    top: -30%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, rgba(16, 185, 129, 0.05) 50%, transparent 70%);
    filter: blur(40px);
    border-radius: 50%;
    z-index: 0;
    pointer-events: none;
    animation: float-glow 8s ease-in-out infinite alternate;
}

@keyframes float-glow {
    0% { transform: translate(0, 0) scale(1); opacity: 0.8; }
    100% { transform: translate(-30px, 20px) scale(1.1); opacity: 1; }
}

/* Premium Card Container */
.elite-profile-card {
    background: linear-gradient(145deg, rgba(15, 23, 42, 0.8) 0%, rgba(9, 14, 23, 0.9) 100%);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    background-image: 
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 20px 20px;
    background-position: center center;
}

/* Cyberpunk Corners */
.cyber-corner {
    position: absolute;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    z-index: 2;
}
.cyber-corner.top-left { top: 12px; left: 12px; border-top-color: #0ea5e9; border-left-color: #0ea5e9; border-radius: 4px 0 0 0; }
.cyber-corner.top-right { top: 12px; right: 12px; border-top-color: #0ea5e9; border-right-color: #0ea5e9; border-radius: 0 4px 0 0; }
.cyber-corner.bottom-left { bottom: 12px; left: 12px; border-bottom-color: #0ea5e9; border-left-color: #0ea5e9; border-radius: 0 0 0 4px; }
.cyber-corner.bottom-right { bottom: 12px; right: 12px; border-bottom-color: #0ea5e9; border-right-color: #0ea5e9; border-radius: 0 0 4px 0; }

/* Moving Scanline */
.scanline {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 0%, rgba(14, 165, 233, 0.05) 50%, transparent 100%);
    opacity: 0.5;
    z-index: 0;
    pointer-events: none;
    animation: scan 4s linear infinite;
}

@keyframes scan {
    0% { transform: translateY(-100%); }
    100% { transform: translateY(100%); }
}

/* Avatar & Energy Ring */
.elite-avatar-container {
    position: relative;
    width: 76px;
    height: 76px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.avatar-energy-ring {
    position: absolute;
    top: -2px; left: -2px; right: -2px; bottom: -2px;
    border-radius: 50%;
    background: conic-gradient(from 0deg, #0ea5e9, #10b981, #8b5cf6, #0ea5e9);
    animation: rotate-ring 4s linear infinite;
    mask: radial-gradient(transparent 65%, black 66%);
    -webkit-mask: radial-gradient(transparent 65%, black 66%);
}

@keyframes rotate-ring {
    100% { transform: rotate(360deg); }
}

.elite-avatar-inner {
    width: 68px;
    height: 68px;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-shadow: inset 0 4px 10px rgba(0,0,0,0.5);
}

.avatar-text {
    font-size: 1.8rem;
    font-weight: 800;
    background: linear-gradient(to right, #fff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 2px 10px rgba(255,255,255,0.1);
}

.elite-status-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background-color: #10b981;
    border-radius: 50%;
    border: 3px solid #090e17;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.8);
}

.pulse-animation {
    animation: status-pulse 2s infinite;
}

@keyframes status-pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Header Info */
.elite-name {
    font-size: 1.5rem;
    font-weight: 800;
    letter-spacing: -0.5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.elite-badge-verified {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
}

.elite-id-tag {
    display: inline-block;
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 4px 12px;
    border-radius: 6px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.75rem;
}

.tag-label { color: #64748b; margin-right: 6px; }
.tag-value { color: #38bdf8; font-weight: 700; letter-spacing: 0.5px; }

/* Data Grid */
.elite-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.elite-data-panel {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(15, 23, 42, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 14px;
    padding: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.elite-data-panel::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.elite-data-panel:hover {
    background: rgba(30, 41, 59, 0.6);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -10px rgba(0,0,0,0.5);
}

.elite-data-panel:hover::after {
    opacity: 1;
}

.panel-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.3);
}

.bg-blue-glow {
    background: rgba(56, 189, 248, 0.12);
    border: 1px solid rgba(56, 189, 248, 0.2);
    color: #38bdf8;
}

.bg-emerald-glow {
    background: rgba(16, 185, 129, 0.12);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.panel-content {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.panel-label {
    font-size: 0.68rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.panel-value {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.2px;
}

.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
