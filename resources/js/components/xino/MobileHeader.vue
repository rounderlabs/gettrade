<template>
    <!-- mobile-header -->
    <div class="responsive main-header">
        <div
            class="mb-1 navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark d-sm-none ">
            <div id="navbarSupportedContent-4" class="collapse navbar-collapse">
                <div class="d-flex order-lg-2 ml-auto">
                    <div class="dropdown main-profile-menu nav nav-item nav-link">
                        <a class="profile-user" href=""><img alt="" src="/img/faces/5.jpg"></a>
                        <div class="dropdown-menu dropdown-menu-arrow animated fadeInUp">
                            <div class="main-header-profile header-img">
                                <div class="main-img-user"><img alt="" src="/img/faces/5.jpg"></div>
                                <h6>{{ $page.props.auth.user.username }}</h6><span>{{
                                    $page.props.auth.user.email
                                }}</span>
                            </div>
                            <inertia-link :href="route('account.profile')" class="dropdown-item"><i
                                class="far fa-user"></i> My Profile
                            </inertia-link>
                            <inertia-link :href="route('account.change.password')" class="dropdown-item"><i
                                class="far fa-edit"></i> Change Password
                            </inertia-link>
                            <a :href="route('logout')" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Sign
                                Out</a>
                        </div>
                    </div>
                    <!--                    <div class="dropdown main-header-message right-toggle">-->
                    <!--                        <a class="nav-link " data-toggle="sidebar-right" data-target=".sidebar-right">-->
                    <!--                            <i class="ti-menu tx-20 bg-transparent"></i>-->
                    <!--                        </a>-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <!-- mobile-header -->
</template>

<script>
export default {
    name: "MobileHeader"
}
</script>

<style scoped>

</style>
