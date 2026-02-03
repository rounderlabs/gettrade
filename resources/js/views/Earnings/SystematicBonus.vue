<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Systematic Trading Incomes</h2>
            </div>

            <div class="row gy-3">

                <div v-if="!level_roi_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus,index) in level_roi_bonuses" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <div class="transaction-image color5">
                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/wallet-svg.svg" alt="tether">
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>Level : <span class="success-color">{{bonus.level}}</span></h5>
                                    <h5> <span class="success-color">{{parseFloat(bonus.income_percent).toFixed(2)}} %</span></h5>
                                    <h3 class="dark-text">{{currencySymbol}}{{bonus.income_display}}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="theme-color"><VueFeather type="user" size="16" /> {{bonus.user_roi_income.user.username}} </h6>
                                    <h5 class="light-text"><span class="light-text">{{formatDate(bonus.created_at)}}</span></h5>
                                    <h5 class="success-color">Credited</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <Paginator :base-url="route('earnings.systematic.bonus.get')" @pageMeta="paginatorPageMeta"
               @responseData="paginatorResponse"></Paginator>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import EarningWidget from "@/components/EarningWidget";
import {computed, ref} from "vue";
import Paginator from "@/components/xino/Paginator.vue";
import {Link, usePage} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "LevelROIBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    props: {
        team: Object,
    },
    setup(props) {
        const level_roi_bonuses = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            level_roi_bonuses.value = data
        }

        function formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        const page = usePage()
        const currencySymbol = computed(() => {
            return page.props.currency?.symbol ?? "₹"
        })

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, level_roi_bonuses, formatDate, currencySymbol
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
