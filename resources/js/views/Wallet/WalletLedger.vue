<template>

    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Wallet Ledger<br> History</h2>
                <div class="dropdown element-dropdown">
                    <a class="btn btn-outline-dark p-1 dropdown-toggle mt-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <VueFeather type="filter" />
                    </a>
                    <ul class="dropdown-menu" style="">
                        <li class="d-inline-block w-100">
                            <a class="dropdown-item" href="#" @click.prevent="applyFilter('')">
                                All
                            </a>
                        </li>
                        <li class="d-inline-block w-100">
                            <a class="dropdown-item" href="#" @click.prevent="applyFilter('Credit')">
                                Credit
                            </a>
                        </li>
                        <li class="d-inline-block w-100">
                            <a class="dropdown-item" href="#" @click.prevent="applyFilter('Debit')">
                                Debit
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div v-if="loading" class="row gy-3">
                <div class="col-12 text-center">
                    <div class="transaction-box">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading transactions...</p>
                    </div>
                </div>
            </div>

            <div v-if="!ledgers.length" class="row gy-3">
                <div class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <div class="transaction-details">
                                <div class="d-flex justify-content-between">
                                    <h5 class="light-text">No Transaction Found</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row gy-3">
                <div v-for="(ledger) in ledgers" class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <div class="transaction-image color5">
                                <img v-if="ledger.wallet_type === 'USDT Wallet'" alt="tether" class="img-fluid icon"
                                     src="/shuchack/assets-panel/assets/images/fund-wallet.svg">
                                <img v-if="ledger.wallet_type === 'Income Wallet'" alt="tether" class="img-fluid icon"
                                     src="/shuchack/assets-panel/assets/images/income-wallet.svg">
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5 v-if="ledger.wallet_type === 'USDT Wallet'">Fund Wallet</h5>
                                    <h5 v-if="ledger.wallet_type === 'Income Wallet'">Earning Wallet</h5>
                                    <h3 class="dark-text">₹ {{ parseFloat(ledger.amount).toFixed(2) }}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5 :class="{'error-color': ledger.txn_type === 'Debit','success-color': ledger.txn_type === 'Credit'}">
                                        {{ ledger.txn_type }}
                                    </h5>
                                    <h5 class="warning-color">{{ ledger.created_at }}</h5>
                                </div>
                            </div>
                        </a>
                        <p class="success-color mt-3 text-center">{{ledger.remarks}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <Paginator
        :key="baseUrl"
        :base-url="baseUrl"
        @pageMeta="paginatorPageMeta"
        @responseData="paginatorResponse">
    </Paginator>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { ref, computed } from "vue";
import Paginator from "@/components/xino/Paginator.vue";
import VueFeather from "vue-feather";

export default {
    name: "WalletLedger",
    components: { VueFeather, Paginator },
    layout: UserLayout,
    setup() {
        const ledgers = ref([]);
        const pageMeta = ref([]);
        const filterType = ref('');
        const loading = ref(false);
        function paginatorResponse(data) {
            ledgers.value = data;
            loading.value = false;
        }

        function paginatorPageMeta(meta) {
            pageMeta.value = meta;
        }

        function applyFilter(type = '') {
            filterType.value = type;
            ledgers.value = [];
            loading.value = true;
        }

        const baseUrl = computed(() => {
            let url = route('wallet.ledger.get');
            if (filterType.value) {
                url += `?type=${filterType.value}`;
            }
            return url;
        });

        return {
            ledgers,
            pageMeta,
            paginatorResponse,
            paginatorPageMeta,
            applyFilter,
            baseUrl,
            filterType,
            loading
        };
    }
};

</script>

<style scoped>
.tab-row {
    min-height: 70px;
    padding: 20px;
    margin-bottom: 10px;
}
</style>
