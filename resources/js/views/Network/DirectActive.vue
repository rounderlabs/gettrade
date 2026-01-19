<template>

    <team-widget :team="team"></team-widget>
    <div class="card bg-radial-gradient border-0 mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-auto mb-2">
                    <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>
                </div>
                <div class="col-auto mb-2">
                    <h6 class="d-inline-block mb-0">Active Direct Referred Team</h6>
                    <p class="text-secondary small">All Member Under You</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-2 text-left">S.No</div>
                <div class="col-6 text-left">Details</div>
                <div class="col-4 text-right">Package</div>
            </div>

            <div v-if="!partners.length" class="row">
                <div class="col-12 text-center">
                    <p>There is no subscriptions</p>
                </div>
            </div>
            <div v-for="(partner,index) in partners" :key="partner.id">
                <div class="card bg-radial-gradient mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2 text-left">{{ index + 1 }}</div>
                            <div class="col-6 text-left">
                                <i class="icon bi bi-person"></i> {{ partner.user.name }} <br>
                                <i class="icon bi bi-person-badge"></i> {{ partner.user.username }} <br>
                                <i class="icon bi bi-person-add"></i> {{ partner.user.created_at }}
                            </div>
                            <div class="col-4 text-right">
                                    <span class="text-white" v-if="partner.user.subscriptions == null">
                                       Not Active
                                    </span>
                                    <span class="text-success" v-else>
                                       <i class="icon bi bi-tag-fill"></i> ${{partner.user.subscriptions.amount}} <br>
                                        <i class="icon bi bi-check-circle-fill"></i> Active Since <br>
                                        {{partner.user.subscriptions.created_at}}
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
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

</template>

<script>

import TeamWidget from "@/components/xino/TeamWidget";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "Direct",
    components: {TeamWidget},
    metaInfo: {
        title: "Direct Partners"
    },
    layout: UserLayout,
    props: {
        team: Object
    },
    data() {
        return {
            partners: [],
            pageUrls: {
                prev: null,
                next: null,
                current: null
            }
        };
    },
    methods: {

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
        axios.get(route("team.active.direct.list")).then(res => {
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
