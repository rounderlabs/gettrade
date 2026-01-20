<script setup>
import { Link, usePage } from "@inertiajs/vue3"
import { computed } from "vue"

/* =========================
   PAGE + SETTINGS
========================= */
const page = usePage()
const siteSettings = page.props.siteSettings

const currentRoute = computed(() => page.url)
</script>

<template>
    <div class="navbar-menu">
        <!-- CENTER LOGO / DASHBOARD -->
        <div class="scanner-bg">
            <Link :href="route('dashboard')" class="scanner-btn">
                <img
                    v-if="siteSettings.logo_mobile || siteSettings.logo_desktop"
                    class="img-fluid"
                    :src="siteSettings.logo_mobile ?? siteSettings.logo_desktop"
                    :alt="siteSettings.site_name ?? 'Logo'"
                />
            </Link>
        </div>

        <ul>
            <!-- DASHBOARD -->
            <li :class="{ active: currentRoute.includes('dashboard') }">
                <Link :href="route('dashboard')">
                    <div class="icon">
                        <img
                            class="unactive"
                            src="/user-panel/assets-panel/assets/images/mpay.svg"
                            alt="Dashboard"
                        />
                        <img
                            class="active"
                            src="/user-panel/assets-panel/assets/images/mpay-fill.svg"
                            alt="Dashboard"
                        />
                    </div>
                    <h5>Dashboard</h5>
                </Link>
            </li>

            <!-- WALLET -->
            <li :class="{ active: currentRoute.includes('wallet') }">
                <Link :href="route('wallet.index')">
                    <div class="icon">
                        <img
                            style="width:22px"
                            class="unactive"
                            src="/user-panel/assets-panel/assets/images/wallet-svg.svg"
                            alt="Wallet"
                        />
                        <img
                            style="width:22px"
                            class="active"
                            src="/user-panel/assets-panel/assets/images/wallet-svg-fill.svg"
                            alt="Wallet"
                        />
                    </div>
                    <h5>Wallet</h5>
                </Link>
            </li>

            <!-- KYC -->
            <li :class="{ active: currentRoute.includes('kyc') }">
                <Link :href="route('kyc.index')">
                    <div class="icon">
                        <img
                            class="unactive"
                            src="/user-panel/assets-panel/assets/images/bar-chart.svg"
                            alt="KYC"
                        />
                        <img
                            class="active"
                            src="/user-panel/assets-panel/assets/images/bar-chart-fill.svg"
                            alt="KYC"
                        />
                    </div>
                    <h5>My KYC</h5>
                </Link>
            </li>

            <!-- PROFILE -->
            <li :class="{ active: currentRoute.includes('account') }">
                <Link :href="route('account.profile')">
                    <div class="icon">
                        <img
                            class="unactive"
                            src="/user-panel/assets-panel/assets/images/user.svg"
                            alt="Profile"
                        />
                        <img
                            class="active"
                            src="/user-panel/assets-panel/assets/images/user-fill.svg"
                            alt="Profile"
                        />
                    </div>
                    <h5>Profile</h5>
                </Link>
            </li>
        </ul>
    </div>
</template>

<style scoped>
/* Optional: ensure center logo fits nicely */
.scanner-btn img {
    max-width: 60px;
    max-height: 60px;
    object-fit: contain;
}
</style>
