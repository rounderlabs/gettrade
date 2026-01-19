<template>
    <div class="app-wrapper">
        <AdminHeaderComponent @toggle-sidebar="toggleSidebar" />
        <AdminSidebarComponent></AdminSidebarComponent>
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <div class="app-content">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </div>
        </main>


        <AdminFooterComponent></AdminFooterComponent>

    </div>
    <NotificationToast></NotificationToast>

</template>

<script>

import MainAdminHeaderComponent from "@/components/AdminComponents/MainAdminHeaderComponent.vue";
import AdminFooterComponent from "@/components/AdminComponents/AdminFooterComponent.vue";
import NotificationToast from "@/components/NotificationToast.vue";
import AdminHeaderComponent from "@/components/AdminComponents/AdminHeaderComponent.vue";
import AdminSidebarComponent from "@/components/AdminComponents/AdminSidebarComponent.vue";
import { toast } from "@/utils/toast";
export default {
    name: "MainAdminLayout",
    components: {
        AdminSidebarComponent,
        AdminHeaderComponent,
        NotificationToast,
        AdminFooterComponent, MainAdminHeaderComponent,
        toast
    },
    methods: {
        toggleSidebar() {
            const body = document.body;
            // Toggle sidebar visibility for mobile and desktop
            if (body.classList.contains("sidebar-open")) {
                body.classList.remove("sidebar-open");
                body.classList.add("sidebar-collapse");
            } else {
                body.classList.add("sidebar-open");
                body.classList.remove("sidebar-collapse");
            }
        },
    },
};
</script>

<style scoped>

</style>
