<template>
    <Head title="Rewards" />

    <AdminLayout>
        <div class="row row-sm mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Manage Rewards</h3>
                        <Link :href="route('admin.rewards.create')" class="btn btn-primary btn-sm">
                            + Add Reward
                        </Link>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom">
                                <thead>
                                    <tr>
                                        <th>Rank Name</th>
                                        <th>Matching Leg Biz.</th>
                                        <th>Reward Amount</th>
                                        <th>Salary Amount</th>
                                        <th>Salary Tenure</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="reward in rewards" :key="reward.id">
                                        <td>{{ reward.rank_name }}</td>
                                        <td>{{ reward.matching_leg_business }}</td>
                                        <td>{{ reward.reward_amount }}</td>
                                        <td>{{ reward.salary_amount }}</td>
                                        <td>{{ reward.salary_tenure }} Months</td>
                                        <td class="text-center">
                                            <Link :href="route('admin.rewards.edit', reward.id)" class="btn btn-sm btn-info me-2 mr-2">
                                                Edit
                                            </Link>
                                            <button @click="deleteReward(reward.id)" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!rewards.length">
                                        <td colspan="6" class="text-center text-muted">
                                            No rewards found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
// Adjust this import based on your actual Admin layout path
import AdminLayout from '@/layouts/Admin/MainAdminLayout.vue'; 

const props = defineProps({
    rewards: {
        type: Array,
        required: true
    }
});

const deleteReward = (id) => {
    if (confirm('Are you sure you want to delete this reward? This action cannot be undone.')) {
        router.delete(route('admin.rewards.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: handle success notification
            }
        });
    }
};
</script>
