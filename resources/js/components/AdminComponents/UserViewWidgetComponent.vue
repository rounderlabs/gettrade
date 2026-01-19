<script>
import { computed } from "vue";

export default {
    name: "UserViewWidgetComponent",
    props: {
        investment: {
            type: Object,
            required: true
        }
    },
    setup(props) {

        const passivePercent = computed(() => {
            const current = parseFloat(props.investment.passive_income ?? 0);
            const limit = parseFloat(props.investment.passive_income_limit ?? 0);
            return limit > 0 ? ((current / limit) * 100).toFixed(2) : 0;
        });

        const workingPercent = computed(() => {
            const current = parseFloat(props.investment.working_income ?? 0);
            const limit = parseFloat(props.investment.working_income_limit ?? 0);
            return limit > 0 ? ((current / limit) * 100).toFixed(2) : 0;
        });

        const totalPercent = computed(() => {
            const earned = parseFloat(props.investment.earned_so_far ?? 0);
            const totalLimit =
                parseFloat(props.investment.working_income_limit ?? 0) +
                parseFloat(props.investment.passive_income_limit ?? 0);
            return totalLimit > 0 ? ((earned / totalLimit) * 100).toFixed(2) : 0;
        });

        return {
            passivePercent,
            workingPercent,
            totalPercent
        };
    }
};
</script>

<template>
    <div class="row mb-4">

        <!-- /.col -->
        <div class="col-md-6">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{ investment.user.name }}</h3>
                    <h5 class="widget-user-desc">{{ investment.user.username }}</h5>
                </div>
                <div class="widget-user-image">
                    <img alt="User Avatar" class="img-circle elevation-2" src="/assets/img/user1-128x128.jpg">
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">${{ investment.amount }}</h5>
                                <span class="description-text">Total Investment</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">${{ investment.earned_so_far }}</h5>
                                <span class="description-text">Total Income</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">
                                    ${{
                                        parseFloat(investment.user.total_withdrawals ? investment.user.total_withdrawals : 0).toFixed(2)
                                    }}
                                </h5>
                                <span class="description-text">Withdrawn</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-body">
                    <!-- Passive Income -->
                    <div class="progress-group">
                        <strong>Passive Income (Non Working Return)</strong>
                        <span class="float-end">
                    <b>${{ parseFloat(investment.passive_income ?? 0).toFixed(2) }}</b> /
                    ${{ parseFloat(investment.passive_income_limit ?? 0).toFixed(2) }}
                </span>
                        <div class="progress progress-sm">
                            <div
                                class="progress-bar text-bg-primary"
                                :style="{ width: passivePercent + '%' }">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Working Income -->
                    <div class="progress-group">
                        <strong>Working Income (Working Return)</strong>
                        <span class="float-end">
                    <b>${{ parseFloat(investment.working_income ?? 0).toFixed(2) }}</b> /
                    ${{ parseFloat(investment.working_income_limit ?? 0).toFixed(2) }}
                </span>
                        <div class="progress progress-sm">
                            <div
                                class="progress-bar text-bg-success"
                                :style="{ width: workingPercent + '%' }">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Total -->
                    <div class="progress-group">
                        <strong>Total Income (Working + Passive Return)</strong>
                        <span class="float-end">
                    <b>${{ parseFloat(investment.earned_so_far ?? 0).toFixed(2) }}</b> /
                    ${{ (parseFloat(investment.working_income_limit ?? 0) + parseFloat(investment.passive_income_limit ?? 0)).toFixed(2) }}
                </span>
                        <div class="progress progress-sm">
                            <div
                                class="progress-bar text-bg-warning"
                                :style="{ width: totalPercent + '%' }">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <strong>Guaranty Type: {{ investment.guaranty_type }}</strong>
                </div>
            </div>
        </div>


    </div>

</template>

<style scoped>
.widget-user .widget-user-image {
    left: 50%;
    margin-left: -45px;
    position: absolute;
    top: 80px;
}

.widget-user .widget-user-image > img {
    border: 3px solid #fff;
    height: auto;
    width: 90px;
}

.elevation-2 {
    box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23) !important;
}

.img-circle {
    border-radius: 50%;
}

.description-block {
    display: block;
    margin: 10px 0;
    text-align: center;
}

.description-block > .description-header {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    padding: 0;
}

.widget-user .card-footer {
    padding-top: 50px;
}
</style>
