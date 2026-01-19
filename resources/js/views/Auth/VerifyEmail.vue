<template>
    <main class="main mainheight container-fluid " style="margin-top: 200px">
        <div class="col-12 align-self-center text-center mt-auto">
            <img src="/panel-assets/img/soluminity_square.png" style="width:200px" alt="" />
        </div>

    <div class="row h-100 justify-content-center">
        <div class="col-12 align-self-center">
            <!-- time and temperature ends -->
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3 text-center text-white py-4 py-lg-5">
                    <h3 class="mb-3 mb-lg-4">OTP Verification</h3>
                    <p class="mb-4">
                        Kindly check your registered email address, We have send OTP. As a part of security and brand trust, we always verify user while creating account to avoid misuse of email address.
                    </p>
                    <form class="mb-4 text-start" @submit.prevent="submit">
                        <!-- alert messages -->
                        <div class="alert alert-danger fade show d-none mb-2 global-alert text-start" role="alert">
                            <div class="row">
                                <div class="col"><strong>Error!</strong> OTP doesn't matched.</div>
                            </div>
                        </div>
                        <div class="alert alert-success fade show d-none mb-2 global-success text-start" role="alert">
                            <div class="row">
                                <div class="col-auto align-self-center">
                                    <div class="spinner-border spinner-border-sm text-success me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="col ps-0 align-self-center">
                                    <strong>You done a good job!</strong><br>You have Signed up successfully.
                                </div>
                            </div>
                        </div>
                        <!-- Form elements -->
                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-group position-relative">
                                    <input type="text" placeholder="" value="" required="" class="form-control form-control-lg border-start-0 text-center numberonly" id="verify1">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group position-relative">
                                    <input type="text" placeholder="" value="" required="" class="form-control form-control-lg border-start-0 text-center numberonly" id="verify2">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group position-relative">
                                    <input type="text" placeholder="" value="" required="" class="form-control form-control-lg border-start-0 text-center numberonly" id="verify3">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group position-relative">
                                    <input type="text" placeholder="" value="" required="" class="form-control form-control-lg border-start-0 text-center numberonly" id="verify4" autofocus="">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-4">
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto">
                                        <!-- Resend loader -->
                                        <div class="progressstimer">
                                            <img src="/panel-assets/img/laoderfixed-white.png" alt="">
                                            <p class="timer text-muted" id="timer">2:36</p>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <p class="mb-0"><a href="" class="text-white">Resend</a></p>
                                        <p class="small text-muted">Allowed once</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3 mt-auto text-center mb-4">
            <!-- submit button -->
            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3"
                    type="submit">
                Resend Verification Email
                <font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>
            </button>
            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-lg btn-theme top-0 end-0 z-index-5 w-100 mb-3" type="button" id="verifyBtn">Verify <i class="bi bi-arrow-right"></i></button>

            <p class="text-dark"><a href="/login" class="text-dark">Already have account? Go back to login </a></p>
        </div>
    </div>


    </main>














    <div class="login">
        <!-- BEGIN login-content -->
        <div class="login-content">
            <h2>Welcome to Master BOT</h2>
            <h2 class="text-left py-3">{{ auth.user.name }}</h2>
            <h4 class="text-left py-1">Please verify your email in order to access your account</h4>
            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3"
                            type="submit">
                        Resend Verification Email
                        <font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>
                    </button>
                </div>
            </form>
            <a :href="route('logout')" class="btn btn-danger btn-sm d-block w-50 fw-500 mb-3">Logout</a>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->

</template>

<script>
import {toast} from "@/utils/toast";
import LogoIcon from "@/components/LogoIcon";
import LogoFull from "@/components/LogoFull";

export default {
    components: {LogoFull, LogoIcon},

    props: {
        auth: Object,
        errors: Object,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form()
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('verification.send'), {
                onSuccess: () => {
                    toast('Email link has been sent successfully')
                }
            })
        },
    },

    computed: {
        verificationLinkSent() {
            return this.status === 'verification-link-sent';
        }
    }
}
</script>
<style scoped>
@media (min-width: 480px) {
    .main-card-signin {
        max-width: 400px;
    }
}
</style>
