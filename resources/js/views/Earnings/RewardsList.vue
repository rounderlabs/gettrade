<template>
    <section class="rewards-dashboard-section">
        <div class="custom-container">
            <!-- Glassmorphic Header Card -->
            <!-- Glassmorphic Header Card with 14-Rank Achievement Matrix -->
            <div class="glass-card main-stats-card futuristic-rank-banner mb-4 mt-3">
                <!-- Rank Scanner Glow overlay -->
                <div class="rank-scanner-line"></div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="badge-icon-container rank-hex-icon animate-gold-glow">
                            <VueFeather type="award" class="gold-glow-icon rank-icon-pulse" size="28" />
                        </div>
                        <div>
                            <h5 class="rank-label small text-uppercase tracking-wider">Current Rank</h5>
                            <h2 class="rank-title text-white">
                                Rank {{ current_rank }} 
                                <span v-if="rewards[current_rank - 1]" class="rank-name-badge ms-2">
                                    {{ rewards[current_rank - 1].rank_name }}
                                </span>
                            </h2>
                        </div>
                    </div>
                    <div class="text-end text-sm-start">
                        <h5 class="rank-label small text-uppercase tracking-wider">Matching Leg Business</h5>
                        <h2 class="business-value">{{ currencySymbol }}{{ matching_leg_business_display }}</h2>
                    </div>
                </div>

                <!-- Next Milestone Progress -->
                <div v-if="nextReward" class="next-milestone-progress mt-4 position-relative">
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span class="tech-subtext">Next Sector: <strong>{{ nextReward.rank_name }}</strong></span>
                        <span class="text-success-neon fw-bold">{{ progressPercentage }}% ({{ currencySymbol }}{{ remainingBusinessDisplay }} remaining)</span>
                    </div>
                    <div class="custom-progress-bar">
                        <div class="progress-fill" :style="{ width: progressPercentage + '%' }"></div>
                    </div>
                </div>
                <div v-else class="next-milestone-progress mt-4 text-center text-success position-relative">
                    <VueFeather type="check-circle" size="20" class="me-2 vm text-success-neon" />
                    <span class="vm fw-bold text-success-neon">All milestones unlocked! You have achieved the highest rank.</span>
                </div>

                <!-- Futuristic 14-segment Rank Achievement Grid -->
                <div class="rank-power-bar-grid mt-4 position-relative">
                    <div 
                        v-for="(r, idx) in rewards" 
                        :key="r.id" 
                        class="rank-bar-segment" 
                        :class="{ 
                            active: r.is_unlocked, 
                            current: current_rank === idx + 1,
                            next: !r.is_unlocked && (idx === 0 || rewards[idx-1].is_unlocked)
                        }"
                        :title="r.rank_name"
                    >
                        <div class="rank-segment-cyber-lines"></div>
                        <span class="rank-bar-text">{{ shortNames[idx] }}</span>
                    </div>
                </div>

                <!-- Telemetry Readouts for Ranks -->
                <div class="row g-2 mt-4 rank-telemetry-row text-uppercase position-relative">
                    <div class="col-6 col-sm-3">
                        <div class="telemetry-card">
                            <span class="telemetry-label">Milestone Depth</span>
                            <span class="telemetry-value text-warning-neon">{{ unlockedCount }} / 14 unlocked</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="telemetry-card">
                            <span class="telemetry-label">Sync Status</span>
                            <span class="telemetry-value text-info-neon">CONNECTED</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="telemetry-card">
                            <span class="telemetry-label">Next Target Req</span>
                            <span class="telemetry-value text-success-neon">{{ nextReward ? nextReward.matching_leg_business_display : 'MAX_DEPTH' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="telemetry-card">
                            <span class="telemetry-label">Rate multiplier</span>
                            <span class="telemetry-value text-primary-neon">100% SCALE</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="title d-flex align-items-center justify-content-between mb-3">
                <h2>Ranks &amp; Milestones</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 small">
                    {{ unlockedCount }} / {{ rewards.length }} Unlocked
                </span>
            </div>

            <!-- Rewards Milestones Grid -->
            <div class="row g-4">
                <div v-for="reward in rewards" :key="reward.id" class="col-12 col-md-6 col-lg-4">
                    <div class="milestone-card" :class="{ 'unlocked': reward.is_unlocked, 'locked': !reward.is_unlocked }">
                        <!-- Lock Overlay -->
                        <div v-if="!reward.is_unlocked" class="lock-overlay d-flex flex-column align-items-center justify-content-center">
                            <div class="lock-circle mb-2">
                                <VueFeather type="lock" size="24" />
                            </div>
                            <span class="text-uppercase small tracking-widest font-semibold text-secondary-light">Locked</span>
                        </div>

                        <!-- Card Header -->
                        <div class="card-top d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="reward-rank-name">{{ reward.rank_name }}</h3>
                                <p class="text-secondary small mt-1">Req: {{ currencySymbol }}{{ reward.matching_leg_business_display }}</p>
                            </div>
                            <div class="badge-status-container">
                                <span v-if="reward.is_unlocked" class="badge-status unlocked-badge">
                                    <VueFeather type="check" size="14" class="me-1" /> Unlocked
                                </span>
                                <span v-else class="badge-status locked-badge">
                                    <VueFeather type="lock" size="12" class="me-1" /> Locked
                                </span>
                            </div>
                        </div>

                        <hr class="card-divider" />

                        <!-- Reward Perks -->
                        <div class="perks-list py-2">
                            <!-- Direct Cash Reward -->
                            <div class="perk-item d-flex align-items-center gap-3 mb-3">
                                <div class="perk-icon-wrap cash-icon">
                                    <VueFeather type="gift" size="18" />
                                </div>
                                <div>
                                    <span class="text-secondary small d-block">One-time Reward</span>
                                    <span class="perk-value text-white">{{ currencySymbol }}{{ reward.reward_amount_display }}</span>
                                </div>
                            </div>

                            <!-- Monthly Salary Reward -->
                            <div class="perk-item d-flex align-items-center gap-3">
                                <div class="perk-icon-wrap salary-icon">
                                    <VueFeather type="calendar" size="18" />
                                </div>
                                <div>
                                    <span class="text-secondary small d-block">Monthly Salary Perks</span>
                                    <span class="perk-value text-white" v-if="parseFloat(reward.salary_amount) > 0">
                                        {{ currencySymbol }}{{ reward.salary_amount_display }} / mo <small class="text-secondary">({{ reward.salary_tenure }} mos)</small>
                                    </span>
                                    <span class="perk-value text-secondary-light" v-else>No salary package</span>
                                </div>
                            </div>
                        </div>

                        <!-- Text / Description -->
                        <div class="reward-description mt-3 pt-2 border-top border-secondary-light" v-if="reward.reward_text">
                            <p class="text-secondary small italic mb-0">"{{ reward.reward_text }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {computed} from "vue";
import {Link, usePage} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "RewardsList",
    components: {VueFeather, Link},
    layout: UserLayout,
    props: {
        matching_leg_business: String,
        matching_leg_business_display: String,
        current_rank: Number,
        rewards: Array
    },
    setup(props) {
        const page = usePage()

        const shortNames = ['ASC', 'STA', 'BRN', 'SLV', 'GLD', 'PLT', 'RBY', 'EMR', 'SPH', 'DMD', 'DBD', 'TPD', 'CRD', 'GBA'];

        const currencySymbol = computed(() => {
            return page.props.currency?.symbol ?? "₹"
        })

        // Filter the next locked milestone
        const nextReward = computed(() => {
            return props.rewards.find(r => !r.is_unlocked && parseFloat(r.matching_leg_business) > parseFloat(props.matching_leg_business))
                || props.rewards.find(r => !r.is_unlocked);
        })

        // Progress percentage computation
        const progressPercentage = computed(() => {
            if (!nextReward.value) return 100;
            const target = parseFloat(nextReward.value.matching_leg_business);
            const current = parseFloat(props.matching_leg_business);
            if (target <= 0) return 0;
            const percentage = Math.min(100, Math.floor((current / target) * 100));
            return isNaN(percentage) ? 0 : percentage;
        })

        // Remaining Leg Business calculation
        const remainingBusinessDisplay = computed(() => {
            if (!nextReward.value) return "0.00";
            const target = parseFloat(nextReward.value.matching_leg_business);
            const current = parseFloat(props.matching_leg_business);
            const remaining = Math.max(0, target - current);
            
            // Format to currency style
            return remaining.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        })

        const unlockedCount = computed(() => {
            return props.rewards.filter(r => r.is_unlocked).length;
        })

        return {
            currencySymbol,
            nextReward,
            progressPercentage,
            remainingBusinessDisplay,
            unlockedCount,
            shortNames
        }
    }
}
</script>

<style scoped>
.rewards-dashboard-section {
    padding: 20px 0 80px 0;
}

/* Glassmorphism Panel styles */
.glass-card {
    background: rgba(30, 41, 59, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
}

.badge-icon-container {
    background: radial-gradient(circle, rgba(234, 179, 8, 0.2) 0%, rgba(234, 179, 8, 0.02) 70%);
    border: 1px solid rgba(234, 179, 8, 0.3);
    padding: 12px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gold-glow-icon {
    color: #eab308;
    filter: drop-shadow(0 0 8px rgba(234, 179, 8, 0.6));
}

.rank-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 0;
    background: linear-gradient(135deg, #fff 30%, #94a3b8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.business-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #38bdf8;
    margin-bottom: 0;
    filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.3));
}

/* Custom Progress Bar */
.custom-progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(15, 23, 42, 0.6);
    border-radius: 99px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #38bdf8 0%, #3b82f6 100%);
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.8);
    border-radius: 99px;
    transition: width 0.8s ease-out;
}

/* Milestone Cards */
.milestone-card {
    position: relative;
    background: rgba(30, 41, 59, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.milestone-card:hover {
    transform: translateY(-4px);
}

.milestone-card.unlocked {
    border-color: rgba(34, 197, 94, 0.3);
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.5) 0%, rgba(20, 83, 45, 0.1) 100%);
    box-shadow: 0 10px 30px rgba(34, 197, 94, 0.08);
}

.milestone-card.locked {
    filter: grayscale(30%);
}

/* Locked Card Overlay */
.lock-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(3px);
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.milestone-card.locked:hover .lock-overlay {
    opacity: 1;
}

.lock-circle {
    background: rgba(30, 41, 59, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
}

.text-secondary-light {
    color: #cbd5e1;
}

.reward-rank-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0;
}

.card-divider {
    border-color: rgba(255, 255, 255, 0.06);
    margin: 15px 0;
}

/* Badge status */
.badge-status {
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
}

.unlocked-badge {
    background: rgba(34, 197, 94, 0.15);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.locked-badge {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Perk Items */
.perk-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cash-icon {
    background: rgba(56, 189, 248, 0.1);
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.2);
}

.salary-icon {
    background: rgba(168, 85, 247, 0.1);
    color: #a855f7;
    border: 1px solid rgba(168, 85, 247, 0.2);
}

.perk-value {
    font-weight: 600;
    font-size: 0.95rem;
}

.italic {
    font-style: italic;
}

/* ==========================================================================
   🌟 DEDICATED CURRENT REWARD RANK CARD STYLING (FUTURISTIC & ULTRA PRO)
   ========================================================================== */
.futuristic-rank-banner {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(15, 23, 42, 0.75) 100%) !important;
    border: 1px solid rgba(245, 158, 11, 0.25) !important;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5), 0 0 15px rgba(245, 158, 11, 0.08) !important;
    position: relative;
    overflow: hidden;
    border-radius: 20px !important;
    padding: 24px !important;
}

.futuristic-rank-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: radial-gradient(rgba(245, 158, 11, 0.08) 1px, transparent 1px);
    background-size: 16px 16px;
    opacity: 0.6;
    pointer-events: none;
}

.rank-scanner-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.4), transparent);
    animation: rank-scan 4s linear infinite;
    pointer-events: none;
}

@keyframes rank-scan {
    0% {
        top: 0%;
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        top: 100%;
        opacity: 0;
    }
}

.rank-hex-icon {
    width: 44px;
    height: 44px;
    background: rgba(245, 158, 11, 0.15) !important;
    border: 1px solid rgba(245, 158, 11, 0.4) !important;
    border-radius: 10px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(245, 158, 11, 0.15);
}

.rank-icon-pulse {
    animation: rank-icon-pulse 2s infinite ease-in-out;
}

@keyframes rank-icon-pulse {
    0% { transform: scale(1); filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.5)); }
    50% { transform: scale(1.1); filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.8)); }
    100% { transform: scale(1); filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.5)); }
}

.rank-status-pulse {
    width: 8px;
    height: 8px;
    background-color: #f59e0b;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
    animation: rank-pulse 2s infinite;
}

@keyframes rank-pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
    }
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

.rank-label {
    color: #ca8a04;
    font-weight: 700;
    font-size: 0.72rem;
}

.rank-title {
    font-size: 1.25rem;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.rank-name-badge {
    background: rgba(245, 158, 11, 0.15) !important;
    border: 1px solid rgba(245, 158, 11, 0.3) !important;
    color: #fbbf24 !important;
    font-size: 0.8rem;
    padding: 2px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    font-family: monospace;
}

.rank-badge {
    background: rgba(245, 158, 11, 0.15) !important;
    border: 1px solid rgba(245, 158, 11, 0.3) !important;
    color: #fbbf24 !important;
    font-family: monospace;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.rank-power-bar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-top: 15px;
}

@media (max-width: 768px) {
    .rank-power-bar-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
    }
}

.rank-bar-segment {
    height: 38px;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    transform: skewX(-10deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.rank-bar-segment:hover {
    border-color: rgba(255, 255, 255, 0.25);
    background: rgba(30, 41, 59, 0.8);
    transform: skewX(-10deg) translateY(-2px);
}

.rank-segment-cyber-lines {
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: rgba(255, 255, 255, 0.03);
}

.rank-bar-text {
    font-family: monospace;
    font-size: 0.75rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.35);
    transform: skewX(10deg);
    transition: all 0.3s ease;
}

.rank-bar-segment.active {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.05) 100%);
    border: 1px solid rgba(245, 158, 11, 0.5);
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.15), inset 0 0 5px rgba(245, 158, 11, 0.1);
}

.rank-bar-segment.active:hover {
    border-color: rgba(245, 158, 11, 0.8);
    box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
}

.rank-bar-segment.active .rank-bar-text {
    color: #fbbf24;
    text-shadow: 0 0 6px rgba(245, 158, 11, 0.6);
}

.rank-bar-segment.current {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.45) 0%, rgba(217, 119, 6, 0.2) 100%) !important;
    border: 1px dashed #fbbf24 !important;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.5), inset 0 0 8px rgba(245, 158, 11, 0.3) !important;
    animation: rank-segment-glow 2s infinite alternate ease-in-out;
}

@keyframes rank-segment-glow {
    0% {
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
    }
    100% {
        border-color: #fbbf24;
        box-shadow: 0 0 22px rgba(245, 158, 11, 0.6), inset 0 0 10px rgba(245, 158, 11, 0.4);
    }
}

.rank-bar-segment.current .rank-bar-text {
    color: #ffffff !important;
    text-shadow: 0 0 8px #fbbf24, 0 0 15px #fbbf24 !important;
    font-weight: 900;
}

.rank-bar-segment.next {
    border: 1px dashed rgba(245, 158, 11, 0.35);
    background: rgba(245, 158, 11, 0.03);
}

.rank-bar-segment.next .rank-bar-text {
    color: rgba(250, 204, 21, 0.5);
}

.rank-telemetry-row {
    border-top: 1px dashed rgba(255, 255, 255, 0.08);
    padding-top: 15px;
}

.telemetry-card {
    background: rgba(10, 15, 30, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: all 0.3s ease;
}

.telemetry-card:hover {
    background: rgba(15, 23, 42, 0.7);
    border-color: rgba(255, 255, 255, 0.1);
}

.telemetry-label {
    font-family: monospace;
    font-size: 0.62rem;
    color: #94a3b8;
    letter-spacing: 0.08em;
}

.telemetry-value {
    font-family: monospace;
    font-size: 0.82rem;
    font-weight: 800;
}

.text-success-neon {
    color: #10b981 !important;
    text-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
}

.text-info-neon {
    color: #06b6d4 !important;
    text-shadow: 0 0 8px rgba(6, 182, 212, 0.5);
}

.text-warning-neon {
    color: #f59e0b !important;
    text-shadow: 0 0 8px rgba(245, 158, 11, 0.5);
}

.text-primary-neon {
    color: #3b82f6 !important;
    text-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
}

.tech-subtext {
    font-family: monospace;
    font-size: 0.65rem;
    color: #64748b;
    letter-spacing: 0.05em;
}
</style>
