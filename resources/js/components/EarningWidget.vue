<template>
    <div class="row row-cards">
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h6 class="text-white">Recurring Bonus</h6>
                            <h2 class="text-white m-0 ">
                                ${{ parseFloat(income_stats.staking) + parseFloat(income_stats.daily) }}</h2>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-file-invoice fa-2x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="card bg-info">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h6 class="text-white">Performance Bonus</h6>
                            <h2 class="text-white m-0 ">${{ parseFloat(income_stats.staking_level_income).toFixed(2) }}</h2>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-file-invoice fa-2x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h6 class="text-white">Direct Rewards</h6>
                            <h2 class="text-white m-0 ">${{ parseFloat(income_stats.direct).toFixed(2) }}</h2>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-file-invoice fa-2x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h6 class="text-white">Total Wonder</h6>
                            <h2 class="text-white m-0 ">${{ income_stats.fast_sponsor }}</h2>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-file-invoice fa-2x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EarningWidget",
    props: {
        income_stats: Object
    }
}
</script>

<style scoped>

</style>
