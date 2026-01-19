<template>
    <!-- Sidebar -->
    <div class="sidebar-wrap">
        <div class="sidebar">
            <div class="container">
                <div class="row mb-4">
                    <div class="col align-self-center">
                        <h6>Main navigation</h6>
                    </div>
                    <div class="col-auto">
                        <a class="" data-bs-toggle="collapse" data-bs-target="#usersidebarprofile" aria-expanded="false" role="button" aria-controls="usersidebarprofile">
                            <i class="bi bi-person-circle"></i>
                        </a>
                    </div>
                </div>

                <!-- user information -->
                <div class="row text-center collapse " id="usersidebarprofile">
                    <div class="col-12">
                        <div class="avatar avatar-100 rounded-circle shadow-sm mb-3 bg-white">
                            <figure class="avatar avatar-90 rounded-circle coverimg">
                                <img src="assets/img/user-1.jpg" alt="" id="userphotoonboarding">
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <h6 class="mb-1" id="usernamedisplay">The Maxartkiller</h6>
                        <p class="text-secondary small">United States</p>
                    </div>
                </div>
                <!-- user menu navigation -->
                <div class="row mb-4">
                    <div class="col-12 px-0">
                        <ul class="nav nav-pills">
                            <li class="nav-item dropdown" v-for="(menu,index) in menus_vue" :key="index">
                                <a  v-if="menu.sub_menus.length" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false" data-bs-display="static" href="#" role="button" aria-expanded="false">
                                    <div class="avatar avatar-40 icon"><i class="bi bi-coin"></i></div>
                                    <div class="col">{{ menu.heading }}</div>
                                    <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i class="bi bi-chevron-up minus"></i>
                                    </div>
                                </a>
                                <Link v-else :href="menu.link" class="nav-link active" aria-current="page">
                                    <div class="avatar avatar-40 icon"><i class="bi bi-house-door"></i></div>
                                    <div class="col">{{ menu.heading }}</div>
                                </Link>
                                <ul :id="'menu-submenu'+index" v-if="menu.sub_menus.length" :ref="menu.heading" class="dropdown-menu" >
                                    <li v-for="sub_menu in menu.sub_menus">
                                        <Link class="dropdown-item nav-link" :href="sub_menu.link" >
                                            <div class="avatar avatar-40 icon"><i class="bi bi-speedometer2"></i>
                                            </div>
                                            <div class="col align-self-center">{{ sub_menu.title }}</div>
                                        </Link>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar ends -->
</template>

<script>
import { Link } from "@inertiajs/vue3";
export default {
    name: "MainAdminSideBarWrapComponent",
    components: {
        Link
    },
    data() {
        return {
            menus_vue: [
                {
                    heading: 'Dashboard',
                    title: 'Dashboard',
                    link: route('admin.dashboard'),
                    iconCLass: 'ti-pie-chart',
                    sub_menus: [

                    ]
                },
                {
                    heading: 'Users',
                    title: 'Users',
                    link: route('admin.users.index'),
                    iconCLass: 'ti-pie-chart',
                    sub_menus: [
                    ]
                },
                {
                    heading: 'Subscription',
                    title: 'Subscriptions',
                    link: route('admin.subscriptions.show'),
                    iconCLass: 'ti-bookmark-alt',
                    sub_menus: [

                    ]
                },
                {
                    heading: 'KYC Request',
                    title: 'KYC Request',
                    link: route('admin.user.kyc.request'),
                    iconCLass: 'ti-bookmark-alt',
                    sub_menus: [

                    ]
                },
                {
                    heading: 'Deposit Request',
                    title: 'Deposit Request',
                    link: route('admin.deposit.inr.request'),
                    iconCLass: 'ti-server',
                    sub_menus: [
                        // {title: 'Deposit History', link: route('history.deposit')},
                        //  {title: 'Withdrawal History', link: route('admin.withdrawal.index')},
                    ]
                },

                {
                    heading: 'Withdrawal',
                    title: 'Withdrawal',
                    link: route('admin.withdrawal.index'),
                    iconCLass: 'ti-server',
                    sub_menus: [
                        // {title: 'Deposit History', link: route('history.deposit')},
                      //  {title: 'Withdrawal History', link: route('admin.withdrawal.index')},
                    ]
                },

                // {
                //     heading: 'Setting',
                //     title: 'Setting',
                //     link: 'javascript:void(0)',
                //     iconCLass: 'ti-settings',
                //     sub_menus: [
                //         {title: 'Module Setting', link: route('admin.module.setting')},
                //     ]
                // }


            ]
        };
    },
}
</script>

<style scoped>

</style>
