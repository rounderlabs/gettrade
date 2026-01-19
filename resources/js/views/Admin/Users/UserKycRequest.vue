<template>
    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">User Kyc Request</h6>
                    <p class="text-secondary small">List Of all Kyc Requested By User</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"
                   data-show-toggle="true" style="">
                <thead>
                <tr class="text-muted footable-header">
                    <th>Sr.no</th>
                    <th>User</th>
                    <th>Aadhar Number</th>
                    <th>PAN Number</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!kyc_requests.length" class="intro-x">
                    <td colspan="5">
                        <div class="text-center">
                            <p>There is no record</p>
                        </div>
                    </td>
                </tr>
                <tr v-for="(request,index) in kyc_requests" class="intro-x">
                    <td class="">
                        {{ index + pageMeta.from }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        Name    : {{ request.user.name }} <br>
                        User ID : {{request.user.ref_code}} <br>
                        System ID : {{request.user.id}}
                    </td>
                    <td class="text-center">
                        {{ request.aadhar_no }}
                    </td>

                    <td class="text-center">
                        {{ request.pan_no }}
                    </td>

                    <td class="text-center">
                        {{ request.kyc_status }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        {{ request.created_at }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        <Link class="btn btn-theme" :href="route('admin.view.kyc.request', [request.id])">View</Link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Paginator :base-url="route('admin.get.user.kyc.request')" @pageMeta="paginatorPageMeta"
                   @responseData="paginatorResponse"></Paginator>
    </div>
</template>

<script>

import {ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import Paginator from "@/components/xino/Paginator.vue";
import { Link } from "@inertiajs/vue3";
export default {
    name: "Index",
    components: { Paginator, Link },
    layout: MainAdminLayout,
    setup(props) {
        const kyc_requests = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            kyc_requests.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, kyc_requests,
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
