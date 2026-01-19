<template>
    <div class="row">
        <div class="col-md-6 col-lg-6 mg-md-t-0 mx-auto">
            <div class="card">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                    Change Admin Password
                </div>
                <div class="card-body">
                    <form @submit.prevent="updatePassword">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label mt-2 font-weight-semibold mt-2">Old Password</label>
                                </div>

                                <div class="col-md-8">
                                    <input v-model="passwordForm.old_password" class="mh form-control"
                                           placeholder="Old Password"
                                           type="password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label mt-2 font-weight-semibold mt-2">New Password</label>
                                </div>

                                <div class="col-md-8">
                                    <input v-model="passwordForm.password" class="mh form-control"
                                           placeholder="New Password"
                                           type="password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label mt-2 font-weight-semibold mt-2">Confirm Password</label>
                                </div>

                                <div class="col-md-8">
                                    <input v-model="passwordForm.password_confirmation" class="mh form-control"
                                           placeholder="Confirm New password"
                                           type="password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8 text-right">
                                    <button :disabled="passwordForm.processing" class="btn btn-primary">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useForm} from "@inertiajs/vue3";
import {toast} from "@/utils/toast";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "ChangePassword",
    layout: MainAdminLayout,
    setup() {
        const passwordForm = useForm({
            old_password: '',
            password: '',
            password_confirmation: ''
        })

        function updatePassword() {
            passwordForm.post(route('admin.update.password'), {
                onSuccess: results => {
                    for (const [key, value] of Object.entries(results)) {
                        toast(value, 'success')
                    }
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        return {passwordForm, updatePassword}
    }

}
</script>

<style scoped>

</style>
