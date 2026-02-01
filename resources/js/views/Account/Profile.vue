<template>
    <section class="section-b-space">
        <div class="custom-container">

            <!-- ================= PROFILE HEADER ================= -->
            <div class="profile-section">
                <div class="profile-banner">
                    <div class="profile-image">
                        <img
                            class="img-fluid profile-pic"
                            src="/user-panel/assets-panel/assets/images/p3.png"
                            alt="profile"
                        />
                    </div>
                </div>

                <h2>{{ profile.full_name }}</h2>
                <h5>{{ profile.username }}</h5>

                <div class="kyc-status mt-2">
                    <span class="badge" :class="kycBadgeClass">
                        {{ kycLabel }}
                    </span>
                </div>
            </div>

            <!-- ================= PROFILE MENU ================= -->
            <ul class="profile-list">

                <li>
                    <a :href="route('account.details')" class="profile-box">
                        <div class="profile-img">
                            <i class="feather feather-user icon"></i>
                        </div>
                        <div class="profile-details">
                            <h4>My Account</h4>
                            <img class="arrow" src="/user-panel/assets-panel/assets/images/arrow.svg">
                        </div>
                    </a>
                </li>

                <li>
                    <Link :href="route('account.withdraw.wallet')" class="profile-box">
                        <div class="profile-img">
                            <i class="feather feather-credit-card icon"></i>
                        </div>
                        <div class="profile-details">
                            <h4>Withdraw Account</h4>
                            <img class="arrow" src="/user-panel/assets-panel/assets/images/arrow.svg">
                        </div>
                    </Link>
                </li>

                <li>
                    <Link :href="route('account.change.password')" class="profile-box">
                        <div class="profile-img">
                            <i class="feather feather-settings icon"></i>
                        </div>
                        <div class="profile-details">
                            <h4>Change Password</h4>
                            <img class="arrow" src="/user-panel/assets-panel/assets/images/arrow.svg">
                        </div>
                    </Link>
                </li>

                <!-- Currency Settings -->
                <li>
                    <a href="javascript:void(0)" class="profile-box" @click="openCurrencyModal">
                        <div class="profile-img">
                            <i class="feather feather-dollar-sign icon"></i>
                        </div>
                        <div class="profile-details">
                            <h4>Currency Settings</h4>
                            <img class="arrow" src="/user-panel/assets-panel/assets/images/arrow.svg">
                        </div>
                    </a>
                </li>

                <li>
                    <a :href="route('logout')" class="profile-box">
                        <div class="profile-img">
                            <i class="feather feather-log-out icon"></i>
                        </div>
                        <div class="profile-details">
                            <h4>Log Out</h4>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
    </section>

    <!-- ================= CURRENCY MODAL ================= -->
    <div v-if="showCurrencyModal" class="currency-modal">
        <div class="currency-modal-card">
            <h4 class="mb-3">Select Default Currency</h4>

            <select v-model="form.currency" class="form-control">
                <option
                    v-for="c in currencies"
                    :key="c.code"
                    :value="c.code"
                >
                    {{ c.code }} — {{ c.name }}
                </option>
            </select>

            <div class="mt-3">
                <button
                    class="btn btn-primary w-100"
                    :disabled="form.processing"
                    @click="saveCurrency"
                >
                    Save
                </button>
            </div>

            <button class="close-btn" @click="showCurrencyModal = false">×</button>
        </div>
    </div>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue"
import { Link, usePage, useForm } from "@inertiajs/vue3"
import { ref, computed } from "vue"

export default {
    name: "Profile",
    layout: UserLayout,
    components: { Link },

    props: {
        profile: Object,
        kyc: Object,
        currencies: {
            type: Array,
            default: () => [],
        },
    },

    setup(props) {
        const page = usePage()
        const showCurrencyModal = ref(false)

        // ✅ always read preferred currency safely
        const preferredCurrency = computed(
            () => page.props.currency?.preferred ?? "INR"
        )

        // ✅ useForm (THIS WAS THE MISSING PIECE)
        const form = useForm({
            currency: preferredCurrency.value,
        })

        const openCurrencyModal = () => {
            form.currency = preferredCurrency.value
            showCurrencyModal.value = true
        }

        const saveCurrency = () => {
            form.post(route("account.currency.update"), {
                preserveScroll: true,
                onSuccess: () => {
                    showCurrencyModal.value = false
                },
                onError: (errors) => {
                    console.error("Currency update error:", errors)
                },
            })
        }

        const kycLabel = computed(() => ({
            not_submitted: "KYC Not Submitted",
            pending: "KYC Pending",
            approved: "KYC Verified",
            rejected: "KYC Rejected",
        })[props.kyc?.status])

        const kycBadgeClass = computed(() => ({
            not_submitted: "bg-secondary",
            pending: "bg-warning",
            approved: "bg-success",
            rejected: "bg-danger",
        })[props.kyc?.status])

        return {
            showCurrencyModal,
            openCurrencyModal,
            form,
            saveCurrency,
            kycLabel,
            kycBadgeClass,
            currencies: props.currencies,
        }
    },
}
</script>

<style scoped>
.currency-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.currency-modal-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    width: 90%;
    max-width: 400px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 8px;
    right: 12px;
    border: none;
    background: none;
    font-size: 22px;
}
</style>
