<script setup>
import {router} from "@inertiajs/vue3";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

defineOptions({layout: MainAdminLayout});

const props = defineProps({
    withdrawals: Object,
    filters: Object,
    totals: Object,
});

const form = {
    username: props.filters.username || "",
    type: props.filters.type || "",
    status: props.filters.status || "",
    from_date: props.filters.from_date || "",
    to_date: props.filters.to_date || "",
};

const applyFilters = () => {
    router.get(route("admin.withdrawal.reports"), form, {
        preserveState: true,
        preserveScroll: true,
    });
};
const changeStatus = (id, status) => {
    let payload = { status };

    if (status === 'success') {
        const txnId = prompt('Enter Transaction ID');
        if (!txnId) {
            alert('Transaction ID is required for success');
            router.reload({ preserveScroll: true });
            return;
        }
        payload.txn_id = txnId;
    }

    if (!confirm(`Change status to "${status}"?`)) {
        router.reload({ preserveScroll: true });
        return;
    }

    router.post(
        route('admin.withdrawal.update.status', id),
        payload,
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};


</script>

<template>
    <div class="container-fluid">

        <!-- Filters -->
        <div class="card">
            <div class="card-body row g-2">
                <input v-model="form.username" class="form-control col" placeholder="Username"/>
                <input v-model="form.from_date" class="form-control col" type="date"/>
                <input v-model="form.to_date" class="form-control col" type="date"/>

                <select v-model="form.type" class="form-control col">
                    <option value="">All Types</option>
                    <option value="level">Level</option>
                    <option value="dividend">Dividend</option>
                    <option value="maturity">Maturity</option>
                </select>

                <select v-model="form.status" class="form-control col">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                </select>

                <button class="btn btn-primary col" @click="applyFilters">
                    Filter
                </button>

                <a
                    :href="route('admin.withdrawal.reports.export', form)"
                    class="btn btn-success col" target="_blank"
                >
                    Export Excel
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="card mt-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User</th>

                        <th>Amount</th>
                        <th>Fees</th>
                        <th>Receivable</th>
                        <th>Type</th>
                        <th>Bank Details</th>
                        <th>Txn</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="w in withdrawals.data" :key="w.id">
                        <td>{{ w.user.username }} <br> {{w.user.name}}</td>

                        <td>₹{{ w.amount }}</td>
                        <td>₹{{ w.fees }}</td>
                        <td>₹{{ w.receivable_amount }}</td>
                        <td class="text-capitalize">{{ w.type }}</td>
                        <td>{{ w.bank_name }}<br>
                            {{ w.bank_ifsc }}<br>
                            {{ w.bank_account_no }}<br>
                            {{ w.upi_id }}<br>
                            {{ w.upi_number }}
                        </td>
                        <!-- Status Badge -->
                        <td>{{ w.txn_id ?? '-' }}</td>
                        <td>
                            <select
                                :class="{
            'bg-warning text-dark': w.status === 'pending',
            'bg-info text-white': w.status === 'processing',
            'bg-success text-white': w.status === 'success',
            'bg-danger text-white': w.status === 'failed',
        }"
                                :value="w.status"
                                class="form-select form-select-sm"
                                @change="changeStatus(w.id, $event.target.value)"
                            >
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                        </td>

                        <td>{{ w.created_at }}</td>

                        <!-- Admin Actions -->
                        <td>
                            <button class="btn btn-sm btn-outline-primary">
                                View
                            </button>
                        </td>
                    </tr>
                    </tbody>

                    <!-- Totals Footer -->
                    <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="2">Total</td>
                        <td>₹{{ totals.total_amount }}</td>
                        <td>₹{{ totals.total_fees }}</td>
                        <td>₹{{ totals.total_receivable }}</td>
                        <td colspan="4"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
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
                    <span
                        v-else
                        class="page-link"
                        v-html="link.label"
                    ></span>
                </li>
            </ul>
        </div>


    </div>
</template>
