<template>


    <section class="section-b-space">
        <div class="custom-container">
            <div v-if="kyc">
                <div class="app-title mb-3">
                    <h2>Bank Account Details</h2>
                </div>
                <div class="col-12">
                    <div class="w-100">
                        <div class="transaction-box">
                            <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">
                                <div class="categories-image color1">
                                    <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3 21.0001H21M4 18.0001H20M6 18.0001V13.0001M10 18.0001V13.0001M14 18.0001V13.0001M18 18.0001V13.0001M12 7.00695L12.0074 7.00022M21 10.0001L14.126 3.88986C13.3737 3.2212 12.9976 2.88688 12.5732 2.75991C12.1992 2.64806 11.8008 2.64806 11.4268 2.75991C11.0024 2.88688 10.6263 3.2212 9.87404 3.88986L3 10.0001H21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-name pb-0">
                                        <h5>Bank Name</h5>
                                        <h5 class="dark-text fw-semibold">{{kyc.bank_name}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-100">
                        <div class="transaction-box">
                            <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">
                                <div class="categories-image color1">
                                    <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3 21.0001H21M4 18.0001H20M6 18.0001V13.0001M10 18.0001V13.0001M14 18.0001V13.0001M18 18.0001V13.0001M12 7.00695L12.0074 7.00022M21 10.0001L14.126 3.88986C13.3737 3.2212 12.9976 2.88688 12.5732 2.75991C12.1992 2.64806 11.8008 2.64806 11.4268 2.75991C11.0024 2.88688 10.6263 3.2212 9.87404 3.88986L3 10.0001H21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-name pb-0">
                                        <h5>IFSC Code</h5>
                                        <h5 class="dark-text fw-semibold">{{kyc.ifsc_code}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-100">
                        <div class="transaction-box">
                            <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">
                                <div class="categories-image color1">
                                    <svg width="64px" height="64px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--emojione-monotone" preserveAspectRatio="xMidYMid meet" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M32 2C15.432 2 2 15.432 2 32s13.432 30 30 30s30-13.432 30-30S48.568 2 32 2M18 16.723L22.619 15H26v14h-3.945V17.908H22l-3.33 1.271l-.67-2.456M23.207 49c-1.832 0-3.377-.459-4.207-.959l.676-2.375c.586.291 1.941.832 3.291.832c1.719 0 2.592-.789 2.592-1.811c0-1.334-1.395-1.939-2.855-1.939h-1.35v-2.271h1.287c1.109-.02 2.523-.414 2.523-1.561c0-.813-.695-1.416-2.09-1.416c-1.154 0-2.375.479-2.963.813l-.674-2.293c.85-.52 2.549-1.02 4.377-1.02c3.027 0 4.707 1.521 4.707 3.373c0 1.439-.85 2.564-2.594 3.148v.039c1.702.294 3.073 1.522 3.073 3.294C29 47.248 26.801 49 23.207 49M35.5 27.053l1.824-1.611c3.08-2.689 4.578-4.236 4.619-5.846c0-1.123-.693-2.014-2.32-2.014c-1.215 0-2.277.594-3.018 1.146l-.93-2.309c1.063-.785 2.711-1.42 4.619-1.42c3.189 0 4.947 1.82 4.947 4.32c0 2.309-1.715 4.152-3.754 5.932l-1.301 1.057v.043H45.5V29h-10v-1.947M46 45.662h-1.832V49h-3.393v-3.338H34v-2.133L39.789 35h4.379v8.205H46v2.457" fill="#000000"></path><path d="M39.389 40.107l-2.047 3.057v.041h3.434v-3.098c0-.844.047-1.703.117-2.609h-.092c-.494.906-.895 1.725-1.412 2.609" fill="#000000"></path></g></svg>
                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-name pb-0">
                                        <h5>Account Number</h5>
                                        <h5 class="dark-text fw-semibold">{{kyc.account_number}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="app-title mb-3">
                        <h2>Identity Cards</h2>
                    </div>
                    <div class="w-100">
                        <div class="transaction-box">
                            <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">
                                <div class="categories-image color1">
                                    <svg width="64px" height="64px" viewBox="0 0 1024 1024" fill="#000000" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M110.4 923.2c-56.8 0-102.4-48-102.4-106.4V285.6c0-58.4 45.6-106.4 102.4-106.4h800.8c56.8 0 102.4 48 102.4 106.4V816c0 58.4-45.6 106.4-102.4 106.4H110.4z m0-701.6c-34.4 0-61.6 28.8-61.6 64V816c0 35.2 28 64 61.6 64h800.8c34.4 0 61.6-28.8 61.6-64V285.6c0-35.2-28-64-61.6-64H110.4z" fill=""></path><path d="M541.6 392c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h328c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24h-328zM541.6 511.2c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h328c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24h-328zM541.6 638.4c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h276.8c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24H541.6zM58.4 886.4c-2.4 0-4.8 0-7.2-0.8-12.8-4-20-18.4-16-32 23.2-78.4 77.6-142.4 148-176l16-8-13.6-12c-40-34.4-63.2-85.6-63.2-139.2 0-100 78.4-180.8 173.6-180.8 96 0 173.6 80.8 173.6 180.8 0 53.6-23.2 104.8-63.2 139.2l-13.6 12 16 8c68 32 132.8 112 157.6 194.4 16 52.8-16.8 36-1.6 16-3.2 4.8-16.8-5.6-32-5.6-12.8 0-19.2 24.8-19.2 22.4-31.2-104-120.8-203.2-217.6-203.2-99.2 0-186.4 67.2-216 166.4-1.6 11.2-11.2 18.4-21.6 18.4z m239.2-498.4c-69.6 0-126.4 58.4-126.4 130.4s56.8 130.4 126.4 130.4c69.6 0 126.4-58.4 126.4-130.4-0.8-72-56.8-130.4-126.4-130.4z" fill=""></path></g></svg>
                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-name pb-0">
                                        <h5>Aadhar Number</h5>
                                        <h5 class="dark-text fw-semibold">{{kyc.aadhaar_number}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="w-100">
                        <div class="transaction-box">
                            <a href="#transaction-detail" data-bs-toggle="modal" class="d-flex gap-3">
                                <div class="categories-image color1">
                                    <svg width="64px" height="64px" viewBox="0 0 1024 1024" fill="#000000" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M110.4 923.2c-56.8 0-102.4-48-102.4-106.4V285.6c0-58.4 45.6-106.4 102.4-106.4h800.8c56.8 0 102.4 48 102.4 106.4V816c0 58.4-45.6 106.4-102.4 106.4H110.4z m0-701.6c-34.4 0-61.6 28.8-61.6 64V816c0 35.2 28 64 61.6 64h800.8c34.4 0 61.6-28.8 61.6-64V285.6c0-35.2-28-64-61.6-64H110.4z" fill=""></path><path d="M541.6 392c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h328c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24h-328zM541.6 511.2c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h328c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24h-328zM541.6 638.4c-12.8 0-23.2-10.4-23.2-24s10.4-24 23.2-24h276.8c12.8 0 23.2 10.4 23.2 24s-10.4 24-23.2 24H541.6zM58.4 886.4c-2.4 0-4.8 0-7.2-0.8-12.8-4-20-18.4-16-32 23.2-78.4 77.6-142.4 148-176l16-8-13.6-12c-40-34.4-63.2-85.6-63.2-139.2 0-100 78.4-180.8 173.6-180.8 96 0 173.6 80.8 173.6 180.8 0 53.6-23.2 104.8-63.2 139.2l-13.6 12 16 8c68 32 132.8 112 157.6 194.4 16 52.8-16.8 36-1.6 16-3.2 4.8-16.8-5.6-32-5.6-12.8 0-19.2 24.8-19.2 22.4-31.2-104-120.8-203.2-217.6-203.2-99.2 0-186.4 67.2-216 166.4-1.6 11.2-11.2 18.4-21.6 18.4z m239.2-498.4c-69.6 0-126.4 58.4-126.4 130.4s56.8 130.4 126.4 130.4c69.6 0 126.4-58.4 126.4-130.4-0.8-72-56.8-130.4-126.4-130.4z" fill=""></path></g></svg>
                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-name pb-0">
                                        <h5>PAN Number</h5>
                                        <h5 class="dark-text fw-semibold">{{kyc.pan_number}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <div v-else id="walletForm" class="row mb-100">
                <Link :href="route('kyc.index')" class="btn theme-btn">Complete KYC Process</Link>
            </div>
        </div>
    </section>
</template>

<script>
import ProfileLeft from "@/views/Account/ProfileLeft.vue";
import InputError from "@/components/InputError.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import ProfileRight from "@/views/Account/ProfileRight.vue";
import {ref} from "vue";
import {useForm, Link} from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";
import {toast} from "@/utils/toast";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "WithdrawWalletForm",
    components: {ProfileRight, FontAwesomeIcon, InputError, ProfileLeft, Link},
    layout: [UserLayout],
    props: {
        kyc: Object,

    },
};
</script>

<style scoped>

</style>
