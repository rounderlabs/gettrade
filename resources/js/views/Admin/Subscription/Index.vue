<template>
    <div class="row">
        <div class="col">
            <div class="card">

                <!-- HEADER FILTER FORM -->
                <div class="card-header">
                    <form @submit.prevent="applyFilter">
                        <div class="row">

                            <!-- FROM DATE -->
                            <div class="col-md-3 mb-3">
                                <label>From Date</label>
                                <datepicker
                                    v-model="date_from"
                                    class="form-control"
                                    placeholder="Select From Date"
                                />
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-3 mb-3">
                                <label>To Date</label>
                                <datepicker
                                    v-model="date_to"
                                    class="form-control"
                                    placeholder="Select To Date"
                                />
                            </div>

                            <!-- USER ID -->
                            <div class="col-md-2 mb-3">
                                <label>User ID</label>
                                <input
                                    v-model="filterUserId"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter User ID"
                                />
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-4 mb-3 d-flex align-items-end gap-2">
                                <button class="btn btn-info btn-sm">
                                    Apply Filter
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm"
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
                                <th>Sr.No.</th>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Max Limit</th>
                                <th>Earned</th>
                                <th>Status</th>
                                <th>Active Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-if="!subscriptions.length">
                                <td colspan="8">No Subscription Found</td>
                            </tr>

                            <tr
                                v-for="(sub, index) in subscriptions"
                                :key="sub.id"
                            >
                                <td>{{ index + pageMeta.from }}</td>

                                <td>
                                    {{ sub.user.id }} <br />
                                    {{ sub.user.name }} <br />
                                    <small>{{ sub.user.username }}</small>
                                </td>

                                <td>{{ sub.plan.name }}</td>

                                <td>₹ {{ sub.amount }}</td>
                                <td>₹ {{ sub.max_income_limit }}</td>
                                <td>₹ {{ sub.earned_so_far }}</td>

                                <td
                                    :class="sub.is_active ? 'text-success' : 'text-danger'"
                                >
                                    {{ sub.is_active ? "Active" : "Inactive" }}
                                </td>

                                <td>{{ sub.created_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATOR -->
                    <Paginator
                        :key="filterKey"
                        :base-url="baseUrl"
                        @responseData="paginatorResponse"
                        @pageMeta="paginatorPageMeta"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue"
import Paginator from "@/components/xino/Paginator.vue"
import Datepicker from "vue3-datepicker"

/* --------------------------
   FILTER STATES
-------------------------- */
const date_from = ref(null)
const date_to = ref(null)
const filterUserId = ref(null)

/* --------------------------
   TABLE DATA
-------------------------- */
const subscriptions = ref([])
const pageMeta = ref({})

/* --------------------------
   FORMAT DATE FOR URL
-------------------------- */
function formatDate(date) {
    if (!date) return null
    const tzOffset = date.getTimezoneOffset() * 60000
    return new Date(date - tzOffset).toISOString().split("T")[0]
}

/* --------------------------
   FILTER APPLY
-------------------------- */
const appliedFrom = ref("noDate")
const appliedTo = ref("noDate")
const appliedUserId = ref("noId")

function applyFilter() {
    appliedFrom.value = date_from.value ? formatDate(date_from.value) : "noDate"
    appliedTo.value = date_to.value ? formatDate(date_to.value) : "noDate"
    appliedUserId.value = filterUserId.value ? filterUserId.value : "noId"
}

/* --------------------------
   RESET FILTER
-------------------------- */
function resetFilter() {
    date_from.value = null
    date_to.value = null
    filterUserId.value = null

    appliedFrom.value = "noDate"
    appliedTo.value = "noDate"
    appliedUserId.value = "noId"
}

/* --------------------------
   PAGINATOR URL
-------------------------- */
const baseUrl = computed(() => {
    return route("admin.subscriptions.get", [
        appliedUserId.value,
        appliedFrom.value,
        appliedTo.value,
    ])
})

/* --------------------------
   FORCE REFRESH PAGINATOR
-------------------------- */
const filterKey = computed(() => {
    return `${appliedUserId.value}-${appliedFrom.value}-${appliedTo.value}`
})

/* --------------------------
   PAGINATOR EVENTS
-------------------------- */
function paginatorResponse(data) {
    subscriptions.value = data
}

function paginatorPageMeta(meta) {
    pageMeta.value = meta
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
}
</style>
