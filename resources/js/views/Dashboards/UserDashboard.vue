<template>
    <div class="mobile-dashboard pb-5">
        <!-- 1. Profile Details Card -->
        <UserLoginDetailsComponent :user="user"></UserLoginDetailsComponent>
        <!-- 3. Instant Operations Grid -->
        <InstantOperationComponent></InstantOperationComponent>

        <!-- 4. Referral & QR Code Card -->
        <ReferralLinkComponent :ref_qr="ref_qr" :user="user"></ReferralLinkComponent>

        <!-- 🚀 7-Day Referral Booster Widget -->
        <section class="section-b-space booster-section py-2" v-if="booster">
            <div class="custom-container">
                <div class="title mb-3 d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0">Referral Booster</h2>
                </div>
                <div class="glass-booster-card futuristic-booster-banner" :class="{'achieved': booster?.is_achieved, 'expired': isBoosterExpired && !booster?.is_achieved}">
                    <!-- Booster Scanner Glow overlay -->
                    <div class="booster-scanner-line"></div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative z-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="booster-icon-container tech-hex-icon" :class="booster?.is_achieved ? 'glow-success' : 'glow-warning'">
                                <vue-feather type="zap" size="24" :class="booster?.is_achieved ? 'text-success-neon' : 'text-warning-neon'" class="booster-icon-pulse"></vue-feather>
                            </div>
                            <div>
                                <h3 class="booster-title text-white mb-0">7-Day Booster</h3>
                                <p class="text-secondary small mb-0 mt-1" v-if="booster?.is_achieved">Maximum Power Unlocked!</p>
                                <p class="text-secondary small mb-0 mt-1" v-else-if="isBoosterExpired">Booster Time Expired</p>
                                <p class="text-secondary small mb-0 mt-1" v-else>Add 2 direct referrals to activate</p>
                            </div>
                        </div>

                        <!-- Status & Timer with Animated Graphic -->
                        <div class="booster-status-area d-flex align-items-center justify-content-end gap-3 gap-sm-4">
                            <div class="text-end">
                                <div v-if="booster?.is_achieved" class="badge booster-badge-success px-3 py-2 small fw-bold mb-2">
                                    <vue-feather type="check-circle" size="14" class="me-1" style="vertical-align: middle;"></vue-feather> BOOSTED ACTIVE
                                </div>
                                <div v-else-if="!isBoosterExpired" class="badge booster-badge-pending px-3 py-2 small fw-bold mb-2">
                                    <span class="booster-status-pulse"></span> PENDING ACTIVATION
                                </div>
                                <div v-else class="badge booster-badge-expired px-3 py-2 small fw-bold mb-2">
                                    EXPIRED
                                </div>

                                <!-- Countdown Timer -->
                                <div v-if="!booster?.is_achieved && !isBoosterExpired" class="countdown-container d-flex justify-content-end gap-2">
                                    <div class="time-block"><span class="time-val">{{ countdown.days }}</span><span class="time-lbl">d</span></div><span class="time-sep">:</span>
                                    <div class="time-block"><span class="time-val">{{ countdown.hours }}</span><span class="time-lbl">h</span></div><span class="time-sep">:</span>
                                    <div class="time-block"><span class="time-val">{{ countdown.minutes }}</span><span class="time-lbl">m</span></div><span class="time-sep">:</span>
                                    <div class="time-block"><span class="time-val">{{ countdown.seconds }}</span><span class="time-lbl">s</span></div>
                                </div>
                            </div>

                            <!-- 🪐 Ultra Pro Animated Booster Graphic -->
                            <div class="ultra-pro-booster-graphic" :class="booster?.is_achieved ? 'active-state' : (isBoosterExpired ? 'expired-state' : 'pending-state')">
                                <div class="orbit orbit-1"></div>
                                <div class="orbit orbit-2"></div>
                                <div class="orbit orbit-3"></div>
                                <div class="center-core">
                                    <vue-feather :type="booster?.is_achieved ? 'zap' : (isBoosterExpired ? 'x' : 'activity')" size="24" class="core-icon"></vue-feather>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar for Referrals -->
                    <div class="booster-progress-wrapper mt-4 position-relative z-1" v-if="!booster?.is_achieved">
                        <div class="d-flex justify-content-between text-xs text-secondary-light mb-1 small">
                            <span>Progress Tracking</span>
                            <span>{{ booster?.referrals_count || 0 }} / 2 Referrals</span>
                        </div>
                        <div class="booster-progress-track">
                            <div class="booster-progress-fill" :style="{ width: Math.min(((booster?.referrals_count || 0) / 2 * 100), 100) + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. 🌟 Current Reward Rank Premium Mobile Widget -->
        <section class="section-b-space reward-rank-section py-2">
            <div class="custom-container">
                <div class="title mb-3 d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0">Current Reward Rank</h2>
                </div>
                <div class="glass-rank-card futuristic-rank-banner" :class="reward_rank_id > 0 ? 'rank-achieved' : 'rank-none'">
                    <!-- Rank Scanner Glow overlay -->
                    <div class="rank-scanner-line"></div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative z-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rank-icon-container tech-hex-icon" :class="reward_rank_id > 0 ? 'glow-gold' : 'glow-dim'">
                                <vue-feather type="award" size="24" :class="reward_rank_id > 0 ? 'text-gold-neon rank-icon-pulse' : 'text-dim-neon'"></vue-feather>
                            </div>
                            <div>
                                <span class="rank-label small text-uppercase tracking-wider text-secondary">Current Reward Rank</span>
                                <h3 class="rank-title text-white mb-0 mt-1">
                                    {{ reward_rank_name || 'No Reward Rank' }}
                                </h3>
                            </div>
                        </div>

                        <!-- Status & Animated Graphic -->
                        <div class="d-flex align-items-center justify-content-end gap-3 gap-sm-4">
                            <div class="text-end">
                                <div v-if="reward_rank_id > 0" class="badge rank-badge-gold px-3 py-2 small fw-bold mb-0">
                                    <span class="rank-status-pulse"></span> RANK LEVEL {{ reward_rank_id }}
                                </div>
                                <div v-else class="badge rank-badge-dim px-3 py-2 small fw-bold mb-0">
                                    UNRANKED
                                </div>
                            </div>

                            <!-- 🛡️ Ultra Pro Animated Rank Graphic -->
                            <div class="ultra-pro-rank-graphic" :class="reward_rank_id > 0 ? 'active-rank' : 'dim-rank'">
                                <div class="rank-ring ring-1"></div>
                                <div class="rank-ring ring-2"></div>
                                <div class="rank-center-core">
                                    <vue-feather :type="reward_rank_id > 0 ? 'star' : 'shield'" size="20" class="rank-core-icon"></vue-feather>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- 5. Portfolio Balances Card -->
        <BalanceInfoComponent :currency="currencySymbol" :investment="investment" :total_withdrawal="total_withdrawal"
                              :user_income="user_income">
        </BalanceInfoComponent>

        <!-- 6. Income Earnings Breakdown -->
        <UserIncomeComponent :user_income="user_income" :currency="currencySymbol" />

        <!-- 7. Team Metrics Widget -->
        <team-widget :team="team"></team-widget>

        <!-- 8. Level Achievement Stats Banner (Futuristic Grid Power Meter) -->
        <!-- 8. Level Unlocking Matrix (Futuristic Cyber Grid Layout) -->
        <section class="section-b-space level-achievement-section py-2">
            <div class="custom-container">
                <div class="title mb-3 d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0">Level Unlocking Matrix</h2>
                </div>
                <div class="statistics-banner glass-stats-banner futuristic-matrix-banner">
                    <!-- Matrix Scanner Glow overlay -->
                    <div class="matrix-scanner-line"></div>
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 position-relative">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-level-icon-glow tech-hex-icon">
                                <vue-feather type="cpu" size="22" class="text-success-neon tech-icon-pulse" />
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-0 tracking-wide">Level Unlocking Matrix</h4>
                                <p class="text-secondary small mb-0 mt-1">Systematic matching grid sector depth</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="matrix-status-pulse"></span>
                            <span class="badge level-opened-badge px-3 py-2 small fw-bold">
                                {{ opened_level }} / 20 Levels Active
                            </span>
                        </div>
                    </div>

                    <!-- Futuristic 20-segment Level Power Meter -->
                    <div class="level-power-bar-grid position-relative">
                        <div 
                            v-for="l in 20" 
                            :key="l" 
                            class="power-bar-segment" 
                            :class="{ active: l <= opened_level, current: l === opened_level }"
                            :title="'Level ' + l"
                        >
                            <div class="segment-cyber-lines"></div>
                            <span class="power-bar-text">{{ l < 10 ? '0' + l : l }}</span>
                        </div>
                    </div>

                    

                    
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import TeamWidget from "@/components/xino/TeamWidget";
import UserLayout from "@/layouts/UserLayouts/UserLayout";
import InputError from "@/components/InputError";
import SubscriptionWidget from "@/components/SubscriptionWidget";
import EarningWidget from "@/components/EarningWidget";
import StakingSubscriptionWidget from "@/components/StakingSubscriptionWidget";
import RewardPointPopup from "@/components/xino/RewardPointPopup";
import DashboardNotice from "@/components/xino/DashboardNotice";
import ReferralLinkComponent from "@/components/ReferralLinkComponent";
import LatestChampionComponent from "@/components/LatestChampionComponent.vue";
import ValidatorStatGraphWidget from "@/components/ValidatorStatGraphWidget.vue";
import IncomeGraphWidget from "@/components/IncomeGraphWidget.vue";
import TokenPriceComponent from "@/components/TokenPriceComponent.vue";
import {computed, onMounted, ref, onUnmounted} from "vue";
import {toast} from "@/utils/toast";
import UserIncomeComponent from "@/components/UserIncomeComponent.vue";
import UserWalletsComponent from "@/components/UserWalletsComponent.vue"
import InstantOperationComponent from "@/components/SunLotusInfra/InstantOperationComponent.vue";
import BalanceInfoComponent from "@/components/SunLotusInfra/BalanceInfoComponent.vue";
import SliderComponent from "@/components/SunLotusInfra/SliderComponent.vue";
import WelcomeModal from "@/components/WelcomeModal.vue";
import {Link, usePage} from "@inertiajs/vue3";
import UserLoginDetailsComponent from "@/components/UserLoginDetailsComponent.vue";
import VueFeather from "vue-feather";

export default {
    name: "UserDashboard",
    components: {
        UserLoginDetailsComponent,
        Link,
        WelcomeModal,
        SliderComponent,
        BalanceInfoComponent,
        InstantOperationComponent,
        UserIncomeComponent,
        TokenPriceComponent,
        IncomeGraphWidget,
        ValidatorStatGraphWidget,
        LatestChampionComponent,
        ReferralLinkComponent,
        DashboardNotice,
        RewardPointPopup,
        StakingSubscriptionWidget,
        InputError,
        SubscriptionWidget,
        EarningWidget,
        TeamWidget, 
        UserWalletsComponent,
        VueFeather
    },
    layout: UserLayout,
    props: {
        user: Object,
        user_income_wallet: Object,
        user_usd_wallet: Object,
        user_income: Object,
        user_income_on_hold: Object,
        active_subscription: Object,
        subscriptions: Object,
        ref_qr: String,
        investment: String,
        total_withdrawal: String,
        team: Object,
        compound: Object,
        total_income: String,
        showWelcomeModal: Boolean,
        welcomeMode: String,
        display_currency: String,
        opened_level: Number,
        reward_rank_id: Number,
        reward_rank_name: String,
        booster: Object,
    },
    setup(props) {
        const page = usePage()
        const currencySymbol = computed(() => page.props.currency.symbol)

        // Booster Countdown State
        const countdown = ref({ days: '00', hours: '00', minutes: '00', seconds: '00' });
        const isBoosterExpired = ref(false);
        let timerInterval = null;

        const updateTimer = () => {
            if (!props.booster || props.booster.is_achieved || !props.booster.expires_at) {
                return;
            }

            const expiryDate = new Date(props.booster.expires_at).getTime();
            const now = new Date().getTime();
            const distance = expiryDate - now;

            if (distance < 0) {
                isBoosterExpired.value = true;
                countdown.value = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                if (timerInterval) clearInterval(timerInterval);
                return;
            }

            isBoosterExpired.value = false;
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.value = {
                days: String(days).padStart(2, '0'),
                hours: String(hours).padStart(2, '0'),
                minutes: String(minutes).padStart(2, '0'),
                seconds: String(seconds).padStart(2, '0')
            };
        };

        onMounted(() => {
            window.copyText = function (value) {
                var s = document.createElement('input');
                s.value = value;
                document.body.appendChild(s);

                if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                    s.contentEditable = true;
                    s.readOnly = false;
                    var range = document.createRange();
                    range.selectNodeContents(s);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                    s.setSelectionRange(0, 999999);
                } else {
                    s.select();
                }
                try {
                    document.execCommand('copy');
                } catch (err) {
                    // ignored
                }
                s.remove();
            };

            // Initialize booster timer
            if (props.booster && !props.booster.is_achieved && props.booster.expires_at) {
                updateTimer();
                timerInterval = setInterval(updateTimer, 1000);
            }
        })

        onUnmounted(() => {
            if (timerInterval) clearInterval(timerInterval);
        });

        return {
            currencySymbol,
            countdown,
            isBoosterExpired
        }
    },
    methods: {
        copy(text) {
            window.copyText(text)
        },
        copyRef() {
            this.copy(route('register', {
                ref_code: this.$page.props.auth.user.ref_code,
            }))
            toast('Copied!', 'success')
        }
    }
};
</script>

<style scoped>
/* Ultra Pro Elite Booster Card Styles */
.glass-booster-card {
    background: rgba(16, 20, 31, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}

.futuristic-booster-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.8), transparent);
}

.futuristic-booster-banner.achieved::before {
    background: linear-gradient(90deg, transparent, rgba(0, 255, 170, 0.8), transparent);
}

.booster-scanner-line {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.05), transparent);
    animation: booster-scan 3s infinite linear;
    pointer-events: none;
}

.futuristic-booster-banner.achieved .booster-scanner-line {
    background: linear-gradient(90deg, transparent, rgba(0, 255, 170, 0.05), transparent);
}

@keyframes booster-scan {
    0% { left: -100%; }
    100% { left: 200%; }
}

.booster-icon-container {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.glow-warning { box-shadow: 0 0 15px rgba(255, 193, 7, 0.3) inset, 0 0 20px rgba(255, 193, 7, 0.2); border-color: rgba(255, 193, 7, 0.4); }
.glow-success { box-shadow: 0 0 15px rgba(0, 255, 170, 0.3) inset, 0 0 20px rgba(0, 255, 170, 0.2); border-color: rgba(0, 255, 170, 0.4); }

.booster-icon-pulse { animation: pulse-icon 2s infinite; }
@keyframes pulse-icon {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}

.countdown-container {
    background: rgba(0,0,0,0.3);
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: inline-flex;
}

.time-block { display: flex; flex-direction: column; align-items: center; min-width: 24px; }
.time-val { font-size: 1.1rem; font-weight: 700; color: #ffc107; font-family: monospace; line-height: 1; }
.time-lbl { font-size: 0.6rem; color: #888; text-transform: uppercase; }
.time-sep { color: #555; font-weight: bold; margin-top: 2px; }

.booster-badge-pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}
.booster-badge-success {
    background: rgba(0, 255, 170, 0.1);
    color: #00ffaa;
    border: 1px solid rgba(0, 255, 170, 0.3);
    box-shadow: 0 0 10px rgba(0, 255, 170, 0.2);
}
.booster-badge-expired {
    background: rgba(255, 75, 75, 0.1);
    color: #ff4b4b;
    border: 1px solid rgba(255, 75, 75, 0.3);
}

.booster-status-pulse {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffc107;
    margin-right: 6px;
    box-shadow: 0 0 8px #ffc107;
    animation: status-pulse 1.5s infinite;
}
@keyframes status-pulse {
    0% { opacity: 0.5; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.2); }
    100% { opacity: 0.5; transform: scale(0.8); }
}

.booster-progress-track { height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
.booster-progress-fill { height: 100%; background: linear-gradient(90deg, #ff9800, #ffc107); border-radius: 10px; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5); transition: width 0.5s ease-out; }

.text-warning-neon { color: #ffc107 !important; text-shadow: 0 0 5px rgba(255, 193, 7, 0.5); }
.text-success-neon { color: #00ffaa !important; text-shadow: 0 0 5px rgba(0, 255, 170, 0.5); }

/* 🪐 Ultra Pro Animated Booster Graphic */
.ultra-pro-booster-graphic {
    position: relative;
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ultra-pro-booster-graphic::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, currentColor 0%, transparent 65%);
    opacity: 0.15;
    z-index: 0;
}
.orbit {
    position: absolute;
    border-radius: 50%;
    border: 1px solid transparent;
    border-top-color: currentColor;
    border-bottom-color: currentColor;
    animation: orbit-spin linear infinite;
    z-index: 1;
}
.orbit-1 { width: 100%; height: 100%; animation-duration: 4s; opacity: 0.6; }
.orbit-2 { width: 75%; height: 75%; animation-duration: 3s; animation-direction: reverse; opacity: 0.8; }
.orbit-3 { width: 50%; height: 50%; animation-duration: 2s; border-style: dashed; }
.center-core {
    position: relative;
    z-index: 2;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    padding: 8px;
    box-shadow: 0 0 15px currentColor;
    display: flex;
    align-items: center;
    justify-content: center;
}
.core-icon {
    color: currentColor;
    animation: core-pulse 1.5s ease-in-out infinite alternate;
}
@keyframes orbit-spin { 100% { transform: rotate(360deg); } }
@keyframes core-pulse {
    0% { transform: scale(0.85); opacity: 0.8; }
    100% { transform: scale(1.15); opacity: 1; text-shadow: 0 0 12px currentColor; }
}
.pending-state { color: #ffc107; }
.active-state { color: #00ffaa; }
.active-state .orbit { animation-duration: 1.5s; }
.active-state .orbit-2 { animation-duration: 1s; }
.active-state .orbit-3 { animation-duration: 0.7s; }
.expired-state { color: #ff4b4b; }
.expired-state .orbit { animation: none; border-color: rgba(255, 75, 75, 0.3); border-style: solid; }
.expired-state .core-icon { animation: none; opacity: 0.5; }

/* 🛡️ Ultra Pro Elite Rank Card Styles */
.glass-rank-card {
    background: rgba(20, 16, 31, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}
.futuristic-rank-banner::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.8), transparent); }
.futuristic-rank-banner.rank-none::before { background: linear-gradient(90deg, transparent, rgba(150, 150, 150, 0.5), transparent); }
.rank-scanner-line { position: absolute; top: 0; left: -100%; width: 50%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.05), transparent); animation: booster-scan 4s infinite linear; pointer-events: none; }
.futuristic-rank-banner.rank-none .rank-scanner-line { background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.02), transparent); }
.rank-icon-container { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); }
.glow-gold { box-shadow: 0 0 15px rgba(255, 215, 0, 0.3) inset, 0 0 20px rgba(255, 215, 0, 0.2); border-color: rgba(255, 215, 0, 0.4); }
.glow-dim { box-shadow: 0 0 10px rgba(150, 150, 150, 0.1) inset; border-color: rgba(150, 150, 150, 0.2); }
.rank-icon-pulse { animation: pulse-icon 2s infinite; }
.rank-badge-gold { background: rgba(255, 215, 0, 0.1); color: #ffd700; border: 1px solid rgba(255, 215, 0, 0.3); box-shadow: 0 0 10px rgba(255, 215, 0, 0.2); }
.rank-badge-dim { background: rgba(150, 150, 150, 0.1); color: #aaa; border: 1px solid rgba(150, 150, 150, 0.3); }
.rank-status-pulse { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ffd700; margin-right: 6px; box-shadow: 0 0 8px #ffd700; animation: status-pulse 1.5s infinite; }
.text-gold-neon { color: #ffd700 !important; text-shadow: 0 0 5px rgba(255, 215, 0, 0.5); }
.text-dim-neon { color: #aaa !important; }
/* 🛡️ Ultra Pro Animated Rank Graphic */
.ultra-pro-rank-graphic { position: relative; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ultra-pro-rank-graphic::before { content: ''; position: absolute; width: 100%; height: 100%; background: radial-gradient(circle, currentColor 0%, transparent 60%); opacity: 0.15; z-index: 0; }
.rank-ring { position: absolute; border-radius: 50%; border: 2px solid transparent; border-top-color: currentColor; border-left-color: currentColor; animation: rank-spin linear infinite; z-index: 1; }
.ring-1 { width: 100%; height: 100%; animation-duration: 5s; opacity: 0.7; }
.ring-2 { width: 70%; height: 70%; animation-duration: 3s; animation-direction: reverse; border-style: dashed; opacity: 0.9; }
.rank-center-core { position: relative; z-index: 2; background: rgba(0, 0, 0, 0.7); border-radius: 30%; padding: 6px; border: 1px solid currentColor; box-shadow: 0 0 10px currentColor; display: flex; align-items: center; justify-content: center; transform: rotate(45deg); }
.rank-core-icon { color: currentColor; transform: rotate(-45deg); animation: rank-core-pulse 2s ease-in-out infinite alternate; }
@keyframes rank-spin { 100% { transform: rotate(360deg); } }
@keyframes rank-core-pulse { 0% { transform: rotate(-45deg) scale(0.9); opacity: 0.8; } 100% { transform: rotate(-45deg) scale(1.1); opacity: 1; text-shadow: 0 0 10px currentColor; } }
.active-rank { color: #ffd700; }
.active-rank .ring-1 { animation-duration: 2s; }
.dim-rank { color: #888; }
.dim-rank .rank-ring { animation: none; border-color: rgba(150, 150, 150, 0.3); border-style: solid; }
.dim-rank .rank-core-icon { animation: none; opacity: 0.5; }
</style>
