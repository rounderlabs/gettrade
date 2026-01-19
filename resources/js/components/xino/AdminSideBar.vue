<template>
    <!-- main-sidebar opened -->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar sidebar-scroll ">
        <div class="main-sidebar-header">
            <a :href="route('dashboard')" class=" desktop-logo logo-light">
                <img alt="logo" class="main-logo" src="/img/brand/smartoken-sun-logo.png">
            </a>
            <a :href="route('dashboard')" class=" desktop-logo logo-dark">
                <img alt="logo" class="main-logo dark-theme" src="/img/brand/smartoken-sun-logo.png">
            </a>
            <a :href="route('dashboard')" class="logo-icon mobile-logo icon-light">
                <img alt="logo" class="logo-icon" src="/img/brand/smartoken-sun-logo.png">
            </a>
            <a :href="route('dashboard')" class="logo-icon mobile-logo icon-dark">
                <img alt="logo" class="logo-icon dark-theme" src="/img/brand/smartoken-sun-logo.png">
            </a>
        </div>
        <div class="main-sidebar-body circle-animation ">
            <ul v-for="(menu,index) in menus" :key="index" class="side-menu circle open">
                <li><h3>{{ menu.heading }}</h3></li>
                <li v-for="sub_menu in menu.sub_menus" class="slide">
                    <inertia-link :href="sub_menu.link" class="side-menu__item">
                        <i :class="menu.iconCLass" class="side-menu__icon menu-icons"></i>
                        <span class="side-menu__label">{{ sub_menu.title }}</span>
                    </inertia-link>
                </li>
            </ul>
        </div>
    </aside>
    <!-- main-sidebar -->
</template>

<script>
import {Link} from "@inertiajs/vue3";
import {onMounted} from "vue";

export default {
    name: "AdminSideBar",
    components: {
        Link
    },
    data() {
        return {
            menus: [

                {
                    heading: 'Dashboard',
                    title: 'Dashboard',
                    link: route('admin.dashboard'),
                    iconCLass: 'ti-pie-chart',
                    sub_menus: [
                        {title: 'Dashboard', link: route('admin.dashboard')},
                        {title: 'Change Coin Price', link: route('admin.token.create', 1)},
                    ]
                },
                {
                    heading: 'Users',
                    title: 'Users',
                    link: route('admin.users.index'),
                    iconCLass: 'ti-pie-chart',
                    sub_menus: [
                        {title: 'Users', link: route('admin.users.index')},
                    ]
                },
                {
                    heading: 'Subscription',
                    title: 'Subscriptions',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-bookmark-alt',
                    sub_menus: [
                        {title: 'Subscription History', link: route('admin.subscriptions.show')},
                    ]
                },

                {
                    heading: 'Withdrawal',
                    title: 'Withdrawal',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-server',
                    sub_menus: [
                        // {title: 'Deposit History', link: route('history.deposit')},
                        {title: 'Withdrawal History', link: route('admin.withdrawal.index')},
                    ]
                },

                {
                    heading: 'Setting',
                    title: 'Setting',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-settings',
                    sub_menus: [
                        {title: 'Module Setting', link: route('admin.module.setting')},
                    ]
                }


            ]
        }
    },
    setup() {
        onMounted(() => {

        })
    }
}
</script>

<style scoped>
.main-logo {
    height: 3rem;
}
</style>
