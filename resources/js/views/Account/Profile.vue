<template>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="profile-section">
                <div class="profile-banner">

                    <div class="profile-image">
                        <img class="img-fluid profile-pic" src="/shuchack/assets-panel/assets/images/p3.png" alt="p3">
                    </div>
                </div>
                <h2>{{ profile.full_name }}</h2>
                <h5>{{ profile.username }}</h5>
                <div class="kyc-status mt-2">
                    <span :class="kycBadgeClass" class="badge">
                        {{ kycLabel }}
                    </span>
                </div>
            </div>

            <ul class="profile-list">
                <li>
                    <a :href="route('account.details')" class="profile-box">
                        <div class="profile-img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="profile-details">
                            <h4>My Account</h4>
                            <img class="img-fluid arrow" src="/shuchack/assets-panel/assets/images/arrow.svg" alt="arrow">
                        </div>
                    </a>
                </li>
                <li>
                    <Link :href="route('account.withdraw.wallet')" class="profile-box">
                        <div class="profile-img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card icon"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div class="profile-details">
                            <h4>Withdraw Account</h4>
                            <img class="img-fluid arrow" src="/shuchack/assets-panel/assets/images/arrow.svg" alt="arrow">
                        </div>
                    </Link>
                </li>
                <li>
                    <Link :href="route('account.change.password')" class="profile-box">
                        <div class="profile-img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings icon"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </div>
                        <div class="profile-details">
                            <h4>Change Password</h4>
                            <img class="img-fluid arrow" src="/shuchack/assets-panel/assets/images/arrow.svg" alt="arrow">
                        </div>
                    </Link>
                </li>
<!--                <li>-->
<!--                    <Link :href="route('account.setting')" class="profile-box">-->
<!--                        <div class="profile-img">-->
<!--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock icon"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>-->
<!--                        </div>-->
<!--                        <div class="profile-details">-->
<!--                            <h4>Settings</h4>-->
<!--                            <img class="img-fluid arrow" src="/shuchack/assets-panel/assets/images/arrow.svg" alt="arrow">-->
<!--                        </div>-->
<!--                    </Link>-->
<!--                </li>-->

<!--                <li>-->
<!--                    <a href="help.html" class="profile-box">-->
<!--                        <div class="profile-img">-->
<!--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle icon"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>-->
<!--                        </div>-->
<!--                        <div class="profile-details">-->
<!--                            <h4>Help Center</h4>-->
<!--                            <img class="img-fluid arrow" src="/shuchack/assets-panel/assets/images/arrow.svg" alt="arrow">-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
                <li>
                    <a :href="route('logout')" class="profile-box">
                        <div class="profile-img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out icon"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        </div>
                        <div class="profile-details">
                            <h4>Log Out</h4>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import InputError from "@/components/InputError";
import ProfileLeft from "@/views/Account/ProfileLeft";
import ProfileRight from "@/views/Account/ProfileRight";
import {Link} from "@inertiajs/vue3";

export default {
    name: "Profile",
    components: {ProfileRight, ProfileLeft, InputError, Link},
    metaInfo: {title: 'Profile'},
    layout: UserLayout,
    props: {
        profile: Object,
        kyc: Object
    },
    computed: {
        kycLabel() {
            return {
                not_submitted: 'KYC Not Submitted',
                pending: 'KYC Pending',
                approved: 'KYC Verified',
                rejected: 'KYC Rejected'
            }[this.kyc.status]
        },
        kycBadgeClass() {
            return {
                not_submitted: 'bg-secondary',
                pending: 'bg-warning',
                approved: 'bg-success',
                rejected: 'bg-danger'
            }[this.kyc.status]
        }
    },
}
</script>

<style scoped>

</style>
