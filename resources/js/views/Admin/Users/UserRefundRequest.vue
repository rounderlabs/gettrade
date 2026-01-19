<template>
    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">User Refund List</h6>
                    <p class="text-secondary small">List Of all Refund Requested By User</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"
                   data-show-toggle="true" style="">
                <thead>
                <tr class="text-muted footable-header">
                    <th>Sr.no</th>
                    <th>User Details</th>
                    <th>Request Date</th>
                    <th>Total Withdrawal Yet</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!refunds.length" class="intro-x">
                    <td colspan="5">
                        <div class="text-center">
                            <p>There is no record</p>
                        </div>
                    </td>
                </tr>
                <tr v-for="(refund,index) in refunds" class="intro-x">
                    <td class="">
                        {{ index + pageMeta.from }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        Name    : {{ refund.user.name }} <br>
                        User ID : {{refund.user.ref_code}} <br>
                        Email ID : {{refund.user.email}} <br>
                    </td>
                    <td class="text-center">
                        {{ refund.created_at }}
                    </td>

                    <td class="text-center">
                        $ {{ parseFloat(refund.net_withdrawal).toFixed(2) }}
                    </td>

                    <td class="text-center">
                         {{ refund.status }}
                    </td>
                    <td class="text-center">
                        <Link class="btn btn-lg btn-theme ">Approve</Link>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>
        <Paginator :base-url="route('admin.refund.request.get')" @pageMeta="paginatorPageMeta"
                   @responseData="paginatorResponse"></Paginator>
    </div>
</template>

<script>

import {ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import Paginator from "@/components/xino/Paginator.vue";
import { Link } from "@inertiajs/vue3";
export default {
    name: "UserRefundRequest",
    components: { Paginator, Link },
    layout: MainAdminLayout,
    setup() {
        const refunds = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            refunds.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, refunds,
        }
    },
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
