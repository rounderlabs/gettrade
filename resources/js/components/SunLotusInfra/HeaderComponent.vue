<template>
    <!-- header start -->
    <header class="section-t-space">
        <div class="custom-container">
            <div class="header-panel">
                <!-- Sidebar toggle -->
                <a
                    class="sidebar-btn"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasLeft"
                >
                    <vue-feather type="menu" class="menu-icon" />
                </a>

                <!-- Logo -->
                <img
                    v-if="siteSettings.logo_mobile || siteSettings.logo_desktop"
                    class="img-fluid logo"
                    :src="siteSettings.logo_mobile ?? siteSettings.logo_desktop"
                    :alt="siteSettings.site_name ?? 'Logo'"
                />

                <!-- Logout -->
                <a href="/logout" class="notification">
                    <vue-feather type="lock" class="menu-icon" />
                </a>
            </div>
        </div>
    </header>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3"

const siteSettings = usePage().props.siteSettings
</script>

<style scoped>
.header-panel {
    position: relative;
}

.header-panel .logo {
    position: absolute;
    width: 75px;
    height: 75px;
    left: 50%;
    transform: translateX(-50%);
}

.header-panel .notification {
    width: 45px;
}
</style>
