<template>
    <section class="section-b-space">
        <div class="custom-container">

            <!-- Title -->
            <div class="title mb-3">
                <h2>Add Funds to Wallet</h2>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills tab-style3 w-100 mt-0" id="myTab" role="tablist">

                <!-- INR TAB -->
                <li class="nav-item w-50" role="presentation">
                    <button
                        class="nav-link active"
                        id="inr-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#inr-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="inr-tab-pane"
                        aria-selected="true"
                    >
                        INR Deposit
                    </button>
                </li>

                <!-- CRYPTO TAB -->
                <li class="nav-item w-50" role="presentation">
                    <button
                        class="nav-link"
                        id="crypto-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#crypto-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="crypto-tab-pane"
                        aria-selected="false"
                    >
                        USDT Deposit
                    </button>
                </li>

            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">

                <!-- ===================== -->
                <!-- INR FORM TAB -->
                <!-- ===================== -->
                <div
                    class="tab-pane fade show active p-2"
                    id="inr-tab-pane"
                    role="tabpanel"
                    aria-labelledby="inr-tab"
                    tabindex="0"
                >
                    <form
                        class="mb-4 auth-form mt-3"
                        enctype="multipart/form-data"
                        @submit.prevent="submitINR"
                    >

                        <h5 class="mb-3">Deposit via UPI / Bank Transfer</h5>

                        <!-- Amount -->
                        <div class="form-group">
                            <label class="form-label">Amount (₹)</label>
                            <input
                                v-model="inrForm.amount"
                                class="form-control"
                                required
                                type="number"
                                min="1"
                            />
                        </div>

                        <!-- Payment Type -->
                        <div class="form-group mt-2">
                            <label class="form-label">Payment Type</label>
                            <select v-model="inrForm.payment_type" class="form-control">
                                <option value="UPI">UPI</option>
                                <option value="NEFT">NEFT</option>
                                <option value="IMPS">IMPS</option>
                                <option value="RTGS">RTGS</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <!-- Transaction ID -->
                        <div class="form-group mt-2">
                            <label class="form-label">Transaction ID</label>
                            <input
                                v-model="inrForm.txn_id"
                                class="form-control"
                                placeholder="Enter transaction id"
                                type="text"
                            />
                        </div>

                        <!-- Payment Proof -->
                        <div class="form-group mt-2">
                            <label class="form-label">Payment Proof</label>
                            <input
                                accept="image/*,application/pdf"
                                class="form-control"
                                required
                                type="file"
                                @change="handleINRFile"
                            />
                        </div>

                        <!-- Submit -->
                        <button
                            :disabled="inrForm.processing"
                            class="btn theme-btn w-100 mt-3"
                            type="submit"
                        >
                            Submit INR Request
                        </button>

                    </form>
                </div>

                <!-- ===================== -->
                <!-- CRYPTO FORM TAB (QR GENERATOR) -->
                <!-- ===================== -->
                <div
                    class="tab-pane fade p-2"
                    id="crypto-tab-pane"
                    role="tabpanel"
                    aria-labelledby="crypto-tab"
                    tabindex="0"
                >
                    <div class="title mb-3 mt-3">
                        <h2>Add USDT To Fund Wallet</h2>
                    </div>

                    <form class="mb-4 auth-form mt-3" @submit.prevent="saveInvoice">

                        <!-- Select Token -->
                        <div class="form-group">
                            <label class="form-label">Select USDT Token</label>

                            <div class="form-input">
                                <select
                                    v-model="purchaseForm.currency"
                                    class="form-control"
                                    required
                                >
                                    <option selected value="">
                                        Select USDT Type
                                    </option>

                                    <option
                                        v-for="currency in currencies"
                                        :key="currency.symbol"
                                        :value="currency.symbol"
                                    >
                                        {{ currency.symbol.toUpperCase() }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="form-group mt-3">
                            <label class="form-label">Amount in USDT</label>

                            <div class="form-input">
                                <input
                                    v-model="purchaseForm.amount"
                                    class="form-control"
                                    placeholder="Enter Amount in USDT"
                                    required
                                    type="text"
                                />
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12 col-md-6 mt-auto mb-4 text-center d-grid mt-4">
                            <button
                                :disabled="purchaseForm.processing"
                                class="btn theme-btn"
                                type="submit"
                            >
                                Generate QR
                            </button>

                            <font-awesome-icon
                                v-if="purchaseForm.processing"
                                class="ml-1 mt-2"
                                icon="spinner"
                                spin
                            />
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from "@/utils/toast";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export default {
    name: "AddFundInWallet",
    components: { FontAwesomeIcon },
    layout: UserLayout,

    props: {
        currencies: Array,
    },

    setup() {

        /* ---------------- INR FORM ---------------- */
        const inrForm = useForm({
            amount: "",
            payment_type: "UPI",
            txn_id: "",
            payment_proof: null,
        });

        function handleINRFile(e) {
            inrForm.payment_proof = e.target.files[0];
        }

        function submitINR() {
            inrForm.post(route("deposit.fund.request.submit"), {
                forceFormData: true,
                onSuccess: () => {
                    toast("INR Fund Request Submitted Successfully", "success");
                    inrForm.reset();
                },
                onError: (errors) => {
                    Object.values(errors).forEach((err) =>
                        toast(err, "danger")
                    );
                },
            });
        }

        /* ---------------- CRYPTO QR FORM ---------------- */
        const purchaseForm = useForm({
            currency: null,
            amount: null,
        });

        function saveInvoice() {
            purchaseForm.post(route("deposit.invoice.create"), {
                onError: (errors) => {
                    Object.values(errors).forEach((err) =>
                        toast(err, "danger")
                    );
                },
            });
        }

        return {
            inrForm,
            handleINRFile,
            submitINR,

            purchaseForm,
            saveInvoice,
        };
    },
};
</script>

<style scoped>
</style>
