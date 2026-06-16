<template>
    <div class="admin-container pb-5 position-relative overflow-hidden">
        <!-- Background Glowing Blobs for True Glassmorphism Blur Effect -->
        <div class="bg-glow-blob blob-1"></div>
        <div class="bg-glow-blob blob-2"></div>
        <div class="bg-glow-blob blob-3"></div>

        <!-- ================= HEADER ================= -->
        <section class="content-header px-4 py-3 position-relative z-index-2">
            <div class="container-fluid">
                <h1 class="welcome-title mb-1">
                    <i class="fas fa-chart-line text-primary mr-2 icon-pulse"></i><span class="gradient-text ultra-pulse">Pro User Report & Control Center</span>
                </h1>
                <p class="welcome-subtitle text-secondary small mb-0">Deep search any user profile, view combined income timelines, track team performance, and perform recursive downline operations.</p>
            </div>
        </section>

        <section class="content px-4 position-relative z-index-2">
            <div class="container-fluid">
                <!-- ================= SEARCH & FILTERS ================= -->
                <div class="card glass-card mb-4" style="animation-delay: 0.1s;">
                    <div class="cyber-scanner-line"></div>
                    <div class="card-header border-bottom py-3">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-search mr-2 text-primary stat-icon-pulse"></i>Search Profile & Filters
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="fetchReport">
                            <div class="row align-items-end">
                                <!-- User search -->
                                <div class="col-md-3">
                                    <div class="form-group mb-md-0">
                                        <label class="text-secondary font-weight-bold">Search Identifier</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input
                                                v-model="filters.user_identifier"
                                                type="text"
                                                class="form-control"
                                                placeholder="Username, Email, or ID"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <!-- From Date -->
                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label class="text-secondary font-weight-bold">Date From</label>
                                        <input type="date" v-model="filters.from_date" class="form-control" />
                                    </div>
                                </div>
                                <!-- To Date -->
                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label class="text-secondary font-weight-bold">Date To</label>
                                        <input type="date" v-model="filters.to_date" class="form-control" />
                                    </div>
                                </div>
                                <!-- Interval -->
                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label class="text-secondary font-weight-bold">Group Timeline By</label>
                                        <select v-model="filters.interval" class="form-control">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Search Button -->
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-block text-white" :disabled="loading">
                                        <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                                        <i v-else class="fas fa-sync-alt mr-2"></i>
                                        Fetch Deep Analytics
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ================= ANALYTICS SHOWCASE ================= -->
                <div v-if="reportData" class="row">
                    <!-- Left Column: User details, Business, Team stats -->
                    <div class="col-md-4">
                        <!-- User Card -->
                        <div class="card glass-card mb-4" style="animation-delay: 0.2s;">
                            <div class="cyber-scanner-line"></div>
                            <div class="card-header border-bottom py-3 bg-light">
                                <h6 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-id-card text-primary mr-2"></i>User Profile Info
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted border-top-0">User ID</td>
                                            <td class="font-weight-bold text-dark text-right border-top-0">#{{ reportData.user.id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Username</td>
                                            <td class="font-weight-bold text-dark text-right">{{ reportData.user.username }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Full Name</td>
                                            <td class="font-weight-bold text-dark text-right">{{ reportData.user.name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Email</td>
                                            <td class="font-weight-bold text-dark text-right small">{{ reportData.user.email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Sponsor</td>
                                            <td class="font-weight-bold text-primary text-right">{{ reportData.user.sponsor }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Joined Date</td>
                                            <td class="font-weight-bold text-secondary text-right">{{ reportData.user.created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td class="text-right">
                                                <span class="badge" :class="reportData.user.is_blocked ? 'badge-danger' : 'badge-success'">
                                                    {{ reportData.user.is_blocked ? 'Blocked' : 'Active' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Team Statistics -->
                        <div class="card glass-card mb-4" style="animation-delay: 0.3s;">
                            <div class="cyber-scanner-line"></div>
                            <div class="card-header border-bottom py-3 bg-light">
                                <h6 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-users text-success mr-2"></i>Directs & Team Metrics
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted border-top-0">Direct Referrals</td>
                                            <td class="font-weight-bold text-dark text-right border-top-0">{{ reportData.team.directs }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Active Referrals</td>
                                            <td class="font-weight-bold text-success text-right">{{ reportData.team.active_directs }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Total Downline Team</td>
                                            <td class="font-weight-bold text-info text-right">{{ reportData.team.team_size }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- leg business stats -->
                        <div class="card glass-card mb-4" style="animation-delay: 0.4s;">
                            <div class="cyber-scanner-line"></div>
                            <div class="card-header border-bottom py-3 bg-light">
                                <h6 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-briefcase text-warning mr-2"></i>Sales & Business Target
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted border-top-0">Direct Sales (INR)</td>
                                            <td class="font-weight-bold text-dark text-right border-top-0">₹ {{ formatCurrency(reportData.business.direct_business) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Total Team Sales (INR)</td>
                                            <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.business.team_business) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Matching Leg Business</td>
                                            <td class="font-weight-bold text-primary text-right">₹ {{ formatCurrency(reportData.business.matching_business) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Financial summary, chart data, control hub -->
                    <div class="col-md-8">
                        <!-- Financial income grid -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card glass-card h-100" style="animation-delay: 0.5s;">
                                    <div class="cyber-scanner-line"></div>
                                    <div class="card-header border-bottom py-3 bg-light">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-wallet text-primary mr-2"></i>Accumulated Incomes
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted border-top-0">Direct Income</td>
                                                    <td class="font-weight-bold text-dark text-right border-top-0">₹ {{ formatCurrency(reportData.incomes.direct) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Level Income</td>
                                                    <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.incomes.level) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Daily Trading (ROI)</td>
                                                    <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.incomes.roi) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Team Trading (Level ROI)</td>
                                                    <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.incomes.level_roi) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Rank Bonus</td>
                                                    <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.incomes.rank_roi) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Reward / Salaries</td>
                                                    <td class="font-weight-bold text-dark text-right">₹ {{ formatCurrency(reportData.incomes.reward) }}</td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <td class="font-weight-bold text-dark">Total Revenue</td>
                                                    <td class="font-weight-bold text-primary text-right">₹ {{ formatCurrency(reportData.incomes.total) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card glass-card h-100" style="animation-delay: 0.6s;">
                                    <div class="cyber-scanner-line"></div>
                                    <div class="card-header border-bottom py-3 bg-light">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="fas fa-hand-holding-usd text-success mr-2"></i>Payouts & Limits
                                        </h6>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div class="text-center my-auto">
                                            <span class="text-muted small font-weight-bold uppercase">Total Completed Withdrawals</span>
                                            <h1 class="text-success font-weight-bold mt-2 mb-0">₹ {{ formatCurrency(reportData.payouts.total) }}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Control Matrix Hub -->
                        <div class="card glass-card mb-4 border-danger" style="animation-delay: 0.7s;">
                            <div class="cyber-scanner-line bg-danger-gradient"></div>
                            <div class="card-header border-bottom border-danger bg-light-danger py-3">
                                <h6 class="font-weight-bold text-danger mb-0">
                                    <i class="fas fa-shield-alt mr-2"></i>Team Control Matrix (Downline Command Center)
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-4">Select an action to recursively modify setting parameters for all <strong>{{ reportData.team.team_size }}</strong> team members located under user <strong>{{ reportData.user.username }}</strong>.</p>
                                <div class="row text-center">
                                    <!-- Stop Incomes -->
                                    <div class="col-md-6 mb-3">
                                        <button
                                            class="btn btn-outline-danger btn-block py-3 font-weight-bold"
                                            @click="confirmTeamAction('stop_income')"
                                            :disabled="actionLoading"
                                        >
                                            <i class="fas fa-ban mr-2"></i>Stop Incomes & Payouts
                                        </button>
                                        <small class="text-muted d-block mt-1">Disables all referral, binary, trading, and withdrawal functions.</small>
                                    </div>
                                    <!-- Restart Incomes -->
                                    <div class="col-md-6 mb-3">
                                        <button
                                            class="btn btn-outline-success btn-block py-3 font-weight-bold"
                                            @click="confirmTeamAction('restart_income')"
                                            :disabled="actionLoading"
                                        >
                                            <i class="fas fa-play mr-2"></i>Restart All Incomes
                                        </button>
                                        <small class="text-muted d-block mt-1">Enables all blocked wallet and payout engines.</small>
                                    </div>
                                    <!-- Block Access -->
                                    <div class="col-md-6 mb-3">
                                        <button
                                            class="btn btn-danger btn-block py-3 font-weight-bold"
                                            @click="confirmTeamAction('block_team')"
                                            :disabled="actionLoading"
                                        >
                                            <i class="fas fa-user-slash mr-2"></i>Block Login Access
                                        </button>
                                        <small class="text-muted d-block mt-1">Logs out and locks panel entry for the entire team.</small>
                                    </div>
                                    <!-- Unblock Access -->
                                    <div class="col-md-6 mb-3">
                                        <button
                                            class="btn btn-success btn-block py-3 font-weight-bold"
                                            @click="confirmTeamAction('unblock_team')"
                                            :disabled="actionLoading"
                                        >
                                            <i class="fas fa-user-check mr-2"></i>Restore Access
                                        </button>
                                        <small class="text-muted d-block mt-1">Unlocks portal sign-in privileges.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Data Table -->
                        <div class="card glass-card" style="animation-delay: 0.8s;">
                            <div class="cyber-scanner-line"></div>
                            <div class="card-header border-bottom py-3 bg-light">
                                <h6 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-history mr-2"></i>Revenue Timeline ({{ filters.interval }})
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0 text-center">
                                        <thead>
                                            <tr>
                                                <th>Interval Key</th>
                                                <th>Directs</th>
                                                <th>Level</th>
                                                <th>ROI</th>
                                                <th>Team ROI</th>
                                                <th>Rank</th>
                                                <th>Reward</th>
                                                <th class="font-weight-bold">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="reportData.timeline.length === 0">
                                                <td colspan="8" class="text-muted py-4">No timeline logs matching date filters</td>
                                            </tr>
                                            <tr v-for="t in reportData.timeline" :key="t.date">
                                                <td class="font-weight-bold text-dark">{{ t.date }}</td>
                                                <td>₹ {{ formatCurrency(t.direct) }}</td>
                                                <td>₹ {{ formatCurrency(t.level) }}</td>
                                                <td>₹ {{ formatCurrency(t.roi) }}</td>
                                                <td>₹ {{ formatCurrency(t.level_roi) }}</td>
                                                <td>₹ {{ formatCurrency(t.rank_roi) }}</td>
                                                <td>₹ {{ formatCurrency(t.reward) }}</td>
                                                <td class="font-weight-bold text-primary">₹ {{ formatCurrency(t.total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= EMPTY STATE ================= -->
                <div v-else class="text-center py-5 text-muted">
                    <i class="fas fa-chart-pie fa-4x text-light mb-3"></i>
                    <h5>No Deep Report Loaded</h5>
                    <p class="small text-secondary">Search a user above using email, username, or ID to begin analytics parsing.</p>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import { ref, reactive } from "vue"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import axios from "axios"

export default {
    name: "ProUserReport",
    layout: MainAdminLayout,

    setup() {
        const filters = reactive({
            user_identifier: "",
            from_date: "",
            to_date: "",
            interval: "daily",
        })

        const loading = ref(false)
        const actionLoading = ref(false)
        const reportData = ref(null)

        const fetchReport = async () => {
            loading.value = true
            try {
                const res = await axios.post(route("admin.reports.pro-user-report.data"), filters)
                if (res.data.success) {
                    reportData.value = res.data
                } else {
                    alert(res.data.message || "Failed to load report data")
                }
            } catch (err) {
                alert("An error occurred during analytics fetching.")
            } finally {
                loading.value = false
            }
        }

        const formatCurrency = (val) => {
            if (val === undefined || val === null) return "0.00"
            return parseFloat(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
        }

        const confirmTeamAction = (actionType) => {
            if (!reportData.value) return
            const actionText = {
                stop_income: "STOP ALL INCOMES AND WITHDRAWALS",
                restart_income: "RESTART ALL INCOMES AND WITHDRAWALS",
                block_team: "SUSPEND LOGIN ACCESS",
                unblock_team: "RESTORE LOGIN ACCESS"
            }[actionType]

            const conf = confirm(
                `WARNING: You are about to ${actionText} recursively for all ${reportData.value.team.team_size} members of the team. Are you sure you want to proceed?`
            )

            if (conf) {
                executeTeamAction(actionType)
            }
        }

        const executeTeamAction = async (actionType) => {
            actionLoading.value = true
            try {
                const res = await axios.post(route("admin.reports.pro-user-report.action"), {
                    user_id: reportData.value.user.id,
                    action: actionType
                })
                alert(res.data.message || "Action processed successfully")
                fetchReport() // Refresh report data to see changes
            } catch (err) {
                alert("An error occurred during downline action modification.")
            } finally {
                actionLoading.value = false
            }
        }

        return {
            filters,
            loading,
            actionLoading,
            reportData,
            fetchReport,
            formatCurrency,
            confirmTeamAction,
        }
    }
}
</script>

<style>
@import '../../../../css/admin-custom.css';
</style>
