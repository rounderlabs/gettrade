<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Withdraw Bonus ({{ display_currency }})</h2>
            </div>

            <ul class="select-bank">
                <li>
                    <div class="balance-box active">
                        <input checked class="form-check-input" type="radio">
                        <img
                            class="img-fluid balance-box-img active"
                            src="/user-panel/assets-panel/assets/images/svg/balance-box-bg-active.svg"
                        >
                        <img
                            class="img-fluid balance-box-img unactive"
                            src="/user-panel/assets-panel/assets/images/svg/balance-box-bg.svg"
                        >
                        <div class="balance-content">
                            <h6>Available Balance</h6>
                            <h3>
                                {{ currency_symbol }} {{ available_balance_display }}
                            </h3>
                            <h5>Withdrawal Balance</h5>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <section>
        <div class="custom-container">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="news-update-box">

                        <form class="auth-form" @submit.prevent="submitWithdraw">

                            <div class="form-group">
                                <label class="form-label">
                                    Withdraw Amount ({{ display_currency }})
                                </label>

                                <div class="form-input">
                                    <input
                                        v-model="withdrawForm.amount"
                                        class="form-control border-start-0"
                                        :placeholder="`Minimum ${currency_symbol}10`"
                                        required
                                        type="number"
                                        step="0.01"
                                        @input="calculateFees"
                                    >
                                </div>
                            </div>

                            <div class="title mt-3">
                                <h2>Withdrawal Details</h2>
                            </div>

                            <div class="row g-3">

                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="bill-details">
                                            <h5 class="dark-text">Min Withdraw</h5>
                                        </div>
                                        <div class="bill-price">
                                            <h5>{{ currency_symbol }} 10.00</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="bill-details">
                                            <h5 class="dark-text">Withdrawal Fees</h5>
                                        </div>
                                        <div class="bill-price">
                                            <h5>{{ currency_symbol }} {{ fees }}</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="bill-details">
                                            <h5 class="dark-text">Withdrawal Amount</h5>
                                        </div>
                                        <div class="bill-price">
                                            <h5>{{ currency_symbol }} {{ amount }}</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="bill-details">
                                            <h5 class="dark-text">Receivable Amount</h5>
                                        </div>
                                        <div class="bill-price">
                                            <h5>{{ currency_symbol }} {{ receivableAmount }}</h5>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <button
                                :disabled="sending"
                                class="btn theme-btn w-100 mt-3"
                                type="submit"
                            >
                                Send Withdraw Request
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "@/utils/toast";

export default {
    name: "WithdrawRequest",
    layout: UserLayout,

    props: {
        available_balance_base: String,
        available_balance_display: String,
        display_currency: String,
        currency_symbol: String,
    },

    setup(props) {

        const sending = ref(false);
        const amount = ref("0.00");
        const fees = ref("0.00");
        const receivableAmount = ref("0.00");

        const withdrawForm = useForm({
            amount: null,
        });

        function calculateFees() {
            const entered = parseFloat(withdrawForm.amount || 0);

            if (entered >= 10) {
                amount.value = entered.toFixed(2);

                const calculatedFee = entered * 0.10;
                fees.value = calculatedFee.toFixed(2);

                receivableAmount.value = (
                    entered - calculatedFee
                ).toFixed(2);
            } else {
                amount.value = "0.00";
                fees.value = "0.00";
                receivableAmount.value = "0.00";
            }
        }

        function submitWithdraw() {
            sending.value = true;

            withdrawForm.post(route('withdraw.submit.request'), {
                onSuccess: () => {
                    toast('Withdrawal Requested Successfully', 'success');
                },
                onError: (errors) => {
                    Object.values(errors).forEach(error => {
                        toast(error, 'danger');
                    });
                },
                onFinish: () => sending.value = false
            });
        }

        return {
            withdrawForm,
            sending,
            submitWithdraw,
            amount,
            fees,
            receivableAmount,
            calculateFees
        };
    }
};
</script>
