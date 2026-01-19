<script setup>
import UserLayout from "../../layouts/UserLayouts/UserLayout.vue";
// import Link from "@inertiajs/vue3"

defineOptions({
    layout: UserLayout,
    name: "KycStatus",
});

const props = defineProps({
    kyc: Object
});

function mask(value, visible = 4) {
    if (!value) return '';
    return value.slice(0, visible) + '*'.repeat(value.length - visible);
}
</script>

<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">
                        <h4>KYC Status</h4>

                        <span
                            class="badge"
                            :class="{
                            'bg-warning': kyc.status === 'submitted',
                            'bg-success': kyc.status === 'approved',
                            'bg-danger': kyc.status === 'rejected'
                        }"
                        >
                        {{ kyc.status.toUpperCase() }}
                    </span>
                    </div>

                    <div class="card-body">

                        <!-- Rejection Reason -->
                        <div v-if="kyc.status === 'rejected'" class="alert alert-danger">
                            <strong>Reason:</strong> {{ kyc.rejection_reason }}
                        </div>

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Aadhaar Number</th>
                                <td>{{ mask(kyc.aadhaar_number, 4) }}</td>
                            </tr>
                            <tr>
                                <th>PAN Number</th>
                                <td>{{ mask(kyc.pan_number, 3) }}</td>
                            </tr>
                            <tr>
                                <th>Bank Name</th>
                                <td>{{ kyc.bank_name }}</td>
                            </tr>
                            <tr>
                                <th>IFSC Code</th>
                                <td>{{ kyc.ifsc_code }}</td>
                            </tr>
                            <tr>
                                <th>Account Number</th>
                                <td>{{ mask(kyc.account_number, 4) }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- Actions -->
                        <div class="mt-3">

                            <a
                                v-if="kyc.status === 'rejected'"
                                method="post"
                                as="button"
                                :href="route('kyc.resubmit')"
                                class="btn btn-warning"
                            >
                                Resubmit KYC
                            </a>

                            <div v-if="kyc.status === 'submitted'" class="alert alert-info">
                                Your KYC is under review. This usually takes 24–48 hours.
                            </div>

                            <div v-if="kyc.status === 'approved'" class="alert alert-success">
                                Your KYC is verified successfully 🎉
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
