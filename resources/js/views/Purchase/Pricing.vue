<template>
    <section class="pricing-section">
        <div class="custom-container">
            <!-- Header section -->
            <div class="pricing-header text-center mb-5">
                <h1 class="pricing-title">Investment Packages</h1>
                <p class="pricing-subtitle">Select the package that fits your investment profile and watch your wealth grow</p>
                
                <!-- Premium Tab Switcher -->
                <div class="tab-switcher-wrapper mt-4">
                    <div class="tab-switcher">
                        <button 
                            class="tab-btn" 
                            :class="{ active: activeTab === 'regular' }" 
                            @click="activeTab = 'regular'"
                        >
                            <vue-feather type="layers" size="16" class="me-2 inline-icon" />
                            Regular Plans
                        </button>
                        <button 
                            class="tab-btn" 
                            :class="{ active: activeTab === 'secure' }" 
                            @click="activeTab = 'secure'"
                        >
                            <vue-feather type="shield" size="16" class="me-2 inline-icon" />
                            Secure Plans
                        </button>
                        <div class="tab-slider" :class="activeTab"></div>
                    </div>
                </div>
            </div>

            <!-- Regular Plans Grid -->
            <div v-if="activeTab === 'regular'" class="row g-4 justify-content-center">
                <div v-if="!plans || plans.length === 0" class="col-12 text-center py-5">
                    <div class="no-plans-card glass-card">
                        <vue-feather type="package" size="48" class="text-muted mb-3" />
                        <h4 class="text-white">No Regular Plans Available</h4>
                        <p class="text-secondary">Please check back later.</p>
                    </div>
                </div>
                
                <div v-for="(plan, index) in plans" :key="plan.id" class="col-12 col-md-6 col-lg-4">
                    <div class="plan-card glass-card" :class="getTierClass(index)">
                        <div class="plan-badge" v-if="index === 1">Most Popular</div>
                        
                        <div class="plan-header">
                            <h3 class="plan-tier-name">{{ getTierName(index) }}</h3>
                            <span class="plan-type-label">Regular Account</span>
                            <div class="plan-price mt-3">
                                <span class="currency">{{ currencySymbol }}</span>
                                <span class="amount">{{ formatAmount(plan.display_amount) }}</span>
                            </div>
                        </div>

                        <div class="plan-features mt-4">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <vue-feather type="trending-up" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Monthly Trading Bonus</span>
                                    <span class="value">{{ plan.monthly_roi_amount }}% / mo</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <vue-feather type="calendar" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Package Tenure</span>
                                    <span class="value">{{ Math.round(plan.tenure) }} Months</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <vue-feather type="git-merge" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Systematic Bonus</span>
                                    <span class="value text-success">20 Level Access</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <vue-feather type="award" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Rank Bonus Return</span>
                                    <span class="value text-info">Upto 10% Extra</span>
                                </div>
                            </div>
                        </div>

                        <div class="plan-action mt-4">
                            <Link :href="route('purchase.topup.form', [plan.id])" class="invest-btn w-100">
                                Invest Now
                                <vue-feather type="arrow-right" size="16" class="ms-2 arrow-icon" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secure Plans Grid -->
            <div v-else class="row g-4 justify-content-center">
                <div v-if="!secured_plans || secured_plans.length === 0" class="col-12 text-center py-5">
                    <div class="no-plans-card glass-card">
                        <vue-feather type="shield-off" size="48" class="text-muted mb-3" />
                        <h4 class="text-white">No Secure Plans Available</h4>
                        <p class="text-secondary">Please check back later.</p>
                    </div>
                </div>

                <div v-for="(plan, index) in secured_plans" :key="plan.id" class="col-12 col-md-6 col-lg-4">
                    <div class="plan-card glass-card secure-mode" :class="getTierClass(index)">
                        <div class="plan-badge secure">Capital Protected</div>
                        
                        <div class="plan-header">
                            <h3 class="plan-tier-name">{{ getTierName(index) }} Secure</h3>
                            <span class="plan-type-label secure"><vue-feather type="shield" size="12" class="me-1" />Guaranteed Capital</span>
                            <div class="plan-price mt-3">
                                <span class="currency">{{ currencySymbol }}</span>
                                <span class="amount">{{ formatAmount(plan.display_amount) }}</span>
                            </div>
                        </div>

                        <div class="plan-features mt-4">
                            <div class="feature-item">
                                <div class="feature-icon secure">
                                    <vue-feather type="trending-up" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Monthly Trading Bonus</span>
                                    <span class="value">{{ plan.monthly_roi_amount }}% / mo</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon secure">
                                    <vue-feather type="calendar" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Package Tenure</span>
                                    <span class="value">{{ Math.round(plan.tenure) }} Months</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon secure">
                                    <vue-feather type="git-merge" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Systematic Bonus</span>
                                    <span class="value text-success">20 Level Access</span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon secure">
                                    <vue-feather type="award" size="16" />
                                </div>
                                <div class="feature-text">
                                    <span class="label">Rank Bonus Return</span>
                                    <span class="value text-info">Upto 10% Extra</span>
                                </div>
                            </div>
                        </div>

                        <div class="plan-action mt-4">
                            <Link :href="route('purchase.topup.form', [plan.id])" class="invest-btn secure w-100">
                                Protect &amp; Invest
                                <vue-feather type="arrow-right" size="16" class="ms-2 arrow-icon" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {computed, ref} from "vue";
import {Link, usePage} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "Pricing",
    props: {
        plans: Array,
        secured_plans: Array,
        display_currency: String
    },
    layout: UserLayout,
    components: {Link, VueFeather},
    setup() {
        const activeTab = ref('regular');
        const page = usePage();

        const currencySymbol = computed(() => {
            return page.props.currency?.symbol ?? "₹";
        });

        const tierNames = ["Bronze", "Silver", "Gold", "Platinum", "Diamond", "Crown", "Ambassador"];
        const tierClasses = ["bronze-tier", "silver-tier", "gold-tier", "platinum-tier", "diamond-tier", "crown-tier", "ambassador-tier"];

        function getTierName(index) {
            return tierNames[index] || `Tier ${index + 1}`;
        }

        function getTierClass(index) {
            return tierClasses[index] || "default-tier";
        }

        function formatAmount(amount) {
            if (!amount) return "0.00";
            return parseFloat(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        return {
            activeTab,
            currencySymbol,
            getTierName,
            getTierClass,
            formatAmount
        };
    }
};
</script>

<style scoped>
.pricing-section {
    padding: 30px 0 80px 0;
    min-height: 100vh;
}

.pricing-title {
    font-size: 2.2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #ffffff 30%, #a5b4fc 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 8px;
}

.pricing-subtitle {
    color: #94a3b8;
    max-width: 600px;
    margin: 0 auto;
    font-size: 0.95rem;
}

/* Tab Switcher Custom styling */
.tab-switcher-wrapper {
    display: flex;
    justify-content: center;
}

.tab-switcher {
    position: relative;
    display: flex;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 99px;
    padding: 4px;
    width: 320px;
    z-index: 1;
}

.tab-btn {
    flex: 1;
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 10px 16px;
    font-size: 0.88rem;
    font-weight: 600;
    border-radius: 99px;
    cursor: pointer;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.tab-btn.active {
    color: #ffffff;
}

.inline-icon {
    vertical-align: middle;
}

.tab-slider {
    position: absolute;
    top: 4px;
    left: 4px;
    bottom: 4px;
    width: calc(50% - 4px);
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 99px;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.tab-slider.secure {
    transform: translateX(100%);
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

/* Glass Cards styling */
.glass-card {
    background: rgba(30, 41, 59, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}

.no-plans-card {
    padding: 50px;
}

/* Plan card spec */
.plan-card {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.plan-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
}

/* Badges */
.plan-badge {
    position: absolute;
    top: 16px;
    right: -32px;
    background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
    color: #000;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 4px 32px;
    transform: rotate(45deg);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.plan-badge.secure {
    background: linear-gradient(135deg, #34d399 0%, #059669 100%);
    color: #fff;
    right: 16px;
    transform: none;
    border-radius: 99px;
    padding: 4px 12px;
}

.plan-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    padding-bottom: 20px;
}

.plan-tier-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 4px;
}

.plan-type-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.plan-type-label.secure {
    color: #34d399;
    display: inline-flex;
    align-items: center;
}

/* Pricing typography */
.plan-price {
    display: flex;
    align-items: baseline;
}

.plan-price .currency {
    font-size: 1.6rem;
    font-weight: 600;
    color: #38bdf8;
    margin-right: 4px;
}

.plan-card.secure-mode .plan-price .currency {
    color: #34d399;
}

.plan-price .amount {
    font-size: 2.2rem;
    font-weight: 800;
    color: #ffffff;
}

/* Features */
.plan-features {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.feature-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(56, 189, 248, 0.1);
    color: #38bdf8;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(56, 189, 248, 0.15);
    flex-shrink: 0;
}

.feature-icon.secure {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.feature-text {
    display: flex;
    flex-direction: column;
}

.feature-text .label {
    font-size: 0.72rem;
    color: #64748b;
    text-transform: uppercase;
}

.feature-text .value {
    font-size: 0.9rem;
    font-weight: 600;
    color: #e2e8f0;
}

/* Investment buttons */
.invest-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 14px 20px;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
}

.invest-btn:hover {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    transform: scale(1.02);
}

.invest-btn .arrow-icon {
    transition: transform 0.2s ease;
}

.invest-btn:hover .arrow-icon {
    transform: translateX(4px);
}

.invest-btn.secure {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
}

.invest-btn.secure:hover {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
}

/* Tier Specific Glows (Regular Plans) */
.bronze-tier:hover {
    border-color: rgba(205, 127, 50, 0.4);
    box-shadow: 0 0 20px rgba(205, 127, 50, 0.15);
}

.silver-tier:hover {
    border-color: rgba(192, 192, 192, 0.4);
    box-shadow: 0 0 20px rgba(192, 192, 192, 0.15);
}

.gold-tier:hover {
    border-color: rgba(212, 175, 55, 0.4);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.15);
}

.platinum-tier:hover {
    border-color: rgba(229, 228, 226, 0.4);
    box-shadow: 0 0 20px rgba(229, 228, 226, 0.15);
}

.diamond-tier:hover {
    border-color: rgba(185, 242, 255, 0.4);
    box-shadow: 0 0 20px rgba(185, 242, 255, 0.15);
}

.crown-tier:hover {
    border-color: rgba(139, 92, 246, 0.4);
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
}

.ambassador-tier:hover {
    border-color: rgba(236, 72, 153, 0.4);
    box-shadow: 0 0 20px rgba(236, 72, 153, 0.15);
}
</style>
