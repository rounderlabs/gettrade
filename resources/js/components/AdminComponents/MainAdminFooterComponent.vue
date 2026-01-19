<template>
    <footer class="footer-sticky">
        <div class="container">
            <ul class="nav nav-pills nav-justified">
                <li class="nav-item">
                    <a class="nav-link" :href="route('team.genealogy')">
                        <span>
                            <i class="nav-icon bi bi-house"></i>
                            <span class="nav-text">Team</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="route('earnings.direct.referral.income')">
                        <span>
                            <i class="nav-icon bi bi-currency-dollar"></i>
                            <span class="nav-text">Income</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item centerbutton">
                    <a class="nav-link" :href="route('admin.dashboard')">
                        <span class="bg-radial-gradient-theme">
                            <i class="bi bi-house size-22"></i>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="route('withdraw.usdt')">
                        <span>
                            <i class="nav-icon bi bi-wallet"></i>
                            <span class="nav-text">Wallet</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="route('account.profile')">
                        <span>
                            <i class="nav-icon bi bi-person"></i>
                            <span class="nav-text">Profile</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </footer>
</template>

<script>
export default {
    name: "MainAdminFooterComponent"
}
</script>

<style scoped>

</style>
