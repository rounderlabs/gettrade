<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Invest For Your Team Member</h2>
            </div>
            <div class="row gy-3">

                <div class="col-12">
                    <!-- Inventory card -->
                    <div class="news-update-box">

                        <form class="auth-form" @submit.prevent="submitActivationForm">

                            <div class="form-group">
                                <label class="form-label">Enter User ID</label>
                                <div class="form-input">
                                    <input v-model="activationForm.username" class="form-control"
                                           placeholder="123456"
                                           required
                                           type="text"
                                           @focusout="checkSponsor">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm Name of user </label>
                                <div class="form-input">
                                    <div class="form-floating">
                                        <input v-model="to_user_name"
                                               :readonly="to_user_name"
                                               class="form-control"
                                               placeholder="John Smith"
                                               type="text">

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Select Plan</label>
                                <div class="form-input">
                                    <select v-model="activationForm.plan_id" class="form-control" required>
                                        <option value="">Select Plan</option>
                                        <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                                            {{ plan.name }} - ₹{{ plan.amount }}
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="form-label">Enter Amount (₹)</label>
                                <div class="form-input">
                                    <input v-model="activationForm.amount"
                                           class="form-control"
                                           required
                                           type="text"
                                           placeholder="Minimum ₹ 35000"
                                    >
                                </div>
                            </div>

                            <button :disabled="sending" class="btn theme-btn w-100"
                                    type="submit">
                                Invest Now
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
import axios from "axios";
import {toast} from "@/utils/toast";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";


export default {
    name: "ActivateMemberFrom",
    components: {
        FontAwesomeIcon,
    },
    layout: UserLayout,
    props: {
        plans: Array
    },
    setup() {
        const sending = ref(false)
        const to_user_name = ref(null);
        const activationForm = useForm({
            username: null,
            plan_id: null,
            amount: null,
        });
        const checkSponsor = () => {
            to_user_name.value = null;
            if (activationForm.username) {
                axios.post(route("validate.user"), {
                    referral: activationForm.username
                }).then(res => {
                    to_user_name.value = res.data.sponsor_name;
                }).catch(err => {
                    toast("User does not exist", "danger");
                });
            }
        };

        function submitActivationForm() {
            sending.value = true;
            activationForm.post(route('wallet.activation.user.submit'), {
                onSuccess: (page) => {
                    const notification = page.props.flash?.notification;

                    if (notification) {
                        toast(notification[0], notification[1]);
                    }
                },
                onFinish: () => {
                    sending.value = false;
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }

                }
            })
        }

        return {
            sending, activationForm, submitActivationForm, checkSponsor, to_user_name
        }
    }

}
</script>

<style scoped>

</style>
