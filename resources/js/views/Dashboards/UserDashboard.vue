<template>
    <!--    <WelcomeModal-->
    <!--        v-if="showWelcomeModal"-->
    <!--        :user="user"-->
    <!--    />-->
    <UserLoginDetailsComponent :user="user"></UserLoginDetailsComponent>
    <InstantOperationComponent></InstantOperationComponent>
    <ReferralLinkComponent :ref_qr="ref_qr" :user="user"></ReferralLinkComponent>
    <BalanceInfoComponent :investment="investment" :total_withdrawal="total_withdrawal"
                          :user_income_stats="user_income_stats"></BalanceInfoComponent>

    <section v-if="subscriptions">
        <div class="custom-container">
            <div class="title">
                <h2>Maturity</h2>

            </div>
            <div class="statistics-banner">
                <div class="d-flex justify-content-center gap-3">

                        <div class="">
                            <img style="width:62px" src="/shuchack/assets-panel/assets/images/maturity.svg" alt="M">
                        </div>
                        <div class="statistics-content d-block">
                            <h5 class="text-white mt-2">Maturity Date</h5>
                            <h4 class="text-white mt-2">{{subscriptions.maturity_date}}</h4>
                        </div>
                        <div class="statistics-content d-block">
                            <h5 class="text-white mt-2">Maturity Amount</h5>
                            <h4 class="text-white mt-2">₹ {{ parseFloat(subscriptions.plan.maturity_amount).toFixed(2) }}</h4>
                        </div>
                </div>

            </div>
        </div>
    </section>
    <UserIncomeComponent :user_income_stats="user_income_stats"></UserIncomeComponent>

    <team-widget :team="team"></team-widget>

</template>

<script>
import TeamWidget from "@/components/xino/TeamWidget";
import UserLayout from "@/layouts/UserLayouts/UserLayout";
import InputError from "@/components/InputError";
import SubscriptionWidget from "@/components/SubscriptionWidget";
import EarningWidget from "@/components/EarningWidget";
import StakingSubscriptionWidget from "@/components/StakingSubscriptionWidget";
import RewardPointPopup from "@/components/xino/RewardPointPopup";
import DashboardNotice from "@/components/xino/DashboardNotice";
import ReferralLinkComponent from "@/components/ReferralLinkComponent";
import LatestChampionComponent from "@/components/LatestChampionComponent.vue";
import ValidatorStatGraphWidget from "@/components/ValidatorStatGraphWidget.vue";
import IncomeGraphWidget from "@/components/IncomeGraphWidget.vue";
import TokenPriceComponent from "@/components/TokenPriceComponent.vue";
import {onMounted} from "vue";
import {toast} from "@/utils/toast";
import UserIncomeComponent from "@/components/UserIncomeComponent.vue";
import UserWalletsComponent from "@/components/UserWalletsComponent.vue"
import InstantOperationComponent from "@/components/SunLotusInfra/InstantOperationComponent.vue";
import BalanceInfoComponent from "@/components/SunLotusInfra/BalanceInfoComponent.vue";
import SliderComponent from "@/components/SunLotusInfra/SliderComponent.vue";
import WelcomeModal from "@/components/WelcomeModal.vue";
import {Link} from "@inertiajs/vue3";
import UserLoginDetailsComponent from "@/components/UserLoginDetailsComponent.vue";

export default {
    name: "UserDashboard",
    components: {
        UserLoginDetailsComponent,
        Link,
        WelcomeModal,
        SliderComponent,
        BalanceInfoComponent,
        InstantOperationComponent,

        UserIncomeComponent,

        TokenPriceComponent,
        IncomeGraphWidget,
        ValidatorStatGraphWidget,
        LatestChampionComponent,
        ReferralLinkComponent,
        DashboardNotice,
        RewardPointPopup,
        StakingSubscriptionWidget,
        InputError,
        SubscriptionWidget,
        EarningWidget,
        TeamWidget, UserWalletsComponent,

    },
    layout: UserLayout,
    props: {
        user: Object,
        user_income_wallet: Object,
        user_usd_wallet: Object,
        user_income_stats: Object,
        user_income_on_hold: Object,
        active_subscription: Object,
        subscriptions: Object,
        ref_qr: String,
        investment: String,
        total_withdrawal: String,
        team: Object,
        compound: Object,
        total_income: String,
        showWelcomeModal: Boolean,
        welcomeMode: String,

    },
    setup() {
        onMounted(() => {
            window.copyText = function (value) {
                var s = document.createElement('input');
                s.value = value;
                document.body.appendChild(s);

                if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                    s.contentEditable = true;
                    s.readOnly = false;
                    var range = document.createRange();
                    range.selectNodeContents(s);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                    s.setSelectionRange(0, 999999);
                } else {
                    s.select();
                }
                try {
                    document.execCommand('copy');

                } catch (err) {

                }
                s.remove();
            };
        })
    },
    methods: {
        copy(text) {
            window.copyText(text)
        },
        copyRef() {
            this.copy(route('register', {
                ref_code: this.$page.props.auth.user.ref_code,
            }))
            toast('Copied!', 'success')
        }
    },
    mounted() {

    },
};
</script>

<style scoped>
.transfer-list li .transfer-box {
    background-color: rgb(99 44 253);
}
</style>
