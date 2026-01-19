<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

function submit() {
    form.post(route("admin.login.store"), {
        onFinish: () => form.reset("password"),
    });
}
</script>

<template>
    <Head title="Admin Login" />

    <div class="login-page bg-light">
        <div class="login-box">

            <div class="login-logo">
                <b>Admin</b>Panel
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form @submit.prevent="submit">

                        <div class="input-group mb-3">
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email"
                                v-model="form.email"
                                autofocus
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.email" class="text-danger small">
                            {{ form.errors.email }}
                        </div>

                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                v-model="form.password"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.password" class="text-danger small">
                            {{ form.errors.password }}
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input
                                        type="checkbox"
                                        id="remember"
                                        v-model="form.remember"
                                    />
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                    :disabled="form.processing"
                                >
                                    Login
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</template>
