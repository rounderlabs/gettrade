<template>
    <MainAdminLayout>
        <section class="content">
            <div class="container-fluid">

                <!-- PAGE HEADER -->
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-bullhorn mr-2"></i>
                            Marketing Bonus Report
                        </h1>
                    </div>
                </div>

                <!-- CARD -->
                <div class="card card-outline card-success shadow-sm">

                    <!-- CARD HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-hand-holding-usd mr-2"></i>
                            Direct Income
                        </h3>

                        <div class="card-tools d-flex gap-2">
                            <input
                                v-model="filters.search"
                                class="form-control form-control-sm"
                                placeholder="Username / Name"
                                style="width: 200px"
                            />

                            <button
                                class="btn btn-sm btn-primary"
                                @click="fetch()"
                            >
                                <i class="fas fa-search mr-1"></i>
                                Search
                            </button>

                            <button
                                class="btn btn-sm btn-success"
                                @click="exportCsv()"
                            >
                                <i class="fas fa-file-csv mr-1"></i>
                                Export
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>User</th>
                                <th>From User</th>
                                <th class="text-right">Income</th>
                                <th class="text-center">Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <!-- LOADING -->
                            <tr v-if="loading">
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Loading data...
                                </td>
                            </tr>

                            <!-- EMPTY -->
                            <tr v-else-if="!rows.length">
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-database mr-1"></i>
                                    No records found
                                </td>
                            </tr>

                            <!-- DATA -->
                            <tr v-for="row in rows" :key="row.id">
                                <!-- USER -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ row.user?.username }}</strong>
                                        <small class="text-muted">
                                            {{ row.user?.name }}
                                        </small>
                                    </div>
                                </td>

                                <!-- FROM USER -->
                                <td>
                                        <span v-if="row.from_user">
                                            {{ row.from_user.username }}
                                        </span>
                                    <span v-else class="text-muted">-</span>
                                </td>

                                <!-- INCOME -->
                                <td class="text-right font-weight-bold text-success">
                                    ₹ {{ money(row.income) }}
                                </td>

                                <!-- DATE -->
                                <td class="text-center text-muted">
                                    {{ formatDate(row.created_at) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER / PAGINATION -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <button
                            class="btn btn-sm btn-secondary"
                            :disabled="!pagination.prev_page_url"
                            @click="fetch(pagination.current_page - 1)"
                        >
                            <i class="fas fa-angle-left mr-1"></i> Prev
                        </button>

                        <span class="text-muted">
                            Page {{ pagination.current_page || 1 }}
                        </span>

                        <button
                            class="btn btn-sm btn-secondary"
                            :disabled="!pagination.next_page_url"
                            @click="fetch(pagination.current_page + 1)"
                        >
                            Next <i class="fas fa-angle-right ml-1"></i>
                        </button>
                    </div>

                </div>
            </div>
        </section>
    </MainAdminLayout>
</template>

<script>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import axios from "axios"

export default {
    layout: MainAdminLayout,

    data() {
        return {
            rows: [],
            pagination: {},
            filters: {
                search: "",
            },
            loading: false,
        }
    },

    mounted() {
        this.fetch()
    },

    methods: {
        fetch(page = 1) {
            this.loading = true
            axios
                .get(route("admin.reports.user.direct.bonus.data"), {
                    params: { ...this.filters, page },
                })
                .then((res) => {
                    this.rows = res.data.data
                    this.pagination = res.data
                })
                .finally(() => {
                    this.loading = false
                })
        },

        exportCsv() {
            window.location.href = route(
                "admin.reports.user.direct.bonus.export",
                this.filters
            )
        },

        money(v) {
            return Number(v || 0).toFixed(2)
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString("en-IN", {
                day: "2-digit",
                month: "short",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            })
        },
    },
}
</script>
