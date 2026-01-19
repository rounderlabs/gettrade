<template>
    <div class="app-wrapper">
        <AdminFooterComponent></AdminFooterComponent>
<!--        <AdminSideBar></AdminSideBar>-->
        <div class="main-content app-content">
<!--            <AdminHeader></AdminHeader>-->
            <!-- container -->
            <div class="container-fluid">
                <!-- breadcrumb -->
                <div class="breadcrumb-header justify-content-between xs-mt-4">

                </div>
                <!-- /breadcrumb -->
                <slot></slot>
            </div>
        </div>
<!--        <Footer></Footer>-->
        <!-- Back-to-top -->
        <a href="#top" id="back-to-top"><i class="la la-chevron-up"></i></a>
<!--        <NotificationToast></NotificationToast>-->
    </div>
</template>

<script>
import Footer from "@/components/xino/Footer";
import {onMounted} from "vue";
import PerfectScrollbar from "perfect-scrollbar";
import AdminSideBar from "@/components/xino/AdminSideBar";
import AdminHeader from "@/components/xino/AdminHeader";
import Notification from "@/components/Notification";
import NotificationToast from "@/components/NotificationToast";
import PreLoader from "@/components/xino/PreLoader.vue";
import AdminFooterComponent from "@/components/AdminComponents/AdminFooterComponent.vue";

export default {
    components: {AdminFooterComponent, PreLoader, NotificationToast, Notification, AdminHeader, AdminSideBar, Footer},
    setup() {
        onMounted(() => {
            const ps1 = new PerfectScrollbar('.sidebar-scroll', {
                suppressScrollX: true
            });

        })

    },
}
</script>

<style scoped>

</style>
