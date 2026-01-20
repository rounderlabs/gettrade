<template>
    <div class="auth-header">
        <a href="/"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left back-btn"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> </a>

        <img class="img-fluid img" src="/user-panel/assets-panel/assets/images/login1.png" alt="v1">
        <div class="auth-content">
            <div>
                <h2>WELCOME !!</h2>
                <h4 class="p-0">To The Get Trade</h4>
            </div>
        </div>
    </div>

    <form class="auth-form" @submit.prevent="submit">
        <div class="custom-container">
            <div class="form-group">
                <label for="inputusername" class="form-label">User id</label>
                <div class="form-input">
                    <input type="text" class="form-control" v-model="form.username" id="inputusername" placeholder="Enter Your User ID">
                </div>
            </div>

            <div class="form-group">
                <label for="inputpin" class="form-label">Password</label>
                <div class="form-input">
                    <input :type="isShowPassword?'text':'password'" v-model="form.password" class="form-control" id="inputpin" placeholder="Enter Your Password">
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

            <h4 class="signup">Don’t have an account ?<a :href="route('register')"> Sign up</a></h4>
            <h4 class="signup">Forget Password ?<a :href="route('password.request')"> Reset</a></h4>
        </div>
    </form>
    <PwaInstallPrompt></PwaInstallPrompt>
</template>


<script>
import { defineComponent, onMounted, ref } from "vue";
import {useForm} from '@inertiajs/vue3'
import {toast} from "@/utils/toast";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import PwaInstallPrompt from "@/components/PwaInstallPrompt.vue";
import NotificationToast from "@/components/NotificationToast.vue";


export default defineComponent({
    name: "Login",
    components: {PwaInstallPrompt, FontAwesomeIcon , NotificationToast},
    setup() {
        const isShowPassword = ref(false);
        const form = useForm({
            username: null,
            password: null,
            remember: false
        })

        function togglePassword() {

            isShowPassword.value = !isShowPassword.value;

        }

        function submit() {
            form.post(route('login'), {
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                },
                onFinish: () => form.reset('password')
            })
        }

        onMounted(() => {
            $('body')

                .removeClass('error-page')
                .addClass('login1')
        })

        return {
            form, submit, togglePassword, isShowPassword
        }
    }
})
</script>

<style scoped>
.bgwhite{
    background: #ffffff;
}
.rounded {
    border-radius: 1.25rem;
}
</style>
