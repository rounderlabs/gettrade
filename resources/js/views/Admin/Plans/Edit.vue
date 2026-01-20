<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm } from "@inertiajs/vue3"

defineOptions({ layout: MainAdminLayout })

const props = defineProps({
    plan: Object,
})

const form = useForm({
    name: props.plan.name,
    amount: props.plan.amount,
    monthly_roi_amount: props.plan.monthly_roi_amount,
    tenure: props.plan.tenure,
    principle_amount: props.plan.principle_amount,
    is_active: props.plan.is_active,
    allow_to_user: props.plan.allow_to_user,
})
</script>

<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Plan</h3>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label>Name</label>
                <input v-model="form.name" class="form-control" />
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input type="number" v-model="form.amount" class="form-control" />
            </div>

            <div class="form-group">
                <label>Monthly ROI</label>
                <input
                    type="number"
                    v-model="form.monthly_roi_amount"
                    class="form-control"
                />
            </div>

            <div class="form-group">
                <label>Tenure</label>
                <input type="number" v-model="form.tenure" class="form-control" />
            </div>

            <div class="form-group">
                <label>Principle Amount</label>
                <input
                    type="number"
                    v-model="form.principle_amount"
                    class="form-control"
                />
            </div>

            <div class="form-check">
                <input type="checkbox" v-model="form.is_active" />
                <label class="ml-2">Active</label>
            </div>

            <div class="form-check">
                <input type="checkbox" v-model="form.allow_to_user" />
                <label class="ml-2">Allow to User</label>
            </div>
        </div>

        <div class="card-footer">
            <button
                class="btn btn-primary"
                :disabled="form.processing"
                @click="form.put(`/admin/plans/${props.plan.id}`)"
            >
                Update Plan
            </button>
        </div>
    </div>
</template>
