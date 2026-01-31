<template>
    <div>
        <!-- ================= HEADER ================= -->
        <section class="content-header">
            <div class="container-fluid">
                <h1>Scheduled Jobs</h1>
            </div>
        </section>

        <!-- ================= CONTENT ================= -->
        <section class="content">
            <div class="container-fluid">

                <!-- ================= CREATE JOB ================= -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-1"></i>
                            Create Scheduled Job
                        </h3>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <div class="row">

                                <!-- Job Name -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Job Name</label>
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            class="form-control"
                                            required
                                        />
                                    </div>
                                </div>

                                <!-- Command -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Command</label>
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
                                        <label>Schedule Type</label>
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
                                        <label>Run Date</label>
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
                                        <label>Run Time</label>
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
                                        <label>Days of Week</label>
                                        <div class="d-flex flex-wrap">
                                            <label
                                                v-for="day in weekDays"
                                                :key="day.value"
                                                class="mr-3"
                                            >
                                                <input
                                                    type="checkbox"
                                                    :value="day.value"
                                                    v-model="form.days_of_week"
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
                                        <label>Day of Month</label>
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
                                    <div class="form-group mt-4">
                                        <label>
                                            <input
                                                type="checkbox"
                                                v-model="form.skip_holidays"
                                            />
                                            Skip Holidays
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Save Job
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ================= JOB LIST ================= -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i>
                            Scheduled Jobs
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Command</th>
                                <th>Schedule</th>
                                <th>Next Run</th>
                                <th>Status</th>
                                <th width="220">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-if="jobsSafe.length === 0">
                                <td colspan="6" class="text-center text-muted">
                                    No scheduled jobs found
                                </td>
                            </tr>

                            <!-- 🚨 ABSOLUTELY SAFE LOOP -->
                            <template v-for="(job, index) in jobsSafe" :key="index">
                                <tr>
                                    <td>{{ job?.name ?? '-' }}</td>

                                    <td>
                                        <code>{{ job?.command ?? '-' }}</code>
                                    </td>

                                    <td>
                                            <span class="badge badge-info">
                                                {{ job?.schedule_type ?? '-' }}
                                            </span>
                                    </td>

                                    <td>
                                        {{ job?.next_run_at ?? '—' }}
                                    </td>

                                    <td>
                                            <span
                                                class="badge"
                                                :class="job?.is_active
                                                    ? 'badge-success'
                                                    : 'badge-secondary'"
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

                                    <td>
                                        <!-- Run Now -->
                                        <button
                                            class="btn btn-sm btn-info mr-1"
                                            title="Run Now"
                                            @click="job && runNow(job.id)"
                                        >
                                            <i class="fas fa-bolt"></i>
                                        </button>

                                        <button
                                            class="btn btn-sm btn-secondary mr-1"
                                            title="View Logs"
                                            @click="openLogs(job)"
                                        >
                                            <i class="fas fa-list"></i>
                                        </button>

                                        <!-- Retry -->
                                        <button
                                            v-if="job?.last_failed_at"
                                            class="btn btn-sm btn-warning mr-1"
                                            title="Retry"
                                            @click="job && runNow(job.id)"
                                        >
                                            <i class="fas fa-redo"></i>
                                        </button>

                                        <!-- Pause / Resume -->
                                        <button
                                            class="btn btn-sm"
                                            :class="job?.is_active
                                                    ? 'btn-danger'
                                                    : 'btn-success'"
                                            title="Pause / Resume"
                                            @click="job && toggle(job.id)"
                                        >
                                            <i
                                                class="fas"
                                                :class="job?.is_active
                                                        ? 'fa-pause'
                                                        : 'fa-play'"
                                            ></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
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
        style="display: block; background: rgba(0,0,0,0.5)"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Execution Logs — {{ logModal.job?.name ?? '' }}
                    </h5>
                    <button class="close" @click="closeLogs">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <table class="table table-striped mb-0">
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
                            <td colspan="5" class="text-center p-4">
                                Loading logs…
                            </td>
                        </tr>

                        <tr v-if="!logs.loading && logs.data.length === 0">
                            <td colspan="5" class="text-center text-muted">
                                No logs found
                            </td>
                        </tr>

                        <tr v-for="log in logs.data" :key="log.id">
                            <td>{{ log.ran_at }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ log.run_type }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge"
                                    :class="log.status === 'success'
                                        ? 'badge-success'
                                        : 'badge-danger'"
                                >
                                    {{ log.status }}
                                </span>
                            </td>
                            <td>{{ log.execution_time_ms ?? '—' }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ log.error ?? log.output ?? '-' }}
                                </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
import { ref, reactive, computed, watch, onMounted } from "vue"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm } from "@inertiajs/vue3"
import { Inertia } from "@inertiajs/inertia"
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

        /* ================= ULTRA SAFE COMPUTED ================= */
        const jobsSafe = computed(() => {
            if (!props.jobs || !Array.isArray(props.jobs.data)) {
                return []
            }
            return props.jobs.data.filter(
                j => j && typeof j === "object"
            )
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
            Inertia.patch(
                `/admin/scheduled/jobs/${id}/toggle`,
                {},
                { preserveScroll: true }
            )
        }

        const runNow = (id) => {
            if (!id) return
            Inertia.post(
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
