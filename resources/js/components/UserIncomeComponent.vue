<script>

import {Link} from "@inertiajs/vue3";

export default {
    name: "UserIncomeComponent",
    components: {
        Link
    },
    props: {
        user_income: Object,
        currency: String,
    }
}
</script>

<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Earnings</h2>
                <a href="#">See all</a>
            </div>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
                            <!--                            <div class="bill-icon">-->
                            <!--                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/6.svg" alt="p6">-->
                            <!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Trading Income</h5>
                                <h6 class="light-text mt-2">Monthly</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>{{ currency }} {{ user_income.roi }}</h5>
                            <Link :href="route('earnings.monthly.trading.bonus')" class="btn bill-pay bill-paid">
                                Details
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
                            <!--                            <div class="bill-icon">-->
                            <!--                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/7.svg" alt="p7">-->
                            <!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Market Earnings</h5>
                                <h6 class="light-text mt-2">Referral Bonus</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>{{ currency }}  {{ user_income.direct }}</h5>
                            <Link :href="route('earnings.direct.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
                            <!--                            <div class="bill-icon">-->
                            <!--                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/8.svg" alt="p8">-->
                            <!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Systematic trading income</h5>
                                <h6 class="light-text mt-2">Monthly</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>{{ currency }} {{ user_income.roi_on_roi }}</h5>
                            <Link :href="route('earnings.systematic.bonus')" class="btn bill-pay bill-paid">Details
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
                            <!--                            <div class="bill-icon">-->
                            <!--                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/9.svg" alt="p9">-->
                            <!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Rank Profit</h5>
                                <h6 class="light-text mt-2">On Team Business</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>{{ currency }}  {{ user_income.rank }}</h5>
                            <Link :href="route('earnings.rank.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
