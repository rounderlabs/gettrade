<template>

    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Review The Select Package</h2>
            </div>
            <ul class="notification-list">
                <li class="notification-box mt-3">
                    <div class="notification-details">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-semibold  text-white">Package Amount</h5>
                                <h6 class="fw-normal  text-white mt-1">Monthly Trading Bonus</h6>
                                <h6 class="fw-normal  text-white mt-1">Package Tenure</h6>
                                <h6 class="fw-normal  text-white mt-1">Systematic Bonus *</h6>
                                <h6 class="fw-normal  text-white mt-1">Rank Bonus Return *</h6>

                            </div>
                            <div>
                                <h6 class="time fw-normal  text-white mt-1">₹ {{ plan.amount }}</h6>
                                <h6 class="time fw-normal  text-white mt-1"> {{ plan.monthly_roi_amount }} %</h6>
                                <h6 class="time fw-normal  text-white mt-1">{{plan.tenure}} Months</h6>
                                <h6 class="time fw-normal  text-white mt-1">20 Level</h6>
                                <h6 class="time fw-normal  text-white mt-1">Upto 10% Extra</h6>
                            </div>
                        </div>
                        <div class="justify-content-between align-items-center mt-3">
                            <form class="" @submit.prevent="submit">
                                <button class="btn theme-btn w-100" type="submit">Invest Now
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>

        </div>

    </section>
    <NotificationToast></NotificationToast>

</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {toast} from "@/utils/toast";
import InputError from "@/components/InputError.vue";
import NotificationToast from "@/components/NotificationToast.vue";
import { computed } from "vue";
export default {
    name: "Buy",
    components: {NotificationToast, Link, InputError},
    layout: UserLayout,
    props: {
        plan: Object,
        available_coin_balance: Object,
    },
    setup(props) {

        const topUpForm = useForm({
            plan_id: props.plan.id,
            amount: props.plan.amount,
        });


        const hasSufficientBalance = computed(() => {
            return Number(props.available_coin_balance.balance) >= Number(props.plan.amount);
        });

        function submit() {
            if (!hasSufficientBalance.value) {
                toast("Insufficient wallet balance", "danger");
                return;
            }
            topUpForm.post(route("purchase.plan.activate"), {
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, "danger");
                    }
                }
            });
        }

        return {
            topUpForm, hasSufficientBalance, submit
        };
    }
};
</script>

<style scoped>
.card-box .card-details {
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    padding: 25px 20px;
    border-radius: 15px;
}


.card-box .card-details .amount-details {
    display: -webkit-box;
    display: -ms-flexbox;
    display: block;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    margin-top: 50px;

}

.notification-list .notification-box{
    background-color: transparent !important;
}

.notification-list {
    background-image: url(/user-panel/assets-panel/assets/images/background/home-card-bg.jpg);
    border-radius : 20px;
    background-color: transparent !important;
}
.notification-list .notification-box .notification-details {
    width: 100% !important;
    background-color: transparent !important;

}

</style>
