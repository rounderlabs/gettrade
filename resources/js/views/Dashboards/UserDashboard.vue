<template>
    <!--    <WelcomeModal-->
    <!--        v-if="showWelcomeModal"-->
    <!--        :user="user"-->
    <!--    />-->
    <UserLoginDetailsComponent :user="user"></UserLoginDetailsComponent>
    <InstantOperationComponent></InstantOperationComponent>
    <ReferralLinkComponent :ref_qr="ref_qr" :user="user"></ReferralLinkComponent>
    <BalanceInfoComponent :currency="currencySymbol" :investment="investment" :total_withdrawal="total_withdrawal"
                          :user_income="user_income">

    </BalanceInfoComponent>

    <UserIncomeComponent :user_income="user_income" :currency="currencySymbol" />

    <team-widget :team="team"></team-widget>

    <section>
        <div class="custom-container">
            <div class="statistics-banner">
                <div class="d-flex justify-content-center gap-3">
                    <div class="statistics-image">
                        <svg fill="#ffffff" height="24px" width="24px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 392.482 392.482" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M344.245,244.564v-34.521c0-6.012-4.849-10.925-10.925-10.925H207.453v-21.786h86.238c6.012,0,10.925-4.849,10.925-10.925 V35.303c0-6.012-4.849-10.925-10.925-10.925H159.809L148.366,5.308c-1.939-3.297-5.495-5.301-9.374-5.301H98.912 c-6.012,0-10.925,4.849-10.925,10.925v155.475c0,6.012,4.849,10.925,10.925,10.925h86.756v21.786H59.607 c-6.012,0-10.925,4.848-10.925,10.925v34.521c-13.382,4.59-23.014,17.261-23.014,32.065c0,18.683,15.192,33.939,34.004,33.939 c18.683,0,34.004-15.192,34.004-33.939c0-14.933-9.632-27.604-23.014-32.065v-23.661h115.135v23.661 c-13.382,4.59-23.014,17.261-23.014,32.065c0,18.683,15.192,33.939,34.004,33.939c18.683,0,34.004-15.192,34.004-33.939 c0-14.933-9.632-27.604-23.014-32.065v-23.661h114.489v23.661c-13.382,4.59-23.014,17.261-23.014,32.065 c0,18.683,15.192,33.939,34.004,33.939c18.683,0,34.004-15.192,34.004-33.939C367.259,261.695,357.627,249.025,344.245,244.564z M71.825,276.629c0,6.659-5.495,12.154-12.154,12.154c-6.659,0-12.154-5.43-12.154-12.154c-0.065-6.659,5.43-12.154,12.154-12.154 C66.395,264.475,71.825,269.906,71.825,276.629z M109.708,155.417v-21.786h16.356c6.012,0,10.925-4.849,10.925-10.925 c0-6.012-4.848-10.925-10.925-10.925h-16.356V89.994h43.572c6.012,0,10.925-4.849,10.925-10.925 c0-6.012-4.849-10.925-10.925-10.925h-43.572V21.792h23.014l11.507,19.071c1.939,3.297,5.495,5.301,9.374,5.301h129.228v109.253 H109.708z M208.746,276.629c0,6.723-5.43,12.154-12.154,12.154s-12.154-5.43-12.154-12.154c0-6.659,5.495-12.154,12.154-12.154 C203.316,264.475,208.746,269.906,208.746,276.629z M332.867,288.782c-6.723,0-12.154-5.43-12.154-12.154 c0-6.659,5.495-12.154,12.154-12.154c6.723,0,12.154,5.43,12.154,12.154C345.086,283.352,339.591,288.782,332.867,288.782z"></path> </g> </g> <g> <g> <path d="M59.671,323.821c-32.194,0-58.44,25.212-59.669,57.406c-0.065,2.909,1.034,5.883,3.038,7.952 c2.069,2.133,4.848,3.297,7.887,3.297h97.422c2.909,0,5.818-1.228,7.887-3.297c2.069-2.133,3.168-5.042,3.038-7.952 C118.047,349.033,91.865,323.821,59.671,323.821z M23.922,370.754c5.172-14.739,19.135-25.083,35.685-25.083 c16.485,0,30.448,10.343,35.685,25.083H23.922z"></path> </g> </g> <g> <g> <path d="M196.593,323.821c-32.194,0-58.44,25.212-59.669,57.406c-0.065,2.909,1.034,5.883,3.038,7.952 c2.069,2.133,4.849,3.297,7.887,3.297h97.422c2.909,0,5.818-1.228,7.887-3.297c2.069-2.133,3.168-5.042,3.038-7.952 C254.968,349.033,228.787,323.821,196.593,323.821z M160.908,370.754c5.172-14.739,19.135-25.083,35.685-25.083 c16.485,0,30.448,10.343,35.685,25.083H160.908z"></path> </g> </g> <g> <g> <path d="M332.867,323.821c-32.194,0-58.44,25.212-59.669,57.406c-0.065,2.909,1.034,5.883,3.038,7.952 c2.069,2.133,4.848,3.297,7.887,3.297h97.422c2.909,0,5.818-1.228,7.887-3.297c2.069-2.133,3.168-5.042,3.038-7.952 C391.308,349.033,365.126,323.821,332.867,323.821z M297.247,370.754c5.172-14.739,19.135-25.083,35.685-25.083 c16.485,0,30.448,10.343,35.685,25.083H297.247z"></path> </g> </g> </g></svg>
                    </div>
                    <div class="statistics-content d-block">
                        <h5>Level Achievement</h5>
                        <h6>Level Opened</h6>
                    </div>
                    <div class="statistics-content">
                        <h1 class="dark-text">{{opened_level}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
import {computed, onMounted} from "vue";
import {toast} from "@/utils/toast";
import UserIncomeComponent from "@/components/UserIncomeComponent.vue";
import UserWalletsComponent from "@/components/UserWalletsComponent.vue"
import InstantOperationComponent from "@/components/SunLotusInfra/InstantOperationComponent.vue";
import BalanceInfoComponent from "@/components/SunLotusInfra/BalanceInfoComponent.vue";
import SliderComponent from "@/components/SunLotusInfra/SliderComponent.vue";
import WelcomeModal from "@/components/WelcomeModal.vue";
import {Link, usePage} from "@inertiajs/vue3";
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
        user_income: Object,
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
        display_currency: String,
        opened_level:Number,
    },
    setup() {
        const page = usePage()
        const currencySymbol = computed(() => page.props.currency.symbol)

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
        return {
            currencySymbol,
        }
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
