<template>
    <MainAdminLayout>
        <section class="content">
            <div class="container-fluid">

                <!-- CARD -->
                <div class="card card-outline card-primary shadow-sm">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-sitemap mr-2"></i>
                            User Systematic Bonus Report (Level On ROI)
                        </h3>

                        <div class="card-tools d-flex gap-2">
                            <input
                                v-model="filters.search"
                                type="text"
                                class="form-control form-control-sm"
                                placeholder="Username / Email"
                                style="width: 200px"
                            />

                            <input
                                type="date"
                                v-model="filters.from"
                                class="form-control form-control-sm"
                            />

                            <input
                                type="date"
                                v-model="filters.to"
                                class="form-control form-control-sm"
                            />

                            <button
                                class="btn btn-sm btn-primary"
                                @click="fetchData"
                            >
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>

                            <button
                                class="btn btn-sm btn-success"
                                @click="exportCsv"
                            >
                                <i class="fas fa-file-csv mr-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th style="width:60px">#</th>
                                <th>User</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">Income %</th>
                                <th class="text-right">Income</th>
                                <th class="text-center">Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <!-- LOADING -->
                            <tr v-if="loading">
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Loading data...
                                </td>
                            </tr>

                            <!-- EMPTY -->
                            <tr v-else-if="!roiData.data.length">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-database mr-1"></i>
                                    No records found
                                </td>
                            </tr>

                            <!-- DATA -->
                            <tr
                                v-for="(row, index) in roiData.data"
                                :key="row.id"
                            >
                                <td>
                                    {{ index + 1 + ((roiData.current_page - 1) * roiData.per_page) }}
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ row.user?.username || '-' }}</strong>
                                        <small class="text-muted">
                                            {{ row.user?.name || '' }}
                                        </small>
                                    </div>
                                </td>

                                <td class="text-center">
                                        <span class="badge badge-info">
                                            L{{ row.level }}
                                        </span>
                                </td>

                                <td class="text-center">
                                        <span class="badge badge-warning">
                                            {{ parseFloat(row.income_percent).toFixed(2) }}%
                                        </span>
                                </td>

                                <td class="text-right font-weight-bold text-success">
                                    ₹ {{ formatCurrency(row.income_usd) }}
                                </td>

                                <td class="text-center text-muted">
                                    {{ formatDate(row.created_at) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER / PAGINATION -->
                    <div class="card-footer clearfix">
                        <AdminPaginator
                            base-url="/admin/reports/get-user-level-roi-bonus-report"
                            @responseData="handleResponse"
                        />
                    </div>

                </div>
            </div>
        </section>
    </MainAdminLayout>
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import AdminPaginator from "@/components/AdminPaginator.vue"

const roiData = ref({ data: [] })
const loading = ref(false)

const filters = ref({
    search: "",
    from: "",
    to: "",
})

const fetchData = async () => {
    loading.value = true
    try {
        const res = await axios.get(
            "/admin/reports/get-user-level-roi-bonus-report",
            { params: filters.value }
        )
        roiData.value = res.data
    } finally {
        loading.value = false
    }
}

const handleResponse = (data) => {
    roiData.value = data
}

const formatDate = (date) =>
    new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    })

const formatCurrency = (val) =>
    val ? parseFloat(val).toFixed(2) : "0.00"

const exportCsv = () => {
    const query = new URLSearchParams(filters.value).toString()
    window.open(
        `/admin/reports/user-level-roi-bonus/export?${query}`,
        "_blank"
    )
}
</script>
