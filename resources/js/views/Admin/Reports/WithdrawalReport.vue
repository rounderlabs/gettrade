<script setup>
import { router, Link } from "@inertiajs/vue3"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"

defineOptions({ layout: MainAdminLayout })

/* ===============================
   PROPS FROM CONTROLLER
=============================== */
const props = defineProps({
    withdrawals: Object,
    filters: Object,
    totals: Object,
})

/* ===============================
   FILTER FORM STATE
=============================== */
const form = {
    username: props.filters.username ?? "",
    type: props.filters.type ?? "",
    status: props.filters.status ?? "",
    from_date: props.filters.from_date ?? "",
    to_date: props.filters.to_date ?? "",
}

/* ===============================
   APPLY FILTERS
=============================== */
function applyFilters() {
    router.get(route("admin.withdrawal.reports"), form, {
        preserveState: true,
        preserveScroll: true,
    })
}

/* ===============================
   RESET FILTERS
=============================== */
function resetFilters() {
    form.username = ""
    form.type = ""
    form.status = ""
    form.from_date = ""
    form.to_date = ""

    applyFilters()
}

/* ===============================
   CHANGE STATUS
=============================== */
function changeStatus(id, status) {
    let payload = { status }

    if (status === "success") {
        const txnId = prompt("Enter Transaction ID")
        if (!txnId) {
            alert("Transaction ID is required")
            return
        }
        payload.txn_id = txnId
    }

    if (!confirm(`Change status to "${status}"?`)) return

    router.post(route("admin.withdrawal.update.status", id), payload, {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <div class="container-fluid">

        <!-- ================= FILTER CARD ================= -->
        <div class="card">
            <div class="card-body row g-2 align-items-end">

                <!-- Username -->
                <div class="col-md-2">
                    <label>Username</label>
                    <input
                        v-model="form.username"
                        class="form-control"
                        placeholder="Search username"
                    />
                </div>

                <!-- From Date -->
                <div class="col-md-2">
                    <label>From</label>
                    <input v-model="form.from_date" type="date" class="form-control" />
                </div>

                <!-- To Date -->
                <div class="col-md-2">
                    <label>To</label>
                    <input v-model="form.to_date" type="date" class="form-control" />
                </div>

                <!-- Type -->
                <div class="col-md-2">
                    <label>Type</label>
                    <select v-model="form.type" class="form-control">
                        <option value="">All</option>
                        <option value="level">Level</option>
                        <option value="dividend">Dividend</option>
                        <option value="maturity">Maturity</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label>Status</label>
                    <select v-model="form.status" class="form-control">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-2 d-flex gap-2">
                    <button @click="applyFilters" class="btn btn-primary w-100">
                        Filter
                    </button>

                    <button @click="resetFilters" class="btn btn-secondary w-100">
                        Reset
                    </button>
                </div>

                <!-- Export -->
                <div class="col-md-2 mt-2">
                    <a
                        class="btn btn-success w-100"
                        target="_blank"
                        :href="route('admin.withdrawal.reports.export', form)"
                    >
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="card mt-3">
            <div class="card-body table-responsive p-0">

                <table class="table table-striped align-middle">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Fees</th>
                        <th>Receivable</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Txn ID</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="w in withdrawals.data" :key="w.id">

                        <!-- User -->
                        <td>
                            <b>{{ w.user.username }}</b><br />
                            <small>{{ w.user.name }}</small>
                        </td>

                        <td>₹{{ w.amount }}</td>
                        <td>₹{{ w.fees }}</td>
                        <td>₹{{ w.receivable_amount }}</td>

                        <td class="text-capitalize">{{ w.type }}</td>

                        <!-- Status Dropdown -->
                        <td>
                            <select
                                class="form-select form-select-sm"
                                :class="{ 'bg-warning text-dark': w.status === 'pending', 'bg-info text-white': w.status === 'processing', 'bg-success text-white': w.status === 'success', 'bg-danger text-white': w.status === 'failed', }"
                                :value="w.status"
                                @change="changeStatus(w.id, $event.target.value)"
                            >
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                        </td>

                        <td>{{ w.txn_id ?? "-" }}</td>
                        <td>{{ w.created_at }}</td>

                        <td>
                            <button class="btn btn-sm btn-outline-primary">
                                View
                            </button>
                        </td>
                    </tr>
                    </tbody>

                    <!-- Totals -->
                    <tfoot>
                    <tr class="fw-bold bg-light">
                        <td>Total</td>
                        <td>₹{{ totals.total_amount }}</td>
                        <td>₹{{ totals.total_fees }}</td>
                        <td>₹{{ totals.total_receivable }}</td>
                        <td colspan="5"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <!-- ================= PAGINATION ================= -->
            <div class="card-footer">
                <ul class="pagination pagination-sm m-0 justify-content-end">
                    <li
                        v-for="link in withdrawals.links"
                        :key="link.label"
                        class="page-item"
                        :class="{ active: link.active, disabled: !link.url }"
                    >
                        <Link
                            v-if="link.url"
                            class="page-link"
                            :href="link.url"
                            preserve-scroll
                            preserve-state
                            v-html="link.label"
                        />
                        <span v-else class="page-link" v-html="link.label"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
