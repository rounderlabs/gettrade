<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    kyc: Object,
    crypto_wallet: Object,
});

const form = useForm({
    withdraw_mode: props.user.withdraw_mode ?? "",
    withdraw_type: props.user.withdraw_type ?? "MANUAL",
});

function submit() {
    form.post(route("withdraw.setup.save"));
}
</script>

<template>
    <UserLayout>
        <section class="section-b-space">
            <div class="custom-container">

                <div class="title mb-4">
                    <h2>Withdrawal Settings</h2>
                    <p class="text-muted">
                        Select your preferred withdrawal method and type.
                    </p>
                </div>

                <form @submit.prevent="submit" class="auth-form">

                    <!-- Withdraw Mode -->
                    <div class="form-group">
                        <label class="form-label">Withdrawal Mode</label>

                        <select v-model="form.withdraw_mode"
                                class="form-control"
                                required>
                            <option value="">Select Mode</option>
                            <option value="INR">₹ INR Withdrawal</option>
                            <option value="CRYPTO">USDT (BEP20 Crypto)</option>
                        </select>
                    </div>

                    <!-- Withdraw Type -->
                    <div class="form-group mt-3">
                        <label class="form-label">Withdrawal Type</label>

                        <select v-model="form.withdraw_type"
                                class="form-control"
                                required>
                            <option value="MANUAL">Manual Request Anytime</option>
                            <option value="AUTO">Automatic (Admin Scheduled)</option>
                        </select>
                    </div>

                    <!-- Conditional Info -->
                    <div v-if="form.withdraw_mode === 'INR'" class="alert alert-warning mt-4">
                        INR withdrawals require approved KYC.
                        <br>
                        Status:
                        <strong>{{ kyc?.status ?? "Not Submitted" }}</strong>
                    </div>

                    <div v-if="form.withdraw_mode === 'CRYPTO'" class="alert alert-info mt-4">
                        Crypto withdrawals require wallet address update.
                        <br>
                        Wallet:
                        <strong>
                            {{ crypto_wallet ? "Updated ✅" : "Not Set ❌" }}
                        </strong>
                    </div>

                    <!-- Submit -->
                    <button class="btn theme-btn w-100 mt-4"
                            :disabled="form.processing">
                        Save & Continue
                    </button>

                </form>
            </div>
        </section>
    </UserLayout>
</template>
