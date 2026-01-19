<template>
    <!-- BEGIN: General Report -->
    <div class="col-span-12 lg:col-span-8 xl:col-span-6 mt-2">
        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">General Report</h2>
        </div>
        <div class="col-span-12 lg:col-span-6 mt-2">
            <div class="intro-y box p-5 mt-12 sm:mt-5">
                <div class="flex flex-col xl:flex-row xl:items-center">
                    <div class="flex">
                        <div>
                            <div class="text-theme-20 dark:text-gray-300 text-lg xl:text-xl font-bold">$15,000</div>
                            <div class="text-gray-600 dark:text-gray-600">This Month</div>
                        </div>
                        <div class="w-px h-12 border border-r border-dashed border-gray-300 dark:border-dark-5 mx-4 xl:mx-6"></div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-600 text-lg xl:text-xl font-medium">$10,000</div>
                            <div class="text-gray-600 dark:text-gray-600">Last Month</div>
                        </div>
                    </div>
                </div>
                <div class="report-chart">
                    <canvas id="report-line-chart" height="160" class="mt-6"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- END: General Report -->
</template>

<script>
export default {
    name: "IncomeGraphWidget"
};
</script>

<style scoped>

</style>
