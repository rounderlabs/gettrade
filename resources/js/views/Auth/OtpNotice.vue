<template>

    <div class="auth-header">
        <a href="/"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left back-btn"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> </a>

        <img class="img-fluid img" src="/sunlotusinfra/assets-panel/assets/images/otp.svg" alt="v1">

        <div class="auth-content">
            <div>
                <h2>OTP verification</h2>
                <h4 class="p-0">Enter 6 digit OTP Code</h4>
            </div>
        </div>
    </div>


    <form @submit.prevent="submit" class="auth-form">
        <div class="custom-container">
            <div class="form-group">
                <h5>Enter the 6-digit number we sent you in a registration message to confirm your email.</h5>
                <label for="otp" class="form-label">OTP</label>
                <div class="form-input">
                    <input type="number" class="form-control"  v-model="form.code" id="otp" placeholder="Enter OTP">
                </div>
            </div>
            <button :disabled="form.processing" type="submit" class="btn theme-btn w-100" id="submitbtn"><font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>Verify</button>



            <h4 class="signup">Didn't Received OTP ?<Link :href="route('otp.resend')"> Resend OTP</Link></h4>
            <h4 class="signup">Not Your Account ?<a :href="route('logout')">Logout</a></h4>
        </div>
    </form>













</template>

<script>
import {Link} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default {
    name: "OtpNotice",

    components: {FontAwesomeIcon, Link},
    props: {
        auth: Object,
        errors: Object,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                code: null
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('otp.verify'), {
                onFinish: () => this.form.reset('code'),
            })
        },

        resendOtp() {
            this.form.post(this.route('otp.resend'), {
                onFinish: () => this.form.reset('code'),
            })
        }
    }
}
</script>

<style scoped>

</style>
