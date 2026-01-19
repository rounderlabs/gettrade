<template>
    <MainAdminLayout>
        <div class="card">
            <h5 class="card-title mb-2">User Dividends Report</h5>
            <div class="card-header">

                <div class="d-flex align-items-center gap-2">
                    <input
                        v-model="filters.search"
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Search username/email"
                        style="width: 220px;"
                    />

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
                        <th>Name</th>
                        <th>Investment</th>
                        <th>Dividends Amount</th>
                        <th>Dividends Income</th>
                        <th>Closing Date</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="loading">
                        <td colspan="9" class="text-center py-3">Loading...</td>
                    </tr>
                    <tr v-else-if="!roiData.data.length">
                        <td colspan="9" class="text-center py-3">No records found</td>
                    </tr>
                    <tr v-for="(row, index) in roiData.data" :key="row.id">
                        <td>{{ index + 1 + ((roiData.current_page - 1) * roiData.per_page) }}</td>
                        <td>{{ row.user && row.user.username ? row.user.username : '-' }}</td>
                        <td>{{ row.user && row.user.name ? row.user.name : '-' }}</td>
                        <td>{{ formatCurrency(row.user.user_investment?.amount) }}</td>
                        <td>{{ formatCurrency(row.amount) }}</td>
                        <td>{{ formatCurrency(row.income) }}</td>
                        <td>{{ formatDate(row.closing_date) }}</td>
                        <td>{{ formatDate(row.created_at) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Global Pagination -->
            <AdminPaginator
                base-url="/admin/reports/get-user-trading-bonus-report"
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

const roiData = ref({ data: [] });
const loading = ref(false);
const filters = ref({ search: "", from: "", to: "" });

const fetchData = async () => {
    loading.value = true;
    const res = await axios.get("/admin/reports/get-user-trading-bonus-report", { params: filters.value });
    roiData.value = res.data;
    loading.value = false;
};

const handleResponse = (data) => {
    roiData.value = data;
};

const formatDate = (date) => new Date(date).toLocaleString();
const formatCurrency = (val) => (val ? parseFloat(val).toFixed(2) : "-");

const exportCsv = () => {
    const query = new URLSearchParams(filters.value).toString();
    window.open(`/admin/reports/user-trading-bonus/export?${query}`, "_blank");
};
</script>
