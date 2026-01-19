<template>

    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Transfer Funds</h2>
            </div>
            <div class="row gy-3">

                <div class="col-12">
                    <!-- Inventory card -->
                    <div class="news-update-box">
                        <form class="auth-form mt-3" @submit.prevent="submitTransferForm">

                                <div class="form-group">
                                    <label class="form-label">Enter User ID</label>
                                    <div class="form-input">
                                        <input v-model="transferForm.username" class="form-control"
                                               placeholder="123456"
                                               required
                                               type="text"
                                               @focusout="checkSponsor">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>Confirm Name of user </label>
                                    <div class="form-input">
                                        <input v-model="to_user_name"
                                               :readonly="to_user_name"
                                               class="form-control"
                                               type="text">
                                    </div>

                                </div>


                            <div class="form-group">

                                    <label class="form-label">Enter Amount To transfer</label>
                                    <div class="form-input">
                                        <input v-model="transferForm.amount" class="form-control"
                                               placeholder="Minimum ₹ 10"
                                               required type="text">
                                    </div>
                                </div>

                            <button :disabled="sending" class="btn theme-btn w-100"
                                    type="submit">
                                Transfer Now
                                <font-awesome-icon v-if="sending" class="ml-1" icon="spinner" spin/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

import UserLayout from "@/layouts/UserLayouts/UserLayout";

import {toast} from "@/utils/toast";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default {
    name: "Index",
    components: {
        FontAwesomeIcon,
        toast
    },
    layout: UserLayout,
    props: {
        user_usd_wallet: Object,
        user_income_wallet: Object,
    },
    setup() {
        const sending = ref(false)
        const to_user_name = ref(null);
        const transferForm = useForm({
            amount: null,
            username: null
        });
        const checkSponsor = () => {
            to_user_name.value = null;
            if (transferForm.username) {
                axios.post(route("validate.user"), {
                    referral: transferForm.username
                }).then(res => {
                    to_user_name.value = res.data.sponsor_name;
                }).catch(err => {
                    toast("Referral does not exist", "danger");
                });
            }
        };

        function submitTransferForm() {
            transferForm.post(route('wallet.fund.transfer.submit'), {

                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }

                }
            })
        }

        return {
            sending, transferForm, submitTransferForm, checkSponsor, to_user_name
        }
    }

}
</script>

<style scoped>

</style>
