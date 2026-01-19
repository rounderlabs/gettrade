<template>
    <!-- Loader -->
    <div id="global-loader" class="light-loader">
        <img src="/img/loaders/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
</template>

<script>
import {onMounted} from "vue";

export default {
    name: "PreLoader",
    setup() {
        onMounted(() => {
            $("#global-loader").fadeOut("slow");
        })

    }
}
</script>

<style scoped>

</style>
