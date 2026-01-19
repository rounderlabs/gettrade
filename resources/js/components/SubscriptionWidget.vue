<template>
    <div class="card border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">My Subscriptions</h6>
                    <p class="text-secondary small">Package Activated For Defenders</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"
                   data-show-toggle="true" style="">
                <thead>
                <tr>
                    <th>Sr Number</th>
                    <th>Package Name</th>
                    <th>Amount (USDT)</th>
                    <th>Status</th>
                    <th>Subscribed On</th>
                </tr>
                </thead>
                <tbody>
                <tr class="intro-x" v-if="!subscriptions.length">
                    <td colspan="5" class="w-100">
                        <div class="flex">
                            <div class="w-100 h-10 image-fit zoom-in">
                               <p>There is no subscriptions</p>
                            </div>
                        </div>
                    </td>


                </tr>
                <tr class="intro-x " v-for="(subscription,index) in subscriptions">
                    <td class="w-40">
                        <div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in">
                                {{ index + 1 }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.plan.name }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Type</div>
                    </td>
                    <td>${{ subscription.amount }} USDT</td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.is_active ? 'Enabled' : 'Disabled' }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Status</div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ subscription.subscription_date }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Active From</div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

</template>

<script>
export default {
    name: "SubscriptionWidget",
    props: {
        subscriptions: Array
    }

}
</script>

<style scoped>
.boxgradient-blue{
    box-shadow: 0px 3px 20px #0000000b;
    background: linear-gradient(32deg, #0034ff, #ff005e);
    --bg-opacity: 1;
    background-color: #fff;
    background-color: rgba(255, 255, 255, var(--bg-opacity));
    border-radius: 0.375rem;
    position: relative;
    color: #fff;
}
</style>
