<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Transfer Funds</h2>
            </div>

            <div class="news-update-box">
                <form class="auth-form mt-3" @submit.prevent="submitTransferForm">

                    <!-- USERNAME -->
                    <div class="form-group">
                        <label class="form-label">Enter User ID</label>
                        <div class="form-input">
                            <input
                                v-model="form.username"
                                class="form-control"
                                placeholder="Enter User ID"
                                type="text"
                                required
                                @blur="checkUser"
                            />
                        </div>
                    </div>

                    <!-- CONFIRM NAME -->
                    <div class="form-group">
                        <label class="form-label text-white">Confirm User Name</label>
                        <div class="form-input">
                            <input
                                class="form-control"
                                type="text"
                                :value="toUserName ?? ''"
                                readonly
                            />
                        </div>
                    </div>

                    <!-- AMOUNT -->
                    <div class="form-group">
                        <label class="form-label">
                            Enter Amount ({{ currencySymbol }})
                        </label>
                        <div class="form-input">
                            <input
                                v-model="form.amount"
                                class="form-control"
                                placeholder="20"
                                type="number"
                                min="20"
                                step="0.01"
                                required
                            />
                        </div>

                        <small class="text-white">
                            Available Balance:
                            {{ currencySymbol }} {{ user_usd_wallet.balance_display }}
                        </small>
                    </div>

                    <!-- SUBMIT -->
                    <button
                        class="btn theme-btn w-100"
                        :disabled="processing"
                        type="submit"
                    >
                        Transfer Now
                    </button>

                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue"
import { useForm, usePage } from "@inertiajs/vue3"
import { ref, computed } from "vue"
import { toast } from "@/utils/toast"
import axios from "axios"

defineOptions({ layout: UserLayout })

const props = defineProps({
    user_usd_wallet: Object,
})

const page = usePage()

/**
 * ------------------------------------------------
 * CURRENCY
 * ------------------------------------------------
 */
const currencySymbol = computed(() => {
    return page.props.currency?.symbol ?? "₹"
})

const walletBalance = computed(() => {
    return props.user_usd_wallet?.balance ?? "0.00"
})

/**
 * ------------------------------------------------
 * FORM
 * ------------------------------------------------
 */
const form = useForm({
    username: "",
    amount: "",
})

const processing = ref(false)
const toUserName = ref(null)

/**
 * ------------------------------------------------
 * CHECK USER
 * ------------------------------------------------
 */
function checkUser() {
    toUserName.value = null

    if (!form.username) return

    axios.post(route("validate.user"), {
        referral: form.username,
    })
        .then(res => {
            toUserName.value = res.data.sponsor_name
        })
        .catch(() => {
            toast("User not found", "danger")
        })
}

/**
 * ------------------------------------------------
 * SUBMIT
 * ------------------------------------------------
 */
function submitTransferForm() {
    if (!toUserName.value) {
        toast("Please verify user before transfer", "danger")
        return
    }

    if (Number(form.amount) > Number(walletBalance.value)) {
        toast("Insufficient wallet balance", "danger")
        return
    }

    processing.value = true

    form.post(route("wallet.fund.transfer.submit"), {
        preserveScroll: true,
        onFinish: () => processing.value = false,
        onError: errors => {
            Object.values(errors).forEach(msg =>
                toast(msg, "danger")
            )
        },
    })
}
</script>

<style scoped>
.news-update-box {
    background: transparent;
}
</style>
