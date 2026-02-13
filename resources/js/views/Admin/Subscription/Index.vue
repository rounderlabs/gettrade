<template>
    <div class="row">
        <div class="col">
            <div class="card">

                <!-- FILTERS -->
                <div class="card-header">
                    <form @submit.prevent="applyFilter">
                        <div class="row">

                            <!-- PLAN DROPDOWN -->
                            <div class="col-md-3 mb-2">
                                <label>Plan</label>
                                <select v-model="filters.plan_id" class="form-control">
                                    <option value="">All Plans</option>
                                    <option v-for="p in plans"  :value="p.id">
                                        {{ p.name }} (₹{{ p.amount }})
                                    </option>
                                </select>
                            </div>

                            <!-- USERNAME SEARCH -->
                            <div class="col-md-3 mb-2">
                                <label>Username</label>
                                <input
                                    v-model="filters.username"
                                    class="form-control"
                                    placeholder="Search GTxxxx"
                                />
                            </div>

                            <!-- FROM DATE -->
                            <div class="col-md-2 mb-2">
                                <label>From</label>
                                <datepicker v-model="filters.from" class="form-control" />
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-2 mb-2">
                                <label>To</label>
                                <datepicker v-model="filters.to" class="form-control" />
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-2 mt-4 d-flex gap-2">
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
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-if="!subscriptions.length">
                                <td colspan="6">No Subscriptions Found</td>
                            </tr>

                            <tr v-for="(sub, index) in subscriptions" :key="sub.id">
                                <td>{{ index + meta.from }}</td>

                                <td>
                                    <b>{{ sub.user?.username ?? "N/A" }}</b><br>
                                    <small>{{ sub.user?.name }}</small>
                                </td>

                                <td>{{ sub.plan?.name }}</td>

                                <td>₹ {{ sub.amount }}</td>

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
                        @responseData="handleResponse"
                        @pageMeta="handleMeta"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from "vue"
import Datepicker from "vue3-datepicker"
import AdminPaginator from "@/components/AdminPaginator.vue"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"

export default {
    layout: MainAdminLayout,

    props: {
        plans: Array
    },

    components: {
        Datepicker,
        AdminPaginator
    },

    setup(props) {
        const subscriptions = ref([])
        const meta = ref({ from: 1 })

        const filters = ref({
            plan_id: "",
            username: "",
            from: null,
            to: null
        })

        const paginatorKey = ref(Date.now())

        function formatDate(date) {
            if (!date) return ""
            const tzOffset = date.getTimezoneOffset() * 60000
            return new Date(date - tzOffset).toISOString().split("T")[0]
        }

        const baseUrl = computed(() => {
            return route("admin.subscriptions.get") +
                `?plan_id=${filters.value.plan_id}` +
                `&username=${filters.value.username}` +
                `&from=${formatDate(filters.value.from)}` +
                `&to=${formatDate(filters.value.to)}`
        })

        function applyFilter() {
            paginatorKey.value = Date.now()
        }

        function resetFilter() {
            filters.value.plan_id = ""
            filters.value.username = ""
            filters.value.from = null
            filters.value.to = null
            paginatorKey.value = Date.now()
        }

        function handleResponse(res) {
            subscriptions.value = res.data
        }

        function handleMeta(m) {
            meta.value = m
        }

        return {
            plans: props.plans,
            subscriptions,
            meta,
            filters,
            baseUrl,
            paginatorKey,
            applyFilter,
            resetFilter,
            handleResponse,
            handleMeta
        }
    }
}
</script>
