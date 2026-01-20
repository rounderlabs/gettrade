<script setup>
import {computed, ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";

/* Props */
const props = defineProps({
    kyc: Object,
});

defineOptions({

    name: "KycWizard",
});

/* Resume step */
const step = ref(props.kyc?.current_step ?? 1);

/* ===============================
   STEP 1 – AADHAAR
================================ */
const aadhaarForm = useForm({
    aadhaar_number: props.kyc?.aadhaar_number ?? "",
    aadhaar_front: null,
    aadhaar_back: null,
});

function submitStep1() {
    aadhaarForm.post(route("kyc.step1"), {
        preserveScroll: true,
        onSuccess: () => (step.value = 2),
    });
}

/* ===============================
   STEP 2 – PAN
================================ */
const panForm = useForm({
    pan_number: props.kyc?.pan_number ?? "",
    pan_file: null,
});

function submitStep2() {
    panForm.post(route("kyc.step2"), {
        preserveScroll: true,
        onSuccess: () => (step.value = 3),
    });
}

/* ===============================
   STEP 3 – BANK
================================ */
const bankForm = useForm({
    bank_name: props.kyc?.bank_name ?? "",
    ifsc_code: props.kyc?.ifsc_code ?? "",
    account_number: props.kyc?.account_number ?? "",
    cancel_cheque: null,
});

function submitStep3() {
    bankForm.post(route("kyc.step3"));
}

/* IFSC Auto-Lookup */
const bankLookup = ref({
    bank: "",
    branch: "",
    address: "",
});

const bankLoading = ref(false);
const bankError = ref(null);

/* Watch IFSC */
watch(
    () => bankForm.ifsc_code,
    async (ifsc) => {
        bankError.value = null;
        bankLookup.value = {bank: "", branch: "", address: ""};

        if (!ifsc || ifsc.length !== 11) return;

        bankLoading.value = true;

        try {
            const res = await fetch(`https://ifsc.razorpay.com/${ifsc}`);
            if (!res.ok) throw new Error("Invalid IFSC");

            const data = await res.json();

            bankLookup.value = {
                bank: data.BANK,
                branch: data.BRANCH,
                address: data.ADDRESS,
            };

            bankForm.bank_name = data.BANK;
        } catch (e) {
            bankError.value = "Invalid IFSC code";
            bankForm.bank_name = "";
        } finally {
            bankLoading.value = false;
        }
    }
);

/* Helpers */
const isBankValid = computed(() => {
    return !!bankForm.bank_name && !bankError.value && !bankLoading.value;
});

const progress = computed(() => (step.value / 3) * 100);
</script>

<template>

    <div class="auth-header">
        <a href="/dashboard">
            <svg class="feather feather-arrow-left back-btn" fill="none" height="24" stroke="currentColor"
                 stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"
                 xmlns="http://www.w3.org/2000/svg">
                <line x1="19" x2="5" y1="12" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>

        <img alt="v1" class="img-fluid img" src="/user-panel/assets-panel/assets/images/login1.svg">
        <div class="auth-content">
            <div>
                <h2>KYC Verification !!</h2>
                <h4 class="p-0">Fill up the form</h4>
            </div>
        </div>
    </div>
    <!-- ================= STEP 1 – AADHAAR ================= -->
    <div class="custom-container auth-form">
        <div class=" mb-5 mt-3">
            <div class="progress mt-2">
                <div
                    :style="{ width: progress + '%' }"
                    class="progress-bar"
                    role="progressbar"
                ></div>
            </div>
            <div class="d-flex justify-content-between mt-2 small">
                <span :class="{ 'fw-bold': step === 1 }">Aadhaar</span>
                <span :class="{ 'fw-bold': step === 2 }">PAN</span>
                <span :class="{ 'fw-bold': step === 3 }">Bank</span>
            </div>
        </div>

        <form v-if="step === 1" @submit.prevent="submitStep1">

                <div class="form-group">
                    <label class="form-label">Aadhaar Number</label>
                    <input
                        v-model="aadhaarForm.aadhaar_number"
                        class="form-control"
                        maxlength="12"
                        type="text"
                    />
                    <div v-if="aadhaarForm.errors.aadhaar_number" class="text-danger small">
                        {{ aadhaarForm.errors.aadhaar_number }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Aadhaar Front</label>
                    <input
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="form-control"
                        type="file"
                        @change="e => aadhaarForm.aadhaar_front = e.target.files[0]"
                    />
                </div>

                <div class="form-group">
                    <label class="form-label">Aadhaar Back</label>
                    <input accept=".jpg,.jpeg,.png,.pdf" class="form-control" type="file"
                           @change="e => aadhaarForm.aadhaar_back = e.target.files[0]"/>
                </div>

                <button :disabled="aadhaarForm.processing" class="btn theme-btn w-100">
                    Save & Continue
                </button>

        </form>

        <!-- ================= STEP 2 – PAN ================= -->
        <form v-if="step === 2" @submit.prevent="submitStep2">

            <div class="form-group">
                <label class="form-label">PAN Number</label>
                <input
                    v-model="panForm.pan_number"
                    class="form-control text-uppercase"
                    maxlength="10"
                    type="text"
                    @input="panForm.pan_number = panForm.pan_number.toUpperCase()"
                />
                <div v-if="panForm.errors.pan_number" class="text-danger small">
                    {{ panForm.errors.pan_number }}
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">PAN File</label>
                <input
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="form-control"
                    type="file"
                    @change="e => panForm.pan_file = e.target.files[0]"
                />
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-secondary" type="button" @click="step = 1">
                    Back
                </button>
                <button :disabled="panForm.processing" class="btn theme-btn w-100">
                    Save & Continue
                </button>
            </div>
        </form>

        <!-- ================= STEP 3 – BANK ================= -->
        <form v-if="step === 3" @submit.prevent="submitStep3">

            <div class="form-group">
                <label class="form-label">IFSC Code</label>
                <input
                    v-model="bankForm.ifsc_code"
                    class="form-control text-uppercase"
                    maxlength="11"
                    placeholder="e.g. SBIN0000062"
                    type="text"
                    @input="bankForm.ifsc_code = bankForm.ifsc_code.toUpperCase()"
                />
                <small v-if="bankLoading" class="text-muted">
                    Verifying IFSC…
                </small>
                <small v-if="bankError" class="text-danger">
                    {{ bankError }}
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Bank Name</label>
                <input
                    v-model="bankForm.bank_name"
                    class="form-control"
                    readonly
                    type="text"
                />
                <small class="text-muted">Auto-filled from IFSC</small>
            </div>

            <div v-if="bankLookup.branch" class="form-group">
                <label class="form-label">Branch</label>
                <input
                    :value="bankLookup.branch"
                    class="form-control"
                    readonly
                    type="text"
                />
            </div>

            <div class="form-group">
                <label class="form-label">Account Number</label>
                <input
                    v-model="bankForm.account_number"
                    class="form-control"
                    type="text"
                />
            </div>

            <div class="form-group">
                <label class="form-label">Cancelled Cheque</label>
                <input
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="form-control"
                    type="file"
                    @change="e => bankForm.cancel_cheque = e.target.files[0]"
                />
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-secondary" type="button" @click="step = 2">
                    Back
                </button>
                <button :disabled="bankForm.processing || !isBankValid" class="btn theme-btn w-100">
                    Submit KYC
                </button>
            </div>
        </form>

    </div>
</template>
