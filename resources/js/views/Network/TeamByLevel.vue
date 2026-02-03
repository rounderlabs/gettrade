<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="element-list transfer-list p-0">
                <div class="row">
                    <div class="title mb-3 ">
                        <h2>My All Down Line Teams </h2>
                    </div>
                        <div class="form-group mb-5 mt-1">
                            <label for="level" class="form-label dark-text">Select Level</label>
                            <div class="form-input">
                                <select id="level" v-model="selectedLevel" class="form-control"
                                        name="level" @change="loadLevel">
                                    <option>Select Level</option>
                                    <option v-for="level in allLevel" :value="level.level">Level {{ level.level }}
                                    </option>
                                </select>

                            </div>
                        </div>
                </div>
                <div v-if="!partners.length" class="row">
                    <div class="col-12 text-center">
                        <p>There is no member on this level</p>
                    </div>
                </div>
                <ul v-for="(partner,index) in partners" :key="partner.id" class="mb-3">
                    <li class="w-100">
                        <div class="transfer-box">

                            <div class="transfer-details">
                                <div>
                                    <h5 class="fw-semibold dark-text">{{ partner.downline_user.name }}</h5>
                                    <h6 class="fw-normal success-color mt-2">{{ partner.downline_user.username }}</h6>
                                </div>
                                <div>
                                    <h5 class="fw-semibold dark-text">Package</h5>
                                    <h5 v-if="!partner.downline_user.subscriptions.length " class="fw-semibold error-color mt-2">Not
                                        Active</h5>
                                    <h5 v-else class="fw-semibold success-color mt-2"> {{ currencySymbol }} {{ partner.downline_user.subscription_display }}</h5>
                                </div>
                                <div>
                                    <h5 class="fw-semibold dark-text">Total Business </h5>
                                    <h5 class="fw-semibold dark-text mt-2">{{ currencySymbol }} {{ partner.downline_user.business_display }}
                                    </h5>
                                </div>
                                <div>
                                    <h5 class="fw-semibold dark-text">Joined On </h5>
                                    <h5 class="fw-semibold dark-text mt-2">{{ formatDate(partner.downline_user.created_at) }}</h5>
                                </div>

                                <div>
                                    <h5 class="fw-semibold dark-text">Activated On </h5>
                                    <h5 v-if="partner.downline_user.subscriptions.length" class="fw-semibold dark-text mt-2"> {{ formatDate(partner.downline_user.subscriptions[0].created_at) }}</h5>
                                    <h5 v-else class="fw-semibold dark-text mt-2">--</h5>
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
                    <a class="page-link" href="javascript:void(0)" @click="goPrev(pageUrls.prev)">Prev</a>
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
    name: "TeamByLevel",
    components: {VueFeather, TeamWidget},
    metaInfo: {
        title: "Team By Level"
    },
    layout: UserLayout,
    props: {
        team: Object,
        allLevel: Array,
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

        formatDate(date) {
            if (!date) return '--'
            return new Date(date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        },

        loadLevel(selectedLevel) {
            axios.get(route("team.by.level.list", [this.selectedLevel, 1])).then(res => {
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
        axios.get(route("team.by.level.list")).then(res => {
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
}


.transfer-list li .transfer-box .transfer-details {
    width: 100% !important;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

.transfer-list li .transfer-box {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    gap: 10px;
    width: 100%;
    padding: 15px;
    border: 1px solid rgba(var(--box-bg), 0.15);
    -webkit-box-shadow: 0px 4px 22px rgba(var(-dark-text), 0.04);
    box-shadow: 0px 4px 22px rgba(var(-dark-text), 0.04);
    border-radius: 10px;
    cursor: pointer;
}

h5 {
    font-size: 8px;
    line-height: 1.2;
    margin-bottom: 0;
}
</style>
