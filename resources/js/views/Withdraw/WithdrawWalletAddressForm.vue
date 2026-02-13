<template>
    <section class="section-b-space">
        <div class="custom-container">

            <!-- Title -->
            <div class="title mb-3">
                <h2>USDT Withdrawal Wallet</h2>


            <p v-if="!walletExists" class="text-light">
                Setup your BEP20 (BNB Chain) wallet address for crypto withdrawals.
            </p>
                <p v-if="walletExists" class="text-light">
                    Crypto withdrawals Will Get Transferred To Address.
                </p>
            </div>

            <!-- ================= ALREADY UPDATED WALLET ================= -->


                <div v-if="walletExists && !otpSent" class="card-box">
                    <div class="card-details">
                        <h5 class="fw-semibold text-center">Withdrawal Wallet Address</h5>
                        <h1 class="mt-2 text-white text-center">BEP20 USDT</h1>

                        <div class="amount-details text-center">
                            <img :src="`data:image/svg+xml;base64,${address_qr}`" alt="" class="p-2 m-2 bg-white">
                        </div>
                        <h6 class="text-center text-white fw-light">
                            {{form.address }}
                        </h6>
                        <div class="text-center">
                            <button
                                class="btn btn-light w-100 mt-4"
                                @click="walletExists = false"
                            >
                                Change Wallet Address
                            </button>
                        </div>


                    </div>
                </div>



            <!-- ================= WARNING ================= -->
            <div v-if="!walletExists" class="alert alert-warning small mb-3">
                ⚠ Only BEP20 wallet addresses are supported.
                Wrong network address may result in permanent loss.
            </div>

            <!-- ================= STEP 1: ENTER WALLET ================= -->
            <form
                v-if="!otpSent && !walletExists"
                class="auth-form"
                @submit.prevent="sendOtp"
            >
                <div class="form-group">
                    <label class="form-label">Enter BEP20 Wallet Address</label>

                    <input
                        v-model="form.address"
                        type="text"
                        class="form-control"
                        placeholder="0x..."
                        required
                    />
                </div>

                <button
                    class="btn theme-btn w-100 mt-3"
                    type="submit"
                    :disabled="sending || !isValidAddress"
                >
                    Send OTP to Email
                    <font-awesome-icon
                        v-if="sending"
                        icon="spinner"
                        spin
                        class="ms-2"
                    />
                </button>

                <small
                    v-if="form.address && !isValidAddress"
                    class="text-danger"
                >
                    Address must start with 0x and be 42 characters long.
                </small>
            </form>

            <!-- ================= STEP 2: OTP VERIFY ================= -->
            <form
                v-if="otpSent"
                class="auth-form"
                @submit.prevent="updateWallet"
            >
                <!-- Wallet -->
                <div class="form-group">
                    <label class="form-label">Wallet Address</label>
                    <input
                        v-model="form.address"
                        class="form-control"
                        disabled
                    />
                </div>

                <!-- OTP -->
                <div class="form-group mt-3">
                    <label class="form-label">Enter OTP</label>
                    <input
                        v-model="form.otp"
                        type="text"
                        class="form-control"
                        maxlength="6"
                        placeholder="6-digit OTP"
                        required
                    />
                </div>

                <!-- Timer -->
                <div class="mt-2 small text-muted">
                    <span v-if="timer > 0">
                        Resend available in {{ timer }}s
                    </span>

                    <button
                        v-else
                        type="button"
                        class="btn btn-link p-0"
                        @click="sendOtp"
                    >
                        Resend OTP
                    </button>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <button
                        type="button"
                        class="btn btn-light w-50"
                        @click="otpSent = false"
                    >
                        Back
                    </button>

                    <button
                        type="submit"
                        class="btn theme-btn w-50"
                        :disabled="saving"
                    >
                        Confirm
                        <font-awesome-icon
                            v-if="saving"
                            icon="spinner"
                            spin
                            class="ms-2"
                        />
                    </button>
                </div>
            </form>

        </div>
    </section>
</template>

<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { ref, computed } from "vue";
import axios from "axios";
import { toast } from "@/utils/toast";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineOptions({
    layout: UserLayout,
});

const props = defineProps({
    withdraw_address: String,
    address_qr: String,
});

/* Wallet Exists */
const walletExists = ref(!!props.withdraw_address);

/* QR */
const addressQr = props.address_qr;

/* Form */
const form = ref({
    address: props.withdraw_address ?? "",
    otp: "",
});

/* States */
const otpSent = ref(false);
const sending = ref(false);
const saving = ref(false);

/* Timer */
const timer = ref(0);
let interval = null;

/* Validation */
const isValidAddress = computed(() => {
    return (
        form.value.address.startsWith("0x") &&
        form.value.address.length === 42
    );
});

/* Timer Start */
function startTimer() {
    timer.value = 30;
    interval = setInterval(() => {
        timer.value--;
        if (timer.value <= 0) clearInterval(interval);
    }, 1000);
}

/* Send OTP */
async function sendOtp() {
    sending.value = true;

    try {
        await axios.post(route("withdraw.sendOtp"));

        otpSent.value = true;
        toast("OTP sent successfully", "success");

        startTimer();
    } catch {
        toast("Failed to send OTP", "danger");
    } finally {
        sending.value = false;
    }
}

/* Update Wallet */
async function updateWallet() {
    saving.value = true;

    try {
        await axios.post(route("withdraw.update.usdt.wallet"), {
            address: form.value.address,
            otp: form.value.otp,
        });

        toast("Wallet updated successfully", "success");

        otpSent.value = false;
        walletExists.value = true;
        form.value.otp = "";

        location.reload(); // reload to get QR updated

    } catch {
        toast("Invalid OTP or error occurred", "danger");
    } finally {
        saving.value = false;
    }
}
</script>

<style scoped>
.card-box .card-details {
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    padding: 25px 20px;
    border-radius: 15px;
}


.card-box .card-details .amount-details {
    display: -webkit-box;
    display: -ms-flexbox;
    display: block;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    margin-top: 50px;
}
</style>
