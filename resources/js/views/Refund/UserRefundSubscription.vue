<template>
    <!--
      1. if compounded Then Not able to Surrender before Compounding ends
      2. if Refund Request exist then not able to make another request
      -->
    <div class="row mt-10">
        <div v-if="active_subscription" class="col-12 mt-3">
            <div class="card border-0 bg-radial-gradient mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <figure class="avatar avatar-50 rounded-circle bg-yellow text-white">
                                <i class="bi bi-star mb-1 vm"></i>
                            </figure>
                        </div>
                        <div class="col ps-0">
                            <h6 class="mb-0">Subscription </h6>
                            <p class="fw-normal">Life Time Plan</p>
                        </div>
                        <div class="col-auto">
                            <h4 class="mb-0">$ 100</h4>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-auto">
                            <p class="small text-muted">Status</p>
                        </div>
                        <div class="col text-end">
                            <p v-if="active_subscription.is_active" class="mb-0">Active</p>
                            <p v-else class="mb-0">Suspended</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-auto">
                            <p class="small text-muted">Subscribed On</p>
                        </div>
                        <div class="col text-end">
                            <p class="mb-0">{{ active_subscription.created_at }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-auto">
                            <p class="small text-muted">Earned Yet</p>
                        </div>
                        <div class="col text-end">
                            <p class="mb-0">$ {{ parseFloat(active_subscription.earned_so_far).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-auto">
                            <p class="small text-muted">Withdrawal Yet</p>
                        </div>
                        <div class="col text-end">
                            <p class="mb-0">$ {{ parseFloat(total_withdrawal).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-auto">
                            <p class="small text-muted">Compounding Status</p>
                        </div>
                        <div v-if="compound" class="col text-end">
                            <p v-if="compound.is_compounded === 1" class="mb-0">Active</p>
                            <p v-if="compound.is_compounded === 0" class="mb-0">Inctive</p>
                        </div>
                        <div v-else class="col text-end">
                            <p class="mb-0">
                                No Compounding
                            </p>
                        </div>
                    </div>
                </div>
                <div v-if="compound" class="card-footer">
                    <div class=" row align-items-center">
                        <div v-if="compound.is_compounded === 1" class="col-auto">
                            <p class="small text-warning">Subscription can not be canceled until you have opted the
                                Compounding Incomes. Once your compounding tenure will be finish then only you can
                                cancel your subscription.</p>
                        </div>
                    </div>
                </div>
                <div v-else class="card-footer">
                    <div class=" row align-items-center">
                        <div class="col-12">
                            <p class="small text-warning">NOTE: Once you submit the cancel subscription request then all type of earning will stop immediately</p>
                        </div>
                    </div>
                    <form @submit.prevent="submitForm" class="mb-4 text-center">
                        <div class="row align-items-center">
                            <div class="col-12 text-center">
                                <button class="btn btn-theme" type="submit">Cancel Subscription !</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout";
import {useForm} from "@inertiajs/vue3";
import {toast} from "@/utils/toast";

export default {
    name: "UserRefundSubscription",
    components: {},
    layout: UserLayout,
    props: {
        user: Object,
        active_subscription: Object,
        compound: Object,
        total_withdrawal: String,
        refund_request: Array,
        user_income_stats: Object
    },

    setup(props) {
        const form = useForm({
            'user_id': props.user.id,
            'subscription_id': props.active_subscription.id,
            'total_withdrawal_yet': props.total_withdrawal,
        })

        function submitForm() {
            form.post(route("refund.submit.request"), {
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                }
            });
        }

        return {submitForm, form}


    },

};
</script>

<style scoped>

</style>
