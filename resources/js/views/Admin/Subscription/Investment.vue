<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Filters -->
                <div class="card-header">
                    <form @submit.prevent="filterSubmit">
                        <div class="row">
                            <!-- From Date -->
                            <div class="col-6 col-md-2">
                                <label>From Date</label>
                                <div class="input-group">
                                    <datepicker v-model="date_from" class="form-control" placeholder="From Date"/>
                                </div>
                            </div>

                            <!-- To Date -->
                            <div class="col-6 col-md-2">
                                <label>To Date</label>
                                <div class="input-group">

                                    <datepicker v-model="date_to" class="form-control" placeholder="To Date"/>
                                </div>
                            </div>

                            <!-- User ID -->
                            <div class="col-6 col-md-2">
                                <label class="form-label">User ID / Username</label>
                                <div class="input-group">
                                    <input v-model="filterId" class="form-control" placeholder="Enter ID or Username"/>
                                </div>
                            </div>

                            <!-- Guaranty Type -->
                            <div class="col-6 col-md-2">
                                <label class="form-label">Guaranty Type</label>
                                <select v-model="guaranty_type" class="form-select digits">
                                    <option value="all">All</option>
                                    <option value="land">Land</option>
                                    <option value="flat">Flat</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="agreement">Agreement</option>
                                    <option value="none">None</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label">Investment Type</label>
                                <select v-model="investment_type" class="form-select digits">
                                    <option value="all">All</option>
                                    <option value="paid">Paid</option>
                                    <option value="zero_pin">Zero Pin</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 col-md-2 d-flex align-items-end justify-content-between mt-2">
                                <button class="btn btn-info btn-sm" type="submit">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                                <Link :href="route('admin.investment.index')" class="btn btn-primary btn-sm p-2">
                                    Reset
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0 text-md-nowrap text-center">
                            <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>User</th>
                                <th>Guaranty</th>
                                <th>Investment Type</th>
                                <th>Investment</th>
                                <th>Passive Income</th>
                                <th>Passive Limit</th>
                                <th>Working Income</th>
                                <th>Working Limit</th>
                                <th>Total Income</th>
                                <th>Status</th>
                                <th>Active Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!investments.length" class="intro-x">
                                <td class="text-center" colspan="12">There is no Investment</td>
                            </tr>
                            <tr v-for="(investment, index) in investments" :key="investment.id" class="intro-x">
                                <td>{{ index + pageMeta.from }}</td>
                                <td>

                                    <Link :href="route('admin.user.create', [investment.user_id])">
                                        {{ investment.user.name }}
                                    </Link>
                                    <br/>
                                    {{ investment.user.username }}
                                </td>
                                <td>{{ investment.guaranty_type }}</td>
                                <td>{{
                                        investment.investment_type === 'paid' ? 'Paid' : investment.investment_type === 'zero_pin' ? 'Zero Pin' : 'None'
                                    }}</td>
                                <td>${{ investment.amount }}</td>
                                <td>${{ investment.passive_income }}</td>
                                <td>${{ investment.passive_income_limit }}</td>
                                <td>${{ investment.working_income }}</td>
                                <td>${{ investment.working_income_limit }}</td>
                                <td>${{ investment.earned_so_far }}</td>
                                <td :class="[investment.is_active === 1 ? 'text-success' : 'text-danger']">
                                    {{ investment.is_active === 1 ? 'Enabled' : 'Disabled' }}
                                </td>
                                <td>{{ investment.created_at }}</td>
                                <td>
                                    <Link class="btn btn-success btn-sm" :href="route('admin.investment.details', [investment.id])">
                                        <i class="bi bi-gear"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginator -->
                    <AdminPaginator
                        :key="randomKey"
                        :base-url="route('admin.investment.get', [
                            newFilterId ? newFilterId : 'noId',
                            date_from_new ? date_from_new : 'noDate',
                            date_to_new ? date_to_new : 'noDate',
                            guaranty_type ? guaranty_type : 'all',
                            investment_type ? investment_type : 'all'
                        ])"
                        @pageMeta="paginatorPageMeta"
                        @responseData="paginatorResponse"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
import Datepicker from "vue3-datepicker";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import AdminPaginator from "@/components/AdminPaginator.vue";

export default {
    name: "Investment",
    layout: MainAdminLayout,
    components: {AdminPaginator, Link, Datepicker},
    setup() {
        const date_from = ref(null);
        const date_to = ref(null);
        const filterId = ref("");
        const guaranty_type = ref("all");
        const investment_type = ref("all");

        const date_from_new = ref(null);
        const date_to_new = ref(null);
        const newFilterId = ref(null);
        const randomKey = ref(null);

        const formatDate = (date) => {
            const tzOffset = date.getTimezoneOffset() * 60 * 1000;
            return new Date(date - tzOffset).toISOString().split("T")[0];
        };

        function filterSubmit() {
            randomKey.value = Math.random();
            date_from_new.value = date_from.value ? formatDate(date_from.value) : null;
            date_to_new.value = date_to.value ? formatDate(date_to.value) : null;
            newFilterId.value = filterId.value ? filterId.value : null;
        }

        const investments = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(response) {
            if (!response) {
                console.warn("Paginator emitted empty response");
                investments.value = [];
                return;
            }

            if (Array.isArray(response)) {
                investments.value = response;
            } else if (response.data) {
                investments.value = response.data;
            } else if (response instanceof Object && response.current_page) {
                investments.value = response.data ?? [];
            } else {
                investments.value = [];
            }
        }


        function paginatorPageMeta(data) {
            pageMeta.value = data;
        }

        return {
            investments,
            paginatorResponse,
            paginatorPageMeta,
            filterSubmit,
            date_from,
            date_to,
            date_from_new,
            date_to_new,
            filterId,
            newFilterId,
            guaranty_type,
            investment_type,
            pageMeta,
            randomKey,
        };


    },


};
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
