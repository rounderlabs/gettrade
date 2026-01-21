<template>
    <section v-if="subscriptions">
        <div class="custom-container">
            <div class="title">
                <h2>Current Rank</h2>

            </div>
            <div v-if="user_rank" class="statistics-banner mb-2">
                <div class="d-flex justify-content-center gap-3">

                    <div class="">
                        <img alt="M" src="/user-panel/assets-panel/assets/images/maturity.svg" style="width:62px">
                    </div>
                    <div class="statistics-content d-block">
                        <h5 class="text-white mt-2">Current Rank</h5>
                        <h4 class="text-white mt-2">Rank {{ user_rank.rank }}</h4>
                    </div>
                    <div class="statistics-content d-block">
                        <h5 class="text-white mt-2">Extra Return</h5>
                        <h4 class="text-white mt-2">{{
                                user_rank.rank * 2
                            }} %</h4>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Rank Incomes</h2>
            </div>


            <div class="row gy-3">

                <div v-if="!rank_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus,index) in rank_bonuses" class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <div class="transaction-image color5">
                                <img alt="tether" class="img-fluid icon" style="width:62px"
                                     src="/user-panel/assets-panel/assets/images/maturity.svg">
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>Rank Achieved</h5>
                                    <h3 class="dark-text"><span class="success-color">Rank: {{ bonus.rank }}</span></h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5>Extra Systematic Bonus %</h5>
                                    <h3 class="theme-color">
                                        {{ bonus.income_percent }}%
                                    </h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5>Bonus Amount</h5>
                                    <h3 class="theme-color">
                                        ₹ {{ bonus.income }}%
                                    </h3>
                                </div>
                                <div class="d-flex justify-content-between">

                                    <h5 class="theme-color">{{ formatDate(bonus.created_at) }}</h5>
                                    <h5 class="success-color"><span class="success-color">Credited</span></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <Paginator :base-url="route('earnings.rank.bonus.get')" @pageMeta="paginatorPageMeta"
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
    name: "RankBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    props: {
        subscriptions: Array,
        user_rank: Object,
    },
    setup(props) {
        const rank_bonuses = ref([]);
        const pageMeta = ref([]);

        function formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        }

        function paginatorResponse(data) {
            rank_bonuses.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, rank_bonuses,formatDate
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
