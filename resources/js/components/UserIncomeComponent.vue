<template>
    <section class="earnings-section py-2">
        <div class="custom-container">
            <div class="title mb-3 d-flex justify-content-between align-items-center">
                <h2 class="text-white mb-0">Earnings Portfolio</h2>
            </div>
            
            <div class="row g-3">
                <!-- 1. Trading Income -->
                <div class="col-6 col-md-3">
                    <div class="glass-earnings-card">
                        <div class="card-top d-flex justify-content-between align-items-start mb-3">
                            <div class="income-icon-wrap bg-blue-glow">
                                <vue-feather type="bar-chart-2" size="18" />
                            </div>
                            <Link :href="route('earnings.monthly.trading.bonus')" class="details-link">
                                <vue-feather type="chevron-right" size="16" />
                            </Link>
                        </div>
                        <div class="card-info">
                            <span class="income-label small text-uppercase">Trading Income</span>
                            <h3 class="income-value text-white mt-1 mb-0">{{ currency }} {{ formatAmount(user_income.roi) }}</h3>
                            <span class="subtext-detail mt-1">Monthly Roi</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Market Earnings -->
                <div class="col-6 col-md-3">
                    <div class="glass-earnings-card">
                        <div class="card-top d-flex justify-content-between align-items-start mb-3">
                            <div class="income-icon-wrap bg-emerald-glow">
                                <vue-feather type="users" size="18" />
                            </div>
                            <Link :href="route('earnings.direct.bonus')" class="details-link">
                                <vue-feather type="chevron-right" size="16" />
                            </Link>
                        </div>
                        <div class="card-info">
                            <span class="income-label small text-uppercase">Market Earnings</span>
                            <h3 class="income-value text-white mt-1 mb-0">{{ currency }} {{ formatAmount(user_income.direct) }}</h3>
                            <span class="subtext-detail mt-1">Referral Bonus</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Systematic Trading Income -->
                <div class="col-6 col-md-3">
                    <div class="glass-earnings-card">
                        <div class="card-top d-flex justify-content-between align-items-start mb-3">
                            <div class="income-icon-wrap bg-purple-glow">
                                <vue-feather type="framer" size="18" />
                            </div>
                            <Link :href="route('earnings.systematic.bonus')" class="details-link">
                                <vue-feather type="chevron-right" size="16" />
                            </Link>
                        </div>
                        <div class="card-info">
                            <span class="income-label small text-uppercase">Systematic Income</span>
                            <h3 class="income-value text-white mt-1 mb-0">{{ currency }} {{ formatAmount(user_income.roi_on_roi) }}</h3>
                            <span class="subtext-detail mt-1">Monthly Matching</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Rank Profit -->
                <div class="col-6 col-md-3">
                    <div class="glass-earnings-card">
                        <div class="card-top d-flex justify-content-between align-items-start mb-3">
                            <div class="income-icon-wrap bg-amber-glow">
                                <vue-feather type="award" size="18" />
                            </div>
                            <Link :href="route('earnings.rank.bonus')" class="details-link">
                                <vue-feather type="chevron-right" size="16" />
                            </Link>
                        </div>
                        <div class="card-info">
                            <span class="income-label small text-uppercase">Rank Profit</span>
                            <h3 class="income-value text-white mt-1 mb-0">{{ currency }} {{ formatAmount(user_income.rank) }}</h3>
                            <span class="subtext-detail mt-1">Team Sales Volume</span>
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
    name: "UserIncomeComponent",
    components: {
        Link,
        VueFeather
    },
    props: {
        user_income: Object,
        currency: String,
    },
    setup() {
        function formatAmount(val) {
            if (val === undefined || val === null) return "0.00";
            const num = parseFloat(val);
            if (isNaN(num)) return "0.00";
            return num.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        return { formatAmount };
    }
}
</script>

<style scoped>
.glass-earnings-card {
    background: rgba(30, 41, 59, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.glass-earnings-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.45);
}

.income-icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
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

.bg-purple-glow {
    background: rgba(168, 85, 247, 0.12);
    border: 1px solid rgba(168, 85, 247, 0.2);
    color: #a855f7;
}

.bg-amber-glow {
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.details-link {
    color: #475569;
    transition: color 0.2s ease;
}

.glass-earnings-card:hover .details-link {
    color: #f1f5f9;
}

.income-label {
    font-size: 0.65rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: block;
}

.income-value {
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -0.2px;
}

.subtext-detail {
    font-size: 0.72rem;
    color: #94a3b8;
    display: block;
}
</style>
