<template>

    <!-- side bar start -->
    <div class="offcanvas sidebar-offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft">
        <div class="offcanvas-header sidebar-header">
            <div class="sidebar-logo">
                <img class="img-fluid logo" src="/shuchack/assets-panel/assets/images/shuchak-logo.png" alt="logo" />
            </div>
            <div class="balance">
                <img class="img-fluid balance-bg" src="/shuchack/assets-panel/assets/images/auth-bg.jpg" alt="auth-bg" />
                <h5>Portfolio</h5>
                <h2>₹ {{ incomeWallet.balance }}</h2>
            </div>
        </div>
        <div class="offcanvas-body">
            <div class="sidebar-content">
                <ul class="link-section">
                    <li v-for="(menu,index) in menus_vue" :key="index">
                        <Link :href="menu.link" class="pages" @click="closeOffCanvas">
                            <vue-feather class="sidebar-icon" :type="menu.menu_icon"></vue-feather>
                            <h3>{{ menu.heading }}</h3>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- side bar end -->

</template>

<script>
import { Link } from "@inertiajs/vue3";
export default {
    name: "SideBarComponent",
    components: {
        Link
    },
    props:{
        incomeWallet: Object,
    },
    methods: {
        closeOffCanvas() {
            const el = document.getElementById('offcanvasLeft');
            if (!el) return;

            const instance = bootstrap.Offcanvas.getInstance(el);
            if (instance) {
                instance.hide();
            }
        }
    },
    data() {
        return {
            menus_vue: [
                {
                    heading: "Dashboard",
                    link: route("dashboard"),
                    menu_icon : "slack",

                },

                {
                    heading: "Package",
                    link: route("purchase.pricing"),
                    menu_icon : "tag",

                },

                {
                    heading: "Direct Team ",
                    link: route("team.direct"),
                    menu_icon : "user",

                },

                {
                    heading: "My Team",
                    link: route("team.by.level"),
                    menu_icon : "users",
                },
                {
                    heading: "Level Bonus",
                    link: route("earnings.front.line.bonus"),
                    menu_icon : "award",

                },

                {
                    heading: "Monthly Dividend",
                    link: route("earnings.monthly.trading.bonus"),
                    menu_icon : "bar-chart-2",

                },


                {
                    heading: "Level On Dividend",
                    link: route("earnings.profit.sharing.bonus"),
                    menu_icon : "framer",

                },

                {
                    heading: "Maturity Bonus",
                    link: route("earnings.maturity.bonus"),
                    menu_icon : "sunrise",

                },


                {
                    heading: "Withdrawals History",
                    link: route("history.withdrawal"),
                    menu_icon : "menu",

                }


            ]
        };
    },
}
</script>

<style scoped>

</style>
