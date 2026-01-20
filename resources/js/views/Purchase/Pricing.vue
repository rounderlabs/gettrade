<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Packages</h2>
            </div>

            <ul v-for="(plan,index) in plans" class="notification-list">
                <li class="notification-box mt-3">
                    <div class="notification-details">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-semibold  text-white">Invest Amount</h5>
                                <h6 class="fw-normal  text-white mt-1">Dividend Monthly</h6>
                                <h6 class="fw-normal  text-white mt-1">Package Tenure</h6>
                                <h6 class="fw-normal  text-white mt-1">Maturity Tenure</h6>
                                <h6 class="fw-normal  text-white mt-1">Maturity Amount</h6>
                            </div>
                            <div>
                                <h6 class="time fw-normal  text-white mt-1">₹ {{ plan.amount }}</h6>
                                <h6 class="time fw-normal  text-white mt-1">₹ {{ plan.monthly_roi_amount }}</h6>
                                <h6 class="time fw-normal  text-white mt-1">{{plan.tenure}} Months</h6>
                                <h6 class="time fw-normal  text-white mt-1">{{ plan.maturity_tenure }} Months</h6>
                                <h6 class="time fw-normal  text-white mt-1">₹ {{ plan.maturity_amount }}</h6>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">

                            <Link :href="route('purchase.topup.form',[plan.id])" class="btn theme-btn pay-btn mt-0 w-100">
                                Invest Now
                            </Link>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: "Pricing",
    props: {
        plans: Array,

    },
    layout: UserLayout,
    components: {Link},
    setup() {
        const panelColor = ref([
            "success",
            "blue",
            "orange",
            "primary",
            "info"
        ]);

        function getBgColor(planId) {
            return panelColor.value[parseInt(planId) - 1];
        }

        return {getBgColor};
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
.notification-list {
    background-image: url(/user-panel/assets-panel/assets/images/background/home-card-bg.jpg);
    border-radius : 20px;
    background-color: transparent !important;
}
.notification-list .notification-box{
    background-color: transparent !important;
}
.notification-list .notification-box .notification-details {
    width: 100% !important;
    background-color: transparent !important;

}

</style>
