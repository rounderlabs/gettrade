<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#" @click.prevent="goDashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Filters card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form @submit.prevent="filterSubmit" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select v-model="status" @change="filterSubmit" class="form-select form-select-sm">
                                    <option v-for="stat in statuses" :key="stat.value" :value="stat.value">
                                        {{ stat.text }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Search</label>
                                <input id="search-contact" v-model="filterText" class="form-control" placeholder="Search Name, Email, username.." type="text">
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit">Search</button>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" type="button" @click="resetFilter">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">User List</h3>
                        <div>
                            <small class="text-muted">Total: {{ total_count.count ?? 0 }}</small>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">S.No</th>
                                <th class="text-nowrap">User</th>
                                <th class="text-center text-nowrap">Sponsor</th>
                                <th class="text-center text-nowrap">Login Credential</th>
                                <th class="text-center text-nowrap">Registration Date</th>
                                <th class="text-center text-nowrap">Total Investment</th>
                                <th class="text-center text-nowrap">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!users.length">
                                <td colspan="8" class="text-center py-4 text-muted">There is no User</td>
                            </tr>

                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>
                                    {{ (pages.per_page * (pages.current - 1)) + index + 1 }}
                                </td>

                                <td>
                                    <div class="fw-bold">USER ID: {{ user.username }}</div>
                                    <div class="fw-bold">Mobile: {{ user.mobile }}</div>
                                    <div class="fw-semibold">{{ user.name }}</div>
                                    <small>
                                        {{ user.email }}
                                        <i v-if="user.email_verified_at" class="fa fa-check-double text-success ms-1"></i>
                                        <span v-else class="d-block mt-1">
                        <i class="fa fa-times text-danger me-1"></i>
                        <button class="btn btn-sm btn-info" @click="verifyEmail(user.id)">Verify Email</button>
                      </span>
                                    </small>
                                    <div class="text-muted mt-1"><small>ID: {{ user.id }}</small></div>

<!--                                    <div v-if="user.subscriptions" class="mt-2">-->
<!--                                        <a class="badge bg-primary p-2 me-1" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modalWithdrawalLimit" @click.prevent="setSelectedItem(user)">-->
<!--                                            Set Withdrawal Limit-->
<!--                                        </a>-->
<!--                                    </div>-->
                                </td>

                                <td class="text-center">
                                    <div class="fw-bold">{{ user.tree?.sponsor ? user.tree.sponsor.username : '---' }}</div>
                                    <div>{{ user.tree?.sponsor ? user.tree.sponsor.name : '---' }}</div>
                                    <div class="text-muted"><small>{{ user.sponsor_id }}</small></div>
                                </td>

                                <td>
                                    <div><strong>LoginID:</strong> {{ user.username }}</div>
                                    <div><strong>Password:</strong> {{ user.security_answer }}</div>
                                </td>



                                <td>
                                    {{ user.created_at }}
                                </td>

                                <td v-if="user.subscriptions.length">
                                    ${{ user.subscription?user.subscription.amount:'0.00'}}
                                </td>
                                <td v-else>
                                    Not Active
                                </td>


                                <td>
                                    <div class="d-grid gap-2">
                                        <div class="d-flex flex-column">
                                            <Link :href="route('admin.user.create', [user.id])" class="btn btn-sm btn-primary mb-1">View</Link>

                                            <!-- Add Fund (opens modal) -->
<!--                                            <button v-if="user" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#modalAddFund" @click.prevent="setSelectedItem(user)">-->
<!--                                                Add Fund-->
<!--                                            </button>-->

                                            <!-- Stop ROI -->
<!--                                            <button v-if="user" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#modalStopRoi" @click.prevent="setSelectedItem(user)">-->
<!--                                                Stop ROI-->
<!--                                            </button>-->

                                            <!-- Team Withdrawal Start/Stop -->
<!--                                            <button v-if="user" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="modal" data-bs-target="#modalStartStopWithdrawal" @click.prevent="setSelectedItem(user)">-->
<!--                                                Team Withdrawal-->
<!--                                            </button>-->

                                            <!-- User Panel -->
                                            <a v-if="user" :href="route('admin.user.access.panel', [user.id])" target="_blank" class="btn btn-sm btn-warning mb-1">User Panel</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer clearfix">
                        <!-- Paginator component (keeps your existing paginator) -->
                        <AdminPaginator
                            :base-url="route('admin.users.get_users')"
                            @responseData="paginatorResponse"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Modals (all inline in same file) -->

        <!-- Add Fund Modal -->
        <div class="modal fade" id="modalAddFund" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Fund to: {{ selectedItem ? selectedItem.username : '' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form @submit.prevent="saveInvoice">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subscription Package</label>
                                <select v-model="purchaseForm.plan_id" class="form-select select2" @change="isStaking($event)">
                                    <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                                        {{ plan.name.toUpperCase() }} (Min: ${{ plan.start_price_usd }} , Max: ${{ plan.end_price_usd }})
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input id="payment_price_usd" v-model="purchaseForm.amount_usd" class="form-control" required type="text" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Tenure</label>
                                <select v-model="purchaseForm.tenure" class="form-select select2">
                                    <option v-for="tenure in tenures" :key="tenure.id" :value="tenure.id">
                                        {{ tenure.staking_name }} ({{ tenure.staking_tenure }} Months) ({{ tenure.guaranteed_apy }}% APY)
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">W/Wo Staking</label>
                                <select v-model="purchaseForm.with_staking" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="1">With Staking</option>
                                    <option value="0">Without Staking</option>
                                </select>
                            </div>

                            <input type="hidden" v-model="purchaseForm.user_id" />
                        </div>

                        <div class="modal-footer">
                            <button :disabled="purchaseForm.processing" type="submit" class="btn btn-primary">Confirm & Pay</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stop ROI Modal -->
        <div class="modal fade" id="modalStopRoi" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Stop ROI: {{ selectedItem ? selectedItem.username : '' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form @submit.prevent="saveStopRoi">
                        <div class="modal-body">
                            <input v-model="stopRoiForm.user_id" class="form-control" type="hidden" />

                            <div class="form-check mb-2">
                                <input id="solid1" v-model="stopRoiForm.level" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid1">Lifetime Income</label>
                            </div>

                            <div class="form-check mb-2">
                                <input id="solid2" v-model="stopRoiForm.rank" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid2">Rank Income (Lifetime)</label>
                            </div>

                            <div class="form-check mb-2">
                                <input id="solid3" v-model="stopRoiForm.stake" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid3">Recurring Income</label>
                            </div>

                            <div class="form-check mb-2">
                                <input id="solid4" v-model="stopRoiForm.xeon" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid4">Wonder Income</label>
                            </div>

                            <div class="form-check mb-2">
                                <input id="solid5" v-model="stopRoiForm.group_dividend" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid5">Group Dividend Income</label>
                            </div>

                            <div class="form-check mb-2">
                                <input id="solid6" v-model="stopRoiForm.withdrawal" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="solid6">Withdrawal</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button :disabled="stopRoiForm.processing" type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdrawal Limit Modal -->
        <div class="modal fade" id="modalWithdrawalLimit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Set User Withdrawal Limit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form @submit.prevent="withdrawalLimitSave">
                        <div class="modal-body">
                            <div class="text-center mb-2">
                                <h6>{{ selectedItem ? selectedItem.username : '' }}</h6>
                                <p class="mb-0">{{ selectedItem ? selectedItem.name : '' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Withdrawal Limit</label>
                                <input v-model="withdrawalLimitForm.amount" class="form-control" placeholder="Enter Amount" required type="text" @keypress="onlyNumber" />
                            </div>

                            <input type="hidden" v-model="withdrawalLimitForm.user_id" />
                        </div>

                        <div class="modal-footer">
                            <button :disabled="withdrawalLimitForm.processing" type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Team Withdrawal Modal -->
        <div class="modal fade" id="modalStartStopWithdrawal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Team Withdrawal Start/Stop</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form @submit.prevent="teamWithdrawalStatusSave">
                        <div class="modal-body">
                            <div class="text-center mb-2">
                                <h6>{{ selectedItem ? selectedItem.username : '' }}</h6>
                                <p class="mb-0">{{ selectedItem ? selectedItem.name : '' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Withdrawal Status</label>
                                <select v-model="teamWithdrawalStartStopForm.withdrawal_stop" class="form-select">
                                    <option>Select Status</option>
                                    <option value="1">Stop Team Withdrawal</option>
                                    <option value="0">Start Team Withdrawal</option>
                                </select>
                            </div>

                            <input type="hidden" v-model="teamWithdrawalStartStopForm.user_id" />
                        </div>

                        <div class="modal-footer">
                            <button :disabled="teamWithdrawalStartStopForm.processing" type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { onMounted, reactive, ref } from "vue";
import { useForm,Link } from "@inertiajs/vue3";
import { toast } from "@/utils/toast";
import { Inertia } from "@inertiajs/inertia";
import Notification from "@/components/Notification";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import Paginator from "@/components/xino/Paginator.vue";
import axios from "axios";
import AdminPaginator from "@/components/AdminPaginator.vue";

export default {
    name: "Index",
    components: {AdminPaginator,  Notification , Link},
    layout: MainAdminLayout,
    props: {
        plans: Array,
        currencies: Array,
        tenures: Array,
    },
    setup(props) {
        const total_count = reactive({ count: null });
        const users = ref([]);
        const links = ref([]);
        const userStopRoi = ref(null);
        const pages = reactive({ current: 1, per_page: 10 });
        const filterText = ref(null);
        const selectedItem = ref(null);

        const statuses = ref([
            { text: "Active", value: "active" },
            { text: "InActive", value: "inactive" },
            { text: "All", value: "all" },
        ]);

        const status = ref("all");

        const stopRoiForm = useForm({
            user_id: selectedItem.value ? selectedItem.value.id : null,
            withdrawal: false,
            level: false,
            rank: false,
            stake: false,
            xeon: false,
            group_dividend: false,
        });

        const withdrawalLimitForm = useForm({
            user_id: selectedItem.value ? selectedItem.value.id : null,
            amount: null,
        });

        const teamWithdrawalStartStopForm = useForm({
            user_id: selectedItem.value ? selectedItem.value.id : null,
            withdrawal_stop: null,
        });

        const sending = ref(false);

        const purchaseForm = useForm({
            plan_id: null,
            tenure: null,
            amount_usd: null,
            user_id: null,
            with_staking: null,
        });

        const randomKey = ref(Date.now());

        function getUsers(url) {
            if (!url) {
                url = route("admin.users.get_users");
            }

            let params = {};

            if (status.value && status.value !== "all") {
                params.status = status.value;
            }

            if (filterText.value) {
                params.filter = filterText.value;
            }

            axios.get(url, { params })
                .then((res) => {
                    let response = res.data;
                    total_count.count = response.total ?? (response.meta ? response.meta.total : null);
                    users.value = response.data ?? response;
                    pages.current = response.current_page ?? (response.meta ? response.meta.current_page : pages.current);
                    pages.per_page = response.per_page ?? (response.meta ? response.meta.per_page : pages.per_page);
                    links.value = response.links ?? [];
                })
                .catch((err) => {
                    console.error("getUsers error", err);
                });
        }

        function filterSubmit() {
            let payload = {
                filter: filterText.value,
                status: status.value,
            };
            axios.post(route("admin.users.filter_users"), payload).then((res) => {
                let response = res.data;
                total_count.count = response.total ?? (response.meta ? response.meta.total : null);
                users.value = response.data ?? response;
                links.value = response.links ?? [];
                pages.current = response.current_page ?? (response.meta ? response.meta.current_page : pages.current);
                pages.per_page = response.per_page ?? (response.meta ? response.meta.per_page : pages.per_page);
            }).catch(err => {
                console.error("filterSubmit error", err);
            });
        }

        function setSelectedItem(item) {
            selectedItem.value = item;
            purchaseForm.user_id = item.id;

            teamWithdrawalStartStopForm.user_id = item.id;

            withdrawalLimitForm.user_id = item.id;
            withdrawalLimitForm.amount = selectedItem.value
                ? selectedItem.value.user_withdrawal_limit
                    ? selectedItem.value.user_withdrawal_limit.daily_limit
                    : null
                : null;

            stopRoiForm.user_id = item.id;
            stopRoiForm.withdrawal = selectedItem.value ? Boolean(selectedItem.value.user_stop?.withdrawal) : false;
            stopRoiForm.level = selectedItem.value ? selectedItem.value.user_stop?.level ?? 0 : 0;
            stopRoiForm.rank = selectedItem.value ? selectedItem.value.user_stop?.rank ?? 0 : 0;
            stopRoiForm.stake = selectedItem.value ? Boolean(selectedItem.value.user_stop?.stake) : false;
            stopRoiForm.xeon = selectedItem.value ? selectedItem.value.user_stop?.xeon ?? 0 : 0;
            stopRoiForm.group_dividend = selectedItem.value ? selectedItem.value.user_stop?.group_dividend ?? 0 : 0;
        }

        function isStaking(event) {
            if (event.target.value > 1) {
                purchaseForm.with_staking = 0;
            }
        }

        function saveInvoice() {
            sending.value = true;
            purchaseForm.post(route("admin.user.fund.deposit"), {
                onSuccess: () => {
                    toast("Fund added successfully.");
                    purchaseForm.reset("amount_usd", "plan_id", "tenure", "user_id");
                    // hide modal via bootstrap 5
                    const modal = document.getElementById("modalAddFund");
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                    getUsers(route("admin.users.get_users"));
                },
                onError: (errors) => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
            });
        }

        function saveStopRoi() {
            sending.value = true;
            stopRoiForm.post(route("admin.user.update.stop.roi"), {
                onSuccess: () => {
                    toast("Stop ROI updated successfully!");
                    stopRoiForm.reset("user_id", "withdrawal", "level", "rank", "stake", "xeon", "group_dividend");
                    const modal = document.getElementById("modalStopRoi");
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                    getUsers(route("admin.users.get_users"));
                },
                onError: (errors) => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
            });
        }

        function withdrawalLimitSave() {
            sending.value = true;
            withdrawalLimitForm.post(route("admin.user.update.withdrawal.limit"), {
                onSuccess: () => {
                    toast("Withdrawal limit successfully saved!");
                    withdrawalLimitForm.reset("user_id", "amount");
                    const modal = document.getElementById("modalWithdrawalLimit");
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                    getUsers(route("admin.users.get_users"));
                },
                onError: (errors) => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
            });
        }

        function teamWithdrawalStatusSave() {
            sending.value = true;
            teamWithdrawalStartStopForm.post(route("admin.user.team.start.stop.withdrawal"), {
                onSuccess: () => {
                    toast("Team withdrawal status saved successfully!");
                    teamWithdrawalStartStopForm.reset("user_id", "withdrawal_stop");
                    const modal = document.getElementById("modalStartStopWithdrawal");
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                    getUsers(route("admin.users.get_users"));
                },
                onError: (errors) => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
            });
        }

        function verifyEmail(user_id) {
            Inertia.post(route("admin.user.verify.email", [user_id]), {
                onSuccess: () => {
                    toast("Email Verified Successfully.", "success", 3000);
                    getUsers(route("admin.users.get_users"));
                },
                onError: (errors) => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
            });
        }


        function onlyNumber($event) {
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                $event.preventDefault();
            }
        }

        function paginatorResponse(response) {
            // Paginator component will emit the full response; adapt to your current state shape
            const res = response;
            total_count.count = res.total ?? (res.meta ? res.meta.total : null);
            users.value = res.data ?? res;
            pages.current = res.current_page ?? (res.meta ? res.meta.current_page : pages.current);
            pages.per_page = res.per_page ?? (res.meta ? res.meta.per_page : pages.per_page);
            links.value = res.links ?? [];
            // refresh key to force child re-renders if needed
            randomKey.value = Date.now();
        }

        function resetFilter() {
            filterText.value = null;
            status.value = "all";
            getUsers(route("admin.users.get_users"));
        }

        function goDashboard() {
            Inertia.visit(route("admin.dashboard"));
        }

        onMounted(() => {
            // initial call
            getUsers(route("admin.users.get_users"));
        });

        return {
            total_count,
            users,
            links,
            pages,
            getUsers,
            filterSubmit,
            filterText,
            setSelectedItem,
            selectedItem,
            purchaseForm,
            saveInvoice,
            verifyEmail,
            stopRoiForm,
            saveStopRoi,
            userStopRoi,
            statuses,
            status,
            isStaking,
            withdrawalLimitForm,
            withdrawalLimitSave,
            onlyNumber,
            teamWithdrawalStartStopForm,
            teamWithdrawalStatusSave,
            paginatorResponse,
            randomKey,
            resetFilter,
            goDashboard,
        };
    },
};
</script>

<style scoped>

</style>
