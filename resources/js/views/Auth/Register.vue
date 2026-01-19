<template>

    <div class="auth-header">
        <a href="/"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left back-btn"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> </a>

        <img class="img-fluid img" src="/shuchack/assets-panel/assets/images/register1.png" alt="v1">
        <div class="auth-content">
            <div>
                <h2>WELCOME !!</h2>
                <h4 class="p-0">TO THE SUCHAK</h4>
            </div>
        </div>
    </div>

    <form class="auth-form" @submit.prevent="submit">
        <div class="custom-container">


            <div class="form-group">
                <label for="ref_id" class="form-label">Referral ID</label>
                <div class="form-input">
                    <input type="text"
                           class="form-control"
                           v-model="form.referral"
                           v-input-error="form.errors.referral"
                           :readonly="referred_by"
                           autofocus @focusout="checkSponsor"
                           id="ref_id"
                    >
                </div>
            </div>


            <div class="form-group">
                <label for="ref_name" class="form-label">Referral By (Name)</label>
                <div class="form-input">
                    <input type="text"
                           class="form-control"
                           placeholder="Referral Name"
                           v-model="sponsorName"
                           v-input-error="form.errors.referral"
                           :readonly="sponsorName"
                           required
                           id="ref_name"
                    >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <div class="form-input">
                    <input type="text"
                           class="form-control"
                           placeholder="Full Name"
                           v-model="form.full_name"
                           v-input-error="form.errors.full_name"
                           required
                           id="name"
                    >
                </div>
            </div>

             <div class="form-group">
                <label for="email" class="form-label">Your Email</label>
                <div class="form-input">
                    <input
                           class="form-control"
                           type="email"
                           placeholder="Email Address"
                           v-model="form.email"
                           v-input-error="form.errors.email"
                           required
                           id="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="mobile" class="form-label">Your 10 Digit Mobile No</label>
                <div class="form-input">
                    <input type="text"
                           class="form-control"
                           placeholder="Mobile"
                           v-model="form.mobile"
                           v-input-error="form.errors.mobile"
                           required
                           id="mobile"
                    >
                </div>
            </div>


            <div class="form-group">
                <label for="password" class="form-label">Create Password</label>
                <div class="form-input">
                    <input :type="isShowPassword?'text':'password'"
                           class="form-control"
                           placeholder="Create Password"
                           v-model="form.password"
                           v-input-error="form.errors.password"
                           required
                           id="password"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="form-input">
                    <input :type="isShowPassword?'text':'password'"
                           class="form-control"
                           placeholder="Confirm Password"
                           v-model="form.password_confirmation"
                           v-input-error="form.errors.password_confirmation"
                           required
                           id="confirm_password"
                    >
                </div>
            </div>



            <div class="remember-option mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                </div>
                <a class="forgot" id="viewpassword" @click="togglePassword()">{{isShowPassword?'Hide Password':'Show Password'}}</a>
            </div>
            <button :disabled="form.processing" type="submit" class="btn theme-btn w-100" id="submitbtn"><font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>Sign in</button>

            <h4 class="signup">Already have an account ?<a :href="route('login')"> Sign In</a></h4>
            <h4 class="signup">Forget Password ?<a :href="route('password.request')"> Reset</a></h4>
        </div>
    </form>
</template>


<script>
import { defineComponent, onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from "@/utils/toast";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import NotificationToast from "@/components/NotificationToast.vue";


export default defineComponent({
    name: "Register",
    components: { FontAwesomeIcon, toast , NotificationToast},
    props: {
        referred_by: String,
        position: String
    },

    setup(props) {
        const isShowPassword = ref(false);
        const sponsorName = ref(null);
        const username = ref(null);
        const form = useForm({
            referral: props.referred_by,
            full_name: null,
            email: null,
            mobile: null,
            password: null,
            password_confirmation: null
        });

        function togglePassword() {

            isShowPassword.value = !isShowPassword.value;

        }

        function submit() {
            form.post(route("register"), {
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                },
                onFinish() {
                    form.reset("password", "password_confirmation");
                }
            });
        }
        const checkSponsor = () => {
            sponsorName.value = null;
            if (form.referral) {
                axios.post(route("validate.user"), {
                    referral: form.referral
                }).then(res => {
                    sponsorName.value = res.data.sponsor_name;
                }).catch(err => {
                    toast("Referral does not exist", "danger");
                });
            }
        };

        const checkUserName = () => {
            username.value = form.username;
            if (form.username) {
                axios.post(route("validate.username"), {
                    username: form.username
                }).then(res => {
                    toast("Username Already Taken By Someone", "danger");
                }).catch(err => {
                    toast("Username Available", "success");
                });
            }
        };




        onMounted(() => {
            $("body")
                .removeClass("main")
                .removeClass("error-page")
                .addClass("app");
        });

        return {
            sponsorName, form, submit, checkSponsor, togglePassword, isShowPassword, checkUserName, username
        };
    }
});
</script>

<style scoped>


</style>
