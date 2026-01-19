<template>

    <div class="row top-125">
        <h1>Welcome, {{ auth.user?.name || 'Admin' }}</h1>
        <h6>Users Activity</h6>
        <div class="col-6">
            <div class="card border-0 mb-4 theme-yellow bg-radial-gradient">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1">Total User</p>
                            <h5 class="fw-medium">{{ users }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card border-0 mb-4 theme-yellow bg-radial-gradient text-dark">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1">Paid Users</p>
                            <h5 class="fw-medium"><small></small>{{ active_participants }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <h6>Incomes</h6>
    <div class="row">
        <!-- summary blocks -->

        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xxl-3">
            <div class="card border-0 mb-4 theme-green bg-radial-gradient text-dark">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white text-dark rounded-circle">
                                <i class="bi bi-thermometer-sun"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1"> Total Level Bonus</p>
                            <h5> <small>$</small>{{parseFloat(total_front_line_bonus).toFixed(0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xxl-3">
            <div class="card border-0 mb-4 theme-yellow bg-radial-gradient text-dark">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1">Total Dividend Bonus</p>
                            <h5 class="fw-medium"> <small>$</small>{{ parseFloat(total_trading_bonus).toFixed(0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xxl-3">
            <div class="card border-0 mb-4 theme-red bg-radial-gradient text-dark">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                <i class="bi bi-coin"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1">Total Level On Dividend Bonus</p>
                            <h5 class="fw-medium"> <small>$</small>{{parseFloat(total_profit_sharing_bonus).toFixed(0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xxl-3">
            <div class="card border-0 mb-4 theme-yellow bg-radial-gradient text-dark">
                <div class="card-body bg-none">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-1">Total Maturity Bonus</p>
                            <h5 class="fw-medium"><small>$</small>{{ parseFloat(total_magic_bonus).toFixed(0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>


</template>

<script>

import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: 'Dashboard',
    layout: MainAdminLayout,
    props: {
        active_participants: Number,
        users: Number,
        total_front_line_bonus : Number,
        total_trading_bonus: Number,
        total_profit_sharing_bonus: Number,
        total_magic_bonus: Number,
        total_reward_bonus: Number,
        total_pool_bonus: Number,
        auth: Object
    },
    setup(props) {

    }
}
</script>
