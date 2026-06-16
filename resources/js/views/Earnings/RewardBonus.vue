<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Rewards Income</h2>
            </div>

            <div class="row gy-3">
                <div v-if="!reward_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a href="javascript:void(0)" class="d-flex gap-3">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus, index) in reward_bonuses" :key="bonus.id" class="col-12">
                    <div class="transaction-box">
                        <a href="javascript:void(0)" class="d-flex gap-3">
                            <div class="transaction-image color5">
                                <img class="img-fluid icon" src="/user-panel/assets-panel/assets/images/maturity.svg" alt="trophy" style="width: 40px;">
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5>{{ bonus.reward ? bonus.reward.rank_name : 'Reward' }}</h5>
                                    <h3 class="success-color">{{ currencySymbol }}{{ bonus.income_display }}</h3>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="theme-color">{{ bonus.reward_text || 'Achievement Bonus' }}</h6>
                                    <h5 class="light-text"><span class="light-text">{{ formatDate(bonus.created_at) }}</span></h5>
                                    <h5 class="success-color">Credited</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <Paginator :base-url="route('earnings.rewards.income.get')" @pageMeta="paginatorPageMeta"
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
    name: "RewardBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    setup() {
        const reward_bonuses = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            reward_bonuses.value = data
        }

        function paginatorPageMeta(data) {
            pageMeta.value = data
        }

        function formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        }

        const page = usePage()
        const currencySymbol = computed(() => {
            return page.props.currency?.symbol ?? "₹"
        })

        return {
            paginatorResponse, paginatorPageMeta, pageMeta, reward_bonuses, currencySymbol, formatDate
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
