<template>
    <div class="home-style-8 mt-30">
        <SiteHeaderComponent></SiteHeaderComponent>

        <slot>

        </slot>

        <SiteFooterComponent></SiteFooterComponent>
    </div>
</template>

<script>
import SiteHeaderComponent from "@/components/frontend/SiteHeaderComponent";
import SiteFooterComponent from "@/components/frontend/SiteFooterComponent";
export default {
    name: "SiteLayout",
    components:{ SiteHeaderComponent,SiteFooterComponent

    }
}
</script>

<style scoped>

</style>
