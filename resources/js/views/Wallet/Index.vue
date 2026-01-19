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

                <div class="col-3">
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
                <div class="col-3">
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
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">₹ {{ user_usd_wallet.balance }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Withdrawal Wallet</h3>
                        <h6>Current Balance</h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">₹ {{ user_income_wallet.balance }}</h5>
                        </div>
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

            <div class="row gy-3">
                <div v-if="!deposit_histories.length" class="col-12">
                    <div class="transaction-box">
                        <a href="#transaction-detail"  class="d-flex gap-3">

                            <div class="transaction-details">
                                <div class="d-flex justify-content-between">
                                    <h5 class="light-text">No Deposit History Found</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div v-for="deposit in deposit_histories" class="col-12">
                    <div class="transaction-box">
                        <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">

                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>₹ {{deposit.amount}}</h5>
                                    <h3 class="error-color">{{deposit.payment_type}}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5><span class="badge" :class="statusBadgeClass(deposit.status)">
                                        {{ formatStatus(deposit.status) }}
                                    </span></h5>
                                    <h5 class="light-text">{{deposit.created_at}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panel-space"></section>
</template>

<script>

import UserLayout from "@/layouts/UserLayouts/UserLayout";

import {toast} from "@/utils/toast";
import {Link} from "@inertiajs/vue3";

export default {
    name: "Index",
    components: {
        toast, Link
    },
    layout: UserLayout,
    props: {
        user_usd_wallet: Object,
        user_income_wallet: Object,
        deposit_histories: Array,
    },
    methods: {
        statusBadgeClass(status) {
            return {
                pending: 'badge bg-warning',
                accepted: 'badge bg-success',
                rejected: 'badge bg-danger'
            }[status] || 'badge bg-secondary';
        },
        formatStatus(status) {
            return status.charAt(0).toUpperCase() + status.slice(1);
        }
    }

};
</script>

<style scoped>



</style>
