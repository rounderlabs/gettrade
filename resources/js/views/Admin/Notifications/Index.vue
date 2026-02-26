<template>
    <section class="content">
        <div class="container-fluid">

            <!-- ================= HEADER ================= -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-bell mr-2"></i>
                                Admin Notifications
                            </h3>

                            <span class="badge badge-danger">
                                {{ unread_count }} Unread
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= TABLE ================= -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>
                        Notification List
                    </h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="60">#</th>
                            <th width="120">Type</th>
                            <th>Message</th>
                            <th width="180">Date</th>
                            <th width="120">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-if="!notifications.data.length">
                            <td colspan="5" class="text-center">
                                No notifications found
                            </td>
                        </tr>

                        <tr
                            v-for="(item, index) in notifications.data"
                            :key="item.id"
                            :class="{ 'table-warning': !item.is_read }"
                        >
                            <td>
                                {{ notifications.from + index }}
                            </td>

                            <td>
                                <span
                                    class="badge"
                                    :class="badgeClass(item.type)"
                                >
                                    {{ item.type }}
                                </span>
                            </td>

                            <td v-html="item.message"></td>

                            <td>
                                {{ item.created_at }}
                            </td>

                            <td>
                                <button
                                    v-if="!item.is_read"
                                    class="btn btn-sm btn-success"
                                    @click="markAsRead(item.id)"
                                >
                                    Mark as Read
                                </button>

                                <span
                                    v-else
                                    class="badge badge-secondary"
                                >
                                    Read
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ================= PAGINATION ================= -->
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button
                            v-if="notifications.prev_page_url"
                            class="btn btn-sm btn-outline-primary mr-2"
                            @click="changePage(notifications.current_page - 1)"
                        >
                            Previous
                        </button>

                        <button
                            v-if="notifications.next_page_url"
                            class="btn btn-sm btn-outline-primary"
                            @click="changePage(notifications.current_page + 1)"
                        >
                            Next
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </section>
</template>

<script>
import AdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import { router } from "@inertiajs/vue3";

export default {
    name: "AdminNotificationsIndex",
    layout: AdminLayout,

    props: {
        notifications: Object,
        unread_count: Number,
    },

    methods: {
        changePage(page) {
            router.get(route('admin.notifications'), { page }, {
                preserveScroll: true,
                preserveState: true
            });
        },

        markAsRead(id) {
            router.post(route('admin.notifications.read', id), {}, {
                preserveScroll: true,
            });
        },

        badgeClass(type) {
            const map = {
                registration: "badge-primary",
                activation: "badge-success",
                deposit: "badge-info",
                kyc: "badge-warning",
                withdrawal: "badge-danger",
                transfer: "badge-dark",
            };

            return map[type] || "badge-secondary";
        }
    }
}
</script>

<style scoped>
.table-warning {
    background-color: #fff3cd !important;
}
</style>
