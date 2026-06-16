<template>
    <div class="admin-container">
        <!-- ================= HEADER ================= -->
        <section class="content-header px-4 py-3">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-dark font-weight-bold mb-1">
                        <i class="fas fa-tasks text-primary mr-2"></i>Scheduled Jobs
                    </h1>
                    <p class="text-muted small mb-0">Monitor, schedule, and execute automated financial and ranking engines.</p>
                </div>
            </div>
        </section>

        <!-- ================= TELEMETRY STATS ================= -->
        <section class="content px-4 mb-4">
            <div class="container-fluid">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="telemetry-card">
                            <span class="text-muted small font-weight-bold uppercase">Total Tasks</span>
                            <h3 class="text-primary font-weight-bold mt-1 mb-0">{{ jobsSafe.length }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="telemetry-card">
                            <span class="text-muted small font-weight-bold uppercase">Active Engines</span>
                            <h3 class="text-success font-weight-bold mt-1 mb-0">
                                {{ jobsSafe.filter(j => j.is_active).length }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="telemetry-card">
                            <span class="text-muted small font-weight-bold uppercase">Allowed Commands</span>
                            <h3 class="text-warning font-weight-bold mt-1 mb-0">{{ commandsSafe.length }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="telemetry-card">
                            <span class="text-muted small font-weight-bold uppercase">System Status</span>
                            <h3 class="text-success font-weight-bold mt-1 mb-0">
                                <span class="pulse-indicator mr-2"></span>ONLINE
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= CONTENT ================= -->
        <section class="content px-4">
            <div class="container-fluid">

                <!-- ================= CREATE JOB ================= -->
                <div class="card card-white mb-4">
                    <div class="card-header border-bottom py-3">
                        <h3 class="card-title text-primary font-weight-bold mb-0">
                            <i class="fas fa-plus-circle mr-2"></i>Create Scheduled Job
                        </h3>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <div class="row">

                                <!-- Job Name -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-secondary">Job Name</label>
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            class="form-control"
                                            placeholder="e.g., Pay Monthly Salary"
                                            required
                                        />
                                    </div>
                                </div>

                                <!-- Command -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-secondary">Command</label>
                                        <select
                                            v-model="form.command"
                                            class="form-control"
                                            required
                                        >
                                            <option value="">Select Command</option>
                                            <option
                                                v-for="cmd in commandsSafe"
                                                :key="cmd"
                                                :value="cmd"
                                            >
                                                {{ cmd }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Schedule Type -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-secondary">Schedule Type</label>
                                        <select
                                            v-model="form.schedule_type"
                                            class="form-control"
                                        >
                                            <option value="once">One Time</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- One Time Date -->
                                <div
                                    class="col-md-3"
                                    v-if="form.schedule_type === 'once'"
                                >
                                    <div class="form-group">
                                        <label class="text-secondary">Run Date</label>
                                        <input
                                            type="date"
                                            v-model="form.run_date"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <!-- Run Time -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-secondary">Run Time</label>
                                        <input
                                            type="time"
                                            v-model="form.run_time"
                                            class="form-control"
                                            required
                                        />
                                    </div>
                                </div>

                                <!-- Weekly Days -->
                                <div
                                    class="col-md-6"
                                    v-if="form.schedule_type === 'weekly'"
                                >
                                    <div class="form-group">
                                        <label class="text-secondary d-block">Days of Week</label>
                                        <div class="d-flex flex-wrap mt-2">
                                            <label
                                                v-for="day in weekDays"
                                                :key="day.value"
                                                class="mr-3 text-secondary cursor-pointer"
                                            >
                                                <input
                                                    type="checkbox"
                                                    :value="day.value"
                                                    v-model="form.days_of_week"
                                                    class="mr-1"
                                                />
                                                {{ day.label }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Monthly -->
                                <div
                                    class="col-md-3"
                                    v-if="form.schedule_type === 'monthly'"
                                >
                                    <div class="form-group">
                                        <label class="text-secondary">Day of Month</label>
                                        <input
                                            type="number"
                                            min="1"
                                            max="31"
                                            v-model="form.day_of_month"
                                            class="form-control"
                                        />
                                    </div>
                                </div>

                                <!-- Skip Holidays -->
                                <div class="col-md-3">
                                    <div class="form-group mt-4 pt-2">
                                        <label class="text-secondary cursor-pointer">
                                            <input
                                                type="checkbox"
                                                v-model="form.skip_holidays"
                                                class="mr-2"
                                            />
                                            Skip Holidays
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-light py-3 text-right">
                            <button class="btn btn-primary px-4">
                                <i class="fas fa-save mr-2"></i>Save Engine
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ================= JOB LIST ================= -->
                <div class="card card-white">
                    <div class="card-header border-bottom py-3">
                        <h3 class="card-title text-primary font-weight-bold mb-0">
                            <i class="fas fa-tasks mr-2"></i>Active Schedules
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Command</th>
                                    <th>Schedule</th>
                                    <th>Next Run</th>
                                    <th>Status</th>
                                    <th width="220" class="text-right">Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-if="jobsSafe.length === 0">
                                    <td colspan="6" class="text-center text-muted py-5">
                                        No scheduled jobs configured
                                    </td>
                                </tr>

                                <tr v-for="(job, index) in jobsSafe" :key="index">
                                    <td class="font-weight-bold text-dark">{{ job?.name ?? '-' }}</td>

                                    <td>
                                        <code class="text-danger bg-transparent p-0">{{ job?.command ?? '-' }}</code>
                                    </td>

                                    <td>
                                        <span class="badge badge-info">
                                            {{ job?.schedule_type ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-secondary">{{ job?.next_run_at ?? '—' }}</span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge"
                                            :class="job?.is_active ? 'badge-success' : 'badge-secondary'"
                                        >
                                            {{ job?.is_active ? 'Active' : 'Paused' }}
                                        </span>

                                        <span
                                            v-if="job?.last_failed_at"
                                            class="badge badge-danger ml-1"
                                        >
                                            Failed
                                        </span>
                                    </td>

                                    <td class="text-right">
                                        <!-- Run Now -->
                                        <button
                                            class="btn btn-sm btn-warning mr-1"
                                            title="Run Now"
                                            @click="job && runNow(job.id)"
                                        >
                                            <i class="fas fa-bolt"></i>
                                        </button>

                                        <!-- Logs -->
                                        <button
                                            class="btn btn-sm btn-info mr-1 text-white"
                                            title="View Logs"
                                            @click="openLogs(job)"
                                        >
                                            <i class="fas fa-list"></i>
                                        </button>

                                        <!-- Retry -->
                                        <button
                                            v-if="job?.last_failed_at"
                                            class="btn btn-sm btn-outline-warning mr-1"
                                            title="Retry"
                                            @click="job && runNow(job.id)"
                                        >
                                            <i class="fas fa-redo"></i>
                                        </button>

                                        <!-- Pause / Resume -->
                                        <button
                                            class="btn btn-sm"
                                            :class="job?.is_active ? 'btn-danger' : 'btn-success'"
                                            title="Pause / Resume"
                                            @click="job && toggle(job.id)"
                                        >
                                            <i class="fas" :class="job?.is_active ? 'fa-pause' : 'fa-play'"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- ================= LOG VIEWER MODAL ================= -->
    <div
        class="modal fade show"
        tabindex="-1"
        role="dialog"
        v-if="logModal.open"
        style="display: block; background: rgba(0, 0, 0, 0.45);"
    >
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Execution Logs — {{ logModal.job?.name ?? '' }}
                    </h5>
                    <button class="close" @click="closeLogs">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Run At</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Time (ms)</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="logs.loading">
                                <td colspan="5" class="text-center py-5 text-primary">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading logs…
                                </td>
                            </tr>

                            <tr v-if="!logs.loading && logs.data.length === 0">
                                <td colspan="5" class="text-center text-muted py-5">
                                    No execution history found
                                </td>
                            </tr>

                            <tr v-for="log in logs.data" :key="log.id">
                                <td class="text-dark">{{ log.ran_at }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ log.run_type ?? 'Cron' }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge"
                                        :class="log.status === 'success' ? 'badge-success' : 'badge-danger'"
                                    >
                                        {{ log.status }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ log.execution_time_ms ?? '—' }}</td>
                                <td>
                                    <code class="text-secondary bg-transparent p-0">
                                        {{ log.error ?? log.output ?? '-' }}
                                    </code>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closeLogs">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, computed } from "vue"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm, router } from "@inertiajs/vue3"
import axios from "axios"

export default {
    name: "ScheduledJobsIndex",
    layout: MainAdminLayout,

    props: {
        jobs: {
            type: Object,
            default: () => ({ data: [] }),
        },
        commands: {
            type: Array,
            default: () => [],
        },
    },

    setup(props) {
        /* ================= FORM ================= */
        const form = useForm({
            name: "",
            command: "",
            schedule_type: "daily",
            run_date: "",
            run_time: "",
            days_of_week: [],
            day_of_month: null,
            skip_holidays: false,
        })

        /* ================= WEEK DAYS ================= */
        const weekDays = [
            { label: "Mon", value: 1 },
            { label: "Tue", value: 2 },
            { label: "Wed", value: 3 },
            { label: "Thu", value: 4 },
            { label: "Fri", value: 5 },
            { label: "Sat", value: 6 },
            { label: "Sun", value: 7 },
        ]

        /* ================= COMPUTED ================= */
        const jobsSafe = computed(() => {
            if (!props.jobs || !Array.isArray(props.jobs.data)) {
                return []
            }
            return props.jobs.data.filter(j => j && typeof j === "object")
        })

        const commandsSafe = computed(() =>
            Array.isArray(props.commands) ? props.commands : []
        )

        /* ================= ACTIONS ================= */
        const submit = () => {
            form.post(route("admin.scheduled.jobs.store"), {
                preserveScroll: true,
                onSuccess: () => form.reset(),
            })
        }

        const toggle = (id) => {
            if (!id) return
            router.patch(
                `/admin/scheduled/jobs/${id}/toggle`,
                {},
                { preserveScroll: true }
            )
        }

        const runNow = (id) => {
            if (!id) return
            router.post(
                route("admin.scheduled.jobs.run-now", id),
                {},
                { preserveScroll: true }
            )
        }

        const logModal = reactive({
            open: false,
            job: null,
        })

        const logs = reactive({
            loading: false,
            data: [],
        })

        const openLogs = async (job) => {
            logModal.open = true
            logModal.job = job
            logs.loading = true
            logs.data = []

            try {
                const res = await axios.get(
                    route("admin.scheduled.jobs.logs", job.id)
                )
                logs.data = res.data.data ?? []
            } finally {
                logs.loading = false
            }
        }

        const closeLogs = () => {
            logModal.open = false
            logModal.job = null
            logs.data = []
        }

        return {
            form,
            weekDays,
            jobsSafe,
            commandsSafe,
            submit,
            toggle,
            runNow,
            openLogs,
            closeLogs,
            logModal,
            logs,
        }
    },
}
</script>

<style scoped>
.admin-container {
    background-color: #f4f6f9;
    min-height: 100vh;
    padding-bottom: 2rem;
}
.card-white {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
}
.telemetry-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
}
.telemetry-card:hover {
    transform: translateY(-1px);
}
.pulse-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #28a745;
    border-radius: 50%;
    box-shadow: 0 0 8px #28a745;
    animation: pulse 1.6s infinite alternate;
}
@keyframes pulse {
    0% { transform: scale(0.9); opacity: 0.7; }
    100% { transform: scale(1.1); opacity: 1; box-shadow: 0 0 10px #28a745; }
}
</style>
