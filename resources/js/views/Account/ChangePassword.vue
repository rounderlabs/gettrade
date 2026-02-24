<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Change Password</h2>
            </div>
            <div class="row gy-3">
                <div class="col-12">
                    <!-- Inventory card -->
                    <div class="news-update-box">
                        <form class="auth-form" @submit.prevent="form.post(route('account.update.password'))">
                            <div class="form-group">
                                <label class="form-label">Enter Current Password</label>
                                <div class="form-input">
                                    <input v-model="form.current_password"
                                           v-input-error="form.errors.current_password"
                                           :type="isShowPassword?'text':'password'"
                                           class="form-control"
                                           placeholder="Current Password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Enter New Password</label>
                                <div class="form-input">
                                    <input v-model="form.new_password"
                                           v-input-error="form.errors.current_password"
                                           :type="isShowPassword?'text':'password'"
                                           class="form-control"
                                           placeholder="Enter New Password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group mb-3 position-relative check-valid">
                                <label class="form-label">Re-Enter New Password</label>
                                <div class="form-floating">
                                    <input v-model="form.new_password_confirmation"
                                           v-input-error="form.errors.new_password_confirmation"
                                           :type="isShowPassword?'text':'password'"
                                           class="form-control border-start-0"
                                           placeholder="Confirm New Password"
                                           required>
                                </div>
                            </div>

                            <div class="title">
                                <h6>&nbsp;</h6>
                                <a href="#" @click="togglePassword()">show Password</a>
                            </div>

                            <button :disabled="form.processing" class="btn theme-btn w-100"
                                    type="submit">
                                Update New Password
                                <font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </section>
</template>

<script>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3"; // <-- important
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import AccountLayout from "@/layouts/AccountLayout";
import InputError from "@/components/InputError";
import ProfileLeft from "@/views/Account/ProfileLeft";

export default {
    name: "ChangePassword",
    components: { FontAwesomeIcon, ProfileLeft, InputError },
    props: {
        profile: Object
    },
    metaInfo: { title: "Change Password" },
    layout: [UserLayout, AccountLayout],

    setup() {
        const isShowPassword = ref(false);

        const form = useForm({
            current_password: "",
            new_password: "",
            new_password_confirmation: ""
        });

        function togglePassword() {
            isShowPassword.value = !isShowPassword.value;
        }

        return {
            form,
            isShowPassword,
            togglePassword
        };
    }
};
</script>

<style scoped>

</style>
