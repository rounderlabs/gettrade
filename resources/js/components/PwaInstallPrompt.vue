<template>
    <div v-if="deferredPrompt" class="text-center mt-3">
        <button @click="installApp" class="btn btn-primary">
            <i class="fas fa-download"></i> Install The Get Trade App
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const deferredPrompt = ref(null);

onMounted(() => {
    window.addEventListener("beforeinstallprompt", (e) => {
        e.preventDefault();
        deferredPrompt.value = e;
        console.log("✅ beforeinstallprompt fired");
    });
});

function installApp() {
    if (!deferredPrompt.value) return;
    deferredPrompt.value.prompt();
    deferredPrompt.value.userChoice.then(() => {
        deferredPrompt.value = null;
    });
}
</script>
