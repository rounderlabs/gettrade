<script setup>
import AdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({
    layout: AdminLayout,
    name: "AdminKycIndex",
});

defineProps({
    kycs: Object,
});
</script>

<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">KYC Verification Requests</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th width="120">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(kyc, index) in kycs.data" :key="kyc.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ kyc.user.name }}</td>
                        <td>{{ kyc.user.email }}</td>
                        <td>
                                <span
                                    class="badge"
                                    :class="{
                                        'bg-warning': kyc.status === 'submitted',
                                        'bg-success': kyc.status === 'approved',
                                        'bg-danger': kyc.status === 'rejected',
                                    }"
                                >
                                    {{ kyc.status }}
                                </span>
                        </td>
                        <td>{{ kyc.updated_at }}</td>
                        <td>
                            <Link
                                :href="route('admin.kyc.show', kyc.id)"
                                class="btn btn-sm btn-primary"
                            >
                                Review
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="!kycs.data.length">
                        <td colspan="6" class="text-center text-muted py-3">
                            No KYC submissions found
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li
                        v-for="link in kycs.links"
                        :key="link.label"
                        class="page-item"
                        :class="{ active: link.active, disabled: !link.url }"
                    >
                        <Link
                            v-if="link.url"
                            class="page-link"
                            :href="link.url"
                            v-html="link.label"
                        />
                        <span v-else class="page-link" v-html="link.label"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
