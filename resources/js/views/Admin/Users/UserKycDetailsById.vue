<template>
    <main class="main mainheight mt-5">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 mb-4 ">
                    <div class="card border-0 bg-radial-gradient h-100">
                        <div class="card-header bg-none">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="fw-medium">
                                        <i class="bi bi-wallet2 h5 me-1 avatar avatar-30 rounded"></i>
                                        Aadhar Card
                                    </h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body bg-none text-white">
                            <a v-bind:href="'/storage/'+ kyc.aadhar_img" target="_blank">
                                <img v-bind:src="'/storage/'+ kyc.aadhar_img" style="width:100%" />
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4 ">
                    <div class="card border-0 bg-radial-gradient h-100">
                        <div class="card-header bg-none">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="fw-medium">
                                        <i class="bi bi-wallet2 h5 me-1 avatar avatar-30 rounded"></i>
                                        PAN Card
                                    </h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body bg-none text-white">
                            <a v-bind:href="'/storage/'+ kyc.pan_img" target="_blank">
                                <img v-bind:src="'/storage/'+ kyc.pan_img" style="width:100%" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 mb-100">
                <div class="col-12">
                    <div class="card border-0 mb-4">
                        <div class="card-header">
                            <div class="row gx-2 align-items-center">
                                <div class="col-auto">
                                    <i class="bi bi-newspaper h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">User KYC Details</h6>
                                    <p class="text-secondary small">Verify User KYC details </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form @submit.prevent="updateKyc">
                                <div class="col-12 px-0">
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Aadhar Number</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.aadhar_no}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">PAN Number</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.pan_no}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">PAN Number</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.pan_no}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Bank Name</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.bank_name}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">IFSC Code</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.ifsc_code}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Account Number</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.account_no}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Holder Name</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.holder_name}}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">UPI ID</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ kyc.upi_id}}</p>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group mb-3 position-relative check-valid">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i class="bi bi-globe"></i></span>
                                            <div class="form-floating">
                                                <select class="form-select border-0" id="txn" v-model="kycForm.status" required="">
                                                    <option :value="null">Select Action</option>
                                                    <option value="success">Approved</option>
                                                    <option value="rejected">Reject</option>
                                                </select>
                                                <label for="yxn">Select Action Type</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback mb-3">Add valid data </div>
                                </div>
                                <LoadingButton :disabled="kycForm.processing" class="btn btn-theme">
                                    Update KYC Status<i v-if="kycForm.processing" class="fa fa-spin fa-spinner"></i>
                                </LoadingButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</template>

<script>
import {Link, useForm} from '@inertiajs/vue3'
import LoadingButton from "@/components/LoadingButton";
import {toast} from "@/utils/toast";
import Paginator from "@/components/xino/Paginator";
import {ref} from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "UserKycDetailsById",
    components: {LoadingButton, Paginator, Link},
    layout: MainAdminLayout,
    props: {
        kyc:Object,
    },
    setup(props) {
        const kycForm = useForm({
            id: props.kyc.id,
            status: null,
        })

        function updateKyc() {
            kycForm.post(route('admin.update.kyc.request'), {
                onSuccess: () => {
                    toast('User KYC successfully updated !')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }




        return {kycForm, updateKyc}
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
