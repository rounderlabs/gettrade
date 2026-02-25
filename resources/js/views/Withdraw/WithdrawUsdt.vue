<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>withdraw USDT</h2>
            </div>
            <ul class="select-bank">
                <li>
                    <div class="balance-box active">
                        <input checked="" class="form-check-input" name="flexRadio" type="radio">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img active"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg-active.svg">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img unactive"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg.svg">
                        <div class="balance-content">
                            <h6>Balance</h6>
                            <h3>{{ currency_symbol }} {{ income_wallet.balance_display }}</h3>
                            <h5 class="dark-text">Withdrawal Balance </h5>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <section>
        <div class="custom-container">
            <div class="news-update-box">

                <!-- STEP 1 -->
                <form v-if="!otpSent" @submit.prevent="withdrawAttempt">

                    <div class="form-group">
                        <label class="form-label dark-text">
                            Withdraw Amount in {{ display_currency }}
                        </label>

                        <div class="form-input">
                            <input
                                v-model="withdrawForm.amount"
                                class="form-control"
                                :placeholder="`Minimum ${currency_symbol}10`"
                                type="number"
                                step="0.01"
                                required
                                @input="calculateFees"
                            >
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-3 col-6">
                            <div class="bill-box theme-color">
                                <h5 class="dark-text">Min Withdraw</h5>
                                <h5 class="dark-text">{{ currency_symbol }}10.00</h5>
                            </div>
                        </div>

                        <div class="col-md-3 col-6">
                            <div class="bill-box">
                                <h5 class="dark-text">Withdrawal Fees</h5>
                                <h5 class="dark-text">{{ currency_symbol }}{{ fees }}</h5>
                            </div>
                        </div>

                        <div class="col-md-3 col-6">
                            <div class="bill-box">
                                <h5 class="dark-text">Withdrawal Amount</h5>
                                <h5 class="dark-text">{{ currency_symbol }}{{ amount }}</h5>
                            </div>
                        </div>

                        <div class="col-md-3 col-6">
                            <div class="bill-box">
                                <h5 class="dark-text">Receivable Amount</h5>
                                <h5 class="dark-text">{{ currency_symbol }}{{ receivableAmount }}</h5>
                            </div>
                        </div>
                    </div>

                    <button
                        :disabled="sending"
                        class="btn theme-btn w-100 mt-3"
                        type="submit"
                    >
                        Submit & Request OTP
                    </button>
                </form>

                <!-- STEP 2 -->
                <form v-else @submit.prevent="submitWithdraw">

                    <div class="form-group">
                        <label class="form-label">
                            Withdraw Amount in {{ display_currency }}
                        </label>

                        <input
                            v-model="withdrawForm.amount"
                            class="form-control"
                            type="number"
                            step="0.01"
                            required
                        />
                    </div>

                    <div class="form-group mt-2">
                        <label class="form-label dark-text">Verify OTP</label>

                        <input
                            v-model="withdrawForm.otp"
                            class="form-control"
                            placeholder="Enter 6 digit OTP"
                            required
                            type="text"
                        />
                    </div>

                    <button
                        :disabled="sending"
                        class="btn theme-btn w-100 mt-3"
                        type="submit"
                    >
                        Withdraw Now
                    </button>
                </form>

            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import {toast} from "@/utils/toast";

export default {
    name: "WithdrawUsdt",
    layout: UserLayout,

    props: {
        income_wallet: Object,
        withdrawable_balance: String,
        display_currency: String,
        currency_symbol: String,
    },

    setup(props) {

        const otpSent = ref(false);
        const sending = ref(false);

        const amount = ref("0.00");
        const fees = ref("0.00");
        const receivableAmount = ref("0.00");

        const withdrawForm = useForm({
            amount: null,
            otp: null,
            withdraw_id: null
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

        function withdrawAttempt() {
            sending.value = true;

            withdrawForm.post(route('withdraw.usdt.attempt'), {
                onSuccess: (page) => {
                    otpSent.value = true;
                    withdrawForm.withdraw_id =
                        page.props.flash.message.withdraw_id;

                    toast('OTP sent successfully', 'success');
                },
                onError: (errors) => {
                    Object.values(errors).forEach(error => {
                        toast(error, 'danger');
                    });
                },
                onFinish: () => sending.value = false
            });
        }

        function submitWithdraw() {
            sending.value = true;

            withdrawForm.post(route('withdraw.verify'), {
                onSuccess: () => {
                    toast('Withdrawal submitted successfully', 'success');
                },
                onError: (errors) => {
                    Object.values(errors).forEach(error => {
                        toast(error, 'danger');
                    });
                },
                onFinish: () => sending.value = false
            });
        }

        watch(() => withdrawForm.amount, calculateFees);

        return {
            withdrawForm,
            otpSent,
            sending,
            amount,
            fees,
            receivableAmount,
            withdrawAttempt,
            submitWithdraw
        };
    }
};
</script>
