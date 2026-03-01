<script setup>
import AdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({
    layout: AdminLayout,
    name: "AdminKycShow",
    components:{Link}
});

const props = defineProps({
    kyc: Object,
    latestSubmission: Object,
    history: Array,
});

const rejectForm = useForm({
    rejection_reason: "",
});

/* Mask helper */
function mask(value, visible = 4) {
    if (!value) return "-";
    return "*".repeat(value.length - visible) + value.slice(-visible);
}

/* ================= FILE PREVIEW ================= */

const previewUrl = ref(null);
const previewType = ref(null); // image | pdf

function openFile(field) {
    if (!props.latestSubmission) return;

    const path = props.latestSubmission[field];

    if (!path) return;

    previewUrl.value = route("admin.kyc.file", {
        submission: props.latestSubmission.id,
        field: field,
    });

    if (path.toLowerCase().endsWith(".pdf")) {
        previewType.value = "pdf";
    } else {
        previewType.value = "image";
    }

    const modal = new bootstrap.Modal(
        document.getElementById("previewModal")
    );
    modal.show();
}
</script>

<template>
    <div class="container-fluid">

        <!-- Header -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">KYC Review</h3>

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
                        <td v-if="latestSubmission">
                            <button
                                class="btn btn-sm btn-outline-primary me-2"
                                @click="openFile('aadhaar_front')"
                            >
                                Front
                            </button>

                            <button
                                class="btn btn-sm btn-outline-primary"
                                @click="openFile('aadhaar_back')"
                            >
                                Back
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>PAN File</th>
                        <td v-if="latestSubmission">
                            <button
                                class="btn btn-sm btn-outline-primary"
                                @click="openFile('pan_file')"
                            >
                                View PAN
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bank Details -->
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
                        <td>{{ mask(kyc.account_number) }}</td>
                    </tr>
                    <tr>
                        <th>Cancelled Cheque</th>
                        <td v-if="latestSubmission">
                            <button
                                class="btn btn-sm btn-outline-primary"
                                @click="openFile('cancel_cheque')"
                            >
                                View Cheque
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mb-3">

            <div class="card-header">
                <h5>Verification Action</h5>
            </div>

            <div class="card-body">

                <!-- If Submitted -->
                <template v-if="kyc.status === 'submitted'">

                    <div class="alert alert-warning">
                        This KYC is pending review. Please verify documents carefully.
                    </div>

                    <Link
                        method="post"
                        as="button"
                        :href="route('admin.kyc.approve', kyc.id)"
                        class="btn btn-success me-2"
                    >
                        Approve KYC
                    </Link>

                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal"
                    >
                        Reject KYC
                    </button>

                </template>

                <!-- If Approved -->
                <template v-else-if="kyc.status === 'approved'">

                    <div class="alert alert-success">
                        ✅ This KYC has already been approved.
                    </div>

                </template>

                <!-- If Rejected -->
                <template v-else-if="kyc.status === 'rejected'">

                    <div class="alert alert-danger">
                        ❌ This KYC was rejected.
                        <br>
                        <strong>Reason:</strong> {{ kyc.rejection_reason }}
                    </div>

                </template>

            </div>
        </div>

        <!-- History -->
        <div class="card">
            <div class="card-header">
                <h5>KYC Submission History</h5>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-bordered mb-0">
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

        <!-- Preview Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Document Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">

                        <img
                            v-if="previewType === 'image'"
                            :src="previewUrl"
                            class="img-fluid"
                            style="max-height: 70vh;"
                        />

                        <iframe
                            v-if="previewType === 'pdf'"
                            :src="previewUrl"
                            width="100%"
                            height="600px"
                        ></iframe>

                    </div>

                </div>
            </div>
        </div>

    </div>
</template>
