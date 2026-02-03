<template>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="element-list transfer-list p-0">
                <div class="title mb-3 ">
                    <h2>Direct Referrals </h2>
                </div>
                <div v-if="!partners.length" class="row">
                    <div class="col-12 text-center">
                        <p>There is no subscriptions</p>
                    </div>
                </div>
                <ul v-for="(partner,index) in partners" :key="partner.id" class="mb-3">
                    <li class="w-100">
                        <div class="transfer-box">


                            <div class="transfer-details">
                                <div>
                                    <h5 class="fw-semibold dark-text">{{ partner.user.name }}</h5>

                                    <h6 class="fw-normal success-color mt-2">{{ partner.user.username }}</h6>
                                </div>

                                <div>
                                    <h5 class="fw-semibold dark-text">Total Business</h5>

                                    <h6 class="fw-normal success-color mt-2">{{ currencySymbol }} {{ partner.user.user_business?partner.user.user_business.amount_display:'0.00' }}
                                    </h6>
                                </div>
                                <div>
                                    <h5 v-if="!partner.user.subscriptions.length " class="fw-semibold error-color ">Not
                                        Active</h5>
                                    <h5 v-else class="fw-semibold success-color">
                                        Active
                                    </h5>

                                    <!--                                    <h6 class="fw-normal light-text mt-2">{{ partner.user.username }}</h6>-->
                                </div>

                                <div class="dropdown">
                                    <a aria-expanded="false" class="" data-bs-toggle="dropdown" href="#" role="button">
                                        <VueFeather type="eye"></VueFeather>
                                    </a>

                                    <ul class="dropdown-menu" style="">
                                        <li><h5 class="dropdown-item w-100">Full Name: {{ partner.user.name }}</h5></li>
                                        <li><h5 class="dropdown-item w-100">Mobile No: {{ partner.user.mobile }}</h5>
                                        </li>
                                        <li><h5 class="dropdown-item w-100">Joined On: {{
                                                partner.user.created_at
                                            }}</h5></li>
                                        <li v-if="partner.user.subscriptions.length"><h5 class="dropdown-item w-100">
                                            Active On: {{ partner.user.subscriptions[0].created_at }}</h5></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section-b-space">
        <div class="custom-container">
            <ul class="pagination justify-content-center">
                <li :class="[!pageUrls.prev ? 'disabled':'']" class="page-item disabled">
                    <a class="page-link" href="javascript:void(0)" @click="goPrev(pageUrls.prev)">Previous</a>
                </li>
                <li class="page-item"><a class="page-link active" href="javascript:void(0)">{{ pageUrls.current }}</a>
                </li>
                <li :class="[!pageUrls.next ? 'disabled':'']" class="page-item">
                    <a class="page-link" href="javascript:void(0)" @click="goNext(pageUrls.next)">Next</a>
                </li>
            </ul>
        </div>
    </section>

</template>

<script>

import TeamWidget from "@/components/xino/TeamWidget";
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import VueFeather from "vue-feather";
import { usePage } from "@inertiajs/vue3"
import { computed } from "vue"

export default {
    name: "Direct",
    components: {VueFeather, TeamWidget},
    metaInfo: {
        title: "Direct Partners"
    },
    layout: UserLayout,
    props: {
        team: Object
    },
    setup(){
        const page = usePage()

        const currencySymbol = computed(() => {
            return page.props.currency?.symbol ?? "₹"
        })

        return {currencySymbol}
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
        axios.get(route("team.direct.list")).then(res => {
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
.transfer-list li .transfer-box .transfer-details .dropdown .dropdown-menu.show {
    margin-top: 5px;
    background-color: #622cfd;
    color: #fff;
}

.transfer-list li .transfer-box .transfer-details {
    width: 100% !important;

}
</style>
