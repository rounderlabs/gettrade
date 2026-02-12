<template>
    <div class="row">
        <div class="col">
            <div class="card">

                <!-- FILTER FORM -->
                <div class="card-header">
                    <form @submit.prevent="applyFilter">
                        <div class="row">

                            <!-- FROM DATE -->
                            <div class="col-md-3 mb-3">
                                <label>From Date</label>
                                <Datepicker
                                    v-model="filters.from_date"
                                    class="form-control"
                                />
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-3 mb-3">
                                <label>To Date</label>
                                <Datepicker
                                    v-model="filters.to_date"
                                    class="form-control"
                                />
                            </div>

                            <!-- USER ID -->
                            <div class="col-md-3 mb-3">
                                <label>User ID</label>
                                <input
                                    v-model="filters.plan_id"
                                    class="form-control"
                                    placeholder="Enter User ID"
                                />
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-3 mt-4 d-flex gap-2">
                                <button class="btn btn-info btn-sm w-50">
                                    Filter
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm w-50"
                                    @click="resetFilter"
                                >
                                    Reset
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Max Limit</th>
                                <th>Earned</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-if="!subscriptions.length">
                                <td colspan="8">No Subscriptions Found</td>
                            </tr>

                            <tr v-for="(sub, index) in subscriptions" :key="sub.id">
                                <td>{{ index + pageMeta.from }}</td>

                                <td>
                                    <b>{{ sub.user.username }}</b><br>
                                    <small>{{ sub.user.name }}</small>
                                </td>

                                <td>{{ sub.plan.name }}</td>

                                <td>₹ {{ sub.amount }}</td>
                                <td>₹ {{ sub.max_income_limit }}</td>
                                <td>₹ {{ sub.earned_so_far }}</td>

                                <td>
                                    <span
                                        :class="sub.is_active ? 'text-success' : 'text-danger'"
                                    >
                                        {{ sub.is_active ? "Active" : "Inactive" }}
                                    </span>
                                </td>

                                <td>{{ sub.created_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATOR -->
                    <AdminPaginator
                        :key="paginatorKey"
                        :base-url="baseUrl"
                        @responseData="paginatorResponse"
                        @pageMeta="paginatorMeta"
                    />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from "vue"
import Datepicker from "vue3-datepicker"
import AdminPaginator from "@/components/AdminPaginator.vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"

export default {
    name: "SubscriptionIndex",
    layout: MainAdminLayout,

    components: {
        Datepicker,
        AdminPaginator,
    },

    setup() {

        const subscriptions = ref([])
        const pageMeta = ref({ from: 1 })

        /* FILTER STATE */
        const filters = ref({
            plan_id: null,
            from_date: null,
            to_date: null,
        })

        /* Force paginator reload */
        const paginatorKey = ref(Date.now())

        /* Format Date */
        function formatDate(date) {
            if (!date) return "noDate"

            const tzOffset = date.getTimezoneOffset() * 60000
            return new Date(date - tzOffset).toISOString().split("T")[0]
        }

        /* Base URL */
        const baseUrl = computed(() => {
            const plan = filters.value.plan_id || "noId"
            const from = formatDate(filters.value.from_date)
            const to = formatDate(filters.value.to_date)

            return route("admin.subscriptions.get", [plan, from, to])
        })

        /* Apply Filter */
        function applyFilter() {
            paginatorKey.value = Date.now()
        }

        /* Reset Filter */
        function resetFilter() {
            filters.value.plan_id = null
            filters.value.from_date = null
            filters.value.to_date = null

            paginatorKey.value = Date.now()
        }

        /* Paginator Callbacks */
        function paginatorResponse(data) {
            subscriptions.value = data
        }

        function paginatorMeta(meta) {
            pageMeta.value = meta
        }

        return {
            subscriptions,
            pageMeta,
            filters,
            baseUrl,
            paginatorKey,
            applyFilter,
            resetFilter,
            paginatorResponse,
            paginatorMeta,
        }
    },
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
}
</style>
