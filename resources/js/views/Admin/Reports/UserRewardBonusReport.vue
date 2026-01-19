<template>
    <MainAdminLayout>
        <div class="card">
            <h5 class="card-title mb-2">User Reward Bonus Report</h5>
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <input v-model="filters.search" type="text" class="form-control form-control-sm"
                           placeholder="Search username/email" style="width: 220px;" />
                    <input type="date" v-model="filters.from" class="form-control form-control-sm" />
                    <input type="date" v-model="filters.to" class="form-control form-control-sm" />
                    <button class="btn btn-sm btn-success" @click="fetchData">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button class="btn btn-sm btn-primary" @click="exportCsv">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-striped text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Reward Title</th>
                        <th>Income (USD)</th>
                        <th>Reward Text</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="loading">
                        <td colspan="6" class="text-center py-3">Loading...</td>
                    </tr>
                    <tr v-else-if="!rewardData.data.length">
                        <td colspan="6" class="text-center py-3">No records found</td>
                    </tr>
                    <tr v-for="(row, index) in rewardData.data" :key="row.id">
                        <td>{{ index + 1 + ((rewardData.current_page - 1) * rewardData.per_page) }}</td>
                        <td>{{ row.user?.username || '-' }}</td>
                        <td>{{ row.reward?.title || '-' }}</td>
                        <td>{{ formatCurrency(row.income_usd) }}</td>
                        <td>{{ row.reward_text || '-' }}</td>
                        <td>{{ formatDate(row.created_at) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <AdminPaginator
                base-url="/admin/reports/get-user-reward-bonus-report"
                @responseData="handleResponse"
            />
        </div>
    </MainAdminLayout>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import AdminPaginator from "@/components/AdminPaginator.vue";

const rewardData = ref({ data: [] });
const loading = ref(false);
const filters = ref({ search: "", from: "", to: "" });

const fetchData = async () => {
    loading.value = true;
    const res = await axios.get("/admin/reports/get-user-reward-bonus-report", { params: filters.value });
    rewardData.value = res.data;
    loading.value = false;
};

const handleResponse = (data) => {
    rewardData.value = data;
};

const formatDate = (date) => new Date(date).toLocaleString();
const formatCurrency = (val) => (val ? parseFloat(val).toFixed(2) : "-");

const exportCsv = () => {
    const query = new URLSearchParams(filters.value).toString();
    window.open(`/admin/reports/user-reward-bonus/export?${query}`, "_blank");
};
</script>
