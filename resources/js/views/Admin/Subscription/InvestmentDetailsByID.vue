<template>
    <MainAdminLayout>
        <UserViewWidgetComponent :investment="investment" />
        <IncomeOverViewComponent :userIncomeStats="investment.user.user_income_stat" />

        <!-- ✅ Tabs -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="#" class="nav-link" :class="{ active: activeTab === 'inventory' }" @click.prevent="activeTab = 'inventory'">
                            <i class="bi bi-file-earmark-text me-1"></i> Guaranty Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" :class="{ active: activeTab === 'payment' }" @click.prevent="activeTab = 'payment'">
                            <i class="bi bi-wallet2 me-1"></i> Payment Record
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <GuarantyInventoryForm
                    v-if="activeTab === 'inventory'"
                    :investment="investment"
                />
                <PaymentForm
                    v-if="activeTab === 'payment'"
                    :investment="investment"
                />
            </div>
        </div>
    </MainAdminLayout>
</template>

<script setup>
import { ref } from "vue";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import UserViewWidgetComponent from "@/components/AdminComponents/UserViewWidgetComponent.vue";
import IncomeOverViewComponent from "@/components/AdminComponents/InvestmentComponents/IncomeOverViewComponent.vue";
import GuarantyInventoryForm from "@/components/AdminComponents/InvestmentComponents/GuarantyInventoryForm.vue";
import PaymentForm from "@/components/AdminComponents/InvestmentComponents/PaymentForm.vue";

const props = defineProps({
    investment: Object,
});

const activeTab = ref("inventory");
</script>
