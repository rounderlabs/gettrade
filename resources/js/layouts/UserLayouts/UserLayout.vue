<template>
    <SideBarComponent :user-income-stat="userIncomeStat"></SideBarComponent>
    <HeaderComponent></HeaderComponent>
    <slot></slot>
    <section class="panel-space"></section>
    <BottonNavBarComponent></BottonNavBarComponent>
    <NotificationToast></NotificationToast>
</template>

<script>

import {onMounted, ref} from "vue";
import NotificationToast from "@/components/NotificationToast.vue";
import SideBarComponent from "@/components/SunLotusInfra/SideBarComponent.vue";
import HeaderComponent from "@/components/SunLotusInfra/HeaderComponent.vue";
import BottonNavBarComponent from "@/components/SunLotusInfra/BottonNavBarComponent.vue";
import axios from "axios";
export default {
    name: "UserLayout",
    components: {
        BottonNavBarComponent,
        HeaderComponent,
        SideBarComponent,

        NotificationToast,
        },
    setup() {
        const userIncomeStat = ref({
            balance_base: "0.00",
            balance_display: "0.00",
        })
        onMounted(async () => {
            const { data } = await axios.get("/sidebar-data")
            userIncomeStat.value = data
        })

        return {
            userIncomeStat,
        };

    }
}
</script>

<style scoped>
</style>
