<template>

    <div class="auth-header">
        <a href="/"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left back-btn"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> </a>

        <img class="img-fluid img" src="/sunlotusinfra/assets-panel/assets/images/login1.svg" alt="v1">
        <div class="auth-content">
            <div>
                <h2>Forgot Your Password ?</h2>
                <h4 class="p-0">Enter your User ID to<br>reset password of your account</h4>
            </div>
        </div>
    </div>

    <form class="auth-form" @submit.prevent="submit">
        <div class="custom-container">
            <div class="form-group">
                <label for="inputusername" class="form-label">User id</label>
                <div class="form-input">
                    <input type="text" class="form-control" v-model="form.username"  placeholder="Enter Your User ID">
                </div>
            </div>
            <button :disabled="form.processing" type="submit" class="btn theme-btn w-100"><font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>Send OTP</button>

            <h4 class="signup">Don’t have an account ?<a :href="route('register')"> Sign up</a></h4>
            <h4 class="signup">Already Have Account ?<a :href="route('login')"> Login</a></h4>
        </div>
    </form>



</template>

<script>
import {defineComponent} from "vue";
import {useForm} from '@inertiajs/vue3'
import {toast} from "@/utils/toast";
import LogoIcon from "@/components/LogoIcon";
import LogoFull from "@/components/LogoFull";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import NotificationToast from "@/components/NotificationToast.vue";


export default defineComponent({
    name: "ForgotPassword",
    components: { FontAwesomeIcon, LogoFull, LogoIcon, NotificationToast},
    setup() {
        const form = useForm({
            username: null
        })

        function submit() {
            form.post(route('password.email'), {
                onFinish: () => {
                    form.reset('username')
                },
                onSuccess: () => {
                    toast('Reset email has been sent successfully')
                }
            })
        }

        return {form, submit}

    }
})
</script>

<style scoped>


</style>
