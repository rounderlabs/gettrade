<template>
    <!-- BEGIN: Best Champion -->
    <div class="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-12 mt-2 lg:mt-6 xl:mt-2">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Recent 5 Trip Bonanza Achiever
            </h2>
        </div>
        <div class="mt-5">
            <div class="intro-y" v-if="!latest_champion.length">
                <div class="box px-4 py-4 mb-3 flex items-center zoom-in">

                    <div class="ml-4 mr-auto">
                        <div class="font-medium">No Pool Achiever Yet, Become 1st </div>
                    </div>
                    <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-black cursor-pointer font-medium">Become One</div>
                </div>
            </div>
            <div class="intro-y" v-for="( champion, index ) in latest_champion">
                <div class="box boxgradient-blue px-4 py-4 mb-3 flex items-center zoom-in">

                    <div class="ml-4 mr-auto">

                        <div class="text-medium">{{champion.user.username}}</div>
                    </div>
                    <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-black cursor-pointer font-medium">Achieved</div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Best Champion  -->
</template>

<script>
export default {
    name: "LatestChampionComponent",
    props:{
        latest_champion: Array,
    }
};
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
