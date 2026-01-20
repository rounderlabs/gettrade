<template>
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
            <Link class="brand-link" href="/admin/dashboard">
                <img
                    class="brand-image shadow"
                    :src="siteSettings.logo_desktop"
                />
                <span class="brand-text fw-light">{{siteSettings.site_name}}</span>
            </Link>
        </div>

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul
                    id="navigation"
                    aria-label="Main navigation"
                    class="nav sidebar-menu flex-column"
                    role="navigation"
                >
                    <!-- Dynamic Menus -->
                    <li
                        v-for="(menu, index) in menus_vue"
                        :key="index"
                        :class="{ 'menu-open': openIndex === index }"
                        class="nav-item"
                    >
                        <!-- Parent menu -->
                        <a
                            class="nav-link"
                            href="javascript:void(0)"
                            @click="toggleSubmenu(index)"
                        >
                            <i :class="menu.iconClass || 'bi-list'" class="nav-icon bi"></i>
                            <p>
                                {{ menu.heading }}
                                <i
                                    :class="openIndex === index ? 'bi-chevron-down' : 'bi-chevron-right'"
                                    class="nav-arrow bi"
                                ></i>
                            </p>
                        </a>

                        <!-- Submenu -->
                        <transition name="slide">
                            <ul
                                v-if="openIndex === index"
                                :id="'submenu-' + index"
                                class="nav nav-treeview"
                            >
                                <li
                                    v-for="(sub, subIndex) in menu.sub_menus"
                                    :key="subIndex"
                                    class="nav-item"
                                >
                                    <Link
                                        :class="{ active: isActive(sub.link) }"
                                        :href="sub.link"
                                        class="nav-link"
                                    >
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>{{ sub.title }}</p>
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="/admin/logout">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!--end::Sidebar-->
</template>

<script>
import {Inertia} from '@inertiajs/inertia';
import {Link, usePage} from '@inertiajs/vue3';
import {onBeforeUnmount, onMounted, ref} from "vue";

export default {
    name: "AdminSidebarComponent",
    components: {Link},

    setup() {
        const {props} = usePage();
        const openIndex = ref(null);
        const currentPath = window.location.pathname;
        const siteSettings = usePage().props.siteSettings
        const menus_vue = [
            {
                heading: "Dashboard",
                iconClass: "bi-speedometer2",
                sub_menus: [
                    {title: "Main Dashboard", link: route("admin.dashboard")},
                ],
            },
            {
                heading: "Users",
                iconClass: "bi-people",
                sub_menus: [{title: "All Users", link: route("admin.users.index")}],
            },
            {
                heading: "KYC List",
                iconClass: "bi-bookmark",
                sub_menus: [
                    {
                        title: "All Kyc",
                        link: route("admin.kyc.index"),
                    },
                ],
            },
            {
                heading: "Fund Request",
                iconClass: "bi-bookmark",
                sub_menus: [
                    {
                        title: "Deposit History",
                        link: route("admin.fund.requests.index"),
                    },
                ],
            },
            {
                heading: "Investment",
                iconClass: "bi-bookmark",
                sub_menus: [
                    // {
                    //     title: "User Investment",
                    //     link: route("admin.investment.index"),
                    // },
                    {
                        title: "Subscription History",
                        link: route("admin.subscriptions.show"),
                    },
                ],
            },
            {
                heading: "Withdrawal",
                iconClass: "bi-cash-stack",
                sub_menus: [
                    {
                        title: "Withdrawal History",
                        link: route("admin.withdrawal.reports"),
                    },
                ],
            },
            {
                heading: "Reports",
                iconClass: "bi-cash-stack",
                sub_menus: [
                    {title: "Dividend Bonus", link: route("admin.reports.user.trading.bonus")},
                    {title: "Level On Dividend Bonus", link: route("admin.reports.user.level.roi.bonus")},
                    {title: "Level Bonus", link: route("admin.reports.user.level.bonus")},
                    {title: "Maturity Bonus", link: route("admin.reports.user.level.bonus")},
                ],
            },
            {
                heading: "Settings",
                iconClass: "bi-gear",
                sub_menus: [
                    {title: "Plan List", link: route("admin.plans.index")},
                    {title: "Commission Setting", link: route("admin.site.settings.index")},
                ],
            },
        ];

        onMounted(() => {
            const updateSidebarState = () => {
                const body = document.body;
                if (window.innerWidth <= 991) {
                    // Mobile view collapsed
                    body.classList.add("sidebar-collapse", "sidebar-closed");
                    body.classList.remove("sidebar-open");
                } else {
                    // Desktop view open
                    body.classList.remove("sidebar-closed", "sidebar-collapse");
                    body.classList.add("sidebar-open");
                }
            };

            // Initial check and resize listener
            updateSidebarState();
            window.addEventListener("resize", updateSidebarState);

            // 🔥 Listen for page navigation (Inertia)
            Inertia.on("navigate", () => {
                openIndex.value = null; // Close all submenus
                if (window.innerWidth <= 991) {
                    // Collapse sidebar on mobile after navigation
                    document.body.classList.add("sidebar-collapse", "sidebar-closed");
                    document.body.classList.remove("sidebar-open");
                }
            });
        });

        onBeforeUnmount(() => {
            window.removeEventListener("resize", updateSidebarState);
            Inertia.off("navigate");
        });

        const toggleSubmenu = (index) => {
            openIndex.value = openIndex.value === index ? null : index;
        };

        const isActive = (link) => currentPath === link;

        const isActiveGroup = (menu) =>
            menu.sub_menus?.some((s) => currentPath === s.link);

        onMounted(() => {
            menus_vue.forEach((menu, index) => {
                if (menu.sub_menus.some((s) => currentPath === s.link)) {
                    openIndex.value = index;
                }
            });
        });

        return {
            menus_vue,
            openIndex,
            toggleSubmenu,
            isActive,
            isActiveGroup,
            siteSettings
        };
    },
};
</script>

<style scoped>
.nav-link.active {
    background-color: #0d6efd !important;
    color: #fff !important;
}

.nav-treeview {
    padding-left: 10px;
}

</style>
