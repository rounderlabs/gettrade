<template>
    <div class="content-wrapper">

        <!-- HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <h1>Welcome, {{ auth?.user?.name ?? 'Admin' }}</h1>
            </div>
        </section>

        <!-- CONTENT -->
        <section class="content">
            <div class="container-fluid">

                <!-- SUMMARY BOXES -->
                <div class="row">
                    <StatBox title="Total Users" :value="users" icon="users" color="info" />
                    <StatBox title="Paid Users" :value="active_participants" icon="user-check" color="success" />
                </div>

                <div class="row">
                    <StatBox title="Direct Bonus" :value="total_direct_bonus" prefix="$" icon="hand-holding-usd" color="primary" />
                    <StatBox title="Trading Bonus" :value="total_trading_bonus" prefix="$" icon="chart-line" color="warning" />
                    <StatBox title="Systematic Bonus" :value="total_systematic_bonus" prefix="$" icon="sync-alt" color="danger" />
                    <StatBox title="Rank Bonus" :value="total_rank_bonus" prefix="$" icon="award" color="secondary" />
                </div>

                <!-- CHARTS -->
                <div class="row">

                    <!-- DONUT -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Income Distribution</h3>
                            </div>
                            <div class="card-body">
                                <apexchart
                                    type="donut"
                                    height="280"
                                    :options="donutOptions"
                                    :series="donutSeries"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- LINE -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Monthly Income Trend</h3>
                            </div>
                            <div class="card-body">
                                <apexchart
                                    type="line"
                                    height="300"
                                    :options="lineOptions"
                                    :series="lineSeries"
                                />
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

/* 🔹 SMALL STAT BOX COMPONENT */
const StatBox = {
    props: ["title", "value", "icon", "color", "prefix"],
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
    `
}

export default {
    name: "Dashboard",
    layout: MainAdminLayout,
    components: {StatBox},

    props: {
        users: Number,
        active_participants: Number,
        total_direct_bonus: Number,
        total_trading_bonus: Number,
        total_systematic_bonus: Number,
        total_rank_bonus: Number,

        /* 🔥 NEW */
        monthly_income: Array, // [{month:'Jan', amount:1000}]
        auth: Object,
    },

    computed: {
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
                    "Direct",
                    "Trading",
                    "Systematic",
                    "Rank",
                ],
                legend: {position: "bottom"},
            }
        },

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
                chart: {toolbar: {show: false}},
                xaxis: {
                    categories: this.monthly_income.map(i => i.month),
                },
                stroke: {curve: "smooth"},
                markers: {size: 4},
            }
        },
    },
}
</script>
