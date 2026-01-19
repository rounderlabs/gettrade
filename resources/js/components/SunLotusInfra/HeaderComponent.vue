<template>
    <!-- header start -->
    <header class="section-t-space">
        <div class="custom-container">
            <div class="header-panel">
                <a class="sidebar-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft">
<!--                    <i class="menu-icon" data-feather="menu"></i>-->
                    <vue-feather type="menu" class="menu-icon"></vue-feather>
                </a>
                <img class="img-fluid logo" src="/shuchack/assets-panel/assets/images/shuchak-logo.png" alt="logo" />

                <a href="/logout" class="notification">
                    <vue-feather type="lock" class="menu-icon"></vue-feather>
<!--                    <i class="notification-icon" data-feather="bell"></i>-->
                </a>
            </div>
        </div>
    </header>


    <!-- header end -->

    <!-- Header -->
<!--    <header class="header theme-blue bg-radial-gradient">-->
<!--        &lt;!&ndash; Fixed navbar &ndash;&gt;-->
<!--        <nav class="navbar fixed-top">-->
<!--            <div class="container-fluid">-->
<!--&lt;!&ndash;                <button class="btn btn-link btn-square menu-btn me-2" type="button">&ndash;&gt;-->
<!--&lt;!&ndash;                    <i class="bi bi-list fs-4"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                </button>&ndash;&gt;-->
<!--                <div class="header-title align-self-center">-->

<!--                    <img alt="Soluminity" src="/panel-assets/img/soluminity.png" style="height:40px" />-->

<!--                </div>-->
<!--                <div class="ms-auto">-->
<!--                    <div class="row">-->
<!--                        <div class="col-auto align-self-center">-->
<!--                            <div class="dropdown">-->
<!--                                <a id="userprofiledd" aria-expanded="false" class="dd-arrow-none dropdown-toggle"-->
<!--                                   data-bs-toggle="dropdown" role="button">-->
<!--                                    <div class="row">-->
<!--                                        <div class="col-auto align-self-center">-->
<!--                                            <figure v-if="$page.props.auth.user.profile_picture!== 'null'" class="avatar avatar-50 fs-5" style="font-size:30px !important;">-->
<!--&lt;!&ndash;                                                <img id="userphotoonboarding2" alt="" :src="'/storage/'+$page.props.auth.user.profile_picture" />&ndash;&gt;-->
<!--                                                <i class="icon bi bi-person-circle"></i>-->
<!--                                            </figure>-->
<!--                                            <figure v-else class="avatar avatar-40 rounded-circle coverimg vm">-->
<!--                                                <i class="icon bi bi-person-circle"></i>-->
<!--                                            </figure>-->
<!--                                        </div>-->
<!--                                        <div class="col ps-0 align-self-center d-none d-lg-block">-->
<!--                                            <p class="mb-0">-->
<!--                                                <span class="text-dark username">{{ $page.props.auth.user.name }}</span><br>-->
<!--                                                <small class="small">User ID {{ $page.props.auth.user.username-->
<!--                                                    }}</small>-->
<!--                                            </p>-->
<!--                                        </div>-->
<!--                                        <div class="col ps-0 align-self-center d-none d-lg-block">-->
<!--                                            <i class="bi bi-chevron-down small vm"></i>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </a>-->
<!--                                <div aria-labelledby="userprofiledd" class="dropdown-menu dropdown-menu-end w-250">-->
<!--                                    <div class="dropdown-info bg-radial-gradient">-->
<!--                                        <div class="row">-->
<!--                                            <div class="col-auto">-->
<!--                                                <figure v-if="$page.props.auth.user.profile_picture!== 'null'"-->
<!--                                                        class="avatar avatar-50 rounded-circle coverimg vm">-->
<!--                                                    <img id="userphotoonboarding3" :src="'/storage/'+$page.props.auth.user.profile_picture"-->
<!--                                                         alt="" />-->
<!--                                                </figure>-->
<!--                                                <figure v-else-->
<!--                                                        class="avatar avatar-50 rounded-circle coverimg vm">-->
<!--                                                    <img id="userphotoonboarding2" alt=""-->
<!--                                                         src="/panel-assets/img/user-1.jpg" />-->
<!--                                                </figure>-->
<!--                                            </div>-->
<!--                                            <div class="col align-self-center ps-0">-->
<!--                                                <h6 class="mb-0"><span class="username">{{ $page.props.auth.user.name-->
<!--                                                    }}</span></h6>-->

<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div>-->
<!--                                        <Link :href="route('account.profile')" class="dropdown-item">My Profile</Link>-->
<!--                                    </div>-->
<!--                                    <div>-->
<!--                                        <Link :href="route('account.change.password')" class="dropdown-item">Change-->
<!--                                            Password-->
<!--                                        </Link>-->
<!--                                    </div>-->
<!--                                    <div>-->
<!--                                        <Link :href="route('account.setting')" class="dropdown-item">Account Settings-->
<!--                                        </Link>-->
<!--                                    </div>-->

<!--                                    <div>-->
<!--                                        <Link :href="route('refund.show')" class="dropdown-item">Cancel Subscription-->
<!--                                        </Link>-->
<!--                                    </div>-->

<!--                                    <div><a class="dropdown-item" href="/logout">Logout</a></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </nav>-->
<!--    </header>-->
    <!-- Header ends -->
</template>

<script>
import { onMounted } from "vue";
import { Link } from "@inertiajs/vue3";


export default {
    name: "HeaderComponent",
    components: {

        Link
    },
    setup() {
        onMounted(() => {


        });

    }
};
</script>

<style scoped>
.header-panel .logo {
    position: absolute;
    width: 75px;
     height: 75px;
    left: 50%;
    -webkit-transform: translate(-50%);
    transform: translate(-50%);
}

.header-panel .notification {
    width: 45px ;
}
</style>
