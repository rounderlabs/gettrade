<template>
    <div v-if="active_subscription" class="col-span-12 xl:col-span-4 mt-6">
        <div class="mt-5">
            <div class="intro-y">
                <div class="box px-4 py-4 mb-3 items-center grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-4">
                    <div class="ml-4 mr-auto text-center text-success">
                        <div class=" font-medium">Current Subscription</div>
                        <div class="text-xl green">Active</div>
                    </div>
                    <div class="ml-4 mr-auto text-center text-success">
                        <div class=" font-medium">Plan Amount</div>
                        <div class="text-xl green">$ {{ active_subscription.amount }}</div>

                    </div>
                    <div class="ml-4 mr-auto text-center text-success">
                        <div class=" font-medium">Max Earning</div>
                        <div class="text-xl green">${{active_subscription.max_income_limit }}</div>

                    </div>
                    <div class="ml-4 mr-auto text-center text-success">
                        <div class=" font-medium">Earned Yet</div>
                        <div class="text-xl green">${{active_subscription.earned_so_far }}</div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <div v-else  class="col-span-12 xl:col-span-4 mt-6">
        <div class="mt-5">
            <div class="intro-y">
                <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">Opps, You don't have any Subscription</div>
                        <div class="text-xs mt-6">Buy a Plan Now</div>
                    </div>
                    <div class="button  mr-1 mb-2 bg-theme-9 text-dark">
                        <Link :href="route('purchase.pricing')">Buy Plan Now</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>

import {toast} from "@/utils/toast";
import {onMounted} from "vue";
import { Link } from "@inertiajs/vue3";
import SubscriptionDetailsCard from "@/components/Hud/Cards/SubscriptionDetailsCard.vue";
import SubscriptionBoxComponent from "@/components/UserComponents/SubscriptionBoxComponent.vue";

export default {
    name: "SubscribeNowComponent",
    components:{
        SubscriptionBoxComponent,
        SubscriptionDetailsCard,
        Link
    },
    props: {
        active_subscription: Object
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
    }


}
</script>

<style scoped>
/*@media screen and (max-width: 1540px) {*/
/*    .profile-greeting {*/
/*        height: auto !important;*/
/*    }*/

/*}*/
.green{
    color: green;
}
.red{
    color: darkred;
}

</style>
