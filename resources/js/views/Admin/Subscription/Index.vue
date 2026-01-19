<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <form @submit.prevent="filterSubmit">
                        <div class="row">
                            <div class="col-12 col-md-3 mb-3">
                                <label>From Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                    <datepicker v-model="date_from" class="form-control"
                                                placeholder="From Date"></datepicker>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label>To Date</label>
                                <div class="input-group"><span class="input-group-text"><i
                                    class="icofont icofont-calendar"></i></span>
                                    <datepicker v-model="date_to" class="form-control"
                                                placeholder="To Date"></datepicker>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">User Id</label>
                                    <div class="input-group">
                                                <span class="input-group-text"><i
                                                    class="icofont icofont-barcode icpfont-ban"></i>
                                                </span>
                                        <input v-model="filterId" class="form-control" placeholder="Enter 'Id'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex justify-content-between mt-4">
                                    <button class="btn btn-info btn-sm " type="submit">
                                        Filter
                                    </button>
                                    <Link :href="route('admin.subscriptions.get')" class="btn btn-primary btn-sm p-2">
                                        Reset Filter
                                    </Link>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0 text-md-nowrap text-center">
                            <thead>
                            <tr>
                                <th class="whitespace-nowrap">Sr.No.</th>
                                <th class="whitespace-nowrap">User</th>
                                <th class="whitespace-nowrap">Name</th>
                                <th class="whitespace-nowrap">Amount</th>
                                <th class="whitespace-nowrap">Max Limit</th>
                                <th class="whitespace-nowrap">Earned So Far</th>
                                <th class="whitespace-nowrap">Status</th>
                                <th class="whitespace-nowrap">Active Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!subscriptions.length" class="intro-x">
                                <td class="text-center" colspan="8">There is no subscriptions</td>

                            </tr>
                            <tr v-for="(subscription,index) in subscriptions" class="intro-x">
                                <td>{{ index + pageMeta.from }}</td>
                                <td>
                                    {{ subscription.user.id }}<br>
                                    <Link :href="route('admin.user.create',[subscription.user_id])">
                                        {{ subscription.user.name }}
                                    </Link>
                                    <br>
                                    {{ subscription.user.username }}
                                </td>
                                <td>
                                    {{ subscription.plan.name }}
                                </td>
                                <td>${{ subscription.amount }}</td>
                                <td>${{ subscription.max_income_limit }}</td>
                                <td>${{ subscription.earned_so_far }}</td>
                                <td :class="[subscription.is_active ? 'text-success': 'text-theme-21']">
                                    {{ subscription.is_active ? 'Enabled' : 'Disabled' }}
                                </td>
                                <td>{{ subscription.created_at }}</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <Paginator :key="newFilterId+date_from_new+date_to_new"
                               :base-url="route('admin.subscriptions.get',[newFilterId?newFilterId:'noId',date_from_new?date_from_new:'noDate',date_to_new?date_to_new:'noDate'])"
                               @pageMeta="paginatorPageMeta" @responseData="paginatorResponse"></Paginator>

<!--                    <Paginator :base-url="route('admin.subscriptions.get')" @pageMeta="paginatorPageMeta" @responseData="paginatorResponse"></Paginator>-->
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {ref} from "vue";
import Paginator from "@/components/xino/Paginator";
import {Link} from "@inertiajs/vue3";

import Datepicker from "vue3-datepicker";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "Index",
    layout: MainAdminLayout,
    components: {Paginator, Link, Datepicker},
    props: {
        date_from_val: String,
        date_to_val: String,
    },
    setup(props) {

        const date_from = ref()
        const date_to = ref()
        const filterId = ref()

        if (props.date_from_val !== 'nodate') {
            date_from.value = new Date(props.date_from_val)
        }
        if (props.date_to_val !== 'nodate') {
            date_to.value = new Date(props.date_to_val)
        }

        const date_from_new = ref(null)
        const date_to_new = ref(null)
        const newFilterId = ref(null)

        const formatDate = (date) => {
            const tzOffset = date.getTimezoneOffset() * 60 * 1000
            return new Date(date - tzOffset).toISOString().split('T')[0]
        }

        function filterSubmit() {
            if (date_from.value) {
                date_from_new.value = date_from.value ? formatDate(date_from.value) : ''
            }
            if (date_to.value) {
                date_to_new.value = date_to.value ? formatDate(date_to.value) : ''
            }
            if (filterId.value) {
                newFilterId.value = filterId.value
            }
        }


        const subscriptions = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            subscriptions.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            subscriptions, paginatorResponse, pageMeta, paginatorPageMeta,
            filterSubmit, formatDate, date_from_new, date_to_new, date_from, date_to, filterId, newFilterId
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
