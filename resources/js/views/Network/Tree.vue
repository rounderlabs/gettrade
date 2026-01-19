<template>
    <main class="main mainheight" style="min-height: 621.039px; margin-top: 60.2109px; padding-bottom: 200px;">
    <div class="container-fluid mb-4">
    <div class="container bg-radial-gradient  h-100 mt-4 py-4">
    <div class="col-span-12 ">
        <div class="row">
            <h4 class="mb-3">
                <span class="text-white">Team Structure</span>
            </h4>
        </div>
        <div class="intro-y overflow-auto lg:overflow-visible mt-5">
            <table class="table  sm:mt-2">
                <tbody>
                <tr class="intro-x">
                    <td colspan="9">
                        <div class="text-center">
                            <p>
                                <button class="btn btn-theme btn-md mr-2" @click="visit(getLeftTree)">
                                    <ArrowLeftIcon size="1.5x"></ArrowLeftIcon>
                                </button>

                                <button class="btn btn-theme btn-md" @click="visit(route('team.genealogy'))">
                                    Root
                                </button>

                                <button class="btn btn-theme btn-md" @click="visit(getRightTree)">
                                    <ArrowRightIcon size="1.5x"></ArrowRightIcon>
                                </button>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr class="intro-x">
                    <td colspan="9">
                        <div class="text-center justify-center">
                            <div class="flex justify-center">
                                <img :id="id" :src="getIcon(tree.root)" class="invert" style="min-width: 40px"
                                     width="40">
                            </div>
                            <div class="mt-2">
                                {{ tree.root.name }}
                            </div>
                            <div class="mt-2 mb-2">
                                {{ tree.root.username }}
                            </div>
                            <div class="text-center">
                                <a v-if="tree" href="javascript:" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalId" @click="setSelectedItem(tree.root)"><i class="bi bi-info-circle"></i></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="intro-x">
                    <td v-for="first in getFirstLevel" colspan="3">
                        <div
                            class="text-center">
                            <div class="flex justify-center">
                                <img :id="id" :src="first ? getIcon(first.user) : getBlankImg()"
                                     class="cursor-pointer invert"
                                     style="min-width: 40px" width="40" @click="visitDownline(first)">
                            </div>
                            <div>
                            </div>
                            <div class="mt-2">
                                {{ first ? first.user.name : '-----' }}
                            </div>
                            <div class="mt-2 mb-2">
                                {{ first ? first.user.username : '-----' }}
                            </div>
                            <a v-if="tree" href="javascript:" class="btn btn-sm btn-outline-secondary"
                               data-bs-toggle="modal" data-bs-target="#modalId" @click="setSelectedItem(first.user)">
                                <i class="bi bi-info-circle"></i></a>

                        </div>
                    </td>

                </tr>
                <tr class="intro-x">
                    <td v-for="second in getSecondLevel">
                        <div
                            class="text-center">
                            <div class="flex justify-center">
                                <img :id="id" :src="second ? getIcon(second.user) : getBlankImg()"
                                     class="cursor-pointer invert"
                                     style="min-width: 40px"
                                     width="40" @click="visitDownline(second)">
                            </div>
                            <div class="mt-2">
                                {{ second ? second.user.name : '-----' }}
                            </div>
                            <div class="mt-2 mb-2">
                                {{ second ? second.user.username : '-----' }}
                            </div>

                            <a v-if="tree" href="javascript:" class="btn btn-sm btn-outline-secondary"
                               data-bs-toggle="modal" data-bs-target="#modalId" @click="setSelectedItem(second.user)">
                                <i class="bi bi-info-circle"></i></a>

                        </div>
                    </td>

                </tr>
                </tbody>
            </table>
            <!-- modal-->
            <div id="modalId" aria-hidden="true" class="modal " tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <a data-dismiss="modal" href="javascript:">
                            <i class="w-8 h-8 text-gray-500" data-feather="x"></i>
                        </a>
                        <div class="modal-body p-0">
                            <div class="text-center">
                                <i class="w-16 h-16 text-theme-20 mx-auto mt-3" data-feather="check-circle"></i>
                                <div class="mt-2">ID {{ selectedItem.username }}</div>

                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table table-hover table-striped table-dashboard-two table-bordered mb-0">
                                    <tbody>
                                    <tr class="bg-gray-200 dark:bg-dark-1">
                                        <td class="border-b dark:border-dark-5">User Name</td>
                                        <td class="border-b dark:border-dark-5">{{ selectedItem.name }}</td>
                                        <td class="border-b dark:border-dark-5">Total Business</td>
                                        <td class="border-b dark:border-dark-5">$.
                                            {{ selectedItem.user_business ? selectedItem.user_business.usd : '0' }}
                                        </td>
                                    </tr>

                                    <tr class="bg-gray-200 dark:bg-dark-1">
                                        <td class="border-b dark:border-dark-5">Direct Team</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ selectedItem.team ? selectedItem.team.direct : '0' }}
                                        </td>
                                        <td class="border-b dark:border-dark-5">Active Direct Team</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ selectedItem.team ? selectedItem.team.active_direct : '0' }}
                                        </td>
                                    </tr>

                                    <tr class="bg-gray-200 dark:bg-dark-1">
                                        <td class="border-b dark:border-dark-5">Total Team</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ selectedItem.team ? selectedItem.team.total : '0' }}
                                        </td>
                                        <td class="border-b dark:border-dark-5">Active Team</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ selectedItem.team ? selectedItem.team.active_total : '0' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border-b dark:border-dark-5 text-center" colspan="4">Subscriptions
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-b dark:border-dark-5 text-center" colspan="2">Amount
                                        </td>
                                        <td class="border-b dark:border-dark-5 text-center" colspan="2">Earned so far
                                        </td>

                                    </tr>
                                    <tr v-for="subs in selectedItem.subscriptions" class="hover:bg-gray-900">
                                        <td class="border-b dark:border-dark-5 text-center" colspan="2">$
                                            {{ subs.amount_in_usd }}
                                        </td>
                                        <td class="border-b dark:border-dark-5 text-center" colspan="2">$
                                            {{ subs.earned_so_far }}
                                        </td>

                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                            <div class="px-5 pt-2 pb-2 text-center">
                                <button class="btn btn-primary w-24" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal" type="button">Exit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal-->
        </div>
    </div>
    </div>
    </div>
    </main>






<!--    <div class="modal fade" id="edittable" tabindex="-1" aria-labelledby="edittableLabel" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">-->
<!--        <div class="modal-dialog modal-xl">-->
<!--            <div class="modal-content">-->
<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title" id="edittableLabel">Edit Multiline table grid</h5>-->
<!--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--                </div>-->
<!--                <div class="modal-body">-->
<!--                    <table class="table">-->
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th>Transaction</th>-->
<!--                            <th>Currency Amount</th>-->
<!--                            <th>Category</th>-->
<!--                            <th>Status</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
<!--                        <tbody>-->
<!--                        <tr>-->
<!--                            <td>-->
<!--                                <div class="row align-items-center">-->
<!--                                    <div class="col-auto">-->
<!--                                        <figure class="avatar avatar-40 mb-0 coverimg rounded-circle" style="background-image: url(&quot;assets/img/company6.png&quot;);">-->
<!--                                            <img src="assets/img/company6.png" alt="" style="display: none;">-->
<!--                                        </figure>-->
<!--                                    </div>-->
<!--                                    <div class="col ps-0">-->
<!--                                        <p class="mb-0">BitCoins</p>-->
<!--                                        <p class="text-secondary small">1 way trip</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                            <td>-->
<!--                                <p class="mb-0">0.000015</p>-->
<!--                                <p class="text-secondary small">BTC</p>-->
<!--                            </td>-->
<!--                            <td>-->
<!--                                <p class="mb-0">Traveling</p>-->
<!--                                <p class="text-secondary small">Work</p>-->
<!--                            </td>-->
<!--                            <td>-->
<!--                                <span class="badge badge-sm bg-green">Paid</span>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                    <div class="row justify-content-center">-->
<!--                        <div class="col-12 col-lg-7 col-xl-7 mb-4 mb-md-0">-->
<!--                            <h6 class="title">Basic Information</h6>-->
<!--                            <div class="row">-->
<!--                                <div class="col-12 col-md-6 mb-2">-->
<!--                                    <div class="form-group mb-3 position-relative check-valid">-->
<!--                                        <div class="input-group input-group-lg">-->
<!--                                            <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>-->
<!--                                            <div class="form-floating">-->
<!--                                                <input type="text" placeholder="First Name" value="Max" required="" class="form-control border-start-0">-->
<!--                                                <label>First Name</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                                </div>-->
<!--                                <div class="col-12 col-md-6 mb-2">-->
<!--                                    <div class="form-group mb-3 position-relative check-valid">-->
<!--                                        <div class="input-group input-group-lg">-->
<!--                                            <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>-->
<!--                                            <div class="form-floating">-->
<!--                                                <input type="text" placeholder="Last Name" value="Johnson" required="" class="form-control border-start-0">-->
<!--                                                <label>last Name</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <h6 class="title">Delivery Address</h6>-->
<!--                            <div class="mb-2">-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-geo-alt"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="text" placeholder="Address Line 1" required="" class="form-control border-start-0">-->
<!--                                            <label>Address line 1</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                            </div>-->

<!--                            <div class="mb-2">-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-geo-alt"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="text" placeholder="Address Line 2" class="form-control border-start-0">-->
<!--                                            <label>Address line 2</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                            </div>-->

<!--                            <div class="row">-->
<!--                                <div class="col-12 col-md-6 mb-2">-->
<!--                                    <div class="form-group mb-3 position-relative check-valid">-->
<!--                                        <div class="input-group input-group-lg">-->
<!--                                            <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-globe"></i></span>-->
<!--                                            <div class="form-floating">-->
<!--                                                <select class="form-select border-0" id="country1" required="">-->
<!--                                                    <option value="">Choose...</option>-->
<!--                                                    <option>United States</option>-->
<!--                                                </select>-->
<!--                                                <label for="country1">Country</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                                </div>-->
<!--                                <div class="col-12 col-md-6 mb-2">-->
<!--                                    <div class="form-group mb-3 position-relative check-valid">-->
<!--                                        <div class="input-group input-group-lg">-->
<!--                                            <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-globe"></i></span>-->
<!--                                            <div class="form-floating">-->
<!--                                                <select class="form-select border-0" id="state1" required="">-->
<!--                                                    <option value="">Choose...</option>-->
<!--                                                    <option>California</option>-->
<!--                                                </select>-->
<!--                                                <label for="state1">State</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <h6 class="title">Crypto Wallet Details</h6>-->
<!--                            <div>-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-currency-bitcoin"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="text" placeholder="Wallet ID" value="" required="" class="form-control border-start-0">-->
<!--                                            <label>Wallet ID</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Please enter valid input</div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-12 col-lg-5 col-xl-5">-->
<!--                            <h6 class="title">Delivery Status</h6>-->
<!--                            <div class="mb-2">-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-box"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <select class="form-select border-0" id="country1" required="">-->
<!--                                                <option value="">Choose...</option>-->
<!--                                                <option>Ready to Pickup</option>-->
<!--                                                <option>Picked up</option>-->
<!--                                                <option>In transit</option>-->
<!--                                                <option>Near to Location</option>-->
<!--                                                <option>Delivered</option>-->
<!--                                            </select>-->
<!--                                            <label for="country1">Delivery Status</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                            </div>-->
<!--                            <h6 class="title">Payment Details</h6>-->
<!--                            <div class="mb-2">-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="text" placeholder="Card Holder Name" value="Max Johnson" required="" class="form-control border-start-0">-->
<!--                                            <label>Card Holder Name</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Add valid data </div>-->
<!--                            </div>-->
<!--                            <div>-->
<!--                                <div class="form-group mb-3 position-relative check-valid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-credit-card"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="text" placeholder="Card Number" value="" required="" class="form-control border-start-0">-->
<!--                                            <label>Card Number</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Please enter valid input</div>-->
<!--                            </div>-->
<!--                            <div class="mb-4">-->
<!--                                <div class="form-group mb-2 position-relative check-valid is-invalid">-->
<!--                                    <div class="input-group input-group-lg">-->
<!--                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-calendar-event"></i></span>-->
<!--                                        <div class="form-floating">-->
<!--                                            <input type="number" placeholder="Expiry Date" value="" required="" class="form-control border-start-0">-->
<!--                                            <label>Expiry Date</label>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="invalid-feedback mb-3">Please enter valid input</div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="modal-footer">-->
<!--                    <div class="col col-lg-auto order-1 ">-->
<!--                        <button type="button" class="btn btn-theme">Save changes</button>-->
<!--                    </div>-->
<!--                    <div class="col-12 col-lg order-3 order-lg-2">-->
<!--                        <p class="text-secondary">By providing multiline data in table cell you are actually saving column to make it more spacious and easy to read data. Overall it improves user experience.</p>-->
<!--                    </div>-->
<!--                    <div class="col-auto col-lg-auto order-2 order-lg-3">-->
<!--                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->


</template>
<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";

export default {
    name: "Tree",
    components: {},
    layout: UserLayout,
    props: {
        tree: Object
    },
    data() {
        return {
            selectedItem: {},
            sponsor: null,
            id: null,
            rootPath: route().params

        }
    },
    methods: {
        setSelectedItem(item) {
            this.selectedItem = item;
        },
        showTree() {
            if (this.level && !this.isRoot) {
                this.$inertia.visit(route('team.geneaology', [this.level.user.id]), {
                        preserveScroll: true
                    }
                )
            }
        },
        show() {
            if (this.id) {
                $('#' + this.id).popover('toggle')
            }
        },
        getPaidImg(){
            return '/assets/dist/tree/paid-user.png'
        },

        getBlankImg() {
            return '/assets/dist/tree/blank-user.png'
        },
        getInactiveImg() {
            return '/assets/dist/tree/unpaid-user.png'
        },
        getIcon(user) {
            if (!user) {
                return this.getBlankImg()
            } else if (user && user.subscriptions) {
                return this.getPaidImg()
            }

            else {
                return this.getInactiveImg()
            }
        },
        visit(url) {
            this.$inertia.visit(url)
        },
        visitDownline(treeLevel) {

            if (treeLevel && treeLevel.user) {
                this.$inertia.visit(route('team.genealogy.sub', [treeLevel.user.id, 1]))
            } else {
                return false
            }
        },
    },
    computed: {
        getFirstLevel() {
            return this.tree.firstLevel
        },
        getSecondLevel() {
            return this.tree.secondLevel
        },
        getRightTree() {
            if (this.rootPath.user) {
                return route('team.genealogy.sub', [this.rootPath.user, parseInt(this.rootPath.position) + 1])
            } else {
                return route('team.genealogy.sub', [this.$page.auth.user.id, 2])
            }
        },
        getLeftTree() {
            if (this.rootPath.user) {
                if (parseInt(this.rootPath.position) === 1) {
                    return route('team.genealogy.sub', [this.rootPath.user, 1])
                } else {
                    return route('team.genealogy.sub', [this.rootPath.user, parseInt(this.rootPath.position) - 1])
                }

            } else {
                return route('team.genealogy.sub', [this.rootPath.user, 1])
            }
        }
    },
    mounted() {

    },
}
</script>

<style scoped>
.badge {
    font-size: 15px;
}
.table > :not(caption) > * > *{
    border-bottom-width: 0px
}
</style>
