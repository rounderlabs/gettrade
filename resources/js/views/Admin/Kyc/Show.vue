<script setup>
import AdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

defineOptions({
    layout: AdminLayout,
    name: "AdminKycShow",
});

const props = defineProps({
    kyc: Object,
    history: Array,
});

const rejectForm = useForm({
    rejection_reason: "",
});

function mask(value, visible = 4) {
    if (!value) return "-";
    return "*".repeat(value.length - visible) + value.slice(-visible);
}
</script>

<template>
    <div class="container-fluid">

        <!-- Header -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">KYC Review</h3>
                <span
                    class="badge"
                    :class="{
                        'bg-warning': kyc.status === 'submitted',
                        'bg-success': kyc.status === 'approved',
                        'bg-danger': kyc.status === 'rejected',
                    }"
                >
                    {{ kyc.status }}
                </span>
            </div>
        </div>

        <!-- User Info -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>User Information</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th width="200">Name</th>
                        <td>{{ kyc.user.name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ kyc.user.email }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Identity -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Identity Details</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th width="200">Aadhaar Number</th>
                        <td>{{ kyc.aadhaar_number }}</td>
                    </tr>
                    <tr>
                        <th>PAN Number</th>
                        <td>{{ kyc.pan_number }}</td>
                    </tr>
                    <tr>
                        <th>Aadhaar Files</th>
                        <td>
                            <Link
                                :href="route('admin.kyc.file', { path: kyc.aadhaar_front })"
                                class="btn btn-sm btn-outline-primary me-2"
                            >
                                Front
                            </Link>
                            <Link
                                :href="route('admin.kyc.file', { path: kyc.aadhaar_back })"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Back
                            </Link>
                        </td>
                    </tr>
                    <tr>
                        <th>PAN File</th>
                        <td>
                            <Link
                                :href="route('admin.kyc.file', { path: kyc.pan_file })"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View PAN
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bank -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Banking Details</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th width="200">Bank Name</th>
                        <td>{{ kyc.bank_name }}</td>
                    </tr>
                    <tr>
                        <th>IFSC Code</th>
                        <td>{{ kyc.ifsc_code }}</td>
                    </tr>
                    <tr>
                        <th>Account Number</th>
                        <td>{{ kyc.account_number }}</td>
                    </tr>
                    <tr>
                        <th>Cancelled Cheque</th>
                        <td>
                            <Link
                                :href="route('admin.kyc.file', { path: kyc.cancel_cheque })"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View Cheque
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mb-3" v-if="kyc.status === 'submitted'">
            <div class="card-body">
                <Link
                    method="post"
                    as="button"
                    :href="route('admin.kyc.approve', kyc.id)"
                    class="btn btn-success me-2"
                >
                    Approve
                </Link>

                <button
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectModal"
                >
                    Reject
                </button>
            </div>
        </div>

        <!-- History -->
        <div class="card">
            <div class="card-header">
                <h5>KYC Submission History</h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Rejection Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, i) in history" :key="row.id">
                        <td>{{ i + 1 }}</td>
                        <td>
                                <span
                                    class="badge"
                                    :class="{
                                        'bg-success': row.status === 'approved',
                                        'bg-danger': row.status === 'rejected',
                                        'bg-warning': row.status === 'submitted',
                                    }"
                                >
                                    {{ row.status }}
                                </span>
                        </td>
                        <td>{{ row.created_at }}</td>
                        <td>{{ row.rejection_reason ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <form
                    class="modal-content"
                    @submit.prevent="rejectForm.post(route('admin.kyc.reject', kyc.id))"
                >
                    <div class="modal-header">
                        <h5 class="modal-title">Reject KYC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <textarea
                            class="form-control"
                            rows="4"
                            placeholder="Enter rejection reason"
                            v-model="rejectForm.rejection_reason"
                        ></textarea>
                        <div class="text-danger mt-1" v-if="rejectForm.errors.rejection_reason">
                            {{ rejectForm.errors.rejection_reason }}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>
