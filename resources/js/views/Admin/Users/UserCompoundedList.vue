<template>
    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">User Compounding List</h6>
                    <p class="text-secondary small">List Of all Compounding By User</p>
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
                    <th>Date</th>
                    <th>For Days</th>
                    <th>Compounding</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!all_compounding.length" class="intro-x">
                    <td colspan="5">
                        <div class="text-center">
                            <p>There is no record</p>
                        </div>
                    </td>
                </tr>
                <tr v-for="(compounding,index) in all_compounding" class="intro-x">
                    <td class="">
                        {{ index + pageMeta.from }}
                    </td>
                    <td class="font-medium whitespace-nowrap">
                        Name    : {{ compounding.user.name }} <br>
                        User ID : {{compounding.user.ref_code}} <br>
                        Email ID : {{compounding.user.email}} <br>
                    </td>
                    <td class="text-center">
                       From {{ compounding.start_date }} To {{ compounding.end_date }}
                    </td>

                    <td class="text-center">
                        {{ compounding.days_count }}
                    </td>

                    <td class="text-center">
                        $ {{ parseFloat(compounding.compounded_amount).toFixed(2) }}
                    </td>

                </tr>
                </tbody>
            </table>
        </div>
        <Paginator :base-url="route('admin.compounding.list.roa.get')" @pageMeta="paginatorPageMeta"
                   @responseData="paginatorResponse"></Paginator>
    </div>
</template>

<script>

import {ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import Paginator from "@/components/xino/Paginator.vue";
import { Link } from "@inertiajs/vue3";
export default {
    name: "UserCompoundedList",
    components: { Paginator, Link },
    layout: MainAdminLayout,
    setup() {
        const all_compounding = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            all_compounding.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, all_compounding,
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
