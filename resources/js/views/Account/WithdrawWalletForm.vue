<template>
    <section class="section-b-space">
        <div class="custom-container">

            <!-- ================= Withdraw Mode Switch ================= -->
            <div class="app-title mb-3">
                <h2>Withdrawal Settings</h2>
            </div>

            <div class="mb-4">
                <label class="form-label dark-text">Withdraw Mode</label>

                <select v-model="modeForm.withdraw_mode"
                        class="form-control"
                        @change="updateMode">
                    <option value="INR">INR (Bank Transfer)</option>
                    <option value="CRYPTO">CRYPTO (USDT - BEP20)</option>
                </select>
            </div>

            <!-- ================= INR MODE ================= -->
            <div v-if="modeForm.withdraw_mode === 'INR'">

                <div v-if="kyc">
                    <div class="app-title mb-3">
                        <h2>Bank Account Details</h2>
                    </div>

                    <InfoBox title="Bank Name" :value="kyc.bank_name" />
                    <InfoBox title="IFSC Code" :value="kyc.ifsc_code" />
                    <InfoBox title="Account Number" :value="kyc.account_number" />
                    <InfoBox title="UPI ID" :value="kyc.upi_id" />
                </div>

                <div v-else>
                    <Link :href="route('kyc.index')" class="btn theme-btn">
                        Complete KYC Process
                    </Link>
                </div>

            </div>

            <!-- ================= CRYPTO MODE ================= -->
            <div v-if="modeForm.withdraw_mode === 'CRYPTO'">

                <div v-if="crypto_wallet">

                    <div class="app-title mb-3">
                        <h2>USDT Wallet Address</h2>
                    </div>


                    <InfoBox title="Network" value="BEP20 (BNB Chain)" />
                    <InfoBox title="Wallet Address"
                             :value="crypto_wallet.address" />

                    <Link :href="route('withdraw.wallet.usdt')"
                          class="btn btn-light w-100 mt-3">
                        Change Wallet Address
                    </Link>

                </div>

                <div v-else>
                    <Link :href="route('withdraw.wallet.usdt')"
                          class="btn theme-btn">
                        Setup Wallet Address
                    </Link>
                </div>

            </div>

        </div>
    </section>
</template>

<script>
import InfoBox from "@/components/InfoBox.vue";
import { useForm, Link } from "@inertiajs/vue3";
import { toast } from "@/utils/toast";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    layout: [UserLayout],
    components:{InfoBox, Link},
    props: {
        kyc: Object,
        withdraw_mode: String,
        crypto_wallet: Object,
    },

    setup(props) {

        const modeForm = useForm({
            withdraw_mode: props.withdraw_mode ?? 'INR'
        });

        function updateMode() {
            modeForm.post(route('account.update.mode'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast("Withdrawal mode updated", "success");
                }
            });
        }

        return { modeForm, updateMode };
    }
};
</script>
