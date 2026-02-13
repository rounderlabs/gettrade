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
                             src="/sunlotusinfra/assets-panel/assets/images/svg/balance-box-bg-active.svg">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img unactive"
                             src="/sunlotusinfra/assets-panel/assets/images/svg/balance-box-bg.svg">
                        <div class="balance-content">
                            <h6>Balance</h6>
                            <h3>${{ withdrawable_balance }}</h3>
                            <h5>Withdrawal Balance </h5>
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
                    <!-- Inventory card -->
                    <div class="news-update-box">
                        <form v-if="!otpSent" class="auth-form" @submit.prevent="withdrawAttempt">
<!--                            <div class="form-group">-->
<!--                                <label class="form-label" for="network">Select Network</label>-->
<!--                                <div class="form-input">-->
<!--                                    <select id="network" class="form-select form-control " required="">-->
<!--                                        <option value="">Choose...</option>-->
<!--                                        <option>BNB Smart Chain</option>-->
<!--                                    </select>-->

<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-group">-->
<!--                                <label class="form-label" for="token">Select Token</label>-->
<!--                                <div class="form-input">-->
<!--                                    <select id="token" class="form-select border-0" required="">-->
<!--                                        <option value="">Choose...</option>-->
<!--                                        <option>BEP20_USDT</option>-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="form-group">
                                <label class="form-label">Withdraw Amount in USDT</label>
                                <div class="form-input">
                                    <input v-model="withdrawForm.amount"
                                           class="form-control border-start-0" placeholder="Minimum $10"
                                           required type="text" @change="calculateFees">
                                </div>
                            </div>

                            <div class="title mt-3">
                                <h2>Withdrawal Details</h2>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">

                                            <div class="bill-details">
                                                <h5 class="dark-text">Min Withdraw</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>$10.00</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">
                                            <div class="bill-details">
                                                <h5 class="dark-text">Withdrawals Fees</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ fees }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">

                                            <div class="bill-details">
                                                <h5 class="dark-text">Withdrawal Amount</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ amount }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">
                                            <div class="bill-details">
                                                <h5 class="dark-text">Receivable Amount</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ receivableAmount }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <button :disabled="sending" class="btn theme-btn w-100"
                                    type="submit">
                                Submit and Request OTP
                                <font-awesome-icon v-if="sending" class="ml-1" icon="spinner" spin/>
                            </button>

                        </form>
                        <form v-if="otpSent" class="auth-form mt-3" @submit.prevent="submitWithdraw">

                            <div class="form-group">
                                <label class="form-label">Withdraw Amount in USDT</label>
                                <div class="form-input">
                                    <input v-model="withdrawForm.amount"
                                           class="form-control" placeholder="Minimum $10"
                                           required type="text">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="form-label">Verify OTP</label>
                                <div class="form-input">
                                    <input v-model="withdrawForm.otp"
                                           class="form-control" placeholder="Enter OTP"
                                           required="" type="text">
                                </div>
                            </div>
                            <div class="title mt-3">
                                <h2>Withdrawal Details</h2>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">

                                            <div class="bill-details">
                                                <h5 class="dark-text">Min Withdraw</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>$10.00</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">
                                            <div class="bill-details">
                                                <h5 class="dark-text">Withdrawals Fees</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ fees }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">

                                            <div class="bill-details">
                                                <h5 class="dark-text">Withdrawal Amount</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ amount }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="bill-box">
                                        <div class="d-flex gap-3">
                                            <div class="bill-details">
                                                <h5 class="dark-text">Receivable Amount</h5>
                                            </div>
                                        </div>
                                        <div class="bill-price">
                                            <h5>${{ receivableAmount }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button :disabled="sending" class="btn theme-btn w-100"
                                    type="submit">
                                Withdraw Now
                                <font-awesome-icon v-if="sending" class="ml-1" icon="spinner" spin/>
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
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import {toast} from "@/utils/toast";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import UserIncomeComponent from "@/components/UserIncomeComponent.vue";

export default {
    name: "WithdrawUsdt",
    components: {UserIncomeComponent, FontAwesomeIcon},
    layout: UserLayout,
    props: {
        user_income_stats: Object,
        income_wallet: Object,
        withdrawable_balance: String,
    },
    setup() {
        const otpSent = ref(false)
        const sending = ref(false)
        const amount = ref(parseFloat('0').toFixed(2))
        const fees = ref(parseFloat('0').toFixed(2))
        const receivableAmount = ref(parseFloat('0').toFixed(2))

        const withdrawForm = useForm({
            amount: null,
            otp: null,
            withdraw_id: null
        })

        function calculateFees() {
            if (withdrawForm.amount >= 10) {
                let amountInUsd;
                amount.value = withdrawForm.amount
                amountInUsd = withdrawForm.amount
                fees.value = parseFloat(amountInUsd * 0.10).toFixed(2)
                receivableAmount.value = parseFloat(amountInUsd) - parseFloat(fees.value)

            }

        }

        function withdrawAttempt() {
            withdrawForm.post(route('withdraw.usdt.attempt'), {
                onSuccess: page => {
                    otpSent.value = true

                    withdrawForm.withdraw_id = page.props.flash.message.withdraw_id
                    toast('OTP sent successfully', 'success')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        // function sendOtpEmail() {
        //     Inertia.post(route('withdraw.sendOtp'), {}, {
        //         onSuccess: page => {
        //             // updateTrxWallet()
        //             otpSent.value = true
        //
        //         },
        //         onError: errors => {
        //
        //         }
        //     })
        // }

        function submitWithdraw() {
            withdrawForm.post(route('withdraw.verify'), {
                onSuccess: page => {
                    toast('Withdrawal Successfull')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }

                }
            })
        }

        return {
            withdrawForm,
            sending,
            otpSent,
            submitWithdraw,
            withdrawAttempt,
            amount,
            fees,
            receivableAmount,
            calculateFees
        }
    }
}
</script>

<style scoped>

.select-bank {
    display: flex;
    align-items: center;

    li {
        @media (max-width: 480px) {
            margin: -5px;
        }

        .balance-box {
            position: relative;

            .balance-content {
                position: absolute;
                top: calc(12px + (15 - 12) * ((100vw - 320px) / (1920 - 320)));
                left: calc(12px + (15 - 12) * ((100vw - 320px) / (1920 - 320)));
                padding: calc(6px + (10 - 6) * ((100vw - 320px) / (1920 - 320)));

                h6 {
                    font-weight: 400;
                    color: rgba(var(--light-text), 1);

                    [dir="rtl"] & {
                        margin-right: 10px;
                    }
                }

                h3 {
                    margin-top: 5px;
                    font-weight: 600;
                    color: rgba(var(--theme-color), 1);
                }

                h5 {
                    margin-top: 10px;
                    font-weight: 400;
                    color: rgba(var(--dark-text), 1);
                }
            }

            .unactive {
                display: block;
                width: 100%;

                [class="dark"] & {
                    filter: invert(1);
                }
            }

            .active {
                display: none;
                width: 100%;
            }

            .form-check-input {
                position: absolute;
                top: 5px;
                right: 10px;
                width: calc(20px + (30 - 20) * ((100vw - 320px) / (1920 - 320)));
                height: calc(20px + (30 - 20) * ((100vw - 320px) / (1920 - 320)));
                margin-top: 0;
                border-radius: 100%;
                border: none;
                box-shadow: none;
                background-color: rgba(var(--box-bg), 0.15);

                &:checked {
                    background-color: rgba(98, 44, 253, 1);
                    border-color: rgba(var(--theme-color), 1);

                    ~ .unactive {
                        display: none;
                        width: 100%;
                    }

                    ~ .active {
                        display: block;
                        width: 100%;
                    }
                }
            }


        }
    }
}


</style>
