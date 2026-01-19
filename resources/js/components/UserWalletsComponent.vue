

<template>
    <div class="row">
        <!-- summary blocks -->
        <div class="col-6 col-md-6 col-lg-6 col-xxl-6">
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center mb-3">
                            <div class="circle-small mx-auto">
                                <div id="circleprogressblue">
                                    <svg style="display: block; width: 100%;" viewBox="0 0 100 100">
                                        <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                              fill-opacity="0" stroke="rgba(66, 157, 255, 0.15)"
                                              stroke-width="10"></path>
                                        <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                              fill-opacity="0" stroke="rgb(1,94,194)" stroke-width="10"
                                              style="stroke-dasharray: 282.783, 282.783; stroke-dashoffset: 98.9741;"></path>
                                    </svg>
                                </div>
                                <div class="avatar h5 bg-light-blue rounded-circle">
                                    <i class="bi bi-wallet"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <p class="text-secondary small mb-1">Activation Wallet</p>
                            <h5 class="fw-medium">{{ user_wallets.user_usd_wallet.balance }}<small>$</small></h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-6 col-xxl-6">
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center mb-3">
                            <div class="circle-small mx-auto">
                                <div id="circleprogressyellow">
                                    <svg style="display: block; width: 100%;" viewBox="0 0 100 100">
                                        <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                              fill-opacity="0" stroke="rgba(255, 200, 0, 0.3)" stroke-width="10"></path>
                                        <path d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90"
                                              fill-opacity="0" stroke="rgb(255,193,7)" stroke-width="10"
                                              style="stroke-dasharray: 282.783, 282.783; stroke-dashoffset: 42.4175;"></path>
                                    </svg>
                                </div>
                                <div class="avatar h5 bg-light-yellow text-yellow rounded-circle">
                                    <i class="bi bi-wallet"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <p class="text-secondary small mb-1">Income Wallet</p>
                            <h5 class="fw-medium">{{ user_wallets.user_income_wallet.balance }}<small>$</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'UserWalletComponent',
    prop: {
        user_wallets: Array,
    }
}
</script>

<style scoped>

</style>
