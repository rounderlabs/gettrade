<template>
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <img style="width:150px" alt=""  src="/assets/dist/images/logo.png">
            </a>
            <a href="javascript:;" id="mobile-menu-toggler">
                <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
        </div>
        <ul id="mobileMenuArea" class="border-t border-theme-24 py-5 hidden">
            <li v-for="(menu,index) in menus_vue" :key="index">
                <a href="javascript:" class="menu" v-if="menu.sub_menus.length" @click="customSubMenuToggle(index)">
                    <div class="menu__icon"><i data-feather="box"></i></div>
                    <div class="menu__title">
                        {{ menu.heading }} <i data-feather="chevron-down" class="menu__sub-icon"></i>
                    </div>
                </a>
                <Link v-else :href="menu.link" class="menu" @click="mobileMenuHide">
                    <div class="menu__icon"><i data-feather="home"></i></div>
                    <div class="menu__title"> {{ menu.heading }}</div>
                </Link>
                <ul :id="'menu-submenu'+index" v-if="menu.sub_menus.length" :ref="menu.heading">
                    <li v-for="sub_menu in menu.sub_menus">
                        <Link :href="sub_menu.link" class="menu" @click="mobileMenuHide">
                            <div class="menu__icon"><i data-feather="activity"></i></div>
                            <div class="menu__title"> {{ sub_menu.title }}</div>
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- END: Mobile Menu -->
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";

export default {
    name: "AdminMobileMenuComponent",
    components: { Link },

    data() {
        return {
            menus_vue: [
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
        };
    },
    setup() {
        function customSubMenuToggle(value) {
            var element = document.getElementById("menu-submenu" + value);
            element.classList.toggle("customSubMenuDisplay");
        }

        function mobileMenuHide() {
            document.getElementById("mobileMenuArea").style.display = "none";
        }

        onMounted(() => {
        });
        return {
            customSubMenuToggle,
            mobileMenuHide
        };
    }


};
</script>

<style scoped>
.hideMobile {
    display: none;
}
</style>
