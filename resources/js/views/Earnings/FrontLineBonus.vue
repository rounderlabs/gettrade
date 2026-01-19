<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Level Bonus</h2>
            </div>

            <div class="row gy-3">

                <div v-if="!level_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus,index) in level_bonuses" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">

                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>Level : <span class="success-color">{{bonus.level}}</span></h5>
                                    <h5> <span class="success-color">{{parseFloat(bonus.income_percent).toFixed(2)}} %</span></h5>
                                    <h3 class="dark-text">₹ {{parseFloat(bonus.income_amount).toFixed(2)}}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="theme-color"><VueFeather type="user" size="16" /> {{bonus.subscription.user.username}} </h6>
                                    <h5 class="light-text"><span class="light-text">{{bonus.created_at}}</span></h5>
                                    <h5 class="success-color">Credited</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <Paginator :base-url="route('earnings.front.line.bonus.get')" @pageMeta="paginatorPageMeta"
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
    name: "LevelBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    props: {
        team: Object,
    },
    setup(props) {
        const level_bonuses = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            level_bonuses.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, level_bonuses
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
