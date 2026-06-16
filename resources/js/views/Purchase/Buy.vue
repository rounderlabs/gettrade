<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue"
import NotificationToast from "@/components/NotificationToast.vue"
import { useForm, usePage, Link } from "@inertiajs/vue3"
import { computed } from "vue"
import { toast } from "@/utils/toast"
import VueFeather from "vue-feather"

defineOptions({ layout: UserLayout })

const props = defineProps({
    plan: Object,
    available_coin_balance: Object,
})

const page = usePage()

const currencySymbol = computed(() => {
    return page.props.currency?.symbol ?? "₹"
})

// ✅ BASE AMOUNT ONLY (backend-safe)
const form = useForm({
    plan_id: props.plan.id,
    amount: props.plan.amount_base,
})

const hasSufficientBalance = computed(() => {
    return Number(props.available_coin_balance.balance_base)
        >= Number(props.plan.amount_base)
})

const remainingBalance = computed(() => {
    const balance = Number(props.available_coin_balance.balance_base);
    const cost = Number(props.plan.amount_base);
    return Math.max(0, balance - cost);
})

const remainingBalanceDisplay = computed(() => {
    // Format remaining to currency format
    return remainingBalance.value.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
})

function submit() {
    if (!hasSufficientBalance.value) {
        toast("Insufficient wallet balance", "danger")
        return
    }

    form.post(route("purchase.plan.activate"))
}
</script>

<template>
    <section class="review-section">
        <div class="custom-container">
            <!-- Header -->
            <div class="review-header mb-4 mt-3">
                <Link :href="route('purchase.pricing')" class="back-link mb-3 d-inline-flex align-items-center">
                    <vue-feather type="arrow-left" size="16" class="me-1" />
                    Back to Packages
                </Link>
                <h1 class="review-title">Review Selected Package</h1>
                <p class="review-subtitle">Please check your package specifications and wallet balance before confirming investment</p>
            </div>

            <div class="row g-4">
                <!-- Package Specifications Card -->
                <div class="col-12 col-lg-7">
                    <div class="glass-card spec-card">
                        <div class="card-header-accent d-flex align-items-center gap-2 mb-4">
                            <div class="icon-wrap bg-primary-glow">
                                <vue-feather type="package" size="20" class="text-primary-light" />
                            </div>
                            <h3 class="card-section-title text-white">Package Specifications</h3>
                        </div>

                        <div class="spec-grid">
                            <div class="spec-box">
                                <div class="spec-icon text-primary">
                                    <vue-feather type="gift" size="22" />
                                </div>
                                <div class="spec-info">
                                    <span class="spec-label">Package Amount</span>
                                    <span class="spec-value highlight">{{ currencySymbol }} {{ plan.amount_display }}</span>
                                </div>
                            </div>

                            <div class="spec-box">
                                <div class="spec-icon text-success">
                                    <vue-feather type="trending-up" size="22" />
                                </div>
                                <div class="spec-info">
                                    <span class="spec-label">Monthly ROI Bonus</span>
                                    <span class="spec-value text-success">{{ plan.monthly_roi_amount }}% / mo</span>
                                </div>
                            </div>

                            <div class="spec-box">
                                <div class="spec-icon text-info">
                                    <vue-feather type="clock" size="22" />
                                </div>
                                <div class="spec-info">
                                    <span class="spec-label">Tenure Duration</span>
                                    <span class="spec-value">{{ Math.round(plan.tenure) }} Months</span>
                                </div>
                            </div>

                            <div class="spec-box">
                                <div class="spec-icon text-warning">
                                    <vue-feather type="award" size="22" />
                                </div>
                                <div class="spec-info">
                                    <span class="spec-label">Bonus Qualifications</span>
                                    <span class="spec-value">Rank &amp; Systematic Enabled</span>
                                </div>
                            </div>
                        </div>

                        <!-- Important Disclaimer/Notification -->
                        <div class="disclaimer-alert mt-4">
                            <vue-feather type="info" size="16" class="me-2 text-info flex-shrink-0 mt-05" />
                            <p class="mb-0 text-secondary-light small">
                                Staking principal payouts will lock according to tenure rules. Monthly dividends will credit directly into your income wallet balance.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Wallet and Checkout Details -->
                <div class="col-12 col-lg-5">
                    <div class="glass-card checkout-card">
                        <div class="card-header-accent d-flex align-items-center gap-2 mb-4">
                            <div class="icon-wrap bg-success-glow">
                                <vue-feather type="credit-card" size="20" class="text-success-light" />
                            </div>
                            <h3 class="card-section-title text-white">Wallet Verification</h3>
                        </div>

                        <!-- Wallet Status Panel -->
                        <div class="wallet-balance-panel mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-secondary small">Available Balance</span>
                                <span v-if="hasSufficientBalance" class="badge-status success-badge">
                                    <vue-feather type="check-circle" size="12" class="me-1" /> Sufficient Funds
                                </span>
                                <span v-else class="badge-status danger-badge">
                                    <vue-feather type="alert-triangle" size="12" class="me-1" /> Insufficient Funds
                                </span>
                            </div>
                            <h2 class="wallet-amount mb-0">{{ currencySymbol }} {{ available_coin_balance.balance_display }}</h2>
                        </div>

                        <!-- Summary Costs -->
                        <div class="cost-summary-list mb-4">
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span class="text-secondary">Selected Package Cost</span>
                                <span class="text-white fw-bold">{{ currencySymbol }} {{ plan.amount_display }}</span>
                            </div>
                            <hr class="summary-divider my-2" />
                            <div class="summary-row d-flex justify-content-between">
                                <span class="text-secondary">Est. Remaining Balance</span>
                                <span class="text-success fw-bold" v-if="hasSufficientBalance">
                                    {{ currencySymbol }} {{ remainingBalanceDisplay }}
                                </span>
                                <span class="text-danger fw-bold" v-else>
                                    Need {{ currencySymbol }} {{ Math.abs(Number(props.plan.amount_base) - Number(props.available_coin_balance.balance_base)).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} more
                                </span>
                            </div>
                        </div>

                        <!-- Submit Form -->
                        <form @submit.prevent="submit">
                            <button 
                                class="confirm-invest-btn w-100" 
                                :class="{ disabled: !hasSufficientBalance || form.processing }"
                                :disabled="!hasSufficientBalance || form.processing"
                            >
                                <span v-if="form.processing">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Activating Package...
                                </span>
                                <span v-else>
                                    Confirm &amp; Invest Now
                                    <vue-feather type="chevron-right" size="18" class="ms-1 align-middle" />
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <NotificationToast />
</template>

<style scoped>
.review-section {
    padding: 30px 0 80px 0;
    min-height: 100vh;
}

.back-link {
    color: #38bdf8;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: #60a5fa;
}

.review-title {
    font-size: 2rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 6px;
    background: linear-gradient(135deg, #ffffff 30%, #a5b4fc 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.review-subtitle {
    color: #94a3b8;
    font-size: 0.92rem;
    max-width: 700px;
}

/* Glass Card styles */
.glass-card {
    background: rgba(30, 41, 59, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    height: 100%;
}

.card-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0;
}

.icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-glow {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.25);
}

.text-primary-light {
    color: #60a5fa;
}

.bg-success-glow {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.text-success-light {
    color: #34d399;
}

/* Spec Grid details */
.spec-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.spec-box {
    background: rgba(15, 23, 42, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 14px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: border-color 0.2s ease;
}

.spec-box:hover {
    border-color: rgba(255, 255, 255, 0.1);
}

.spec-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.03);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.spec-info {
    display: flex;
    flex-direction: column;
}

.spec-label {
    font-size: 0.72rem;
    color: #64748b;
    text-transform: uppercase;
}

.spec-value {
    font-size: 1.05rem;
    font-weight: 700;
    color: #f1f5f9;
}

.spec-value.highlight {
    color: #38bdf8;
    font-size: 1.2rem;
}

.disclaimer-alert {
    background: rgba(14, 116, 144, 0.1);
    border: 1px solid rgba(14, 116, 144, 0.2);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
}

.mt-05 {
    margin-top: 2px;
}

.text-secondary-light {
    color: #cbd5e1;
}

/* Wallet verification panel */
.wallet-balance-panel {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(30, 41, 59, 0.4) 100%);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 20px;
}

.wallet-amount {
    font-size: 1.8rem;
    font-weight: 800;
    color: #ffffff;
    filter: drop-shadow(0 0 8px rgba(255,255,255,0.1));
}

/* Badges */
.badge-status {
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
}

.success-badge {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.danger-badge {
    background: rgba(239, 68, 68, 0.12);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.25);
}

/* Cost summary list */
.cost-summary-list {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 12px;
    padding: 16px;
    border: 1px solid rgba(255, 255, 255, 0.03);
}

.summary-divider {
    border-color: rgba(255,255,255,0.06);
}

.summary-row {
    font-size: 0.9rem;
}

/* Confirm Button styling */
.confirm-invest-btn {
    background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
    color: #ffffff;
    border: none;
    border-radius: 14px;
    padding: 16px 20px;
    font-weight: 700;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
}

.confirm-invest-btn:not(.disabled):hover {
    background: linear-gradient(135deg, #60a5fa 0%, #38bdf8 100%);
    box-shadow: 0 6px 22px rgba(37, 99, 235, 0.5);
    transform: translateY(-2px);
}

.confirm-invest-btn.disabled {
    background: #1e293b;
    color: #475569;
    border: 1px solid rgba(255, 255, 255, 0.03);
    cursor: not-allowed;
    box-shadow: none;
}
</style>
