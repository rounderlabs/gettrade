<template>
    <section class="content">
        <div class="container-fluid">

            <!-- ================= PROFILE CARD ================= -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">

                        <div class="card-body text-center">
                            <img
                                class="img-circle elevation-2 mb-3"
                                src="/assets/img/avatar.png"
                                width="120"
                            />

                            <h5 class="mb-0">{{ user.name }}</h5>
                            <p class="text-muted">{{ user.username }}</p>
                            <button
                                class="btn btn-sm btn-primary mt-2"
                                data-bs-target="#editProfileModal"
                                data-bs-toggle="modal"
                            >
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                            <hr>

                            <div class="row text-left">
                                <div class="col-6">
                                    <strong>Activation Wallet</strong>
                                    <p class="text-success">
                                        ₹ {{ user_usd_wallet.balance }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <strong>Income Wallet</strong>
                                    <p class="text-danger">₹ {{ user_income_wallet.balance }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= MODIFY WALLET ================= -->
                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-wallet mr-2"></i>
                                Modify Activation Balance
                            </h3>
                        </div>

                        <form @submit.prevent="updateUsdWallet">
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Transaction Type</label>
                                    <select v-model="walletForm.type" class="form-control" required>
                                        <option :value="null">Select</option>
                                        <option value="credit">Add</option>
                                        <option value="debit">Deduct</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Amount (₹)</label>
                                    <input
                                        v-model="walletForm.amount"
                                        class="form-control"
                                        required
                                        type="number"
                                    />
                                </div>

                            </div>

                            <div class="card-footer">
                                <button :disabled="walletForm.processing" class="btn btn-info">
                                    Update Activation Wallet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-wallet mr-2"></i>
                                Modify Income Balance
                            </h3>
                        </div>

                        <form @submit.prevent="updateIncomeWallet">
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Transaction Type</label>
                                    <select v-model="incomeWalletForm.type" class="form-control" required>
                                        <option :value="null">Select</option>
                                        <option value="credit">Add</option>
                                        <option value="debit">Deduct</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Amount (₹)</label>
                                    <input
                                        v-model="incomeWalletForm.amount"
                                        class="form-control"
                                        required
                                        type="number"
                                    />
                                </div>

                            </div>

                            <div class="card-footer">
                                <button :disabled="incomeWalletForm.processing" class="btn btn-info">
                                    Update Income Balance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ================= TEAM STATS ================= -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-success">
                        <div class="card-body text-center">
                            <h6>Team Subscription Amount</h6>
                            <h3>₹ {{ team_subscriptions_amount_count }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-warning">
                        <div class="card-body text-center">
                            <h6>Team Withdrawal Amount</h6>
                            <h3>₹ {{ team_withdrawal_amount_count }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-info">
                        <div class="card-body text-center">

                            <h6>Unlocked Level</h6>

                            <h3 class="mb-2">
                                {{ user.manual_unlocked_level > 0
                                ? user.manual_unlocked_level
                                : user.auto_unlocked_level }}
                            </h3>

                            <small class="text-muted d-block mb-3">
                                {{ user.manual_unlocked_level > 0
                                ? 'Manual Override'
                                : 'Auto Calculated' }}
                            </small>

                            <button
                                class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#manualLevelModal"
                            >
                                <i class="fas fa-edit"></i> Update Level
                            </button>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Control / Stop Settings</h3>
                        </div>

                        <div class="card-body">
                            <form @submit.prevent="submit">

                                <Toggle v-model="form.is_blocked" label="Block User"/>
                                <Toggle v-model="form.direct" label="Direct Bonus"/>
                                <Toggle v-model="form.roi" label="Trading Bonus"/>
                                <Toggle v-model="form.roi_on_roi" label="Systematic Bonus"/>
                                <Toggle v-model="form.rank" label="Rank Bonus"/>
                                <Toggle v-model="form.bonanza" label="Bonanza"/>
                                <Toggle v-model="form.reward" label="Reward"/>
                                <Toggle v-model="form.withdrawal" label="Withdrawal"/>
                                <hr>
                                <Toggle label="Allow Fund Transfer" v-model="form.can_transfer" />
                                <Toggle label="Allow Downline Activation" v-model="form.can_activate_downline" />

                                <button :disabled="form.processing" class="btn btn-primary mt-3">
                                    Save Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= SUBSCRIPTIONS TABLE ================= -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i> Subscriptions
                    </h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Earned</th>
                            <th>Status</th>
                            <th>Activated</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-if="!subscriptions.length">
                            <td class="text-center" colspan="7">
                                No subscriptions found
                            </td>
                        </tr>

                        <tr v-for="(subscription, index) in subscriptions" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ subscription.plan.name }}</td>
                            <td>₹ {{ subscription.amount }}</td>
                            <td>₹ {{ subscription.earned_so_far }}</td>
                            <td>
                                <span
                                    :class="subscription.is_active ? 'badge-success' : 'badge-secondary'"
                                    class="badge"
                                >
                                    {{ subscription.is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>{{ subscription.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <Paginator
                        :base-url="route('admin.user.subscriptions', [user.id])"
                        @responseData="paginatorResponse"
                    />
                </div>
            </div>

        </div>
        <!-- ================= EDIT PROFILE MODAL ================= -->
        <div
            id="editProfileModal"
            aria-hidden="true"
            class="modal fade"
            tabindex="-1"
        >
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Update User Profile
                        </h5>
                        <button
                            class="btn-close"
                            data-bs-dismiss="modal"
                            type="button"
                        ></button>
                    </div>

                    <!-- FORM -->
                    <form @submit.prevent="updateProfile">
                        <div class="modal-body">

                            <!-- NAME -->
                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input
                                    v-model="profileForm.name"
                                    class="form-control"
                                    placeholder="Enter Name"
                                    required
                                    type="text"
                                />
                            </div>

                            <!-- EMAIL -->
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input
                                    v-model="profileForm.email"
                                    class="form-control"
                                    placeholder="Enter Email"
                                    required
                                    type="email"
                                />
                            </div>

                            <!-- MOBILE -->
                            <div class="form-group mb-3">
                                <label>Mobile</label>
                                <input
                                    v-model="profileForm.mobile"
                                    class="form-control"
                                    placeholder="Enter Mobile"
                                    required
                                    type="text"
                                />
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer">
                            <button
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                                type="button"
                            >
                                Cancel
                            </button>

                            <button
                                :disabled="profileForm.processing"
                                class="btn btn-primary"
                                type="submit"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>
    <!-- ================= MANUAL LEVEL MODAL ================= -->
    <div
        id="manualLevelModal"
        aria-hidden="true"
        class="modal fade"
        tabindex="-1"
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Update Manual Unlocked Level
                    </h5>
                    <button
                        class="btn-close"
                        data-bs-dismiss="modal"
                        type="button"
                    ></button>
                </div>

                <form @submit.prevent="updateManualLevel">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label>Current Active Level</label>
                            <input
                                :value="user.manual_unlocked_level > 0
                                ? user.manual_unlocked_level
                                : user.auto_unlocked_level"
                                class="form-control"
                                disabled
                                type="text"
                            />
                        </div>

                        <div class="form-group mb-3">
                            <label>Manual Override Level</label>
                            <input
                                v-model="manualLevelForm.manual_unlocked_level"
                                class="form-control"
                                max="20"
                                min="0"
                                placeholder="Enter level (0 = auto)"
                                required
                                type="number"
                            />
                            <small class="text-muted">
                                Set 0 to enable auto calculation
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            type="button"
                        >
                            Cancel
                        </button>

                        <button
                            :disabled="manualLevelForm.processing"
                            class="btn btn-primary"
                            type="submit"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>


<script>
import {Link, useForm} from '@inertiajs/vue3'
import LoadingButton from "@/components/LoadingButton";
import {toast} from "@/utils/toast";
import Paginator from "@/components/xino/Paginator";
import {ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import Toggle from "@/components/Toggle.vue";

export default {
    name: "Index",
    components: {Toggle, LoadingButton, Paginator, Link},
    layout: MainAdminLayout,
    props: {
        user: Object,
        sponsor: Object,
        team_subscriptions_amount_count: String,
        team_withdrawal_amount_count: String,
        user_usd_wallet: Object,
        user_income_wallet: Object,
        user_stop: Object,
    },
    setup(props) {
        const walletForm = useForm({
            amount: null,
            type: null,
            user_id: props.user.id
        })

        const incomeWalletForm = useForm({
            amount: null,
            type: null,
            user_id: props.user.id
        })

        const userSubscriptionForm = useForm({
            amount: null,
            type: 'credit',
            investment_type: null,
            plan_id: 1,
            user_id: props.user.id
        })
        const profileForm = useForm({
            user_id: props.user.id,
            email: props.user.email,
            name: props.user.name,
            mobile: props.user.mobile
        })

        const manualLevelForm = useForm({
            user_id: props.user.id,
            manual_unlocked_level: props.user.manual_unlocked_level ?? 0
        })

        function updateManualLevel() {
            manualLevelForm.post(route('admin.user.update.manual.level'), {
                onSuccess: () => {
                    const modalEl = document.getElementById("manualLevelModal")

                    // Properly hide modal
                    const modalInstance = bootstrap.Modal.getInstance(modalEl)
                    if (modalInstance) {
                        modalInstance.hide()
                    }

                    // Force cleanup (important)
                    document.body.classList.remove('modal-open')
                    document.body.style.removeProperty('padding-right')

                    document.querySelectorAll('.modal-backdrop')
                        .forEach(el => el.remove())

                    toast("Unlocked level updated successfully", "success")
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        function updateUsdWallet() {
            walletForm.post(route('admin.user.update.activation.wallet.balance'), {
                onSuccess: () => {
                    walletForm.reset('amount', 'type')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        function updateIncomeWallet() {
            incomeWalletForm.post(route('admin.user.update.income.wallet.balance'), {
                onSuccess: () => {
                    incomeWalletForm.reset('amount', 'type')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        function createUserSubscription() {
            userSubscriptionForm.post(route('admin.user.create.investment'), {
                onSuccess: () => {
                    userSubscriptionForm.reset('amount', 'type')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        function updateProfile() {
            profileForm.post(route("admin.user.store"), {
                onSuccess: () => {
                    //  toast("Profile Updated Successfully")

                    const modalEl = document.getElementById("editProfileModal")
                    const modal = bootstrap.Modal.getInstance(modalEl)

                    modal.hide()

                    // Cleanup backdrop
                    modalEl.addEventListener("hidden.bs.modal", () => {
                        document.querySelectorAll(".modal-backdrop").forEach(el => el.remove())
                        document.body.classList.remove("modal-open")
                    }, {once: true})
                }
            })
        }


        const subscriptions = ref([]);

        function paginatorResponse(data) {
            subscriptions.value = data
        }

        const form = useForm({
            user_id: props.user.id,
            is_blocked: props.user_stop?.is_blocked ?? false,
            direct: props.user_stop?.direct ?? false,
            roi: props.user_stop?.roi ?? false,
            roi_on_roi: props.user_stop?.roi_on_roi ?? false,
            rank: props.user_stop?.rank ?? false,
            bonanza: props.user_stop?.bonanza ?? false,
            reward: props.user_stop?.reward ?? false,
            withdrawal: props.user_stop?.withdrawal ?? false,

            can_transfer: props.user.can_transfer ?? false,
            can_activate_downline: props.user.can_activate_downline ?? false,
        });

        function submit() {
            form.post(route("admin.user.update.stops"));
        }

        return {
            manualLevelForm,
            updateManualLevel,
            form,
            subscriptions,
            paginatorResponse,
            profileForm,
            updateProfile,
            walletForm,
            incomeWalletForm,
            updateUsdWallet,
            updateIncomeWallet,
            userSubscriptionForm,
            createUserSubscription,
            submit
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
