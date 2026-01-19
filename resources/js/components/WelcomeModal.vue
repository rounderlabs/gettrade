<script setup>
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
    user: Object,
});

const show = ref(true);
const preference = ref("every_login");
const loading = ref(false);

function close() {
    show.value = false;
}

async function submit() {
    if (loading.value) return;

    loading.value = true;

    try {
        await axios.post(route("welcome.preference"), {
          //  preference: preference.value,
        });
    } catch (error) {
        console.error("Welcome preference error:", error);
    } finally {
        loading.value = false;
        show.value = false;
    }
}
</script>

<template>
    <!-- Backdrop -->
    <div v-if="show" class="modal-backdrop-custom"></div>

    <!-- Modal -->
    <div v-if="show" class="modal welcome-modal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h2 class="modal-title">
                        👋 Welcome, {{ user.name }}
                    </h2>
                </div>

                <div class="modal-body">
                    <p class="mb-3">We’re glad to have you onboard.</p>

                    <div class="form-group mb-2">
                        <label class="form-label">Login ID</label>
                        <input class="form-control" :value="user.username" readonly />
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Password</label>
                        <input class="form-control" :value="user.security_answer" readonly />
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Email</label>
                        <input class="form-control" :value="user.email" readonly />
                    </div>

<!--                    <div class="form-group mb-3">-->
<!--                        <label class="form-label">Show this welcome message</label>-->
<!--                        <select v-model="preference" class="form-select">-->
<!--                            <option value="every_login">On every login</option>-->
<!--                            <option value="weekly">Once a week</option>-->
<!--                            <option value="monthly">Once a month</option>-->
<!--                            <option value="never">Never again</option>-->
<!--                        </select>-->
<!--                    </div>-->

                    <button
                        class="btn theme-btn w-100"
                        :disabled="loading"
                        @click="submit"
                    >
                        {{ loading ? "Saving..." : "Continue to Dashboard" }}
                    </button>
                </div>

                <button class="btn close-btn" @click="close">
                    ✕
                </button>

            </div>
        </div>
    </div>
</template>

<style scoped>
.modal {
    position: fixed;
    inset: 0;
    z-index: 1055;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-backdrop-custom {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1050;
}

.modal-content {
    pointer-events: auto;
}

.welcome-modal {
    --bs-modal-width: 900px;
}
</style>
