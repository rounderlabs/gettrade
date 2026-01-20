<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import {Link} from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3"

defineOptions({ layout: MainAdminLayout })

defineProps({
    plans: Array,
})

function deletePlan(id) {
    if (!confirm("Are you sure you want to delete this plan?")) return

    router.delete(`/admin/plans/${id}`)
}
</script>

<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Plans</h3>

            <!-- ✅ ADD PLAN BUTTON -->
            <Link :href="route('admin.plans.create')" class="btn btn-primary btn-sm">
                + Add Plan
            </Link>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Monthly ROI</th>
                    <th>Tenure</th>
                    <th>Status</th>
                    <th width="160">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="plan in plans" :key="plan.id">
                    <td>{{ plan.name }}</td>
                    <td>{{ plan.amount }}</td>
                    <td>{{ plan.monthly_roi_amount }}</td>
                    <td>{{ plan.tenure }}</td>
                    <td>
                            <span
                                class="badge"
                                :class="plan.is_active ? 'badge-success' : 'badge-danger'"
                            >
                                {{ plan.is_active ? 'Active' : 'Inactive' }}
                            </span>
                    </td>
                    <td>
                        <a
                            class="btn btn-sm btn-primary mr-1"
                            :href="`/admin/plans/${plan.id}/edit`"
                        >
                            Edit
                        </a>

                        <button
                            class="btn btn-sm btn-danger"
                            @click="deletePlan(plan.id)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
