<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Wallet Service</h2>
            </div>
            <div class="row gy-3">
                <div class="col-3">
                    <Link :href="route('deposit.add.fund')">
                        <div class="service-box">
                            <svg class="feather feather-activity service-icon" fill="none" height="24" stroke="currentColor"
                                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                 width="24" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <h5 class="mt-2 text-center dark-text">Add Fund</h5>
                    </Link>
                </div>

                <div v-if="permissions.can_transfer" class="col-3">
                    <a :href="route('wallet.fund.transfer')">
                        <div class="service-box">
                            <svg class="feather feather-repeat categories-icon" fill="none" height="24" stroke="currentColor"
                                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                 width="24" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                        </div>

                        <h5 class="mt-2 text-center dark-text">Transfer</h5>
                    </a>
                </div>
                <div v-if="permissions.can_activate_downline" class="col-3">
                    <a :href="route('wallet.activate.user')">
                        <div class="service-box">
                            <svg class="feather feather-activity categories-icon" fill="none" height="24" stroke="currentColor"
                                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                 width="24" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                        </div>
                        <h5 class="mt-2 text-center dark-text">Activate</h5>
                    </a>
                </div>

                <div class="col-3">
                    <a :href="route('wallet.ledger')">
                        <div class="service-box">
                            <svg class="feather feather-file-text service-icon" fill="none" height="24" stroke="currentColor"
                                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                 width="24" xmlns="http://www.w3.org/2000/svg">
                                <rect height="14" rx="2" ry="2" width="20" x="2" y="3"></rect>
                                <line x1="8" x2="16" y1="21" y2="21"></line>
                                <line x1="12" x2="12" y1="17" y2="21"></line>
                            </svg>
                        </div>
                        <h5 class="mt-2 text-center dark-text">History</h5>
                    </a>
                </div>

            </div>
        </div>
    </section>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Wallet Balance</h2>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Investment Wallet</h3>
                        <h6>Current Balance</h6>
                        <h5 class="theme-color">
                            {{ currencySymbol }} {{ user_usd_wallet.balance_display }}
                        </h5>
                    </div>
                </div>

                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Withdrawal Wallet</h3>
                        <h6>Current Balance</h6>
                        <h5 class="theme-color">
                            {{ currencySymbol }} {{ user_income_wallet.balance_display }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Deposit History</h2>
            </div>

            <div v-if="!deposit_histories.length" class="transaction-box">
                <h5 class="light-text">No Deposit History Found</h5>
            </div>

            <div
                v-for="deposit in deposit_histories"
                :key="deposit.id"
                class="transaction-box"
            >
                <div class="transaction-details">
                    <h5>
                        {{ currencySymbol }} {{ deposit.amount_display }}
                    </h5>

                    <span
                        class="badge"
                        :class="statusBadgeClass(deposit.status)"
                    >
                        {{ deposit.status }}
                    </span>

                    <div class="light-text mt-1">
                        {{ deposit.payment_type }} • {{ deposit.created_at }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panel-space"></section>
</template>

<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue"
import { Link, usePage } from "@inertiajs/vue3"
import { computed } from "vue"

defineOptions({
    layout: UserLayout,
})

/**
 * --------------------------------------------------
 * PROPS (must be Objects, NOT Arrays)
 * --------------------------------------------------
 */
const props = defineProps({
    user_usd_wallet: {
        type: Object,
        required: true,
    },
    user_income_wallet: {
        type: Object,
        required: true,
    },
    deposit_histories: {
        type: Array,
        default: () => [],
    },
    display_currency: {
        type: String,
        default: "INR",
    },
    permissions: {
        type: Array,
        required: true,
    }
})

/**
 * --------------------------------------------------
 * PAGE GLOBALS (SAFE USAGE)
 * --------------------------------------------------
 */
const page = usePage()

/**
 * --------------------------------------------------
 * CURRENCY SYMBOL
 * Priority:
 * 1. page.props.currency.symbol (from backend)
 * 2. fallback by display_currency
 * --------------------------------------------------
 */
const currencySymbol = computed(() => {
    if (page.props.currency?.symbol) {
        return page.props.currency.symbol
    }

    return {
        INR: "₹",
        USD: "$",
        EUR: "€",
    }[props.display_currency] ?? "₹"
})

/**
 * --------------------------------------------------
 * STATUS BADGE CLASS
 * --------------------------------------------------
 */
function statusBadgeClass(status) {
    return {
        pending: "badge bg-warning",
        accepted: "badge bg-success",
        rejected: "badge bg-danger",
    }[status] ?? "badge bg-secondary"
}
</script>


<style scoped>



</style>
