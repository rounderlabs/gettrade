<template>
    <!-- Header -->
    <header class="header">
        <!-- Fixed navbar -->
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <button class="btn btn-link btn-square menu-btn me-2" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="header-title align-self-center">

                        <img src="/assets/img/AdminLTEFullLogo.png" style="width:40px" alt="" />

                </div>

                <div class="ms-auto">
                    <div class="row">
                        <div class="col-auto align-self-center">
                            <div class="dropdown">
                                <a class="dd-arrow-none dropdown-toggle" id="userprofiledd" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                    <div class="row">
                                        <div class="col-auto align-self-center">
                                            <figure class="avatar avatar-30 rounded-circle coverimg vm">
                                                <img src="/assets/img/avatar.png" style="width:80px"  alt="" id="userphotoonboarding2" />
                                            </figure>
                                        </div>
                                        <div class="col ps-0 align-self-center d-none d-lg-block">
                                            <p class="mb-0">
                                                <span class="text-dark username">{{ $page.props.auth.user.name }}</span><br>
                                                <small class="small">User ID {{ $page.props.auth.user.username }}</small>
                                            </p>
                                        </div>
                                        <div class="col ps-0 align-self-center d-none d-lg-block">
                                            <i class="bi bi-chevron-down small vm"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end w-250" aria-labelledby="userprofiledd">
                                    <div class="dropdown-info bg-radial-gradient-theme">
                                        <div class="row">
                                            <div class="col-auto">
                                                <figure class="avatar avatar-50 rounded-circle coverimg vm">
                                                    <img src="/assets/img/avatar.png" alt="" id="userphotoonboarding3" />
                                                </figure>
                                            </div>
                                            <div class="col align-self-center ps-0">
                                                <h6 class="mb-0"><span class="username">{{ $page.props.auth.user.name }}</span></h6>

                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <Link class="dropdown-item" :href="route('account.profile')">My Profile</Link>
                                    </div>
                                    <div>
                                        <Link class="dropdown-item" :href="route('account.change.password')">Change Password</Link>
                                    </div>
                                    <div>
                                        <Link class="dropdown-item" :href="route('account.setting')">Account Settings</Link>
                                    </div>

                                    <div><a class="dropdown-item" href="/logout">Logout</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Header ends -->
</template>

<script>
import {onMounted} from "vue";
import { Link } from "@inertiajs/vue3";

export default {
    name: "MainAdminHeaderComponent",
    components: {
        Link
    },
    setup() {
        onMounted(() => {})

    }
}
</script>

<style scoped>

</style>
