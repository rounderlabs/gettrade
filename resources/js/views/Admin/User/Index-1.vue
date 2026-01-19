<template>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-14.jpg">
                    <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="camera" class="lucide lucide-camera w-4 h-4 text-white" data-lucide="camera"><path d="M14.5 4h-5L7 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle></svg> </div>
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ user.name }}</div>
                    <div class="text-slate-500">{{ user.ref_code }}</div>
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="mail" data-lucide="mail" class="lucide lucide-mail w-4 h-4 mr-2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ user.email }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="instagram" data-lucide="instagram" class="lucide lucide-instagram w-4 h-4 mr-2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg> {{ user.mobile }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="twitter" data-lucide="twitter" class="lucide lucide-twitter w-4 h-4 mr-2"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5 0-.28-.03-.56-.08-.83A7.72 7.72 0 0023 3z"></path></svg> {{ user.email }} </div>
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-5">User Wallet</div>
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-100 flex"> Activation Wallet: <span class="ml-3 font-large text-success">${{ user_usd_wallet.balance }}</span> USDT</div>

                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="mr-2 w-100 flex"> Income Wallet: <span class="ml-3 font-large text-success">${{ user_income_wallet.balance }}</span> USDT</div>

                </div>
            </div>
        </div>

    </div>


    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y box sm:col-span-12 lg:col-span-6 col-span-12">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Activation Wallet Modification
                </h2>
            </div>
            <div id="ActivationWallet" class="p-5">
                <form @submit.prevent="updateUsdWallet">
                <div class="intro-y col-span-12 sm:col-span-12">
                    <div>
                        <label for="txn-typ" class="form-label">Select Transaction Type</label>
                        <select id="txn-typ" class="form-select input w-full border bg-gray-100 cursor-not-allowed mt-2"  v-model="walletActivationForm.type" required aria-label="Default select example">
                            <option :value="null">Select Txn Type</option>
                            <option value="debit">DEDUCT</option>
                            <option value="credit">ADD</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input id="amount" type="number" v-model="walletActivationForm.amount" class="form-control input w-full border bg-gray-100  mt-2" required placeholder="Amount In USD">
                    </div>

                    <LoadingButton :disabled="walletActivationForm.processing" class="button bg-theme-1 text-white mt-5">Update Activation Balance <i v-if="walletActivationForm.processing" class="fa fa-spin fa-spinner"></i></LoadingButton>
                </div>
                </form>
            </div>
        </div>

        <div class="intro-y box sm:col-span-12 lg:col-span-6 col-span-12">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Withdrawal Wallet Modification
                </h2>
            </div>
            <div id="WithdrawalWallet" class="p-5">
                <form @submit.prevent="updateIncomeWallet">
                    <div class="intro-y col-span-12 sm:col-span-12">
                        <div>
                            <label for="txn-typ" class="form-label">Select Transaction Type</label>
                            <select id="txn-typ" class="form-select input w-full border bg-gray-100 cursor-not-allowed mt-2"  v-model="walletIncomeForm.type" required aria-label="Default select example">
                                <option :value="null">Select Txn Type</option>
                                <option value="debit">DEDUCT</option>
                                <option value="credit">ADD</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input id="amount" type="number" v-model="walletIncomeForm.amount" class="form-control input w-full border bg-gray-100  mt-2" required placeholder="Amount In USD">
                        </div>

                        <LoadingButton :disabled="walletIncomeForm.processing" class="button bg-theme-1 text-white mt-5">Update Withdrawal Balance <i v-if="walletIncomeForm.processing" class="fa fa-spin fa-spinner"></i></LoadingButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y box sm:col-span-12 lg:col-span-6 col-span-12">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    This User Team Sales
                </h2>
            </div>
            <div id="ActivationWallet" class="p-5">
                <div>
                    <label class="tx-12 text-muted mb-0">Team Subscription Amount</label>
                    <p class="font-weight-bold tx-20">{{ team_subscriptions_amount_count }}</p>
                </div>
                <div>
                    <label class="tx-12 text-muted mb-0">Team Withdrawal Amount</label>
                    <p class="font-weight-bold tx-20">{{ team_withdrawal_amount_count }}</p>
                </div>
            </div>
        </div>

        <div class="intro-y box sm:col-span-12 lg:col-span-6 col-span-12">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    User Profile Update Form
                </h2>
            </div>
            <div id="WithdrawalWallet" class="p-5">
                <form @submit.prevent="updateProfile">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Email</label>
                            </div>
                            <div class="col-md-9">
                                <input v-model="profileForm.email" class="form-control input w-full border bg-gray-100  mt-2" placeholder="Email"
                                       type="email">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Name Of User</label>
                            </div>
                            <div class="col-md-9">
                                <input v-model="profileForm.name" class="form-control input w-full border bg-gray-100  mt-2"
                                       placeholder="Full Name"
                                       type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Mobile Number
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input v-model="profileForm.mobile" class="form-control input w-full border bg-gray-100  mt-2"
                                       placeholder="Full Name"
                                       type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Joined On</label>
                            </div>
                            <div class="col-md-9">
                                <input :value="user.created_at" class="form-control input w-full border bg-gray-100  mt-2" disabled
                                       placeholder="Joined On"
                                       readonly
                                       type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-3">
                                <label >Active On</label>
                            </div>
                            <div class="col-md-9">
                                <input :value="user.active_at" class="form-control input w-full border bg-gray-100  mt-2" disabled
                                       placeholder="Active On"
                                       readonly
                                       type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9 text-right">
                                <LoadingButton :disabled="profileForm.processing" class="button bg-theme-1 text-white mt-5">
                                    Update <i v-if="profileForm.processing" class="fa fa-spin fa-spinner"></i>
                                </LoadingButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">
        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">My Subscriptions</h2>
        </div>
        <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
            <table class="table table-report sm:mt-2">
                <thead>
                <tr>
                    <th class="whitespace-nowrap">Sr Number</th>
                    <th class="whitespace-nowrap">Package Name</th>
                    <th class="text-center whitespace-nowrap">Amount (USDT)</th>
                    <th class="text-center whitespace-nowrap">Max Limit (USDT)</th>
                    <th class="text-center whitespace-nowrap">Earned Yet (USDT)</th>
                    <th class="text-center whitespace-nowrap">Status</th>
                    <th class="text-center whitespace-nowrap">Subscribed On</th>
                </tr>
                </thead>
                <tbody>
                <tr class="intro-x" v-if="!subscriptions.length">
                    <td colspan="5" class="w-100">
                        <div class="flex">
                            <div class="w-100 h-10 image-fit zoom-in">
                                <p>There is no subscriptions</p>
                            </div>
                        </div>
                    </td>


                </tr>
                <tr class="intro-x " v-for="(subscription,index) in subscriptions">
                    <td class="w-40">
                        <div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in">
                                {{ index + 1 }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.plan.name }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Type</div>
                    </td>
                    <td class="text-center">${{ subscription.amount }} USDT</td>
                    <td class="text-center">${{ subscription.max_income_limit }}</td>
                    <td class="text-center">${{ subscription.earned_so_far }}</td>
                    <td class="w-40">
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.is_active ? 'Enabled' : 'Disabled' }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Status</div>
                    </td>
                    <td class="table-report__action w-56">
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.subscription_date }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Active From</div>
                    </td>
                </tr>

                </tbody>
            </table>
            <Paginator :base-url="route('admin.user.subscriptions',[user.id])"
                       @responseData="paginatorResponse"></Paginator>
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

export default {
    name: "Index",
    components: {LoadingButton, Paginator, Link},
    layout: MainAdminLayout,
    props: {
        user: Object,
        sponsor: Object,
        team_subscriptions_amount_count: String,
        team_withdrawal_amount_count: String,
        user_usd_wallet: Object,
        user_income_wallet: Object
    },
    setup(props) {
        const walletIncomeForm = useForm({
            amount: null,
            type: null,
            user_id: props.user.id
        })
        const walletActivationForm = useForm({
            amount: null,
            type: null,
            user_id: props.user.id
        })
        const profileForm = useForm({
            user_id: props.user.id,
            email: props.user.email,
            name: props.user.name,
            mobile: props.user.mobile
        })

        function updateUsdWallet() {
            walletActivationForm.post(route('admin.user.update.activation.wallet.balance'), {
                onSuccess: () => {
                    walletActivationForm.reset('amount', 'type')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        function updateIncomeWallet() {
            walletIncomeForm.post(route('admin.user.update.income.wallet.balance'), {
                onSuccess: () => {
                    walletIncomeForm.reset('amount', 'type')
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

        return {subscriptions, paginatorResponse, profileForm, updateProfile, walletActivationForm, walletIncomeForm, updateUsdWallet, updateIncomeWallet}
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
