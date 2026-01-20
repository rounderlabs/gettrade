<template>


    <section class="section-b-space">
        <div class="custom-container">
            <div class="profile-section">
                <div class="profile-banner">

                    <div class="profile-image">
                        <img alt="p3" class="img-fluid profile-pic" src="/user-panel/assets-panel/assets/images/p3.png">
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

            <div class="auth-form pt-0 mt-3">
                <div class="form-group">
                    <label class="form-label" for="email">Name</label>
                    <input id="name" :value="profile.full_name" class="form-control" readonly type="text">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Id</label>
                    <input id="email" :value="profile.email" class="form-control" readonly type="text">
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone number</label>
                    <input id="phone" :value="profile.mobile" class="form-control" readonly type="text">
                </div>

                <div class="form-group">
                    <label class="form-label" for="user_id">Login id / Referral ID</label>
                    <div class="form-input">
                        <input id="user_id" :value="profile.username" class="form-control" readonly type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ref_by">Referral By</label>
                    <div class="form-input">
                        <input id="ref_by" :value="profile.referred_by" class="form-control" readonly type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="joined">Joined On</label>
                    <div class="form-input">
                        <input id="joined" :value="profile.joined_on" class="form-control" readonly type="text">
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="panel-space"></section>

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
