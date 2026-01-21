<template>
    <div>

        <!-- ================= HEADER ================= -->
        <section class="content-header">
            <div class="container-fluid">
                <h1>
                    Welcome, {{ auth?.user?.name ?? 'Admin' }}
                </h1>
            </div>
        </section>

        <!-- ================= CONTENT ================= -->
        <section class="content">
            <div class="container-fluid">

                <!-- ===== USER STATS ===== -->
                <div class="row">
                    <StatBox
                        title="Total Users"
                        :value="users"
                        icon="users"
                        color="info"
                    />
                    <StatBox
                        title="Paid Users"
                        :value="active_participants"
                        icon="user-check"
                        color="success"
                    />
                </div>

                <!-- ===== INCOME STATS ===== -->
                <div class="row">
                    <StatBox
                        title="Direct Bonus"
                        :value="total_direct_bonus"
                        prefix="$"
                        icon="hand-holding-usd"
                        color="primary"
                    />
                    <StatBox
                        title="Trading Bonus"
                        :value="total_trading_bonus"
                        prefix="$"
                        icon="chart-line"
                        color="warning"
                    />
                    <StatBox
                        title="Systematic Bonus"
                        :value="total_systematic_bonus"
                        prefix="$"
                        icon="sync-alt"
                        color="danger"
                    />
                    <StatBox
                        title="Rank Bonus"
                        :value="total_rank_bonus"
                        prefix="$"
                        icon="award"
                        color="secondary"
                    />
                </div>

                <!-- ================= CHARTS ================= -->
                <div class="row">

                    <!-- DONUT CHART -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Income Distribution
                                </h3>
                            </div>
                            <div class="card-body">
                                <apexchart
                                    v-if="hasIncomeData"
                                    type="donut"
                                    height="280"
                                    :options="donutOptions"
                                    :series="donutSeries"
                                />
                                <p v-else class="text-center text-muted">
                                    No income data available
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- LINE CHART -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Monthly Income Trend
                                </h3>
                            </div>
                            <div class="card-body">
                                <apexchart
                                    v-if="monthly_income.length"
                                    type="line"
                                    height="300"
                                    :options="lineOptions"
                                    :series="lineSeries"
                                />
                                <p v-else class="text-center text-muted">
                                    No monthly income data
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</template>

<script>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"

/* ================= SMALL STAT BOX ================= */
const StatBox = {
    props: {
        title: String,
        value: [Number, String],
        icon: String,
        color: String,
        prefix: {
            type: String,
            default: "",
        },
    },
    template: `
        <div class="col-lg-3 col-6">
            <div class="small-box" :class="'bg-' + color">
                <div class="inner">
                    <h3>{{ prefix }}{{ Number(value || 0).toFixed(0) }}</h3>
                    <p>{{ title }}</p>
                </div>
                <div class="icon">
                    <i class="fas" :class="'fa-' + icon"></i>
                </div>
            </div>
        </div>
    `,
}

export default {
    name: "AdminDashboard",
    layout: MainAdminLayout,
    components: { StatBox },

    props: {
        users: { type: Number, default: 0 },
        active_participants: { type: Number, default: 0 },
        total_direct_bonus: { type: Number, default: 0 },
        total_trading_bonus: { type: Number, default: 0 },
        total_systematic_bonus: { type: Number, default: 0 },
        total_rank_bonus: { type: Number, default: 0 },

        /* 🔥 IMPORTANT FIX */
        monthly_income: {
            type: Array,
            default: () => [],
        },

        auth: Object,
    },

    computed: {
        hasIncomeData() {
            return (
                this.total_direct_bonus +
                this.total_trading_bonus +
                this.total_systematic_bonus +
                this.total_rank_bonus
            ) > 0
        },

        /* ===== DONUT ===== */
        donutSeries() {
            return [
                this.total_direct_bonus || 0,
                this.total_trading_bonus || 0,
                this.total_systematic_bonus || 0,
                this.total_rank_bonus || 0,
            ]
        },

        donutOptions() {
            return {
                labels: [
                    "Direct Bonus",
                    "Trading Bonus",
                    "Systematic Bonus",
                    "Rank Bonus",
                ],
                legend: {
                    position: "bottom",
                },
                dataLabels: {
                    enabled: true,
                },
            }
        },

        /* ===== LINE ===== */
        lineSeries() {
            return [
                {
                    name: "Income",
                    data: this.monthly_income.map(i => i.amount),
                },
            ]
        },

        lineOptions() {
            return {
                chart: {
                    toolbar: { show: false },
                },
                xaxis: {
                    categories: this.monthly_income.map(i => i.month),
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                markers: {
                    size: 4,
                },
                tooltip: {
                    y: {
                        formatter: val => `$${val}`,
                    },
                },
            }
        },
    },
}
</script>
