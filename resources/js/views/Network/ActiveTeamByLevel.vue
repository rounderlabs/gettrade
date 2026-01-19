<template>

    <div class="row mb-4">
        <div class="card bg-radial-gradient border-0 mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto mb-2">
                        <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                    </div>
                    <div class="col-auto mb-2">
                        <h6 class="d-inline-block mb-0">Active Referral List By Level</h6>
                        <p class="text-secondary small">All Member In Your Team By Level</p>
                    </div>
                    <div class="col-12 ms-auto mb-2">
                        <div class="input-group border">
                            <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-globe"></i></span>
                            <div class="form-floating">
                                <select id="level" v-model="selectedLevel" class="form-select border-0"
                                        name="level" @change="loadLevel">
                                    <option>Select Level</option>
                                    <option v-for="level in allLevel" :value="level.level">Level {{level.level}}</option>
                                </select>
                                <label for="level">Select Level</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="row">
                    <div class="col-2 text-left">SN</div>
                    <div class="col-4 text-left">Details</div>
                    <div class="col-3 text-left">Team</div>
                    <div class="col-3 text-right">Package</div>
                </div>

                <div v-if="!partners.length" class="row">
                    <div class="col-12 text-center">
                        <p>There is no Active Member</p>
                    </div>
                </div>
                <div v-for="(partner,index) in partners" :key="partner.id">
                    <div class="card bg-radial-gradient mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 text-left">{{ index + 1 }}</div>
                                <div class="col-4 text-left">
                                    <i class="icon bi bi-person"></i> {{ partner.downline_user.name }} <br>
                                    <i class="icon bi bi-person-badge"></i> {{ partner.downline_user.username }}
                                </div>
                                <div class="col-3 text-right">
                                    Total: {{ partner.downline_user.team.total }} <br>
                                    Active : {{ partner.downline_user.team.active_total }}
                                </div>
                                <div class="col-3 text-right">
                                    <span class="text-white" v-if="partner.downline_user.subscriptions == null">
                                       Not Active
                                    </span>
                                    <span class="text-success" v-else>
                                        <i class="icon bi bi-tag-fill"></i> ${{ partner.downline_user.subscriptions.amount}} <br>
                                    </span>
                                </div>
                                <div class="col-12 text-center mt-3" style="border-top: solid 1px #c49c44;">
                                    <span class="text-white" v-if="partner.downline_user.subscriptions == null">
                                       Not Active
                                    </span>
                                    <span class="text-success" v-else>
                                        <i class="icon bi bi-check-circle-fill"></i> Active | <i class="icon bi bi-calendar-check"></i> Date : {{ partner.downline_user.subscriptions.created_at}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>





<!--            <div class="card-body p-0">-->
<!--                <table class="table footable footable-1 footable-paging footable-paging-center breakpoint breakpoint-xs"-->
<!--                       data-show-toggle="true" style="display: block; overflow-x: auto; white-space: nowrap;">-->
<!--                    <thead>-->
<!--                    <tr class="text-muted footable-header">-->

<!--                        <th>SNo</th>-->
<!--                        <th>Username</th>-->
<!--                        <th>Name</th>-->
<!--                        <th>Total Team</th>-->
<!--                        <th>Active Team</th>-->
<!--                        <th>Subscription</th>-->
<!--&lt;!&ndash;                        <th>Activated On</th>&ndash;&gt;-->
<!--&lt;!&ndash;                        <th>Joined On</th>&ndash;&gt;-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr v-if="!partners.length" class="intro-x">-->
<!--                        <td colspan="8">-->
<!--                            <div class="text-center">-->
<!--                                <p>There is no Records Found</p>-->
<!--                            </div>-->
<!--                        </td>-->


<!--                    </tr>-->
<!--                    <tr v-for="(partner,index) in partners" :key="partner.id" class="intro-x">-->
<!--                        <td class="">-->
<!--                            {{ index + 1 }}-->
<!--                        </td>-->
<!--                        <td class="font-medium whitespace-nowrap">{{ partner.downline_user.username }}</td>-->
<!--                        <td class="text-center">{{ partner.downline_user.name }}</td>-->
<!--&lt;!&ndash;                        <td class="text-center">{{ partner.downline_user.email }}</td>&ndash;&gt;-->
<!--&lt;!&ndash;                        <td class="font-medium whitespace-nowrap">&ndash;&gt;-->
<!--&lt;!&ndash;                            {{ partner.downline_user.user_business ? partner.downline_user.user_business.usd : 0 }}&ndash;&gt;-->
<!--&lt;!&ndash;                        </td>&ndash;&gt;-->
<!--                        <td class="table-report__action font-medium whitespace-nowrap">{{ partner.downline_user.team.total }}-->
<!--                        </td>-->

<!--                        <td class="table-report__action font-medium whitespace-nowrap">-->
<!--                            {{ partner.downline_user.team.active_total }}-->
<!--                        </td>-->
<!--                        <td class="table-report__action font-medium whitespace-nowrap">-->
<!--                            Active-->
<!--                        </td>-->

<!--&lt;!&ndash;                        <td class="table-report__action font-medium whitespace-nowrap">&ndash;&gt;-->
<!--&lt;!&ndash;                            {{ partner.downline_user.created_at }}&ndash;&gt;-->
<!--&lt;!&ndash;                        </td>&ndash;&gt;-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
            <div class="card-footer bg-none text-center">
                <div class="row align-items-center">

                    <div id="footable-pagination" class="col-12 footable-paging-external footable-paging-center">
                        <div class="footable-pagination-wrapper">
                            <ul class="pagination">
                                <li :class="[!pageUrls.prev ? 'disabled':'']" class="footable-page-nav">
                                    <a class="footable-page-link" href="javascript:void(0)" title="Prev"
                                       @click="goPrev(pageUrls.prev)">
                                        «
                                    </a>
                                </li>
                                <li class="footable-page visible active">
                                    <a class="footable-page-link" href="javascript:void(0)">{{ pageUrls.current }}</a>
                                </li>
                                <li :class="[!pageUrls.next ? 'disabled':'']" class="footable-page-nav">
                                    <a class="footable-page-link" href="javascript:void(0)" title="Next"
                                       @click="goNext(pageUrls.next)">
                                        »</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import TeamWidget from "@/components/xino/TeamWidget";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "TeamByLevel",
    components: { TeamWidget },
    metaInfo: {
        title: "Team By Level"
    },
    layout: UserLayout,
    props: {
        team: Object,
        allLevel: Array,
    },
    data() {
        return {
            selectedLevel: 1,
            partners: [],
            pageUrls: {
                prev: null,
                next: null,
                current: null
            }
        };
    },
    methods: {

        loadLevel(selectedLevel) {
            axios.get(route("team.active.by.level.list", [this.selectedLevel, 1])).then(res => {
                let response = res.data;
                this.partners = response.data;
                this.pageUrls.prev = response.prev_page_url;
                this.pageUrls.next = response.next_page_url;
                this.pageUrls.current = response.current_page;
            }).catch(err => {

            });

        },

        goPrev(url) {
            if (url) {
                axios.get(url).then(res => {
                    let response = res.data;
                    this.partners = response.data;
                    this.pageUrls.prev = response.prev_page_url;
                    this.pageUrls.next = response.next_page_url;
                    this.pageUrls.current = response.current_page;
                }).catch(err => {

                });
            }

        },
        goNext(url) {
            if (url) {
                axios.get(url).then(res => {
                    let response = res.data;
                    this.partners = response.data;
                    this.pageUrls.prev = response.prev_page_url;
                    this.pageUrls.next = response.next_page_url;
                    this.pageUrls.current = response.current_page;
                }).catch(err => {

                });
            }

        }
    },

    mounted() {
        axios.get(route("team.active.by.level.list")).then(res => {
            let response = res.data;
            this.partners = response.data;
            this.pageUrls.prev = response.prev_page_url;
            this.pageUrls.next = response.next_page_url;
            this.pageUrls.current = response.current_page;
        }).catch(err => {

        });
    }
};
</script>

<style scoped>

</style>
