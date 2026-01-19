<template>
    <form @submit.prevent="saveInventory">
        <div class="row">
            <div class="col-md-6">
                <label>Guaranty Type</label>
                <select v-model="form.guaranty_type" class="form-select">
                    <option value="">Select</option>
                    <option value="land">Land</option>
                    <option value="flat">Flat</option>
                    <option value="mou">MOU</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>

            <div class="col-md-6">
                <label>Project Name</label>
                <input v-model="form.project_name" class="form-control" />
            </div>

            <div class="col-md-6 mt-2">
                <label>Project Location</label>
                <input v-model="form.project_location" class="form-control" />
            </div>

            <div class="col-md-6 mt-2">
                <label>Plot / Flat Number</label>
                <input v-model="form.plot_or_flat_number" class="form-control" />
            </div>

            <div class="col-md-6 mt-2">
                <label>Acquisition Type</label>
                <select v-model="form.acquisition_type" class="form-select">
                    <option value="">Select</option>
                    <option value="Registry">Registry</option>
                    <option value="Agreement">Agreement</option>
                </select>
            </div>

            <div class="col-md-6 mt-2">
                <label>Upload Document</label>
                <input type="file" class="form-control" @change="handleFileUpload" />
            </div>

            <div class="col-12 mt-3">
                <label>Notes</label>
                <textarea v-model="form.notes" rows="2" class="form-control"></textarea>
            </div>

            <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <i v-if="loading" class="fas fa-spinner fa-spin me-1"></i> Save Inventory
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
    guaranty_type: "",
    project_name: "",
    project_location: "",
    plot_or_flat_number: "",
    acquisition_type: "",
    document: null,
    notes: "",
});

const loading = ref(false);
const message = ref("");
const errorMessage = ref("");

const handleFileUpload = (e) => {
    form.value.document = e.target.files[0];
};

const saveInventory = async () => {
    loading.value = true;
    message.value = "";
    errorMessage.value = "";

    try {
        const data = new FormData();
        Object.entries(form.value).forEach(([k, v]) => v && data.append(k, v));

        const res = await axios.post(route("admin.investment.inventory.save"), data, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        message.value = res.data.message;
    } catch (e) {
        errorMessage.value = e.response?.data?.message || "Error saving inventory";
    } finally {
        loading.value = false;
    }
};
</script>
