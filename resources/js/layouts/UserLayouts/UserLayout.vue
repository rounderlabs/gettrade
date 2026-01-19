<template>
    <SideBarComponent :income-wallet="incomeWallet"></SideBarComponent>
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
        const incomeWallet = ref({});

        onMounted(async () => {
            try {
                const response = await axios.get("/sidebar-data", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                });
                incomeWallet.value = response.data;
            } catch (error) {
                console.error("Error fetching sidebar data:", error);
            }
        });

        return {
            incomeWallet,
        };

    }
}
</script>

<style scoped>
</style>
