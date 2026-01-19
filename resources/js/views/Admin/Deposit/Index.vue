<template>
    <div class="row row-sm">
        <div class="col-sm-12">
            <div class="card">
                <!-- Filters -->
                <div class="card-header border-bottom">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mt-3 float-left">Deposit History</h4>
                        </div>
                        <div class="col-12 mt-3">
                            <form @submit.prevent="filterSubmit">
                                <div class="row">
                                    <!-- Crypto -->
                                    <div class="col-12 col-md-4 mb-3 m-form__group">
                                        <label class="form-label">Crypto Type</label>
                                        <select v-model="crypto_type" class="form-select digits">
                                            <option v-for="crypt in crypto_list" :key="crypt.value" :value="crypt.value">
                                                {{ crypt.text }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 m-form__group">
                                        <label>Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-user"></i></span>
                                            <input
                                                v-model="filterUsername"
                                                type="text"
                                                class="form-control wd-100 border-right-0"
                                                placeholder="Search by Username">
                                        </div>
                                    </div>

                                    <!-- From Date -->
                                    <div class="col-12 col-md-3 mb-3 m-form__group">
                                        <label>From Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                            <datepicker v-model="date_from" class="form-control"
                                                        placeholder="From Date"></datepicker>
                                        </div>
                                    </div>

                                    <!-- To Date -->
                                    <div class="col-12 col-md-3 mb-3 m-form__group">
                                        <label>To Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                            <datepicker v-model="date_to" class="form-control"
                                                        placeholder="To Date"></datepicker>
                                        </div>
                                    </div>

                                    <!-- Filter Text -->
                                    <div class="col-12 col-md-4 mb-3 m-form__group">
                                        <label>Filter By TXN</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-search"></i></span>
                                            <input v-model="filterText" type="text"
                                                   class="form-control wd-100 border-right-0"
                                                   placeholder="Search address, Transaction Hash">
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 col-md-8 mb-3 m-form__group mt-4 d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-search"></i> Filter
                                            </button>
                                            <Link :href="route('admin.deposit.index')" class="btn btn-info ml-2">
                                                Reset Filter
                                            </Link>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>User</th>
                            <th>Txn Date</th>
                            <th>Txn Id</th>
                            <th>In Currency</th>
                            <th>Amount</th>
                            <th>Currency Price</th>
                            <th>Amount(USDT)</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="depositHistory.length === 0">
                            <td colspan="8" class="text-center">No Record Found!</td>
                        </tr>
                        <tr v-for="(txn,index) in depositHistory" :key="txn.id" class="intro-x zoom-in">
                            <td>{{ index + pageMeta.from }}</td>
                            <td>
                                ID: {{ txn.user.id }}<br>
                                {{ txn.user.ref_code }}<br>
                                {{ txn.user.name }}
                            </td>
                            <td>{{ txn.txn_date }}</td>
                            <td>
                                <span v-if="txn.deposit_transactionable_type === 'App\\Models\\VoucherTransaction'">
                                    Fund Added by Admin
                                </span>
                                <span v-else>
                                    <div v-if="txn.deposit_transactionable">
                                        <a v-if="txn.deposit_transactionable.txn_id || txn.deposit_transactionable.txn_in"
                                           :href="getExplorer(txn)"  target="_blank">
                                            {{
                                                (txn.deposit_transactionable.txn_id
                                                    ? txn.deposit_transactionable.txn_id.substr(0, 5)
                                                    : txn.deposit_transactionable.txn_in.substr(0, 5))
                                                + '....' +
                                                (txn.deposit_transactionable.txn_id
                                                    ? txn.deposit_transactionable.txn_id.slice(-5)
                                                    : txn.deposit_transactionable.txn_in.slice(-5))
                                            }}
                                        </a>
                                    </div>
                                </span>
                            </td>
                            <td>{{ txn.currency_symbol }}</td>
                            <td>{{ parseFloat(txn.amount).toFixed(2) }}</td>
                            <td>$ 1.00</td>
                            <td>{{ parseFloat(txn.quote_amount).toFixed(2) }} USDT</td>
                            <td>
                                <div class="badge bg-warning" v-if="txn.deposit_transactionable">
                                {{txn.deposit_transactionable.invoice.status}}
                            </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginator -->
                <AdminPaginator
                    :key="randomKey"
                    :base-url="buildBaseUrl()"
                    @responseData="paginatorResponse"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import Datepicker from "vue3-datepicker";
import { Link } from "@inertiajs/vue3";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";
import AdminPaginator from "@/components/AdminPaginator.vue";

export default {
    name: "DepositHistory",
    components: { AdminPaginator, Datepicker, Link },
    layout: MainAdminLayout,
    props: { explorers: Object },

    setup(props) {
        const pageMeta = ref([]);
        const depositHistory = ref([]);

        const crypto_list = ref([
            { text: "ALL", value: "all" },
            { text: "TRC20_USDT", value: "trc20_usdt" },
            { text: "USDT", value: "usdt" },
            { text: "TRON", value: "trx" },
            { text: "BNB", value: "bep20_bnb" },
            { text: "BUSD", value: "bep20_busd" },
            { text: "ETHER", value: "bep20_eth" },
            { text: "Admin Voucher", value: "voucher_usd" },
        ]);

        const crypto_type = ref("all");
        const date_from = ref(null);
        const date_to = ref(null);
        const date_from_new = ref(null);
        const date_to_new = ref(null);
        const filterText = ref("");
        const filterTextNew = ref("");
        const randomKey = ref(null);
        const filterUsername = ref('');
        const filterUsernameNew = ref('');

        // ✅ Format date to YYYY-MM-DD for Laravel
        const formatDate = (date) => {
            if (!date) return null;
            const tzOffset = date.getTimezoneOffset() * 60000;
            return new Date(date - tzOffset).toISOString().split("T")[0];
        };

        // ✅ Build backend-compatible query URL
        const buildBaseUrl = () => {
            return route('admin.deposit.get.list') +
                '?crypto_type=' + crypto_type.value +
                '&filter=' + (filterTextNew.value ? filterTextNew.value : 'noText') +
                '&username=' + (filterUsernameNew.value ? filterUsernameNew.value : 'noUser') +
                '&from_date=' + (date_from_new.value ? date_from_new.value : 'noDate') +
                '&to_date=' + (date_to_new.value ? date_to_new.value : 'noDate');
        };

        // ✅ Handle filter form submit
        function filterSubmit() {
            randomKey.value = Math.random();
            filterTextNew.value = filterText.value;
            filterUsernameNew.value = filterUsername.value;
            date_from_new.value = date_from.value ? formatDate(date_from.value) : null;
            date_to_new.value = date_to.value ? formatDate(date_to.value) : null;
        }

        // ✅ Map Explorer URL
        function getExplorer(txn) {
            const explorer = props.explorers[txn.currency_symbol.toLowerCase()];
            if (!explorer) return "#";
            const txid =
                txn.deposit_transactionable?.txn_id ||
                txn.deposit_transactionable?.txn_in ||
                "";
            return explorer.txn + txid;
        }

        // ✅ Handle Laravel paginator response
        function paginatorResponse(data) {
            depositHistory.value = data.data ?? [];
            pageMeta.value = {
                from: data.from ?? 1,
                to: data.to ?? 1,
                current_page: data.current_page ?? 1,
                last_page: data.last_page ?? 1,
                total: data.total ?? 0,
            };
        }

        return {
            depositHistory,
            pageMeta,
            crypto_list,
            crypto_type,
            date_from,
            date_to,
            date_from_new,
            date_to_new,
            filterText,
            filterTextNew,
            randomKey,
            filterSubmit,
            paginatorResponse,
            getExplorer,
            buildBaseUrl,
            filterUsername,
            filterUsernameNew,
        };
    },
};
</script>

