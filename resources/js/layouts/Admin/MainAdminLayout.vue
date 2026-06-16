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
                        <div class="pt-3">
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
        AdminFooterComponent,
        toast
    },
    methods: {
        toggleSidebar() {
            const body = document.body;
            if (body.classList.contains("sidebar-collapse")) {
                body.classList.remove("sidebar-collapse");
                body.classList.add("sidebar-open");
            } else {
                body.classList.add("sidebar-collapse");
                body.classList.remove("sidebar-open");
            }
            window.dispatchEvent(new Event('resize'));
        }
    }
};
</script>

<style>
/* Responsive layout offsets keeping standard AdminLTE flow intact */
.app-wrapper {
    min-height: 100vh;
    background-color: #f8fafc;
}
</style>
