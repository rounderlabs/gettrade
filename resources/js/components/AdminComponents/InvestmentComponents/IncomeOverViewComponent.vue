<script setup>
import { computed } from "vue";

const props = defineProps({
    userIncomeStats: {
        type: Object,
        default: () => ({}), // ✅ ensures we always get an object, not null
    },
});

// ✅ computed fallback values for easy use in template
const stats = computed(() => ({
    total: Number(props.userIncomeStats?.total ?? 0),
    front_line: Number(props.userIncomeStats?.front_line ?? 0),
    trading: Number(props.userIncomeStats?.trading ?? 0),
    profit_sharing: Number(props.userIncomeStats?.profit_sharing ?? 0),
    pool: Number(props.userIncomeStats?.pool ?? 0),
    magic: Number(props.userIncomeStats?.magic ?? 0),
    reward: Number(props.userIncomeStats?.reward ?? 0),
}));

// ✅ computed to show loading state if all values are 0
const isEmpty = computed(() =>
    Object.values(stats.value).every((v) => v === 0)
);
</script>

<template>
    <div class="row">
        <!-- Skeleton Loader (only if no data yet) -->
        <div v-if="isEmpty" class="col-12 text-center py-4 text-muted">
            <i class="fas fa-spinner fa-spin me-1"></i> Loading income overview...
        </div>

        <template v-else>
            <!-- Total Income -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-primary shadow-sm">
            <i class="bi bi-cash-stack"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Income</span>
                        <span class="info-box-number">${{ stats.total.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Front Line -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-success shadow-sm">
            <i class="bi bi-graph-up-arrow"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Front Line Income</span>
                        <span class="info-box-number">${{ stats.front_line.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Returns -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-warning shadow-sm">
            <i class="bi bi-calendar3"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Monthly Returns</span>
                        <span class="info-box-number">${{ stats.trading.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Profit Sharing -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-info shadow-sm">
            <i class="bi bi-diagram-3"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Profit Sharing</span>
                        <span class="info-box-number">${{ stats.profit_sharing.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Pool -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-danger shadow-sm">
            <i class="bi bi-people-fill"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pool Income</span>
                        <span class="info-box-number">${{ stats.pool.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Magic -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-purple shadow-sm">
            <i class="bi bi-stars"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Magic Income</span>
                        <span class="info-box-number">${{ stats.magic.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Reward -->
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="info-box">
          <span class="info-box-icon text-bg-secondary shadow-sm">
            <i class="bi bi-award"></i>
          </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Reward Income</span>
                        <span class="info-box-number">${{ stats.reward.toFixed(2) }}</span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>
.info-box {
    display: flex;
    align-items: center;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    background-color: #fff;
    padding: 0.75rem;
}
.info-box-icon {
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
}
.info-box-content {
    margin-left: 0.75rem;
}
.info-box-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: #666;
}
.info-box-number {
    font-size: 1.2rem;
    font-weight: 600;
}
</style>
