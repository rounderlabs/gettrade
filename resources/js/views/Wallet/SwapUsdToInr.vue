<template>
    <main class="main mainheight" style="min-height: 621.039px; margin-top: 60.2109px; padding-bottom: 200px;">
        <div class="container">
            <div id="walletForm" class="row mx-auto mb-100">

                <div class="col-12 col-md-6 col-lg-6 col-xxl-6 mb-4 ">
                    <!-- Inventory card -->
                    <div class="card border-0 bg-gradient-theme-light theme-blue h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="fw-medium">
                                        <i class="bi bi-arrow-left-right h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                                        Swap USDT to INR
                                    </h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">

                            <h6 class="fw-medium mb-4 text-center">
                                USDT <i class="bi-arrow-left-right h4 me-1 avatar me-2"></i> INR
                            </h6>
                            <h6 class="fw-medium mb-4 text-center">
                                <i class="bi bi-wallet h4 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i><br>
                                ₹ {{ user_usd_wallet.balance }} <br>
                                <span>USDT Wallet Balance</span>
                            </h6>

                            <div class="col-12  mb-4 mb-md-0">

                                <form @submit.prevent="submitSwapUsdToInrForm">
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-currency-dollar"></i></span>
                                                <div class="form-floating">
                                                    <input class="form-control border-start-0" v-model="swapUsdToInrForm.amount_in_usd"
                                                           placeholder="Enter USDT Amount For Swapping" @change="calculateInr"
                                                           required type="text">
                                                    <label>Enter amount</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">Add .com at last to insert valid data</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-at"></i></span>
                                                <div class="form-floating">
                                                    <input class="form-control border-start-0" v-model="usd_in_inr.price_in_usd" readonly type="text" >
                                                    <label>Current USDT Rate in INR</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">Add .com at last to insert valid data</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-currency-rupee">₹</i></span>
                                                <div class="form-floating">
                                                    <input class="form-control border-start-0" v-model="amount_in_inr" placeholder="0.00"  type="text" readonly>
                                                    <label>Receivable ₹ (INR)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">Add .com at last to insert valid data</div>
                                    </div>
                                    <div class="mb-2">
                                        <button :disabled="sending" class="btn btn-theme w-100" type="submit">
                                            Swap Now
                                            <font-awesome-icon v-if="sending" class="ml-1" icon="spinner" spin />
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import {toast} from "@/utils/toast";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export default {
    name: "SwapUsdToInr",
    components: { FontAwesomeIcon },
    layout: UserLayout,
    props: {
        user_income_wallet: Object,
        user_usd_wallet: Object,
        user_inr_wallet: Object,
        usd_in_inr:Object,
    },
    setup(props) {
        const amount_in_inr = ref(parseFloat('0').toFixed(2))
        const sending = ref(false)

        const swapUsdToInrForm = useForm({
            amount_in_usd: null,
        })

        function calculateInr() {
            if (swapUsdToInrForm.amount_in_usd >= 1) {
                amount_in_inr.value =parseFloat(parseFloat(swapUsdToInrForm.amount_in_usd).toFixed(2) * parseFloat(props.usd_in_inr.price_in_usd).toFixed(2)).toFixed(2)
            }

        }


        function submitSwapUsdToInrForm() {
            swapUsdToInrForm.post(route('wallet.submit.swap.usd.inr'), {
                onSuccess: page => {
                    toast('Swapping Successfully Completed')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }

                }
            })
        }

        return {swapUsdToInrForm, submitSwapUsdToInrForm, amount_in_inr, sending, calculateInr}
    }
}
</script>

<style scoped>

</style>
