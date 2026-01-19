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
                                        Slip Uploaded
                                    </h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body bg-none text-white">
                            <a v-bind:href="'/storage/'+ request.payment_proof" target="_blank">
                                <img v-bind:src="'/storage/'+ request.payment_proof" style="width:100%" />
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
                                    <h6 class="fw-medium mb-0">User Deposit Request</h6>
                                    <p class="text-secondary small">Verify Requested Amount With Slip Uploaded </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form @submit.prevent="updateDepositRequest">
                                <div class="col-12 px-0">
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Amount Requested</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">₹ {{ request.amount}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class=" list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <p class="mb-0">Status</p>
                                                </div>
                                                <div class="col ps-0 text-end">
                                                    <p class="mb-0">{{ request.status}}</p>
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
                                                <select class="form-select border-0" id="txn" v-model="depositRequestForm.status" required="">
                                                    <option :value="null">Select Action</option>
                                                    <option value="success">Approved</option>
                                                    <option value="rejected">Reject</option>
                                                </select>
                                                <label for="yxn">Select Action</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback mb-3">Add valid data </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group mb-3 position-relative check-valid">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i class="bi bi-globe"></i></span>
                                            <div class="form-floating">
                                                <select class="form-select border-0" id="txn" v-model="depositRequestForm.is_credited" required="">
                                                    <option :value="null">Select option</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <label for="yxn">Have You Added Fund ?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback mb-3">Add valid data </div>
                                </div>
                                <LoadingButton :disabled="depositRequestForm.processing" class="btn btn-theme">
                                    Update Now<i v-if="depositRequestForm.processing" class="fa fa-spin fa-spinner"></i>
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
        request:Object,
    },
    setup(props) {
        const depositRequestForm = useForm({
            id: props.request.id,
            status: null,
            is_credited: null,
        })
        function updateDepositRequest() {
            depositRequestForm.post(route('admin.deposit.update.inr.deposit.request'), {
                onSuccess: () => {
                    toast('User Deposit Request successfully updated !')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }
        return {depositRequestForm, updateDepositRequest}
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
