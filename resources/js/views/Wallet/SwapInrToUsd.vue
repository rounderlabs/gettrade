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
                                        Swap INR to USDT
                                    </h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">

                            <h6 class="fw-medium mb-4 text-center">
                                 INR <i class="bi-arrow-left-right h4 me-1 avatar me-2"></i> USDT
                            </h6>
                            <h6 class="fw-medium mb-4 text-center">
                                <i class="bi bi-wallet h4 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i><br>
                                ₹ {{ user_income_wallet.balance }} <br>
                                <span>Income Balance</span>
                            </h6>

                            <div class="col-12  mb-4 mb-md-0">

                                <form @submit.prevent="submitSwapInrToUsdForm">
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-currency-rupee">₹</i></span>
                                                <div class="form-floating">
                                                    <input class="form-control border-start-0" v-model="swapInrToUsdForm.amount_in_inr"
                                                           placeholder="Enter INR Amount For Swapping" @change="calculateUsd"
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
                                                class="bi bi-currency-dollar"></i></span>
                                                <div class="form-floating">
                                                    <input class="form-control border-start-0" v-model="amount_in_usd" placeholder="0.00"  type="text" readonly>
                                                    <label>Receivable USDT</label>
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
    name: "SwapInrToUsd",
    components: { FontAwesomeIcon },
    layout: UserLayout,
    props: {
        user_income_wallet: Object,
        user_usd_wallet: Object,
        user_inr_wallet: Object,
        usd_in_inr:Object,
    },
    setup(props) {

        const amount_in_usd = ref(parseFloat('0').toFixed(6))
        const sending = ref(false)

        const swapInrToUsdForm = useForm({
            amount_in_inr: null,
        })

        function calculateUsd() {
            console.log(swapInrToUsdForm.amount_in_inr)
            if (swapInrToUsdForm.amount_in_inr >= 1) {
                amount_in_usd.value =parseFloat(parseFloat(swapInrToUsdForm.amount_in_inr).toFixed(2) / parseFloat(props.usd_in_inr.price_in_usd).toFixed(2)).toFixed(2)
            }

        }


        function submitSwapInrToUsdForm() {
            swapInrToUsdForm.post(route('wallet.submit.swap.inr.usd'), {
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

        return {swapInrToUsdForm, submitSwapInrToUsdForm, amount_in_usd, sending, calculateUsd}
    }
}
</script>

<style scoped>

</style>
