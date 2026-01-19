<template>
    <!-- Admin-header -->
    <div class="main-header sticky side-header nav nav-item">
        <div class="container-fluid">
            <div class="main-header-left ">
                <div class="app-sidebar__toggle mobile-toggle" data-toggle="sidebar">
                    <a class="open-toggle" href="#"><i class="ti-menu bg-transparent tx-20"></i></a>
                    <a class="close-toggle" href="#"><i class="ti-close bg-transparent tx-20"></i></a>
                </div>
            </div>
            <div class="main-header-center">
                <div class="responsive-logo">
                    <a :href="route('admin.dashboard')"><img alt="logo" class="mobile-logo"
                                                             src="/img/brand/smartoken-sun-logo.png"></a>
                </div>
            </div>
            <div class="main-header-right">
                <div class="nav nav-item  navbar-nav-right ml-auto">
                    <div class="nav-item full-screen fullscreen-button">
                        <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
                    </div>
                    <button aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation"
                            class="navbar-toggler navresponsive-toggler d-sm-none"
                            data-target="#navbarSupportedContent-4" data-toggle="collapse"
                            type="button">
                        <span class="navbar-toggler-icon fe fe-more-vertical "></span>
                    </button>
                    <div class="dropdown main-profile-menu nav nav-item nav-link">
                        <a class="profile-user" href=""><img alt="" src="/img/faces/5.jpg"></a>
                        <div class="dropdown-menu dropdown-menu-arrow animated fadeInUp">
                            <div class="main-header-profile header-img">
                                <div class="main-img-user"><img alt="" src="/img/faces/5.jpg"></div>
                                <h6>{{ $page.props.auth.user.name }}</h6><span>{{ $page.props.auth.user.email }}</span>
                            </div>
                            <!--                            <inertia-link class="dropdown-item" :href="route('account.profile')"><i class="far fa-user"></i> My Profile</inertia-link>-->
                            <inertia-link :href="route('admin.change.password')" class="dropdown-item"><i
                                class="far fa-edit"></i> Change Password
                            </inertia-link>
                            <a :href="route('admin.logout')" class="dropdown-item"><i class="fas fa-sign-out-alt"></i>
                                Sign Out</a>
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
    <!-- Admin-header -->
</template>

<script>
export default {
    name: "AdminHeader"
}
</script>

<style scoped>

</style>
