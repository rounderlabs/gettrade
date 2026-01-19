<template>
    <div class="row row-sm">
        <div class="col-sm-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="row">
                        <div class="col-lg-3 ">
                            <h4 class="card-title">Users</h4>

                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0 text-md-nowrap">
                            <thead>
                            <tr>
                                <th class="wd-5p serial-num text-center">S.No</th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Balance</th>
                                <th>Subscription Sum</th>
                                <th>Total Withdrawal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="teams.length==0">
                                <td colspan="8">No Record Found!</td>
                            </tr>
                            <tr v-for="(user,index) in teams">

                                <td>{{ index + 1 }}</td>
                                <td>
                                    <b>{{ user.username }}</b><br>
                                    <b>{{ user.name }}</b>
                                    <br>
                                    <small>
                                        {{ user.email }}
                                    </small>
                                </td>
                                <td>{{ user.mobile }}</td>
                                <td>{{ user.balance }}</td>
                                <td>{{ user.sub_sum }}</td>
                                <td>{{ user.with_sum }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "TeamOverview",
    layout: MainAdminLayout,
    props: {
        teams: Array
    }
}
</script>

<style scoped>
.card {
    box-shadow: none !important;
    transform: none !important;
}
</style>
