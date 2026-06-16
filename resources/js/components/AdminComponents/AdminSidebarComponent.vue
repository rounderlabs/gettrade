<template>
    <!--begin::Sidebar-->
    <aside class="app-sidebar shadow-lg" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand-wrapper">
            <Link class="brand-link-wrapper" href="/admin/dashboard">
                <img
                    v-if="siteSettings.logo_desktop"
                    class="brand-logo shadow"
                    :src="siteSettings.logo_desktop"
                    alt="Logo"
                />
                <span class="brand-title-text">{{siteSettings.site_name}}</span>
            </Link>
        </div>

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-scroll-wrapper">
            <nav class="sidebar-nav-container mt-3">
                <ul
                    id="navigation"
                    aria-label="Main navigation"
                    class="nav flex-column navigation-list"
                    role="navigation"
                >
                    <!-- Dynamic Menus -->
                    <li
                        v-for="(menu, index) in menus_vue"
                        :key="index"
                        :class="{ 'menu-open': openIndex === index }"
                        class="nav-item-wrapper"
                    >
                        <!-- Parent menu -->
                        <a
                            class="nav-link-parent"
                            :class="{ 'active-parent': isActiveGroup(menu) }"
                            href="javascript:void(0)"
                            @click="toggleSubmenu(index)"
                            :title="isCollapsed ? menu.heading : ''"
                        >
                            <div class="d-flex align-items-center w-100 justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container">
                                        <i :class="menu.iconClass || 'bi-list'" class="nav-icon"></i>
                                    </div>
                                    <span class="nav-text ms-3">{{ menu.heading }}</span>
                                </div>
                                <i
                                    v-if="menu.sub_menus && menu.sub_menus.length"
                                    :class="openIndex === index ? 'bi-chevron-down' : 'bi-chevron-right'"
                                    class="nav-arrow ms-auto"
                                ></i>
                            </div>
                        </a>

                        <!-- Submenu -->
                        <transition name="slide">
                            <ul
                                v-if="openIndex === index && !isCollapsed"
                                :id="'submenu-' + index"
                                class="nav nav-tree-submenu flex-column"
                            >
                                <li
                                    v-for="(sub, subIndex) in menu.sub_menus"
                                    :key="subIndex"
                                    class="nav-item-sub"
                                >
                                    <Link
                                        :class="{ active: isActive(sub.link) }"
                                        :href="sub.link"
                                        class="nav-link-sub"
                                    >
                                        <i class="dot-icon fas fa-minus me-2"></i>
                                        <span>{{ sub.title }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item-wrapper mt-4">
                        <a class="nav-link-parent text-danger logout-btn" href="/admin/logout" :title="isCollapsed ? 'Logout' : ''">
                            <div class="d-flex align-items-center">
                                <div class="icon-container">
                                    <i class="nav-icon bi bi-box-arrow-right text-danger"></i>
                                </div>
                                <span class="nav-text ms-3 text-danger">Logout</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!--end::Sidebar-->
</template>

<script>
import {Link, usePage, router} from '@inertiajs/vue3';
import {onBeforeUnmount, onMounted, ref} from "vue";

export default {
    name: "AdminSidebarComponent",
    components: {Link},

    setup() {
        const page = usePage();
        const openIndex = ref(null);
        const siteSettings = page.props.siteSettings || {};
        const isCollapsed = ref(false);

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
                iconClass: "bi-shield-check",
                sub_menus: [
                    {
                        title: "All Kyc",
                        link: route("admin.kyc.index"),
                    },
                ],
            },
            {
                heading: "Fund Request",
                iconClass: "bi-journal-arrow-down",
                sub_menus: [
                    {
                        title: "Deposit History",
                        link: route("admin.fund.requests.index"),
                    },
                ],
            },
            {
                heading: "Investment",
                iconClass: "bi-wallet2",
                sub_menus: [
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
                iconClass: "bi-graph-up-arrow",
                sub_menus: [
                    {title: "Trading Bonus", link: route("admin.reports.user.trading.bonus")},
                    {title: "Systematic Bonus", link: route("admin.reports.user.level.roi.bonus")},
                    {title: "Direct Bonus", link: route("admin.reports.user.direct.bonus")},
                    {title: "Rank Bonus", link: route("admin.reports.user.rank.income")},
                    {title: "Pro User Report", link: route("admin.reports.pro-user-report")},
                ],
            },
            {
                heading: "Settings",
                iconClass: "bi-sliders",
                sub_menus: [
                    {title: "Plan List", link: route("admin.plans.index")},
                    {title: "Rewards List", link: route("admin.rewards.index")},
                    {title: "Rank Setting", link: route("admin.ranks.index")},
                    {title: "Commission Setting", link: route("admin.site.settings.index")},
                    {title: "Schedule Setting", link: route("admin.scheduled.jobs.index")},
                    {title: "Currency Setting", link: route("admin.currencies.index")},
                ],
            },
            {
                heading: "Notifications",
                iconClass: "bi-bell",
                sub_menus: [
                    {
                        title: "Activity Notifications",
                        link: route("admin.notifications.index"),
                    },
                ],
            },
        ];

        const updateSidebarState = () => {
            const body = document.body;
            isCollapsed.value = body.classList.contains("sidebar-collapse");
            if (window.innerWidth <= 991) {
                body.classList.add("sidebar-closed");
            } else {
                body.classList.remove("sidebar-closed");
            }
        };

        const isActive = (link) => {
            try {
                const relativeLink = link.replace(window.location.origin, '');
                const cleanLink = relativeLink.split('?')[0];
                const cleanCurrent = page.url.split('?')[0];
                return cleanCurrent === cleanLink;
            } catch (e) {
                return page.url === link;
            }
        };

        const isActiveGroup = (menu) =>
            menu.sub_menus?.some((s) => isActive(s.link));

        let unsubscribeNavigate;

        onMounted(() => {
            updateSidebarState();
            window.addEventListener("resize", updateSidebarState);

            unsubscribeNavigate = router.on("navigate", () => {
                if (window.innerWidth <= 991) {
                    document.body.classList.add("sidebar-collapse", "sidebar-closed");
                    document.body.classList.remove("sidebar-open");
                }
                // Check sidebar collapse state after navigation
                setTimeout(() => {
                    isCollapsed.value = document.body.classList.contains("sidebar-collapse");
                }, 100);
            });

            menus_vue.forEach((menu, index) => {
                if (menu.sub_menus.some((s) => isActive(s.link))) {
                    openIndex.value = index;
                }
            });
        });

        onBeforeUnmount(() => {
            window.removeEventListener("resize", updateSidebarState);
            if (unsubscribeNavigate) unsubscribeNavigate();
        });

        const toggleSubmenu = (index) => {
            isCollapsed.value = document.body.classList.contains("sidebar-collapse");
            if (isCollapsed.value) {
                return;
            }
            openIndex.value = openIndex.value === index ? null : index;
        };

        return {
            menus_vue,
            openIndex,
            toggleSubmenu,
            isActive,
            isActiveGroup,
            siteSettings,
            isCollapsed
        };
    },
};
</script>

<style scoped>
/* Glassmorphism Dark Mode Theme */
.app-sidebar {
    background: #111827 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    min-height: 100vh;
}

.sidebar-brand-wrapper {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.02);
    overflow: hidden;
    white-space: nowrap;
    transition: padding 0.3s ease;
}

.brand-link-wrapper {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.brand-logo {
    max-height: 38px;
    width: auto;
    border-radius: 8px;
    margin-right: 12px;
    transition: margin 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-title-text {
    font-size: 1.2rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 0.03em;
    background: linear-gradient(to right, #ffffff, #e2e8f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    opacity: 1;
    transition: opacity 0.3s ease;
}

.sidebar-scroll-wrapper {
    overflow-y: auto;
    height: calc(100vh - 80px);
}

.sidebar-scroll-wrapper::-webkit-scrollbar {
    width: 5px;
}
.sidebar-scroll-wrapper::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

.navigation-list {
    padding-left: 0;
}

.nav-item-wrapper {
    list-style: none;
    margin-bottom: 6px;
    padding: 0 14px;
}

.nav-link-parent {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.925rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
}

.nav-link-parent:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.active-parent {
    color: #ffffff;
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.15) 0%, rgba(13, 110, 253, 0.05) 100%) !important;
    border: 1px solid rgba(13, 110, 253, 0.3) !important;
}

.icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    width: 24px;
    min-width: 24px;
    height: 24px;
    transition: color 0.25s ease;
}

.active-parent .icon-container {
    color: #0d6efd;
}

.nav-text {
    font-weight: 600;
    letter-spacing: 0.015em;
    opacity: 1;
    transition: opacity 0.3s ease;
}

.nav-arrow {
    font-size: 0.75rem;
    color: #64748b;
    transition: transform 0.3s ease;
}

.nav-tree-submenu {
    padding-left: 0;
    margin-top: 4px;
    margin-bottom: 4px;
    list-style: none;
    border-left: 1px dashed rgba(255, 255, 255, 0.1);
    margin-left: 26px;
    padding-left: 10px;
}

.nav-item-sub {
    margin-bottom: 3px;
}

.nav-link-sub {
    display: flex;
    align-items: center;
    padding: 8px 14px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #94a3b8;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.nav-link-sub:hover {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.03);
}

.nav-link-sub.active {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.12) 0%, rgba(13, 110, 253, 0.03) 100%) !important;
    color: #0d6efd !important;
    font-weight: 700;
}

.dot-icon {
    font-size: 0.55rem;
    color: #475569;
}

.nav-link-sub.active .dot-icon {
    color: #0d6efd;
}

.logout-btn:hover {
    background-color: rgba(220, 38, 38, 0.1) !important;
    border-color: rgba(220, 38, 38, 0.2) !important;
}

.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.3s ease-in-out, opacity 0.25s ease;
    max-height: 400px;
    overflow: hidden;
}
.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}

/* Collapsed / Mini Sidebar Custom Styles to prevent text leaks */
@media (min-width: 992px) {
    body.sidebar-collapse .app-sidebar {
        width: 72px !important;
    }
    body.sidebar-collapse .brand-title-text,
    body.sidebar-collapse .nav-text,
    body.sidebar-collapse .nav-arrow,
    body.sidebar-collapse .nav-tree-submenu {
        display: none !important;
        opacity: 0;
    }
    body.sidebar-collapse .sidebar-brand-wrapper {
        padding: 1.5rem 0.5rem;
        justify-content: center;
    }
    body.sidebar-collapse .brand-logo {
        margin-right: 0;
    }
    body.sidebar-collapse .nav-link-parent {
        justify-content: center;
        padding: 12px;
    }
    body.sidebar-collapse .nav-item-wrapper {
        padding: 0 10px;
    }
}
</style>

