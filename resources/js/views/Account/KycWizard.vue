<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    kyc: Object,
    withdraw_mode: String,
    withdraw_address: String,
});

/* Step Control */
const step = ref(props.kyc?.current_step ?? 1);

/* 🔥 Sync step after reload */
watch(
    () => props.kyc,
    (newKyc) => {
        if (newKyc?.current_step) {
            step.value = newKyc.current_step;
        }
    }
);

const progress = computed(() => (step.value / 3) * 100);

/* STEP 1 */
const aadhaarForm = useForm({
    aadhaar_number: props.kyc?.aadhaar_number ?? "",
    aadhaar_front: null,
    aadhaar_back: null,
});

function submitStep1() {
    aadhaarForm.post(route("kyc.step1"), {
        forceFormData: true,
        onSuccess: () => {
            step.value = 2;
        },
    });
}

/* STEP 2 */
const panForm = useForm({
    pan_number: props.kyc?.pan_number ?? "",
    pan_file: null,
});

watch(
    () => panForm.pan_number,
    (val) => {
        if (val) {
            panForm.pan_number = val.toUpperCase();
        }
    }
);

function submitStep2() {
    panForm.post(route("kyc.step2"), {
        forceFormData: true,
        onSuccess: () => {
            step.value = 3;
        },
    });
}

/* STEP 3 */
const bankForm = useForm({
    bank_name: props.kyc?.bank_name ?? "",
    ifsc_code: props.kyc?.ifsc_code ?? "",
    account_number: props.kyc?.account_number ?? "",
    cancel_cheque: null,
});

function submitStep3() {
    bankForm.post(route("kyc.step3"));
}
</script>

<template>
    <div class="auth-header">-->
        <a href="/dashboard">
            <svg class="feather feather-arrow-left back-btn"
                 fill="none" height="24"
                 stroke="currentColor"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 stroke-width="2"
                 viewBox="0 0 24 24"
                 width="24">
                <line x1="19" x2="5" y1="12" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>

        <img alt="v1" class="img-fluid img"
             src="/user-panel/assets-panel/assets/images/login1.svg">

        <div class="auth-content">
            <div>
                <h2>KYC Verification !!</h2>
                <h4 class="p-0">Fill up the form</h4>
            </div>
        </div>
    </div>
    <div class="custom-container auth-form">

        <!-- Progress -->
        <div class="mb-4">
            <div class="progress">
                <div class="progress-bar"
                     :style="{ width: progress + '%' }">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-1 mb-4 small">
            <span :class="{ 'fw-bold': step === 1 }" class="dark-text fs">Aadhaar</span>
            <span :class="{ 'fw-bold': step === 2 }" class="dark-text">PAN</span>
            <span :class="{ 'fw-bold': step === 3 }" class="dark-text">Bank</span>
        </div>

        <!-- STEP 1 -->
        <form v-if="step === 1" @submit.prevent="submitStep1">
            <input v-model="aadhaarForm.aadhaar_number"
                   class="form-control"
                   placeholder="Aadhaar Number"/>

            <input type="file"
                   class="form-control mt-2"
                   @change="e => aadhaarForm.aadhaar_front = e.target.files[0]"/>

            <input type="file"
                   class="form-control mt-2"
                   @change="e => aadhaarForm.aadhaar_back = e.target.files[0]"/>

            <button :disabled="aadhaarForm.processing"
                    class="btn theme-btn w-100 mt-3">
                Continue
            </button>
        </form>

        <!-- STEP 2 -->
        <form v-if="step === 2" @submit.prevent="submitStep2">
            <input
                v-model="panForm.pan_number"
                @input="panForm.pan_number = panForm.pan_number.toUpperCase()"
                class="form-control text-uppercase"
                maxlength="10"
                placeholder="ABCDE1234F"
            />

            <input type="file"
                   class="form-control mt-2"
                   @change="e => panForm.pan_file = e.target.files[0]"/>

            <button :disabled="panForm.processing"
                    class="btn theme-btn w-100 mt-3">
                Continue
            </button>
        </form>

        <!-- STEP 3 -->
        <form v-if="step === 3" @submit.prevent="submitStep3">
            <input v-model="bankForm.ifsc_code"
                   class="form-control"
                   placeholder="IFSC Code"/>

            <input v-model="bankForm.bank_name"
                   class="form-control mt-2"
                   placeholder="Bank Name"/>

            <input v-model="bankForm.account_number"
                   class="form-control mt-2"
                   placeholder="Account Number"/>

            <input type="file"
                   class="form-control mt-2"
                   @change="e => bankForm.cancel_cheque = e.target.files[0]"/>

            <button :disabled="bankForm.processing"
                    class="btn theme-btn w-100 mt-3">
                Submit KYC
            </button>
        </form>

    </div>
</template>

<!--<template>-->


<!--            <div class="auth-header">-->
<!--                <a href="/dashboard">-->
<!--                    <svg class="feather feather-arrow-left back-btn"-->
<!--                         fill="none" height="24"-->
<!--                         stroke="currentColor"-->
<!--                         stroke-linecap="round"-->
<!--                         stroke-linejoin="round"-->
<!--                         stroke-width="2"-->
<!--                         viewBox="0 0 24 24"-->
<!--                         width="24">-->
<!--                        <line x1="19" x2="5" y1="12" y2="12"></line>-->
<!--                        <polyline points="12 19 5 12 12 5"></polyline>-->
<!--                    </svg>-->
<!--                </a>-->

<!--                <img alt="v1" class="img-fluid img"-->
<!--                     src="/user-panel/assets-panel/assets/images/login1.svg">-->

<!--                <div class="auth-content">-->
<!--                    <div>-->
<!--                        <h2>KYC Verification !!</h2>-->
<!--                        <h4 class="p-0">Fill up the form</h4>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            <div class="custom-container auth-form ">-->
<!--                <div class="mb-5 mt-3">-->
<!--                    <div class="progress mt-2">-->
<!--                        <div-->
<!--                            :style="{ width: progress + '%' }"-->
<!--                            class="progress-bar"-->
<!--                            role="progressbar"-->
<!--                        ></div>-->
<!--                    </div>-->

<!--                    <div class="d-flex justify-content-between mt-2 small">-->
<!--                        <span :class="{ 'fw-bold': step === 1 }" class="dark-text fs">Aadhaar</span>-->
<!--                        <span :class="{ 'fw-bold': step === 2 }" class="dark-text">PAN</span>-->
<!--                        <span :class="{ 'fw-bold': step === 3 }" class="dark-text">Bank</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--                &lt;!&ndash; STEP 1 &ndash;&gt;-->
<!--                <form v-if="step === 1" @submit.prevent="submitStep1">-->
<!--                    <label class="dark-text">Aadhaar Number</label>-->
<!--                    <input v-model="aadhaarForm.aadhaar_number"-->
<!--                           class="form-control"/>-->

<!--                    <label class="dark-text mt-2">Aadhaar Front</label>-->
<!--                    <input type="file"-->
<!--                           class="form-control"-->
<!--                           @change="e => aadhaarForm.aadhaar_front = e.target.files[0]"/>-->

<!--                    <label class="dark-text mt-2">Aadhaar Back</label>-->
<!--                    <input type="file"-->
<!--                           class="form-control"-->
<!--                           @change="e => aadhaarForm.aadhaar_back = e.target.files[0]"/>-->

<!--                    <button class="btn theme-btn w-100 mt-3">-->
<!--                        Continue-->
<!--                    </button>-->
<!--                </form>-->

<!--                &lt;!&ndash; STEP 2 &ndash;&gt;-->
<!--                <form v-if="step === 2" @submit.prevent="submitStep2">-->
<!--                    <label class="dark-text">PAN Number</label>-->
<!--                    <input v-model="panForm.pan_number"-->
<!--                           class="form-control"/>-->

<!--                    <label class="dark-text mt-2">Upload PAN</label>-->
<!--                    <input type="file"-->
<!--                           class="form-control"-->
<!--                           @change="e => panForm.pan_file = e.target.files[0]"/>-->

<!--                    <button class="btn theme-btn w-100 mt-3">-->
<!--                        Continue-->
<!--                    </button>-->
<!--                </form>-->

<!--                &lt;!&ndash; STEP 3 INR &ndash;&gt;-->
<!--                <form v-if="step === 3 && withdraw_mode === 'INR'"-->
<!--                      @submit.prevent="submitStep3">-->

<!--                    <label class="dark-text">IFSC Code</label>-->
<!--                    <input v-model="bankForm.ifsc_code"-->
<!--                           class="form-control"/>-->

<!--                    <label class="dark-text mt-2">Bank Name</label>-->
<!--                    <input v-model="bankForm.bank_name"-->
<!--                           class="form-control"/>-->

<!--                    <label class="dark-text mt-2">Account Number</label>-->
<!--                    <input v-model="bankForm.account_number"-->
<!--                           class="form-control"/>-->

<!--                    <button class="btn theme-btn w-100 mt-3">-->
<!--                        Submit KYC-->
<!--                    </button>-->
<!--                </form>-->

<!--                &lt;!&ndash; STEP 3 CRYPTO &ndash;&gt;-->
<!--                &lt;!&ndash; STEP 3 CRYPTO &ndash;&gt;-->
<!--                &lt;!&ndash; STEP 3 CRYPTO &ndash;&gt;-->
<!--                <div v-if="step === 3 && withdraw_mode === 'CRYPTO'">-->

<!--                    &lt;!&ndash; Wallet Already Exists &ndash;&gt;-->
<!--                    <div v-if="walletExists && !otpSent" class="card-box">-->
<!--                        <div class="card-details text-center">-->
<!--                            <h5>Withdrawal Wallet Address</h5>-->
<!--                            <h6 class="dark-text mt-2">{{ walletForm.address }}</h6>-->

<!--                            <button-->
<!--                                class="btn btn-light w-100 mt-3"-->
<!--                                @click="walletExists = false"-->
<!--                            >-->
<!--                                Change Wallet Address-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    &lt;!&ndash; Warning &ndash;&gt;-->
<!--                    <div v-if="!walletExists" class="alert alert-warning small mb-3">-->
<!--                        ⚠ Only BEP20 wallet addresses are supported.-->
<!--                    </div>-->

<!--                    &lt;!&ndash; STEP 1: ENTER ADDRESS &ndash;&gt;-->
<!--                    <form-->
<!--                        v-if="!otpSent && !walletExists"-->
<!--                        @submit.prevent="sendWalletOtp"-->
<!--                    >-->
<!--                        <div class="form-group">-->
<!--                            <label class="form-label dark-text">Enter BEP20 Wallet Address</label>-->

<!--                            <input-->
<!--                                v-model="walletForm.address"-->
<!--                                type="text"-->
<!--                                class="form-control"-->
<!--                                placeholder="0x..."-->
<!--                                required-->
<!--                            />-->
<!--                        </div>-->

<!--                        <button-->
<!--                            class="btn theme-btn w-100 mt-3"-->
<!--                            type="submit"-->
<!--                            :disabled="sending || !isValidAddress"-->
<!--                        >-->
<!--                            Send OTP-->
<!--                        </button>-->

<!--                        <small-->
<!--                            v-if="walletForm.address && !isValidAddress"-->
<!--                            class="text-danger"-->
<!--                        >-->
<!--                            Address must start with 0x and be 42 characters long.-->
<!--                        </small>-->
<!--                    </form>-->

<!--                    &lt;!&ndash; STEP 2: OTP VERIFY &ndash;&gt;-->
<!--                    <form-->
<!--                        v-if="otpSent"-->
<!--                        @submit.prevent="verifyWallet"-->
<!--                    >-->
<!--                        <div class="form-group">-->
<!--                            <label class="form-label dark-text">Wallet Address</label>-->
<!--                            <input-->
<!--                                v-model="walletForm.address"-->
<!--                                class="form-control"-->
<!--                                disabled-->
<!--                            />-->
<!--                        </div>-->

<!--                        <div class="form-group mt-3">-->
<!--                            <label class="form-label dark-text">Enter OTP</label>-->
<!--                            <input-->
<!--                                v-model="walletForm.otp"-->
<!--                                type="text"-->
<!--                                class="form-control"-->
<!--                                maxlength="6"-->
<!--                                required-->
<!--                            />-->
<!--                        </div>-->

<!--                        <div class="mt-2 small text-muted">-->
<!--            <span v-if="timer > 0">-->
<!--                Resend available in {{ timer }}s-->
<!--            </span>-->

<!--                            <button-->
<!--                                v-else-->
<!--                                type="button"-->
<!--                                class="btn btn-link p-0"-->
<!--                                @click="sendWalletOtp"-->
<!--                            >-->
<!--                                Resend OTP-->
<!--                            </button>-->
<!--                        </div>-->

<!--                        <div class="d-flex gap-2 mt-4">-->
<!--                            <button-->
<!--                                type="button"-->
<!--                                class="btn btn-light w-50"-->
<!--                                @click="otpSent = false"-->
<!--                            >-->
<!--                                Back-->
<!--                            </button>-->

<!--                            <button-->
<!--                                type="submit"-->
<!--                                class="btn theme-btn w-50"-->
<!--                                :disabled="saving"-->
<!--                            >-->
<!--                                Confirm-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </form>-->

<!--                </div>-->

<!--            </div>-->

<!--</template>-->
