<template>
    <div>
        <div class="my-auto page page-h">
            <div class="main-signin-wrapper">
                <div class="main-card-signin d-md-flex wd-100p">

                    <div class="sign-up-body wd-md-100p">
                        <div class="main-signin-header text-center">
                            <div class="">
                                <LogoFull></LogoFull>
                                <h2>Welcome back!</h2>
                                <h4 class="text-left">Reset Your Password</h4>
                                <form @submit.prevent="submit">
                                    <div class="intro-x mt-8">
                                        <input
                                            v-model="form.email"
                                            v-input-error="form.errors.email"
                                            class="form-control border-start-0"
                                            placeholder="Email Address"
                                            readonly
                                            type="text"
                                        />
                                        <span v-if="form.errors.email" class="invalid-feedback">
                                {{ form.errors.email }}
                            </span>
                                        <input
                                            v-model="form.password"
                                            v-input-error="form.errors.password"
                                            class="form-control border-start-0"
                                            placeholder="Password"
                                            type="password"
                                        />
                                        <span v-if="form.errors.password" class="invalid-feedback">
                                {{ form.errors.password }}
                                </span>
                                        <input
                                            v-model="form.password_confirmation"
                                            v-input-error="form.errors.password_confirmation"
                                            class="form-control border-start-0"
                                            placeholder="Password Confirmation"
                                            type="password"
                                        />
                                        <span v-if="form.errors.password_confirmation" class="invalid-feedback">
                                {{ form.errors.password_confirmation }}
                                </span>
                                    </div>

                                    <div class="intro-x mt-5  text-center">
                                        <button :disabled="form.processing"
                                                class="btn btn-primary py-3 px-4 w-full align-top"
                                                type="submit">
                                            Reset Password
                                            <font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="main-signup-footer mg-t-10">
                            <p>Already have an account? <a :href="route('login')">Sign In</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import {useForm} from '@inertiajs/vue3'
import {toast} from "@/utils/toast";
import LogoIcon from "@/components/LogoIcon";
import LogoFull from "@/components/LogoFull";


export default defineComponent({
    name: "ResetPassword",
    components: {LogoFull, LogoIcon},
    props: {
        email: String,
        token: String,
    },
    setup(props) {
        const isShowPassword = ref(false);
        const form = useForm({
            token: props.token,
            email: props.email,
            password: '',
            password_confirmation: '',
        })

        function submit() {
            form.post(route('password.update'), {
                onFinish: () => {
                    form.reset('password', 'password_confirmation')
                },
                onSuccess: () => {
                    toast('Password updated successfully')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger', 3000)
                    }
                }
            })
        }

        function togglePassword() {

            isShowPassword.value = !isShowPassword.value;

        }

        return {form, submit, isShowPassword, togglePassword}

    }
})
</script>

<style scoped>
@media (min-width: 480px) {
    .main-card-signin {
        max-width: 400px;
    }
}
</style>
