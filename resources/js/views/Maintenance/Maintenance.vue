<template>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="intro-y flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5"> My All Wallets </h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box boxgradient-blue p-5 text-center">
                            <div class="">
                                <div class="text-base text-slate-500 mt-1">We are Upgrading Our System Now</div>
                            </div>
                            <div class="">
                                <div class="text-base text-slate-500 mt-1">Deposit Withdrawal & Package Activation Will Resume Today At .</div>
                            </div>
                            <div class="text-3xl  font-medium leading-8 mt-6">19/11/2023 @ 05:00 AM</div>
                            <div class="text-base text-slate-500 mt-1">Make Sure That,Request Any Deposit Withdrawal & Package Activation after That.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout";

export default {
    name: "Maintenance",
    layout: UserLayout,

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
