<template>
    <div class="container">
        <HeaderComponent></HeaderComponent>
        <main class="main mainheight" style="min-height: 724.898px; margin-top: 60.3516px; padding-bottom: env(safe-area-inset-bottom);margin-bottom: 60.3516px;">
            <slot></slot>
        </main>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script>

import HeaderComponent from "@/components/Panel-Components/HeaderComponent.vue";
import FooterComponent from "@/components/Panel-Components/FooterComponent.vue";
export default {
    name: "MenuLayout",
    components: {FooterComponent, HeaderComponent},

}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
</style>
