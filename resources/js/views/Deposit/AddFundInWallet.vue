<template>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="title mb-3 ">
                <h2>Add To Fund Wallet</h2>
            </div>
            <form class="mb-4 auth-form mt-3" enctype="multipart/form-data" @submit.prevent="submit">

                <!-- Amount -->
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input
                        v-model="form.amount"
                        class="form-control"
                        required
                        type="number"
                    />
                </div>

                <!-- Payment Type -->
                <div class="form-group mt-2">
                    <label class="form-label">Payment Type</label>
                    <select v-model="form.payment_type" class="form-control">
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
                        v-model="form.txn_id"
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
                        @change="handleFile"
                    />
                </div>

                <!-- Submit -->
                <button
                    :disabled="form.processing"
                    class="btn theme-btn w-100 mt-3"
                    type="submit"
                >
                    Submit Request
                </button>

            </form>
        </div>
    </section>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {toast} from "@/utils/toast";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default {
    name: "AddFundInWallet",
    components: {FontAwesomeIcon},
    layout: UserLayout,
    props: {
        currencies: Array
    },
    setup(props) {
        const form = useForm({
            amount: "",
            payment_type: "UPI",
            txn_id: "",
            payment_proof: null,
        });

        function handleFile(e) {
            form.payment_proof = e.target.files[0];
        }

        function submit() {
            form.post(route("deposit.fund.request.submit"), {
                forceFormData: true,
                onSuccess: () => {
                    toast("Fund request submitted successfully", "success");
                    form.reset();
                },
                onError: errors => {
                    Object.values(errors).forEach(err => toast(err, "danger"));
                }
            });
        }

        return {
            form, handleFile, submit
        }
    }
};
</script>

<style scoped>

</style>
