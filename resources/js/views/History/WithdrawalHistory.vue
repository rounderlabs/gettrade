<template>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>withdraw Bonus</h2>
            </div>
            <div class="d-flex gap-3">
            <ul class="select-bank ">
                <li>
                    <div class="balance-box active">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img active"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg-active.svg">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img unactive"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg.svg">
                        <div class="balance-content">
                            <h6>Balance</h6>
                            <h3>₹ {{ parseFloat(income_on_hold.level).toFixed(2) }}</h3>
                            <h5>Level Bonus </h5>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="select-bank">
                <li>
                    <div class="balance-box active">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img active"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg-active.svg">
                        <img alt="balance-box"
                             class="img-fluid balance-box-img unactive"
                             src="/user-panel/assets-panel/assets/images/svg/balance-box-bg.svg">
                        <div class="balance-content">
                            <h6>Balance</h6>
                            <h3>₹ {{ parseFloat(parseFloat(income_on_hold.roi) + parseFloat(income_on_hold.roi_on_roi)).toFixed(2) }}</h3>
                            <h5>Dividend Balance </h5>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </section>
    <section>
        <div class="custom-container">
            <div class="title">
                <h2>Withdrawn History</h2>
            </div>
            <div v-if="!history.length" class="row gy-3">
                <div class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <div class="transaction-details">
                                <div class="d-flex justify-content-between">
                                    <h5 class="light-text">No Transaction Found</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row gy-3">
                <div v-for="(txn,index) in history" :key="txn.id" class="col-12">
                    <div class="transaction-box">
                        <a class="d-flex gap-3" href="">
                            <div class="transaction-details">
                                <div class="transaction-name">
                                    <h6 class="dark-text">Amount <br> ₹ {{ parseFloat(txn.amount).toFixed(2) }}</h6>
                                    <h6 class="dark-text">Fees <br> ₹ {{ parseFloat(txn.fees).toFixed(2) }}</h6>
                                    <h6 class="dark-text">Received Amount <br> ₹
                                        {{ parseFloat(txn.receivable_amount).toFixed(2) }}</h6>

                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5 :class="{'error-color': txn.status === 'pending','success-color': txn.status === 'success'}">
                                        Status : {{ txn.status }}
                                    </h5>
                                    <h5 class="warning-color">{{ txn.created_at }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import VueFeather from "vue-feather";

export default {
    name: "WithdrawalHistory",
    components: {VueFeather},
    metaInfo: {title: 'Withdrawal History'},
    layout: UserLayout,
    props: {
        history: Array,
        income_on_hold: Object,
    }
}
</script>

<style scoped>
.tab-row {
    min-height: 70px;
    padding: 20px;
    margin-bottom: 10px;
}

.borderTop {
    border-top: solid 1px #d1b064;
}

.transaction-box .transaction-details {
    width: 100% !important;
}

</style>
