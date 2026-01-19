<template>
    <div class="grid grid-cols-1 lg:grid-cols-5 xl:grid-cols-5 gap-6">
        <div class="lg:col-start-2 lg:col-span-3 xl:col-start-2 xl:col-span-3">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    TRX Deposit
                </h2>

            </div>

            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12">
                    <!-- BEGIN: Form Layout -->
                    <div class="intro-y box p-5">
                        <div class="mt-3 px-3">

                            <div class="relative mt-2">
                                <ul style="list-style-type: disc" class="font-bold">
                                    <li>Send only TRX to this deposit address.</li>
                                    <li>Sending coin or token other than TRX to this address may result in the loss of
                                        your
                                        deposit.
                                    </li>
                                    <li>Coins will be deposited after 20 network confirmations.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label>Enter the deposit amount (USD)</label>
                            <div class="relative mt-2">
                                <input type="number"
                                       class="intro-x login__input form-control py-3 px-4 border-gray-300 block"
                                       placeholder="USD" v-model="usdAmount">
                            </div>
                        </div>
                        <div class="mt-3" v-if="depositAddress">
                            <a :href="`//tronscan.org/#/address/${depositAddress}`" target="_blank"
                               class="button border items-center text-gray-700 dark:border-dark-5 dark:text-gray-300 hidden sm:flex">
                                <arrow-up-right-icon size="1.5x" class="w-4 h-4 mr-2"></arrow-up-right-icon>
                                View on blockchain
                            </a>

                        </div>
                        <div class="mt-3 block w-1/2 mx-auto" v-if="depositAddress">
                            <img :src="`data:image/svg+xml;base64,${depositSvgQr}`">
                        </div>
                        <!--                    <div class="mt-3">-->
                        <!--                        <label>You receive ECC</label>-->
                        <!--                        <div class="relative mt-2">-->
                        <!--                            <input type="text" class="input input&#45;&#45;lg pr-12 w-full border col-span-4"-->
                        <!--                                   placeholder="ECC receive" readonly disabled v-model="noOfTokens">-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <div class="mt-3 flex flex-row justify-center items-center" v-if="depositAddress">
                            <div class="mt-2 w-9/12">
                                <input type="text"
                                       class="intro-x login__input form-control py-3 px-4 border-gray-300 block"
                                       placeholder="TRX" readonly disabled v-model="cryptoAmount">
                            </div>
                            <div class="mt-2 w-1/4">
                                <button class="btn btn-primary py-3 px-4 w-full mt-2"
                                        @click="copy(cryptoAmount)">Copy
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 flex flex-row justify-center items-center" v-if="depositAddress">
                            <div class="mt-2 w-9/12">
                                <!--                            <span>{{depositAddress}}</span>-->
                                <input type="text"
                                       class="intro-x login__input form-control py-3 px-4 border-gray-300 block"
                                       readonly disabled v-model="depositAddress">
                            </div>
                            <div class="w-1/4">
                                <button class="btn btn-primary py-3 px-4 w-full mt-2"
                                        @click="copy(depositAddress)">Copy
                                </button>
                            </div>
                        </div>
                        <div class="text-right mt-5">
                            <button
                                class="btn py-3 px-4 w-full align-top"
                                :disabled="!showIPaid"
                                :class="[!showIPaid ? 'btn-outline-secondary cursor-not-allowed' :'btn-primary']"
                                v-if="showIPaid"
                                @click="iPaid">
                                <font-awesome-icon icon="spinner" spin v-if="sending"></font-awesome-icon>
                                Click Here After Transfer
                            </button>
                            <button @click="getConversionPrice" v-else
                                    class="btn py-3 px-4 w-full align-top"
                                    :disabled="hasPriview"
                                    :class="[hasPriview ? 'btn-outline-secondary cursor-not-allowed' :'btn-primary']">
                                Confirm
                            </button>
                        </div>
                        <div class="text-right mt-5">
                            <button @click="getDepositAddress" v-if="!showIPaid"
                                    class="btn py-3 px-4 w-full mt-3 align-top"
                                    :disabled="!hasPriview || confirming"
                                    :class="[!hasPriview ? 'btn-outline-secondary cursor-not-allowed' :'btn-primary']">
                                Show QR
                                <font-awesome-icon icon="spinner" spin v-if="confirming"></font-awesome-icon>
                            </button>
                        </div>
                        <div class="text-right mt-5">
                            <button
                                class="btn  py-3 px-4 w-full mt-3 align-top"
                                :disabled="!hasPriview"
                                :class="[!hasPriview ? 'btn-outline-secondary cursor-not-allowed' :'btn-primary']"
                                @click="resetForm">
                                Reset
                            </button>
                        </div>
                    </div>
                    <!-- END: Form Layout -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {toast} from "@/utils/toast";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "TronForm",
    layout: UserLayout,
    data() {
        return {
            usdAmount: null,
            noOfTokens: '0.00',
            cryptoAmount: '0.00',
            hasPriview: false,
            showIPaid: false,
            depositSvgQr: null,
            depositAddress: null,
            confirming: false,
            sending: false
        }
    },
    methods: {
        getConversionPrice() {
            if (this.usdAmount && this.usdAmount > '0') {
                axios.post(route('deposit.trx.summary'), {
                    amount_usd: this.usdAmount
                }).then(res => {
                    let result = res.data

                    this.cryptoAmount = result.payable_crypto
                    this.hasPriview = true
                }).catch(err => {
                })
            }
        },
        resetForm() {
            this.usdAmount = null
            this.noOfTokens = '0.00'
            this.cryptoAmount = '0.00'
            this.hasPriview = false
            this.depositSvgQr = null
            this.depositAddress = null
            this.showIPaid = false
        },
        getDepositAddress() {
            this.depositSvgQr = null
            this.depositAddress = null
            this.confirming = true
            this.showIPaid = false

            if (this.hasPriview && this.cryptoAmount > '0.00') {
                axios.post(route('deposit.trx.address'), {
                    amount: this.cryptoAmount
                }).then(res => {
                    this.depositSvgQr = res.data.qr
                    this.depositAddress = res.data.address
                    // this.showPaymentFormModal()
                    this.confirming = false
                    this.showIPaid = true
                }).catch(err => {
                    this.confirming = false
                    toast(err.response.data.error, 'danger')
                    this.showIPaid = false
                })
            }
        },
        copy(text) {
            window.copyText(text)
            toast('Copied!', 'warning')
        },
        iPaid() {
            this.sending = true
            axios.post(route('deposit.crypto.paid'), {
                currency: 'trx'
            }).then(res => {
                toast(res.data[1])
                this.sending = false
            }).catch(err => {
                this.sending = false
            })
        }
    },
    mounted() {
        // axios.post(route('crypto.paid'), {
        //     currency: 'tron'
        // }).then(res => {
        // }).catch(err => {
        // })
    },
    beforeDestroy() {
        axios.post(route('deposit.crypto.paid.delay'), {
            currency: 'trx'
        }).then(res => {

        }).catch(err => {

        })
    }
}
</script>

<style scoped>

</style>
