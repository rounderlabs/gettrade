<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="title">
                <h2>Marketing Incomes</h2>
            </div>

            <div class="row gy-3">

                <div v-if="!direct_bonuses.length" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">
                            <h5 class="success-color">No Transaction Found</h5>
                        </a>
                    </div>
                </div>

                <div v-for="(bonus,index) in direct_bonuses" class="col-12">
                    <div class="transaction-box">
                        <a href="" class="d-flex gap-3">

                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h5 class="theme-color">Direct User</h5>
                                    <h5><span class="success-color">{{bonus.subscription.user.username}}</span></h5>
                                </div>
                                <div class="transaction-name">
                                    <h5 class="theme-color">Package</h5>
                                    <h5>
                                        <span class="success-color">
                                            {{ currencySymbol }} {{ bonus.subscription.amount_display }}
                                        </span>
                                    </h5>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="theme-color">Direct Bonus</h6>
                                    <h5 class="success-color">{{ currencySymbol }} {{ bonus.income_display }}</h5>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="success-color">Credited</h6>
                                    <h5 class="light-text"><span class="light-text">{{formatDate(bonus.created_at) }}</span></h5>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <Paginator :base-url="route('earnings.direct.bonus.get')" @pageMeta="paginatorPageMeta"
               @responseData="paginatorResponse"></Paginator>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import EarningWidget from "@/components/EarningWidget";
import {ref} from "vue";
import Paginator from "@/components/xino/Paginator.vue";
import {Link} from "@inertiajs/vue3";
import VueFeather from "vue-feather";
import { usePage } from "@inertiajs/vue3"
import { computed } from "vue"

export default {
    name: "MarketingBonus",
    components: {VueFeather, Link, EarningWidget, Paginator},
    layout: UserLayout,
    props: {
        team: Object,
    },
    setup(props) {
        const direct_bonuses = ref([]);
        const pageMeta = ref([]);

        function paginatorResponse(data) {
            direct_bonuses.value = data
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
            paginatorResponse, paginatorPageMeta, pageMeta, direct_bonuses, formatDate, currencySymbol
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
