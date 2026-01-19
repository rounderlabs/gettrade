<template>
    <div class="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3 mt-2 lg:mt-6 xl:mt-2">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">Validator Stats</h2>
        </div>
        <div class="report-box-2 intro-y mt-5">
            <div class="box p-5">
                <ul class="
                                    nav
                                    nav-pills
                                    w-4/5
                                    bg-slate-100
                                    dark:bg-black/20
                                    rounded-md
                                    mx-auto
                                " role="tablist">
                    <li id="active-users-tab" class="nav-item flex-1" role="presentation">
                        <button class="nav-link w-full py-1.5 px-2 active" data-tw-toggle="pill" data-tw-target="#active-users" type="button" role="tab" aria-controls="active-users" aria-selected="true">
                            Active Validators
                        </button>
                    </li>
                </ul>
                <div class="tab-content mt-6">
                    <div class="tab-pane active" id="active-users" role="tabpanel" aria-labelledby="active-users-tab">
                        <div class="relative">
                            <div class="h-[208px]">
                                <canvas class="mt-3" id="report-donut-chart" width="435" height="392" style="display: block; box-sizing: border-box; height: 196px; width: 217.5px;"></canvas>
                            </div>
                            <div class="flex flex-col justify-center items-center absolute w-full h-full top-0 left-0">
                                <div class="text-2xl font-medium">1,000</div>
                                <div class="text-slate-500 mt-0.5">Total Validators</div>
                            </div>
                        </div>
                        <div class="w-52 sm:w-auto mx-auto mt-5">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                <span class="truncate">Active Validator</span>
                                <span class="font-medium ml-auto">15%</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                <span class="truncate">Available Validator</span>
                                <span class="font-medium ml-auto">80%</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                <span class="truncate">Node Validator</span>
                                <span class="font-medium ml-auto">5%</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ValidatorStatGraphWidget"
};
</script>

<style scoped>

</style>
