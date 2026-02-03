<template>
    <!-- side bar start -->
    <div
        class="offcanvas sidebar-offcanvas offcanvas-start"
        tabindex="-1"
        id="offcanvasLeft"
    >
        <!-- HEADER -->
        <div class="offcanvas-header sidebar-header">
            <div class="sidebar-logo text-center">
                <img
                    v-if="siteSettings.logo_mobile || siteSettings.logo_desktop"
                    class="img-fluid logo"
                    :src="siteSettings.logo_mobile ?? siteSettings.logo_desktop"
                    :alt="siteSettings.site_name ?? 'Logo'"
                />

                <h6 v-else class="mt-2">
                    {{ siteSettings.site_name }}
                </h6>
            </div>

            <!-- BALANCE -->
            <div class="balance">
                <img
                    class="img-fluid balance-bg"
                    src="/user-panel/assets-panel/assets/images/auth-bg.jpg"
                    alt="auth-bg"
                />
                <h5>Portfolio</h5>
                <h2>{{ currencySymbol }} {{ userIncomeStat.balance_display }}</h2>
            </div>
        </div>

        <!-- BODY -->
        <div class="offcanvas-body">
            <div class="sidebar-content">
                <ul class="link-section">
                    <li v-for="(menu, index) in menus_vue" :key="index">
                        <Link
                            :href="menu.link"
                            class="pages"
                            @click="closeOffCanvas"
                        >
                            <vue-feather
                                class="sidebar-icon"
                                :type="menu.menu_icon"
                            />
                            <h3>{{ menu.heading }}</h3>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- side bar end -->
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3"
import {computed} from "vue";

/* =========================
   PROPS
========================= */
defineProps({
    userIncomeStat: Object,
})

/* =========================
   GLOBAL SETTINGS
========================= */
const siteSettings = usePage().props.siteSettings

const page = usePage()

const currencySymbol = computed(() => {
    return page.props.currency?.symbol ?? "₹"
})

/* =========================
   MENU CONFIG
========================= */
const menus_vue = [
    {
        heading: "Dashboard",
        link: route("dashboard"),
        menu_icon: "slack",
    },
    {
        heading: "Package",
        link: route("purchase.pricing"),
        menu_icon: "tag",
    },
    {
        heading: "Direct Team",
        link: route("team.direct"),
        menu_icon: "user",
    },
    {
        heading: "My Team",
        link: route("team.by.level"),
        menu_icon: "users",
    },
    {
        heading: "Marketing Bonus",
        link: route("earnings.direct.bonus"),
        menu_icon: "award",
    },
    {
        heading: "Trading Income",
        link: route("earnings.monthly.trading.bonus"),
        menu_icon: "bar-chart-2",
    },
    {
        heading: "Systematic Trading Income",
        link: route("earnings.systematic.bonus"),
        menu_icon: "framer",
    },
    {
        heading: "Rank Bonus",
        link: route("earnings.rank.bonus"),
        menu_icon: "sunrise",
    },
    {
        heading: "Withdrawals History",
        link: route("history.withdrawal"),
        menu_icon: "menu",
    },
]

/* =========================
   METHODS
========================= */
function closeOffCanvas() {
    const el = document.getElementById("offcanvasLeft")
    if (!el) return

    const instance = bootstrap.Offcanvas.getInstance(el)
    if (instance) instance.hide()
}
</script>

<style scoped>
.sidebar-logo {
    width: 100%;
    padding: 10px 0;
}

.sidebar-logo .logo {
    max-width: 120px;
    max-height: 80px;
    object-fit: contain;
}
</style>
