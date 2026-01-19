<template>
    <div class="row row-sm">
        <div class="col-sm-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <h4 class="card-title mt-3 float-left">Withdrawal History</h4>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-md-flex d-sm-block">

                                <div class="input-group wd-sm-100p wd-md-200 float-right my-auto ml-auto">
                                    <label class="form-label mt-2 font-weight-semibold mt-2">TXN Status Filter</label>
                                </div>
                                <div class="input-group wd-sm-100p wd-md-200 float-right my-auto ml-auto">
                                    <select id="status" v-model="statusText" class="form-control" name="status"
                                            @change="statusFilter">
                                        <option selected>Please select one</option>
                                        <option value="">All</option>
                                        <option value="pending">Pending</option>
                                        <option value="success">Success</option>
                                        <option value="processing">Processing</option>
                                        <option value="failure">Failed</option>
                                    </select>

                                </div>
                                <div class="input-group wd-sm-100p wd-md-200 float-right my-auto ml-auto">
                                    <input id="search-contact" v-model="filterText"
                                           class="form-control wd-100 border-right-0"
                                           placeholder="Search" required type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-search btn-primary br-tl-0 br-bl-0" type="button"
                                                @click="filterSubmit"><i
                                            class="bi bi-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0 text-md-nowrap text-center">
                            <thead>
                            <tr>
                                <th class="wd-5p serial-num text-center">S.No</th>
                                <th>Txn Date</th>
                                <th>Action</th>
                                <th>Txn Raw Id</th>
                                <th>User</th>
                                <th>Txn Details</th>
                                <th>Fee</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wh,index) in histories">
                                <td class="serial-num text-center">
                                    {{ (pages.per_page * (pages.current - 1)) + index + 1 }}
                                </td>
                                <td class="no-wrap">{{ wh.created_at }}</td>
                                <td>
                                    <button v-if="wh.status ==='processing'" class="btn ripple btn-danger btn-sm"
                                            @click="process(wh.id)">Resend
                                        <i v-if="processing" class="bi bu-spin bi-spinner"></i>
                                    </button>

                                </td>
                                <td>
                                    {{ wh.id }}
                                </td>
                                <td>
                                    <b>{{ wh.user.username }}</b>
                                    <br>
                                    <b>{{ wh.user.name }}</b>
                                    <br>
                                    <small>{{ wh.user.email }}</small>
                                </td>

                                <td>
                                    <small>
                                        <a :href="`//bscscan.com/tx/${wh.txn_id}`" target="_blank">{{
                                                wh.txn_id ?
                                                    wh.txn_id.substr(0, 5) + '....' + wh.txn_id.slice(-5) : ''
                                            }}
                                        </a>
                                    </small>
                                    <br>
                                    <b>BEP20USDT</b>
                                    <br>
                                    <small>
                                        <a :href="`//bscscan.com/address/${wh.address}`" target="_blank">
                                            {{ wh.address }}
                                        </a>
                                    </small>
                                </td>
                                <td class="contact-num"><i class="las la-phone mr-2"></i>{{ wh.fees }}</td>
                                <td class="text-center">
                                    <small>{{ wh.amount }}</small>
                                </td>
                                <td class="text-center">
                                    <span v-if="wh.status === 'success'" class="badge bg-success">Success</span>
                                    <span v-if="wh.status === 'pending'" class="badge bg-warning">Pending</span>
                                    <span v-if="wh.status === 'processing'" class="badge bg-info">Processing</span>
                                    <span v-if="wh.status === 'failed'" class="badge bg-danger">Failed</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="text-center contact-table-msg  col-12">
                            <span id="errmsg" class="mx-auto pt-3 pb-4"></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <AdminPaginator
                        :base-url="route('admin.withdrawal.withdrawal_history')"
                        :payload="{ filter: filterText, status: statusText }"
                        method="post"
                        @responseData="paginatorResponse"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, reactive, ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import AdminPaginator from "@/components/AdminPaginator.vue";

export default {
    name: "Index",
    components: {AdminPaginator},
    layout: MainAdminLayout,

    setup() {
        const histories = ref([]);
        const links = ref([]);
        const pages = reactive({
            current: 1,
            per_page: 50,
        });
        const filterText = ref("");
        const statusText = ref("all");

        function getWithdrawals(url = null) {
            const postUrl = url ?? route("admin.withdrawal.withdrawal_history");

            axios
                .post(postUrl, {
                    filter: filterText.value,
                    status: statusText.value,
                })
                .then((res) => {
                    const response = res.data;
                    histories.value = response.data;
                    links.value = response.links;
                    pages.current = response.current_page;
                    pages.per_page = response.per_page;
                });
        }

        function filterSubmit() {
            pages.current = 1;
            getWithdrawals();
        }

        function statusFilter() {
            pages.current = 1;
            getWithdrawals();
        }

        function process(id) {
            axios
                .post(route("admin.withdrawal.process"), {id})
                .then((res) => {
                    toast(res.data, "success");
                    getWithdrawals();
                })
                .catch((err) => {
                    toast(err, "danger");
                });
        }

        // ✅ Add this new function here
        function paginatorResponse(response) {
            histories.value = response.data;
            links.value = response.links;
            pages.current = response.current_page;
            pages.per_page = response.per_page;
        }

        onMounted(() => {
            getWithdrawals();
        });

        return {
            histories,
            links,
            pages,
            filterText,
            statusText,
            getWithdrawals,
            filterSubmit,
            statusFilter,
            process,
            paginatorResponse,
        };
    },
};
</script>


<style scoped>
.card-footer {
    padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
    color: var(--bs-card-cap-color);
    background-color: transparent;
    border-top: none;
}

</style>
