<template>
    <!-- main-sidebar opened -->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar sidebar-scroll ">
        <div class="main-sidebar-header">
            <Link :href="route('dashboard')" class=" desktop-logo logo-light">
                <img alt="logo" class="main-logo fa-spin" height="50" src="/img/brand/smartIcon.png">
            </Link>
            <Link :href="route('dashboard')" class=" desktop-logo logo-dark">
                <img alt="logo" class="main-logo dark-theme fa-spin" height="50" src="/img/brand/smartIcon.png">
            </Link>
            <Link :href="route('dashboard')" class="logo-icon mobile-logo icon-light">
                <img alt="logo" class="logo-icon fa-spin" height="50" src="/img/brand/smartIcon.svg">
            </Link>
            <Link :href="route('dashboard')" class="logo-icon mobile-logo icon-dark">
                <img alt="logo" class="logo-icon dark-theme fa-spin" height="50" src="/img/brand/smartIcon.png">
            </Link>
        </div>
        <div class="main-sidebar-body circle-animation ">
            <ul v-for="(menu,index) in menus" :key="index" class="side-menu circle open">
                <li><h3>{{ menu.heading }}</h3></li>
                <li v-for="sub_menu in menu.sub_menus" class="slide">
                    <inertia-link :href="sub_menu.link" class="side-menu__item">
                        <i :class="sub_menu.iconCLass" class="side-menu__icon menu-icons"></i>
                        <span class="side-menu__label">{{ sub_menu.title }}</span>
                    </inertia-link>
                </li>
            </ul>
        </div>
    </aside>
    <!-- main-sidebar -->
</template>

<script>
import {onMounted} from "vue";
import {Link} from '@inertiajs/vue3'
import LogoIcon from "@/components/LogoIcon";


export default {
    name: "SideBar",
    components: {
        LogoIcon,
        Link
    },
    data() {
        return {
            menus: [
                {
                    heading: 'Dashboard',
                    title: 'Dashboard',
                    link: route('dashboard'),
                    iconCLass: 'la la-users',
                    sub_menus: [
                        {title: 'Dashboard', link: route('dashboard'), iconCLass: 'la la-desktop'},
                    ]
                },
                {
                    heading: 'Network',
                    title: 'Partners',
                    link: 'javascript:void(0)',
                    iconCLass: 'la la-users',
                    sub_menus: [
                        {title: 'Team', link: route('team.genealogy'), iconCLass: 'la la-users'},
                        {title: 'Direct', link: route('team.direct'), iconCLass: 'la la-arrows'},
                    ]
                },
                {
                    heading: 'Plan & Tenure',
                    title: 'Purchase',
                    link: 'javascript:void(0)',
                    iconCLass: 'la la-money-bill',
                    sub_menus: [
                        {title: 'Add Fund', link: route('deposit.add.fund'), iconCLass: 'la la-dollar-sign'},
                        {title: 'Plans', link: route('purchase.pricing'), iconCLass: 'la la-cubes'},

                    ]
                },
                {
                    heading: 'History',
                    title: 'History',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-server',
                    sub_menus: [
                        {title: 'Deposit History', link: route('history.deposit'), iconCLass: 'la la-cloud-upload-alt'},
                        {
                            title: 'Withdrawal History',
                            link: route('history.withdrawal'),
                            iconCLass: 'la la-cloud-download-alt'
                        },
                        {
                            title: 'Wallet Txn History',
                            link: route('history.wallet.usd'),
                            iconCLass: 'la la-exchange-alt'
                        },
                    ]
                },
                {
                    heading: 'Earning & Bonus',
                    title: 'Bonus',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-money',
                    sub_menus: [
                        {title: 'Recurring Bonus', link: route('earnings.staking'), iconCLass: 'la la-layer-group'},
                        {
                            title: 'Performance Bonus',
                            link: route('earnings.performance'), iconCLass: 'la la-running'
                        },
                        {
                            title: 'Direct Rewards',
                            link: route('earnings.direct'),
                            iconCLass: 'la la-briefcase'
                        },

                        // {
                        //     title: 'Lifetime Rewards',
                        //     link: route('earnings.matching.rewards'),
                        //     iconCLass: 'la la-briefcase'
                        // },

                        {
                            title: 'Group Dividend',
                            link: route('earnings.group.matching.dividend'), iconCLass: 'la la-users-cog'
                        },
                        {title: 'Wonder Income', link: route('earnings.wonder.income'), iconCLass: 'la la-hand-paper'},
                        {title: 'Reward Point', link: route('earnings.reward.points'), iconCLass: 'la la-coins'},

                    ]
                },

                {
                    heading: 'Withdraw & Accounts',
                    title: 'Withdraw',
                    link: 'javascript:void(0)',
                    iconCLass: 'ti-credit-card',
                    sub_menus: [
                        {
                            title: 'Withdraw Account',
                            link: route('withdraw.accounts'),
                            iconCLass: 'la la-angle-double-down'
                        },
                        {title: 'Withdraw ', link: route('withdraw.usdt'), iconCLass: 'la la-chevron-down'},
                    ]
                },


            ]
        }
    },
    setup() {
        onMounted(() => {
            require('../../../assets/plugins/side-menu/sidemenu')

        })
    }
}
</script>

<style scoped>
.app-sidebar {
    background: linear-gradient(135deg, #000000 0%, #4b5f83 100%);
}

</style>
