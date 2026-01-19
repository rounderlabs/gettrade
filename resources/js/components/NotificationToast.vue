<template>
    <div></div>
</template>

<script setup>
import { watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "@/utils/toast";

const page = usePage();

watch(
    () => page.props.flash?.notification,
    (notification) => {
        if (!notification) return;

        if (Array.isArray(notification)) {
            toast(notification[0], notification[1] ?? "info");
        } else {
            toast(notification, "info");
        }
    },
    { immediate: true }
);
</script>

<style scoped>

</style>
