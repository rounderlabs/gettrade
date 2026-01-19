<template>
    <div class="row">
        <div class="col-lg-4">
            <div class="card bg-radial-gradient border-0 mb-4 mg-b-20">
                <div class="card-body">
                    <div class="main-content-label tx-13 mg-b-25">
                        Withdraw Accounts
                    </div>
                    <div class="main-profile-contact-list">
                        <div class="media">
                            <div class="media-icon bg-primary-transparent text-primary">
                                <i class="icon ion-md-phone-portrait"></i>
                            </div>
                            <div class="media-body">
                                <div>
                                    <a href="javascript:" @click="visitPage(route('withdraw.wallet.usdt'))"
                                       class="flex items-center"
                                       :class="[route('withdraw.wallet.usdt') === activeLink ? 'text-theme-1 dark:text-theme-10 font-medium':'']">
                                        <activity-icon size="1.5x" class="w-4 h-4 mr-2"></activity-icon>
                                        BEP20 USDT
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- main-profile-contact-list -->
                </div>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>

export default {
    name: "WithdrawAccountLayout",
    // components: {
    //     UserIcon, LockIcon, CreditCardIcon, ShieldIcon
    // },
    data() {
        return {
            activeLink: null
        }
    },
    methods: {
        visitPage(link) {
            this.activeLink = link

            this.$inertia.visit(link, {
                preserveScroll: true
            })
        }
    },
    mounted() {
        this.activeLink = route(route().current())
    }


}
</script>

<style scoped>

</style>
