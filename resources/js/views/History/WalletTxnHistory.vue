<template>
    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">Wallet Transactions</h6>
                    <p class="text-secondary small">List Of All Wallet Transaction History</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"
                   data-show-toggle="true" style="">
                <thead>
                <tr class="text-muted footable-header">
                    <th>Sr Number</th>
                    <th>Txn Date</th>
                    <th>Amount</th>
                    <th>Last Amount</th>
                    <th>Summary</th>
                    <th>Status </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!history.length" class="intro-x">
                    <td colspan="5">
                        <div >

                            <p>There is no Record </p>

                        </div>
                    </td>


                </tr>
                <tr v-for="(txn,index) in history" :key="txn.id" class="intro-x">
                    <td class="">
                        {{ index + 1 }}
                    </td>

                    <td >{{ txn.txn_time }}</td>
                    <td >{{ txn.amount_in_usd }}</td>
                    <td >{{ txn.last_amount }}</td>
                    <td >{{ txn.summary }}</td>
                    <td >{{ txn.status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <Paginator :base-url="route('earnings.level.roi.income.stat')" @pageMeta="paginatorPageMeta"
                   @responseData="paginatorResponse"></Paginator>
    </div>
</template>

<script>
import Paginator from "@/components/xino/Paginator";
import {ref} from "vue";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "WalletTxnHistory",
    layout: UserLayout,
    components: {Paginator},
    setup() {
        const history = ref([]);

        function paginatorResponse(data) {
            history.value = data
        }

        return {
            history,
            paginatorResponse
        }

    }
}
</script>

<style scoped>

</style>
