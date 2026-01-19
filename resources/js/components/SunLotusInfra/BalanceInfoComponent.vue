<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "BalanceInfoComponent",
    components:{
        Link
    },
    props: {
        user_income_stats: Object,
        investment: Number,
        total_withdrawal: Number
    }
}
</script>

<template>
    <!-- card start -->
    <section class="section-b-space">
        <div class="custom-container">
            <div class="card-box">
                <div class="card-details">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-semibold">My Portfolio</h5>
                        <img alt="ellipse" src="/shuchack/assets-panel/assets/images/ellipse.svg"/>
                    </div>

                    <h1 class="mt-2 text-white">₹ {{parseFloat(user_income_stats.total).toFixed(2)}}</h1>

                    <div class="amount-details">
                        <div class="amount w-50 text-start">
                            <div class="d-flex align-items-center justify-content-start">
                                <img alt="down"
                                     class="img-fluid icon" src="/shuchack/assets-panel/assets/images/arrow-down-right.svg"/>
                                <h5>Investment</h5>
                            </div>
                            <h3 class="text-white">₹ {{parseFloat(investment).toFixed(2)}}</h3>
                        </div>
                        <div class="amount w-50 text-end border-0">
                            <div class="d-flex align-items-center justify-content-end">
                                <img alt="up"
                                     class="img-fluid icon" src="/shuchack/assets-panel/assets/images/arrow-up-right.svg"/>
                                <h5>Withdrawn</h5>
                            </div>
                            <h3 class="text-white">₹ {{parseFloat(total_withdrawal).toFixed(2)}}</h3>
                        </div>
                    </div>
                </div>
                <Link class="add-money theme-color"  :href="route('deposit.add.fund')">+ Add Fund</Link>
            </div>
        </div>
    </section>
    <!-- card end -->
</template>

<style scoped>

</style>
