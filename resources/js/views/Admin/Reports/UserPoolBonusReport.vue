<template>
    <MainAdminLayout>
        <div class="card">
            <h5 class="card-title mb-2">User Pool Bonus Report</h5>
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
                        <th>Pool Closing ID</th>
                        <th>Income %</th>
                        <th>Income (USD)</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="loading">
                        <td colspan="6" class="text-center py-3">Loading...</td>
                    </tr>
                    <tr v-else-if="!poolData.data.length">
                        <td colspan="6" class="text-center py-3">No records found</td>
                    </tr>
                    <tr v-for="(row, index) in poolData.data" :key="row.id">
                        <td>{{ index + 1 + ((poolData.current_page - 1) * poolData.per_page) }}</td>
                        <td>{{ row.user?.username || '-' }}</td>
                        <td>{{ row.pool_income_closing?.id || '-' }}</td>
                        <td>{{ row.income_percent }}</td>
                        <td>{{ formatCurrency(row.income_usd) }}</td>
                        <td>{{ formatDate(row.created_at) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <AdminPaginator
                base-url="/admin/reports/get-user-pool-bonus-report"
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

const poolData = ref({ data: [] });
const loading = ref(false);
const filters = ref({ search: "", from: "", to: "" });

const fetchData = async () => {
    loading.value = true;
    const res = await axios.get("/admin/reports/get-user-pool-bonus-report", { params: filters.value });
    poolData.value = res.data;
    loading.value = false;
};

const handleResponse = (data) => {
    poolData.value = data;
};

const formatDate = (date) => new Date(date).toLocaleString();
const formatCurrency = (val) => (val ? parseFloat(val).toFixed(2) : "-");

const exportCsv = () => {
    const query = new URLSearchParams(filters.value).toString();
    window.open(`/admin/reports/user-pool-bonus/export?${query}`, "_blank");
};
</script>
