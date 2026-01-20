<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm } from "@inertiajs/vue3"

defineOptions({ layout: MainAdminLayout })

const form = useForm({
    name: "",
    amount: 0,
    monthly_roi_amount: 0,
    tenure: 0,
    principle_amount: 0,
    is_active: true,
    allow_to_user: false,
})
</script>

<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Plan</h3>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label>Plan Name</label>
                <input v-model="form.name" class="form-control" />
                <div class="text-danger">{{ form.errors.name }}</div>
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input type="number" v-model="form.amount" class="form-control" />
            </div>

            <div class="form-group">
                <label>Monthly ROI Amount</label>
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
                class="btn btn-success"
                :disabled="form.processing"
                @click="form.post(route('admin.plans.store'))"
            >
                Create Plan
            </button>

            <a :href="route('admin.plans.index')" class="btn btn-secondary ml-2">
                Cancel
            </a>
        </div>
    </div>
</template>
