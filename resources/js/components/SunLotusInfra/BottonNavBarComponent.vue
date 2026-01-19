<script>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export default {
    name: "BottomNavBarComponent",
    components: { Link },
    setup() {
        const page = usePage();
        // const currentRoute = computed(() => page.props.value.ziggy.location);
        const currentRoute = computed(() => page.url);
        return { currentRoute };
    }
};
</script>

<template>
    <div class="navbar-menu">
        <div class="scanner-bg">
            <Link :href="route('dashboard')" class="scanner-btn">
                <img class="img-fluid" src="/shuchack/assets-panel/assets/images/shuchak-logo.png" alt="scan" />
            </Link>
        </div>

        <ul>
            <li :class="{ active: currentRoute.includes('dashboard') }">
                <Link :href="route('dashboard')">
                    <div class="icon">
                        <img class="unactive" src="/shuchack/assets-panel/assets/images/mpay.svg" alt="mPay" />
                        <img class="active" src="/shuchack/assets-panel/assets/images/mpay-fill.svg" alt="mPay" />
                    </div>
                    <h5>Dashboard</h5>
                </Link>
            </li>

            <li :class="{ active: currentRoute.includes('wallet') }">
                <Link :href="route('wallet.index')">
                    <div class="icon">
                        <img style="width:22px" class="unactive" src="/shuchack/assets-panel/assets/images/wallet-svg.svg" alt="Wallet" />
                        <img style="width:22px"  class="active" src="/shuchack/assets-panel/assets/images/wallet-svg-fill.svg" alt="Wallet" />
                    </div>
                    <h5>Wallet</h5>
                </Link>
            </li>

            <li :class="{ active: currentRoute.includes('kyc') }">
                <Link :href="route('kyc.index')">
                    <div class="icon">
                        <img class="unactive" src="/shuchack/assets-panel/assets/images/bar-chart.svg" alt="KYC" />
                        <img class="active" src="/shuchack/assets-panel/assets/images/bar-chart-fill.svg" alt="KYC" />
                    </div>
                    <h5>My kyc</h5>
                </Link>
            </li>

            <li :class="{ active: currentRoute.includes('account') }">
                <Link :href="route('account.profile')">
                    <div class="icon">
                        <img class="unactive" src="/shuchack/assets-panel/assets/images/user.svg" alt="Profile" />
                        <img class="active" src="/shuchack/assets-panel/assets/images/user-fill.svg" alt="Profile" />
                    </div>
                    <h5>Profile</h5>
                </Link>
            </li>
        </ul>
    </div>
</template>

<style scoped>

</style>
