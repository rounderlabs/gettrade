<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Monthly Dividends (Credits On Daily Basis)</h2>
            </div>

            <div class="row gy-3">

                <div v-if="!monthly_roi_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus,index) in monthly_roi_bonuses" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <div class="transaction-image color5">
                                <img class="img-fluid icon" src="/shuchack/assets-panel/assets/images/wallet-svg-fill.svg" alt="tether">
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>Package Amount : <span class="success-color">₹ {{parseFloat(bonus.subscription.amount).toFixed(2)}}</span></h5>

                                    <h3 class="dark-text">₹ {{parseFloat(bonus.income).toFixed(2)}}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5 class="light-text"><span class="light-text">{{bonus.closing_date}}</span></h5>
                                    <h5 class="success-color">Credited</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <Paginator :base-url="route('earnings.monthly.trading.bonus.get')" @pageMeta="paginatorPageMeta"
               @responseData="paginatorResponse"></Paginator>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import EarningWidget from "@/components/EarningWidget";
import {ref} from "vue";
import Paginator from "@/components/xino/Paginator.vue";
import {Link} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "MonthlyROIBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    props: {
        team: Object,
    },
    setup(props) {
        const monthly_roi_bonuses = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            monthly_roi_bonuses.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, monthly_roi_bonuses
        }
    }
}
</script>

<style scoped>
.tab-row {
    min-height: 70px;
    padding: 20px;
    margin-bottom: 10px;
}
</style>
