<script setup>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue"
import NotificationToast from "@/components/NotificationToast.vue"
import { useForm, usePage } from "@inertiajs/vue3"
import { computed } from "vue"
import { toast } from "@/utils/toast"

defineOptions({ layout: UserLayout })

const props = defineProps({
    plan: Object,
    available_coin_balance: Object,
})

const page = usePage()

const currencySymbol = computed(() => {
    return page.props.currency?.symbol ?? "₹"
})

// ✅ BASE AMOUNT ONLY (backend-safe)
const form = useForm({
    plan_id: props.plan.id,
    amount: props.plan.amount_base,
})

const hasSufficientBalance = computed(() => {
    return Number(props.available_coin_balance.balance_base)
        >= Number(props.plan.amount_base)
})

function submit() {
    if (!hasSufficientBalance.value) {
        toast("Insufficient wallet balance", "danger")
        return
    }

    form.post(route("purchase.plan.activate"))
}
</script>

<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Review The Selected Package</h2>
            </div>

            <ul class="notification-list">
                <li class="notification-box mt-3">
                    <div class="notification-details">

                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white">Package Amount</h6>
                                <h6 class="text-white mt-1">Monthly Trading Bonus</h6>
                                <h6 class="text-white mt-1">Package Tenure</h6>
                            </div>

                            <div class="text-end">
                                <h6 class="text-white">
                                    {{ currencySymbol }} {{ plan.amount_display }}
                                </h6>
                                <h6 class="text-white mt-1">
                                    {{ plan.monthly_roi_amount }} %
                                </h6>
                                <h6 class="text-white mt-1">
                                    {{ plan.tenure }} Months
                                </h6>
                            </div>
                        </div>

                        <form class="mt-3" @submit.prevent="submit">
                            <button class="btn theme-btn w-100">
                                Invest Now
                            </button>
                        </form>

                    </div>
                </li>
            </ul>
        </div>
    </section>

    <NotificationToast />
</template>
