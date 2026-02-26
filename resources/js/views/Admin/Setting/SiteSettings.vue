<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import {useForm} from "@inertiajs/vue3"
import {ref} from "vue"

/* =========================
   LAYOUT
========================= */
defineOptions({
    layout: MainAdminLayout,
})

/* =========================
   PROPS
========================= */
const props = defineProps({
    settings: Object,
    groups: Object,
})

/* =========================
   STATE
========================= */
const activeTab = ref("general")
const MAX_LEVELS = 30

/* =========================
   GENERAL FORM
========================= */
const generalForm = useForm({
    site_name: props.settings.site_name ?? "",
    site_tagline: props.settings.site_tagline ?? "",
    referral_prefix: props.settings.referral_prefix ?? "",
})

/* =========================
   BRANDING FORM
========================= */
const brandingForm = useForm({
    logo_desktop: null,
    logo_mobile: null,
})

/* =========================
   COMMISSION FORM
========================= */
const commissionForm = useForm({
    direct_percent: props.settings.direct_percent ?? 0,
    level_percentages:
        props.settings.level_percentages?.length
            ? props.settings.level_percentages
            : [{level: 1, percent: 0}],
})

/* =========================
   COMMISSION HELPERS
========================= */
function addLevel() {
    if (commissionForm.level_percentages.length >= MAX_LEVELS) return

    commissionForm.level_percentages.push({
        level: commissionForm.level_percentages.length + 1,
        percent: 0,
    })
}

function removeLevel(index) {
    if (index === 0) return
    commissionForm.level_percentages.splice(index, 1)
    reindexLevels()
}

function reindexLevels() {
    commissionForm.level_percentages.forEach((item, index) => {
        item.level = index + 1
    })
}

const emailForm = useForm({
    email_from_name: props.settings.email_from_name ?? '',
    email_from_address: props.settings.email_from_address ?? '',
    email_footer_text: props.settings.email_footer_text ?? '',
    welcome_email_subject: props.settings.welcome_email_subject ?? '',
    welcome_email_enabled: props.settings.welcome_email_enabled === 1,
})

function saveEmailSettings() {
    emailForm.post(route('admin.site.settings.update.email'), {
        preserveScroll: true,
    })
}

/* =========================
   DEPOSIT SETTINGS FORM
========================= */
const depositForm = useForm({
    deposit_wallet_address: props.settings.deposit_wallet_address ?? '',
    deposit_network: props.settings.deposit_network ?? 'BEP20',
})

function saveDepositSettings() {
    depositForm.post(route('admin.site.settings.update.deposit'), {
        preserveScroll: true,
    })
}

/* =========================
   WITHDRAWAL SETTINGS FORM
========================= */
const withdrawalForm = useForm({
    withdrawal_wallet_address: props.settings.withdrawal_wallet_address ?? '',
    withdrawal_network: props.settings.withdrawal_network ?? 'BEP20',
    min_withdraw_amount: props.settings.min_withdraw_amount ?? 10,
    withdraw_fee_percent: props.settings.withdraw_fee_percent ?? 10,
})

function saveWithdrawalSettings() {
    withdrawalForm.post(route('admin.site.settings.update.withdrawal'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="card">
        <!-- ================= TABS ================= -->
        <div class="card-header p-0">
            <ul class="nav nav-tabs">
                <li
                    v-for="(label, key) in groups"
                    :key="key"
                    class="nav-item"
                >
                    <a
                        :class="{ active: activeTab === key }"
                        class="nav-link"
                        href="#"
                        @click.prevent="activeTab = key"
                    >
                        {{ label }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- ================= GENERAL TAB ================= -->
            <div v-if="activeTab === 'general'">
                <div class="form-group">
                    <label>Site Name</label>
                    <input
                        v-model="generalForm.site_name"
                        class="form-control"
                        type="text"
                    />
                    <div class="text-danger">
                        {{ generalForm.errors.site_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label>Tagline</label>
                    <input
                        v-model="generalForm.site_tagline"
                        class="form-control"
                        type="text"
                    />
                </div>
                <div class="form-group">
                    <label>Referral Code Prefix</label>
                    <input
                        v-model="generalForm.referral_prefix"
                        class="form-control text-uppercase"
                        maxlength="5"
                        placeholder="Leave empty for numeric only"
                        type="text"
                    />
                    <small class="text-muted">
                        Optional. Uppercase letters only (2–5 chars).
                        Leave empty to generate numeric referral codes.
                    </small>

                    <div class="text-danger">
                        {{ generalForm.errors.referral_prefix }}
                    </div>
                </div>

                <button
                    :disabled="generalForm.processing"
                    class="btn btn-primary"
                    @click="generalForm.post(route('admin.site.settings.update.general'), { preserveScroll: true })"
                >
                    Save General
                </button>
            </div>

            <!-- ================= BRANDING TAB ================= -->
            <div v-if="activeTab === 'branding'">
                <div class="form-group">
                    <label>Desktop Logo</label>
                    <input
                        class="form-control"
                        type="file"
                        @change="e => brandingForm.logo_desktop = e.target.files[0]"
                    />
                </div>

                <div class="form-group">
                    <label>Mobile Logo</label>
                    <input
                        class="form-control"
                        type="file"
                        @change="e => brandingForm.logo_mobile = e.target.files[0]"
                    />
                </div>

                <button
                    :disabled="brandingForm.processing"
                    class="btn btn-primary"
                    @click="brandingForm.post(route('admin.site.settings.update.branding'), { preserveScroll: true })"
                >
                    Save Branding
                </button>
            </div>

            <!-- ================= COMMISSION TAB ================= -->
            <div v-if="activeTab === 'commission'">
                <div class="form-group">
                    <label>Direct Commission (%)</label>
                    <input
                        v-model.number="commissionForm.direct_percent"
                        class="form-control"
                        max="100"
                        min="0"
                        type="number"
                    />
                </div>

                <hr/>

                <label class="mb-2">Level Wise Commission</label>

                <div
                    v-for="(item, index) in commissionForm.level_percentages"
                    :key="item.level"
                    class="d-flex align-items-center mb-2"
                >
                    <span class="badge badge-secondary mr-2">
                        Level {{ item.level }}
                    </span>

                    <input
                        v-model.number="item.percent"
                        class="form-control mr-2"
                        max="100"
                        min="0"
                        style="max-width: 120px"
                        type="number"
                    />

                    <button
                        v-if="index !== 0"
                        class="btn btn-danger btn-sm"
                        @click="removeLevel(index)"
                    >
                        Remove
                    </button>
                </div>

                <button
                    :disabled="commissionForm.level_percentages.length >= MAX_LEVELS"
                    class="btn btn-outline-primary btn-sm mt-2"
                    @click="addLevel"
                >
                    + Add Level
                </button>

                <small class="text-muted d-block mt-1">
                    Maximum {{ MAX_LEVELS }} levels allowed
                </small>

                <div class="text-danger mt-2">
                    {{ commissionForm.errors.level_percentages }}
                </div>

                <button
                    :disabled="commissionForm.processing"
                    class="btn btn-primary mt-3"
                    @click="commissionForm.post(route('admin.site.settings.update.commission'), { preserveScroll: true })"
                >
                    Save Commission
                </button>
            </div>

            <!-- ================= EMAIL TAB ================= -->
            <div v-if="activeTab === 'email'">

                <div class="form-group">
                    <label>From Name</label>
                    <input v-model="emailForm.email_from_name" class="form-control">
                </div>

                <div class="form-group">
                    <label>From Email</label>
                    <input v-model="emailForm.email_from_address" class="form-control">
                </div>

                <div class="form-group">
                    <label>Welcome Email Subject</label>
                    <input v-model="emailForm.welcome_email_subject" class="form-control">
                </div>

                <div class="form-group">
                    <label>Email Footer</label>
                    <textarea v-model="emailForm.email_footer_text" class="form-control"
                              rows="3"></textarea>
                </div>

                <div class="form-check mb-3">
                    <input v-model="emailForm.welcome_email_enabled" class="form-check-input"
                           type="checkbox">
                    <label class="form-check-label">Enable Welcome Email</label>
                </div>

                <button
                    :disabled="emailForm.processing"
                    class="btn btn-primary"
                    @click="saveEmailSettings">
                    Save Email Settings
                </button>

                <hr>

                <h5>Email Preview</h5>

                <iframe
                    :src="route('admin.site.settings.email.preview')"
                    style="width:100%;height:600px;border:1px solid #ddd;border-radius:6px;">
                </iframe>
                ```

            </div>

            <!-- ================= DEPOSIT TAB ================= -->
            <div v-if="activeTab === 'deposit'">

                <div class="form-group">
                    <label>Deposit Wallet Address</label>
                    <input v-model="depositForm.deposit_wallet_address"
                           class="form-control"
                           placeholder="Enter deposit wallet address">
                </div>

                <div class="form-group">
                    <label>Network</label>
                    <select v-model="depositForm.deposit_network"
                            class="form-control">
                        <option value="BEP20">BEP20</option>
                        <option value="ERC20">ERC20</option>
                        <option value="TRC20">TRC20</option>
                    </select>
                </div>

                <button
                    :disabled="depositForm.processing"
                    class="btn btn-primary"
                    @click="saveDepositSettings">
                    Save Deposit Settings
                </button>
            </div>

            <!-- ================= WITHDRAWAL TAB ================= -->
<!--            <div v-if="activeTab === 'withdrawal'">-->

<!--                <div class="form-group">-->
<!--                    <label>Withdrawal Wallet Address</label>-->
<!--                    <input v-model="withdrawalForm.withdrawal_wallet_address"-->
<!--                           class="form-control"-->
<!--                           placeholder="Enter withdrawal hot wallet">-->
<!--                </div>-->

<!--                <div class="form-group">-->
<!--                    <label>Network</label>-->
<!--                    <select v-model="withdrawalForm.withdrawal_network"-->
<!--                            class="form-control">-->
<!--                        <option value="BEP20">BEP20</option>-->
<!--                        <option value="ERC20">ERC20</option>-->
<!--                        <option value="TRC20">TRC20</option>-->
<!--                    </select>-->
<!--                </div>-->

<!--                <div class="form-group">-->
<!--                    <label>Minimum Withdrawal Amount</label>-->
<!--                    <input v-model.number="withdrawalForm.min_withdraw_amount"-->
<!--                           type="number"-->
<!--                           class="form-control">-->
<!--                </div>-->

<!--                <div class="form-group">-->
<!--                    <label>Withdrawal Fee (%)</label>-->
<!--                    <input v-model.number="withdrawalForm.withdraw_fee_percent"-->
<!--                           type="number"-->
<!--                           class="form-control">-->
<!--                </div>-->

<!--                <button-->
<!--                    :disabled="withdrawalForm.processing"-->
<!--                    class="btn btn-primary"-->
<!--                    @click="saveWithdrawalSettings">-->
<!--                    Save Withdrawal Settings-->
<!--                </button>-->
<!--            </div>-->


        </div>
    </div>
</template>
