<template>

    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">Add Fund Request Stats</h6>
                    <p class="text-secondary small">INR funds add request status</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"
                   data-show-toggle="true" style="">
                <thead>
                <tr class="text-muted footable-header">
                    <th>Sr Number</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Is Credited</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                </tr>
                </thead>
                <tbody>
<!--                <tr v-if="!ledgers.length" class="intro-x">-->
<!--                    <td colspan="7">-->
<!--                        <div class="text-center">-->
<!--                            <p>There is no record</p>-->
<!--                        </div>-->
<!--                    </td>-->


<!--                </tr>-->
                <tr v-for="(request,index) in inr_requests" class="intro-x">
                    <td class="">
                        {{ index + pageMeta.from }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        {{ request.currency }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        ₹ {{ parseFloat(request.amount).toFixed(2)}}
                    </td>
                    <td v-if="request.is_credited === 0" class="text-center">
                        Not Credited To INR Wallet
                    </td>
                    <td v-else class="text-center">
                        Credited To INR Wallet
                    </td>
                    <td class="table-report__action font-medium whitespace-nowrap">
                        {{ request.status }}
                    </td>
                    <td class="table-report__action font-medium whitespace-nowrap">
                        {{ request.created_at }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Paginator :base-url="route('wallet.get.add.fund.request')" @pageMeta="paginatorPageMeta"
                   @responseData="paginatorResponse"></Paginator>
    </div>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import EarningWidget from "@/components/EarningWidget";
import { ref } from "vue";
import Paginator from "@/components/xino/Paginator.vue";

export default {
    name: "AddFundRequest",
    components: { Paginator },
    layout: UserLayout,
    props: { },
    setup() {
        const inr_requests = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            inr_requests.value = data;
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data;
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, inr_requests
        };
    }
};
</script>

<style scoped>

</style>
