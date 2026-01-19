<template>
    <section class="content">
        <div class="container-fluid">

            <!-- ================= PROFILE CARD ================= -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">

                        <div class="card-body text-center">
                            <img
                                src="/assets/img/avatar.png"
                                class="img-circle elevation-2 mb-3"
                                width="120"
                            />

                            <h5 class="mb-0">{{ user.name }}</h5>
                            <p class="text-muted">{{ user.username }}</p>

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
                                        type="number"
                                        class="form-control"
                                        required
                                    />
                                </div>

                            </div>

                            <div class="card-footer">
                                <button class="btn btn-info" :disabled="walletForm.processing">
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
                                        type="number"
                                        class="form-control"
                                        required
                                    />
                                </div>

                            </div>

                            <div class="card-footer">
                                <button class="btn btn-info" :disabled="incomeWalletForm.processing">
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
                <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Control / Stop Settings</h3>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submit">

                            <Toggle label="Block User" v-model="form.is_blocked" />
                            <Toggle label="Level Income" v-model="form.level" />
                            <Toggle label="ROI Income" v-model="form.roi" />
                            <Toggle label="ROI on ROI" v-model="form.roi_on_roi" />
                            <Toggle label="Maturity Level" v-model="form.maturity_level" />
                            <Toggle label="Bonanza" v-model="form.bonanza" />
                            <Toggle label="Reward" v-model="form.reward" />
                            <Toggle label="Withdrawal" v-model="form.withdrawal" />

                            <button class="btn btn-primary mt-3" :disabled="form.processing">
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
                            <td colspan="7" class="text-center">
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
                                    class="badge"
                                    :class="subscription.is_active ? 'badge-success' : 'badge-secondary'"
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
    </section>
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
            investment_type : null,
            plan_id : 1,
            user_id: props.user.id
        })
        const profileForm = useForm({
            user_id: props.user.id,
            email: props.user.email,
            name: props.user.name,
            mobile: props.user.mobile
        })

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
            profileForm.post(route('admin.user.store'), {
                onSuccess: () => {
                    toast('User details successfully updated !')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
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
            level: props.user_stop?.level ?? false,
            roi: props.user_stop?.roi ?? false,
            roi_on_roi: props.user_stop?.roi_on_roi ?? false,
            maturity_level: props.user_stop?.maturity_level ?? false,
            bonanza: props.user_stop?.bonanza ?? false,
            reward: props.user_stop?.reward ?? false,
            withdrawal: props.user_stop?.withdrawal ?? false,
        });

        function submit() {
            form.post(route("admin.user.update.stops"));
        }

        return {form,subscriptions, paginatorResponse, profileForm, updateProfile, walletForm,incomeWalletForm, updateUsdWallet, updateIncomeWallet, userSubscriptionForm, createUserSubscription, submit}
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
