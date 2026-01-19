<script>

import {Link} from "@inertiajs/vue3";

export default {
    name: "UserIncomeComponent",
    components: {
        Link
    },
    props: {
        user_income_stats: Object
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
<!--                                <img class="img-fluid icon" src="/shuchack/assets-panel/assets/images/6.svg" alt="p6">-->
<!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Dividend Profit</h5>
                                <h6 class="light-text mt-2">Monthly</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>₹ {{ parseFloat(user_income_stats.roi).toFixed(2) }}</h5>
                            <Link :href="route('earnings.monthly.trading.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
<!--                            <div class="bill-icon">-->
<!--                                <img class="img-fluid icon" src="/shuchack/assets-panel/assets/images/7.svg" alt="p7">-->
<!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Level Profit</h5>
                                <h6 class="light-text mt-2">Level Bonus</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>₹ {{ parseFloat(user_income_stats.level).toFixed(2) }}</h5>
                            <Link :href="route('earnings.front.line.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
<!--                            <div class="bill-icon">-->
<!--                                <img class="img-fluid icon" src="/shuchack/assets-panel/assets/images/8.svg" alt="p8">-->
<!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Level On Dividend</h5>
                                <h6 class="light-text mt-2">Monthly</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>₹ {{ parseFloat(user_income_stats.roi_on_roi).toFixed(2) }}</h5>
                            <Link :href="route('earnings.profit.sharing.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bill-box">
                        <div class="d-flex gap-3">
<!--                            <div class="bill-icon">-->
<!--                                <img class="img-fluid icon" src="/shuchack/assets-panel/assets/images/9.svg" alt="p9">-->
<!--                            </div>-->
                            <div class="">
                                <h5 class="dark-text">Maturity Level Profit</h5>
                                <h6 class="light-text mt-2">On Team Maturity</h6>
                            </div>
                        </div>
                        <div class="bill-price">
                            <h5>₹ {{ parseFloat(user_income_stats.maturity_level).toFixed(2) }}</h5>
                            <Link :href="route('earnings.maturity.bonus')" class="btn bill-pay bill-paid">Details</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
