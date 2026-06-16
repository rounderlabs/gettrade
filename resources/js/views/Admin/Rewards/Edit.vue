<template>
    <Head title="Edit Reward" />

    <AdminLayout>
        <div class="row row-sm mt-4">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Reward: {{ reward.rank_name }}</h3>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group mb-3">
                                <label for="rank_name" class="form-label">Rank Name</label>
                                <input v-model="form.rank_name" type="text" id="rank_name" class="form-control" />
                                <div v-if="form.errors.rank_name" class="text-danger mt-1">{{ form.errors.rank_name }}</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="matching_leg_business" class="form-label">Matching Leg Business</label>
                                <input v-model="form.matching_leg_business" type="number" step="1" id="matching_leg_business" class="form-control" />
                                <div v-if="form.errors.matching_leg_business" class="text-danger mt-1">{{ form.errors.matching_leg_business }}</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reward_amount" class="form-label">Reward Amount</label>
                                <input v-model="form.reward_amount" type="number" step="1" id="reward_amount" class="form-control" />
                                <div v-if="form.errors.reward_amount" class="text-danger mt-1">{{ form.errors.reward_amount }}</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="salary_amount" class="form-label">Salary Amount</label>
                                <input v-model="form.salary_amount" type="number" step="1" id="salary_amount" class="form-control" />
                                <div v-if="form.errors.salary_amount" class="text-danger mt-1">{{ form.errors.salary_amount }}</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="salary_tenure" class="form-label">Salary Tenure (in months)</label>
                                <input v-model="form.salary_tenure" type="number" id="salary_tenure" class="form-control" />
                                <div v-if="form.errors.salary_tenure" class="text-danger mt-1">{{ form.errors.salary_tenure }}</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reward_text" class="form-label">Reward Text (Optional)</label>
                                <textarea v-model="form.reward_text" id="reward_text" rows="3" class="form-control"></textarea>
                                <div v-if="form.errors.reward_text" class="text-danger mt-1">{{ form.errors.reward_text }}</div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <Link :href="route('admin.rewards.index')" class="btn btn-light me-2 mr-2">
                                    Cancel
                                </Link>
                                <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                    Update Reward
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
// Adjust this import based on your actual Admin layout path
import AdminLayout from '@/layouts/Admin/MainAdminLayout.vue'; 

const props = defineProps({
    reward: {
        type: Object,
        required: true
    }
});

const form = useForm({
    rank_name: props.reward.rank_name || '',
    matching_leg_business: props.reward.matching_leg_business || 0,
    reward_amount: props.reward.reward_amount || 0,
    salary_amount: props.reward.salary_amount || 0,
    salary_tenure: props.reward.salary_tenure || 0,
    reward_text: props.reward.reward_text || '',
});

const submit = () => {
    form.put(route('admin.rewards.update', props.reward.id), {
        preserveScroll: true,
    });
};
</script>
