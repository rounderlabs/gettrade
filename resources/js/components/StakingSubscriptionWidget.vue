<template>
    <div class="col-span-12 mt-6">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">My Staking </h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
                <p class="tx-12 tx-gray-500 mb-2">List Of All Stakings</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mg-b-0 text-md-nowrap">
                        <thead>
                        <tr>
                            <th class="whitespace-nowrap">S.N0</th>
                            <th class="whitespace-nowrap">Amount</th>
                            <th class="whitespace-nowrap">Txn Id</th>
                            <th class="whitespace-nowrap">Tokens</th>
                            <th class="whitespace-nowrap">Tenure</th>
                            <th class="whitespace-nowrap">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="!stakingTransactions.length" class="intro-x">
                            <td class="text-center" colspan="7">There is no recurring transaction</td>
                        </tr>
                        <tr v-for="(stakingTxn,index) in stakingTransactions" class="intro-x">
                            <td>{{ index + 1 }}</td>
                            <td>${{ stakingTxn.amount_usd }}</td>
                            <td>
                                <a :href="'https://bscscan.com/tx/'+stakingTxn.txn_hash" target="_blank">
                                    {{
                                        stakingTxn.txn_hash ? stakingTxn.txn_hash.substr(0, 5) + '....' + stakingTxn.txn_hash.slice(-5) : ''
                                    }}
                                </a>
                            </td>
                            <td>{{ stakingTxn.tokens }} SMT</td>
                            <td>{{ stakingTxn.tenure }} Months</td>
                            <td>{{ stakingTxn.status }}</td>
                            <td>{{ stakingTxn.subscription_date }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        stakingTransactions: Array
    }

}
</script>

<style scoped>

</style>
