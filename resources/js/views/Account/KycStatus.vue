<script setup>
import UserLayout from "../../layouts/UserLayouts/UserLayout.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({
    layout: UserLayout,
    name: "KycStatus",
});

const props = defineProps({
    kyc: Object,
    withdraw_mode: String,
});

/* Mask helper */
function mask(value, visible = 4) {
    if (!value) return '';
    return '*'.repeat(value.length - visible) + value.slice(-visible);
}
</script>

<template>
    <div class="container-fluid">

        <!-- Header Card -->
        <div class="col-12 mt-5 mb-3">
            <div class="transaction-box p-3">

                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="dark-text mb-0">KYC Status</h4>

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

                <div v-if="kyc.status === 'submitted'" class="dark-text mt-3">
                    Your KYC is under review. This usually takes 24–48 hours.
                </div>

                <div v-if="kyc.status === 'approved'" class="dark-text alert-success mt-3">
                    Your KYC is verified successfully 🎉
                    <div class="mt-2">
                        <Link
                            :href="route('withdraw.redirect')"
                            class="btn btn-success btn-sm"
                        >
                            Proceed to Withdrawal
                        </Link>
                    </div>
                </div>

                <div v-if="kyc.status === 'rejected'" class="alert alert-danger mt-3">
                    <strong>Reason:</strong> {{ kyc.rejection_reason }}
                </div>

            </div>
        </div>

        <!-- Aadhaar Card -->
        <div class="col-12 mb-3">
            <div class="transaction-box">
                <div class="d-flex gap-3 p-3 align-items-center">

                    <div class="transaction-image">
                        <img class="img-fluid transaction-icon"
                             src="/user-panel/assets-panel/assets/images/aadhar.png"
                             alt="aadhaar">
                    </div>

                    <div class="transaction-details w-100">
                        <div class="transaction-name d-flex justify-content-between">
                            <h5>Aadhaar Number</h5>
                            <h5 class="light-text">
                                {{ mask(kyc.aadhaar_number, 4) }}
                            </h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- PAN Card -->
        <div class="col-12 mb-3">
            <div class="transaction-box">
                <div class="d-flex gap-3 p-3 align-items-center">

                    <div class="transaction-image">
                        <img class="img-fluid transaction-icon"
                             src="/user-panel/assets-panel/assets/images/pan.png"
                             alt="pan">
                    </div>

                    <div class="transaction-details w-100">
                        <div class="transaction-name d-flex justify-content-between">
                            <h5>PAN Number</h5>
                            <h5 class="light-text">
                                {{ mask(kyc.pan_number, 3) }}
                            </h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- INR MODE DETAILS -->
        <template v-if="withdraw_mode === 'INR'">

            <div class="col-12 mb-3">
                <div class="transaction-box">
                    <div class="d-flex gap-3 p-3 align-items-center">

                        <div class="transaction-image">
                            <img class="img-fluid transaction-icon"
                                 src="/assets/images/svg/4.svg"
                                 alt="bank">
                        </div>

                        <div class="transaction-details w-100">
                            <div class="transaction-name d-flex justify-content-between">
                                <h5>Bank Name</h5>
                                <h5 class="light-text">
                                    {{ kyc.bank_name }}
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="transaction-box">
                    <div class="d-flex gap-3 p-3 align-items-center">

                        <div class="transaction-image">
                            <img class="img-fluid transaction-icon"
                                 src="/assets/images/svg/4.svg"
                                 alt="ifsc">
                        </div>

                        <div class="transaction-details w-100">
                            <div class="transaction-name d-flex justify-content-between">
                                <h5>IFSC Code</h5>
                                <h5 class="light-text">
                                    {{ kyc.ifsc_code }}
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="transaction-box">
                    <div class="d-flex gap-3 p-3 align-items-center">

                        <div class="transaction-image">
                            <img class="img-fluid transaction-icon"
                                 src="/assets/images/svg/4.svg"
                                 alt="account">
                        </div>

                        <div class="transaction-details w-100">
                            <div class="transaction-name d-flex justify-content-between">
                                <h5>Account Number</h5>
                                <h5 class="light-text">
                                    {{ mask(kyc.account_number, 4) }}
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </template>

        <!-- CRYPTO MODE -->
        <template v-if="withdraw_mode === 'CRYPTO'">

            <div class="col-12 mb-3">
                <div class="transaction-box">
                    <div class="d-flex gap-3 p-3 align-items-center">

                        <div class="transaction-image">
                            <img class="img-fluid transaction-icon"
                                 src="/assets/images/svg/4.svg"
                                 alt="wallet">
                        </div>

                        <div class="transaction-details w-100">
                            <div class="transaction-name d-flex justify-content-between">
                                <h5>Wallet Address</h5>
                                <h5 class="light-text">
                                    Saved in withdrawal settings
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </template>

        <!-- Resubmit Button -->
        <div v-if="kyc.status === 'rejected'" class="col-12 mt-2">
            <Link
                method="post"
                :href="route('kyc.resubmit')"
                as="button"
                class="btn btn-warning w-100"
            >
                Resubmit KYC
            </Link>
        </div>

    </div>
</template>
