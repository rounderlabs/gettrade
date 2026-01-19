<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fund Add Requests</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th>Txn ID</th>
                        <th>Status</th>
                        <th width="200">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(req, index) in requests.data" :key="req.id">
                        <td>{{ index + 1 }}</td>
                        <td>
                            {{ req.user.name }} <br />
                            <small class="text-muted">{{ req.user.email }}</small>
                        </td>
                        <td>{{ req.payment_type }}</td>
                        <td>₹{{ req.amount }}</td>
                        <td>{{ req.txn_id ?? '-' }}</td>

                        <!-- STATUS BADGE -->
                        <td>
                                <span
                                    class="badge"
                                    :class="{
                                        'badge-pending': req.status === 'pending',
                                        'badge-accepted': req.status === 'accepted',
                                        'badge-rejected': req.status === 'rejected',
                                    }"
                                >
                                    {{ req.status }}
                                </span>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <Link
                                :href="route('admin.fund.requests.download', req.id)"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Proof
                            </Link>

                            <button
                                v-if="req.status === 'pending'"
                                class="btn btn-sm btn-success ms-1"
                                @click="accept(req.id)"
                            >
                                Accept
                            </button>

                            <button
                                v-if="req.status === 'pending'"
                                class="btn btn-sm btn-danger ms-1"
                                @click="openReject(req)"
                            >
                                Reject
                            </button>
                        </td>
                    </tr>

                    <tr v-if="!requests.data.length">
                        <td colspan="7" class="text-center text-muted py-3">
                            No fund requests found
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li
                        v-for="link in requests.links"
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

        <!-- REJECT MODAL -->
        <div class="modal fade show d-block" v-if="showReject">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Fund Request</h5>
                        <button class="btn-close" @click="closeReject"></button>
                    </div>

                    <div class="modal-body">
                        <textarea
                            v-model="rejectComment"
                            class="form-control"
                            placeholder="Enter rejection reason"
                        ></textarea>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="closeReject">
                            Cancel
                        </button>
                        <button class="btn btn-danger" @click="reject">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show" v-if="showReject"></div>
    </div>
</template>

<script>
import { Link, router } from "@inertiajs/vue3";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "AdminFundRequestIndex",
    layout: MainAdminLayout,
    components: { Link },

    props: {
        requests: Object,
    },

    data() {
        return {
            showReject: false,
            rejectId: null,
            rejectComment: "",
        };
    },

    methods: {
        accept(id) {
            if (!confirm("Are you sure you want to accept this request?")) return;

            router.post(route("admin.fund.requests.accept", id));
        },

        openReject(req) {
            this.rejectId = req.id;
            this.rejectComment = "";
            this.showReject = true;
        },

        closeReject() {
            this.showReject = false;
            this.rejectId = null;
        },

        reject() {
            if (!this.rejectComment) {
                alert("Please enter rejection reason");
                return;
            }

            router.post(route("admin.fund.requests.reject", this.rejectId), {
                comment: this.rejectComment,
            });

            this.closeReject();
        },
    },
};
</script>
<style scoped>
.badge-pending {
    background-color: #ffc107;
    color: #212529;
}

.badge-accepted {
    background-color: #28a745;
}

.badge-rejected {
    background-color: #dc3545;
}
</style>

