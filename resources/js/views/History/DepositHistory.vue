<template>
    <div class="card bg-radial-gradient border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">Deposit History</h6>
                    <p class="text-secondary small">Fund Added To Activation Wallet History</p>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row text-muted text-center mx-auto mb-4">
                <div class="col-3">Date</div>
                <div class="col-3">Amount</div>
                <div class="col-4">Txn Hash</div>
                <div class="col-2">Status</div>
            </div>
            <div v-if="!history.length" class="row">
                <div class="col-12">
                    <div class="card tab-row">
                        <div class="text-center">
                            <p>There is no record</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="(txn,index) in history" :key="txn.id" class="row">
                <div class="col-12">
                    <div class="card tab-row">
                        <div class="row text-center">
                            <div class="col-3">{{ txn.txn_date }}</div>
                            <div class="col-3">$ {{ parseFloat(txn.amount).toFixed(2) }}</div>
                            <div v-if="txn.currency_symbol === 'bep20_usdt'" class="col-4">
                                <a :href="getExplorer(txn)" target="_blank">{{
                                        txn.deposit_transactionable.txn_in ?
                                            txn.deposit_transactionable.txn_in.substr(0, 5) + '....' + txn.deposit_transactionable.txn_in.slice(-5) : ''
                                    }}</a>
                            </div>
                            <div class="col-4" v-else>
                                <a :href="getExplorer(txn)" target="_blank">{{
                                        txn.deposit_transactionable.txn_id ?
                                            txn.deposit_transactionable.txn_id.substr(0, 5) + '....' + txn.deposit_transactionable.txn_id.slice(-5) : ''
                                    }}</a>
                            </div>
                            <div class="col-2">
                                <span v-if="txn.deposit_transactionable.txn_in"
                                    class="badge badge-sm bg-green">Success
                                </span>
                                <span v-else class="badge badge-sm bg-red">Failed

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="card-body p-0">-->
<!--            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"-->
<!--                   data-show-toggle="true" style="">-->
<!--                <thead>-->
<!--                <tr class="text-muted footable-header">-->
<!--                    <th>Sr Number</th>-->
<!--                    <th>Txn Date</th>-->
<!--                    <th>Txn ID</th>-->
<!--                    <th>Amount</th>-->
<!--                    <th>Amount </th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                <tr v-if="!history.length" class="intro-x">-->
<!--                    <td colspan="5">-->
<!--                        <div class="text-center">-->

<!--                            <p>There is no Record </p>-->

<!--                        </div>-->
<!--                    </td>-->


<!--                </tr>-->
<!--                <tr v-for="(txn,index) in history" :key="txn.id" class="intro-x">-->
<!--                    <td class="">-->
<!--                        {{ index + pageMeta.from }}-->
<!--                    </td>-->

<!--                    <td class="text-center">-->
<!--                        {{ txn.txn_date }}-->
<!--                    </td>-->
<!--                    <td v-if="txn.crypto === 'bep20_usdt' || txn.crypto === 'bnb'">-->
<!--                        <a :href="getExplorer(txn)" target="_blank">{{-->
<!--                                txn.deposit_transactionable.txn_in ?-->
<!--                                    txn.deposit_transactionable.txn_in.substr(0, 5) + '....' + txn.deposit_transactionable.txn_in.slice(-5) : ''-->
<!--                            }}</a>-->
<!--                    </td>-->
<!--                    <td class="text-center" v-else>-->
<!--                        <a :href="getExplorer(txn)" target="_blank">{{-->
<!--                                txn.deposit_transactionable.txn_id ?-->
<!--                                    txn.deposit_transactionable.txn_id.substr(0, 5) + '....' + txn.deposit_transactionable.txn_id.slice(-5) : ''-->
<!--                            }}</a>-->
<!--                    </td>-->
<!--                    <td class="text-center">-->
<!--                        {{ txn.amount_in_usd }}-->
<!--                    </td>-->
<!--                    <td class="text-center">-->
<!--                        {{ txn.amount }}-->
<!--                    </td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
    </div>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "DepositHistory",
    components: {  },
    layout: UserLayout,
    metaInfo: {title: 'Deposit History'},
    props: {
        history: Array,
    },
    methods: {
        getExplorer(txn) {
            if (txn.currency_symbol === 'bep20_usdt' || txn.currency_symbol === 'bnb') {
                return `//bscscan.com/tx/${txn.deposit_transactionable.txn_in}`
            } else {
                return `//bscscan.com/tx/${txn.deposit_transactionable.txn_in}`
            }
        },
        getCryptoPrice(txn) {
            if (txn.currency_symbol === 'bep20_usdt') {
                return parseFloat(txn.crypto_price).toFixed(2)
            } else {
                return parseFloat(txn.crypto_price).toFixed(2)
            }
        }
    }
}
</script>

<style scoped>
.tab-row {
    min-height: 70px;
    padding: 20px;
    margin-bottom: 10px;
}
</style>
