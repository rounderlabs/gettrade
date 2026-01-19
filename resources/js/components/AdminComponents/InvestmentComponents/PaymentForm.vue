<template>
  <form @submit.prevent="savePayment">
    <div class="row">
      <div class="col-md-4">
        <label>Payment Mode</label>
        <select v-model="form.payment_mode" class="form-select">
          <option value="">Select</option>
          <option value="Cash">Cash</option>
          <option value="Cheque">Cheque</option>
          <option value="OnlineBanking">Online Banking</option>
          <option value="Crypto">Crypto</option>
        </select>
      </div>

      <div class="col-md-4">
        <label>Amount</label>
        <input v-model="form.amount" type="number" class="form-control" />
      </div>

      <div class="col-md-4">
        <label>Payment Date</label>
        <input v-model="form.payment_date" type="date" class="form-control" />
      </div>

      <!-- Dynamic sections -->
      <div v-if="form.payment_mode === 'Cheque'" class="col-12 mt-3">
        <label>Cheque Number</label>
        <input v-model="form.cheque_number" class="form-control" />
      </div>

      <div v-if="form.payment_mode === 'OnlineBanking'" class="col-12 mt-3">
        <label>Transaction ID</label>
        <input v-model="form.transaction_id" class="form-control" />
        <label>Bank Name</label>
        <input v-model="form.bank_name" class="form-control mt-2" />
      </div>

      <div v-if="form.payment_mode === 'Crypto'" class="col-12 mt-3">
        <label>Crypto Network</label>
        <input v-model="form.crypto_network" class="form-control" />
        <label>Wallet Address</label>
        <input v-model="form.wallet_address" class="form-control mt-2" />
      </div>

      <div class="col-12 mt-3">
        <label>Remarks</label>
        <textarea v-model="form.remarks" class="form-control" rows="2"></textarea>
      </div>

      <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
        <button :disabled="loading" class="btn btn-success" type="submit">
          <i v-if="loading" class="fas fa-spinner fa-spin me-1"></i> Save Payment
        </button>
        <span v-if="message" class="text-success">{{ message }}</span>
        <span v-if="errorMessage" class="text-danger">{{ errorMessage }}</span>
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
  investment: Object,
});

const form = ref({
  user_id: props.investment.user_id,
  user_investment_id: props.investment.id,
  payment_mode: "",
  amount: "",
  payment_date: "",
  transaction_id: "",
  bank_name: "",
  cheque_number: "",
  crypto_network: "",
  wallet_address: "",
  remarks: "",
});

const loading = ref(false);
const message = ref("");
const errorMessage = ref("");

const savePayment = async () => {
  loading.value = true;
  message.value = "";
  errorMessage.value = "";

  try {
    const res = await axios.post(route("admin.investment.payment.save"), form.value);
    message.value = res.data.message;
  } catch (err) {
    errorMessage.value = err.response?.data?.message || "Error saving payment.";
  } finally {
    loading.value = false;
  }
};
</script>
