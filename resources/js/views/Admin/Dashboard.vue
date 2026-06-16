<template>
    <div class="dashboard-container position-relative overflow-hidden">
        <!-- Background Glowing Blobs for True Glassmorphism Blur Effect -->
        <div class="bg-glow-blob blob-1"></div>
        <div class="bg-glow-blob blob-2"></div>
        <div class="bg-glow-blob blob-3"></div>

        <!-- ================= WELCOME HEADER ================= -->
        <header class="dashboard-header mb-4 position-relative z-index-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="welcome-title">Welcome back, <span class="gradient-text ultra-pulse">{{ auth?.user?.name ?? 'Admin' }}</span>!</h1>
                    <p class="welcome-subtitle text-secondary">Here's a breakdown of your system metrics and analytics today.</p>
                </div>
                <div class="glass-date-badge shadow-sm">
                    <i class="bi bi-calendar3 me-2 text-primary icon-pulse"></i>
                    <span>{{ currentDate }}</span>
                </div>
            </div>
        </header>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="dashboard-content position-relative z-index-2">
            <!-- ===== COHESIVE STAT CARDS ROW ===== -->
            <div class="row g-3 mb-4">
                <StatCard
                    title="Total Users"
                    :value="users"
                    icon="bi-people"
                    gradient="linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)"
                    glowColor="rgba(59, 130, 246, 0.5)"
                    :index="1"
                />
                <StatCard
                    title="Paid Users"
                    :value="active_participants"
                    icon="bi-shield-check"
                    gradient="linear-gradient(135deg, #064e3b 0%, #10b981 100%)"
                    glowColor="rgba(16, 185, 129, 0.5)"
                    :index="2"
                />
                <StatCard
                    title="Direct Bonus"
                    :value="total_direct_bonus"
                    prefix="$"
                    icon="bi-arrow-up-right-circle"
                    gradient="linear-gradient(135deg, #312e81 0%, #6366f1 100%)"
                    glowColor="rgba(99, 102, 241, 0.5)"
                    :index="3"
                />
                <StatCard
                    title="Trading Bonus"
                    :value="total_trading_bonus"
                    prefix="$"
                    icon="bi-graph-up-arrow"
                    gradient="linear-gradient(135deg, #78350f 0%, #f59e0b 100%)"
                    glowColor="rgba(245, 158, 11, 0.5)"
                    :index="4"
                />
                <StatCard
                    title="Systematic Bonus"
                    :value="total_systematic_bonus"
                    prefix="$"
                    icon="bi-arrow-repeat"
                    gradient="linear-gradient(135deg, #7f1d1d 0%, #ef4444 100%)"
                    glowColor="rgba(239, 68, 68, 0.5)"
                    :index="5"
                />
                <StatCard
                    title="Rank Bonus"
                    :value="total_rank_bonus"
                    prefix="$"
                    icon="bi-award"
                    gradient="linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%)"
                    glowColor="rgba(139, 92, 246, 0.5)"
                    :index="6"
                />
            </div>

            <!-- ================= CHARTS ================= -->
            <div class="row g-4">
                <!-- LINE CHART -->
                <div class="col-xl-8">
                    <div class="glass-card shadow-sm border h-100">
                        <div class="card-header-custom d-flex align-items-center justify-content-between p-4 border-bottom">
                            <div>
                                <h3 class="card-title-custom mb-1"><i class="bi bi-activity me-2 text-primary"></i>Monthly Income Trend</h3>
                                <p class="card-desc mb-0 text-secondary">Visual chart of historical monthly revenues earned.</p>
                            </div>
                        </div>
                        <div class="card-body-custom p-4">
                            <apexchart
                                v-if="monthly_income.length"
                                type="area"
                                height="320"
                                :options="lineOptions"
                                :series="lineSeries"
                            />
                            <div v-else class="empty-state p-5 text-center text-secondary">
                                <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
                                No monthly income data currently available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DONUT CHART -->
                <div class="col-xl-4">
                    <div class="glass-card shadow-sm border h-100">
                        <div class="card-header-custom d-flex align-items-center justify-content-between p-4 border-bottom">
                            <div>
                                <h3 class="card-title-custom mb-1"><i class="bi bi-pie-chart me-2 text-info"></i>Income Distribution</h3>
                                <p class="card-desc mb-0 text-secondary">Breakdown of system bonuses earned.</p>
                            </div>
                        </div>
                        <div class="card-body-custom p-4 d-flex align-items-center justify-content-center">
                            <div class="w-100">
                                <apexchart
                                    v-if="hasIncomeData"
                                    type="donut"
                                    height="320"
                                    :options="donutOptions"
                                    :series="donutSeries"
                                />
                                <div v-else class="empty-state p-5 text-center text-secondary">
                                    <i class="bi bi-pie-chart fs-1 mb-2 d-block"></i>
                                    No distributions recorded yet
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"

/* ================= PRESTIGE GRADIENT STAT CARD ================= */
const StatCard = {
    props: {
        title: String,
        value: [Number, String],
        icon: String,
        gradient: String,
        glowColor: String,
        prefix: {
            type: String,
            default: "",
        },
            index: {
                type: Number,
                default: 0,
            },
    },
    template: `
        <div class="col-xl-2 col-md-4 col-sm-6 col-12">
            <div class="premium-stat-card border-0 position-relative h-100" 
                 :style="{ background: gradient, '--card-glow': glowColor, animationDelay: (index * 0.1) + 's', borderRadius: '24px', padding: '1.75rem 1.5rem', overflow: 'hidden' }">
                
                <!-- Background Sci-Fi Effects -->
                <div class="cyber-scanner-line"></div>
                <div class="glass-shine"></div>
                <!-- Ambient Gradient Orb -->
                <div class="position-absolute" :style="{ background: glowColor, top: '-30px', right: '-30px', width: '120px', height: '120px', filter: 'blur(50px)', opacity: '0.8', zIndex: '0', pointerEvents: 'none' }"></div>
                
                <div class="d-flex flex-column justify-content-between h-100 position-relative z-index-2">
                    <!-- Top Section: Icon & Live Pulse -->
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div class="glass-icon-wrapper shadow-lg d-flex align-items-center justify-content-center" 
                             style="width: 54px; height: 54px; border-radius: 16px; background: rgba(255,255,255,0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="bi text-white stat-icon-pulse" :class="icon" style="font-size: 1.65rem; filter: drop-shadow(0 0 10px rgba(255,255,255,0.6));"></i>
                        </div>
                        <div class="d-flex align-items-center mt-2" title="Live System Metric">
                            <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #10b981; box-shadow: 0 0 12px #10b981; animation: pulse 2s infinite;"></span>
                        </div>
                    </div>
                    
                    <!-- Bottom Section: Label & Value -->
                    <div class="mt-auto">
                        <span class="text-white fw-bold text-uppercase d-block mb-1" style="font-size: 0.75rem; letter-spacing: 2px; opacity: 0.85;">{{ title }}</span>
                        <h3 class="text-white mb-0" style="font-size: 2.1rem; font-weight: 900; letter-spacing: -1px; text-shadow: 0 5px 15px rgba(0,0,0,0.25); line-height: 1.1;">{{ prefix }}{{ formatNumber(value) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        formatNumber(val) {
            const num = Number(val || 0);
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        }
    }
}

export default {
    name: "AdminDashboard",
    layout: MainAdminLayout,
    components: { StatCard },

    props: {
        users: { type: Number, default: 0 },
        active_participants: { type: Number, default: 0 },
        total_direct_bonus: { type: Number, default: 0 },
        total_trading_bonus: { type: Number, default: 0 },
        total_systematic_bonus: { type: Number, default: 0 },
        total_rank_bonus: { type: Number, default: 0 },
        monthly_income: {
            type: Array,
            default: () => [],
        },
        auth: Object,
    },

    setup() {
        const currentDate = new Date().toLocaleDateString(undefined, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        return { currentDate };
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
                colors: ['#6366f1', '#f59e0b', '#ef4444', '#8b5cf6'],
                legend: {
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                    labels: {
                        colors: '#475569'
                    }
                },
                stroke: {
                    show: true,
                    colors: ['#ffffff'],
                    width: 3
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontFamily: 'Inter, sans-serif',
                                    color: '#64748b'
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 'bold',
                                    color: '#1e293b',
                                    formatter: (val) => `$${Number(val).toLocaleString()}`
                                },
                                total: {
                                    show: true,
                                    label: 'Total Payout',
                                    color: '#64748b',
                                    formatter: (w) => {
                                        const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return `$${sum.toLocaleString()}`;
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                }
            }
        },

        lineSeries() {
            return [
                {
                    name: "Revenue Payout",
                    data: this.monthly_income.map(i => i.amount),
                },
            ]
        },

        lineOptions() {
            return {
                chart: {
                    fontFamily: "Inter, sans-serif",
                    toolbar: { show: false },
                    sparkline: { enabled: false }
                },
                colors: ['#3b82f6'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: "smooth",
                    width: 4,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.25,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: this.monthly_income.map(i => i.month),
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '12px'
                        },
                        formatter: val => `$${val.toLocaleString()}`
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: val => `$${val.toLocaleString()}`
                    }
                }
            }
        },
    },
}
</script>

<style>
@import '../../../css/admin-custom.css';
</style>
