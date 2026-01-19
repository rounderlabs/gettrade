<template>
    <div class="row">
        <div class="col col-sm-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Withdrawal Maintenance</h1>
                </div>
                <div class="card-body">
                    <form @submit.prevent="withdrawalModuleSave">
                        <div class="form-group">
                            <label class="form-label mt-2 font-weight-semibold mt-2">Module Name</label>
                            <input v-model="withdrawalModuleForm.module_name"
                                   class="mh form-control text-uppercase" placeholder="Module Name" readonly
                                   type="text">
                        </div>

                        <div class="form-group">
                            <label class="form-label mt-2 font-weight-semibold mt-2">Module Status</label>
                            <select v-model="withdrawalModuleForm.in_maintenance"
                                    class="mh form-control text-uppercase">
                                <option>Select</option>
                                <option value="1">Maintenance On</option>
                                <option value="0">Maintenance Off</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label mt-2 font-weight-semibold mt-2">Notice Title</label>
                            <input v-model="withdrawalModuleForm.message_title" class="mh form-control" type="text">
                        </div>

                        <div class="form-group">
                            <label class="form-label mt-2 font-weight-semibold mt-2">Notice Message</label>
                            <textarea v-model="withdrawalModuleForm.message" class="mh form-control"
                                      rows="6"></textarea>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8 text-right">
                                    <button :disabled="withdrawalModuleForm.processing" class="btn btn-primary">
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
    name: "ModuleSetting",
    layout: MainAdminLayout,
    props: {
        withdrawal_module_setting: Object,
        deposit_module_setting: Object,
    },
    setup(props) {
        const withdrawalModuleForm = useForm({
            module_name: 'withdrawal',
            in_maintenance: props.withdrawal_module_setting ? props.withdrawal_module_setting.in_maintenance : null,
            message_title: props.withdrawal_module_setting ? props.withdrawal_module_setting.message_title : null,
            message: props.withdrawal_module_setting ? props.withdrawal_module_setting.message : null,
        })

        function withdrawalModuleSave() {
            withdrawalModuleForm.post(route('admin.module.setting.update'), {
                onSuccess: results => {
                    toast('Withdrawal Module maintenance status successfully updated.', 'success')
                    // for (const [key, value] of Object.entries(results)) {
                    //     toast(value, 'success')
                    // }
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        return {
            withdrawalModuleForm, withdrawalModuleSave
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
