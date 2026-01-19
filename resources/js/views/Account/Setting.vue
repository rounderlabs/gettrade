<template>
    <section>
        <div v-if="subscription" class="custom-container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 theme-red mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6 col-xl-5 text-center">
                                    <div class="h-200 overflow-hidden rounded mb-3 position-relative z-index-0">
                                        <figure v-if="$page.props.auth.user.profile_picture !== 'null'"
                                                class="avatar avatar-100 coverimg rounded-circle mb-3 mx-auto">
                                            <img :src="'/storage/'+$page.props.auth.user.profile_picture"
                                                 alt="" class="ususerphotoonboarding">
                                        </figure>
                                        <figure v-else class="avatar avatar-100 coverimg rounded-circle mb-3 mx-auto">
                                            <img alt="" class="ususerphotoonboarding"
                                                 src="/shuchack/panel-assets/img/user-1.jpg">
                                        </figure>
                                        <div class="row text-center">
                                            <Link :href="route('account.profile.picture')" class="btn btn-theme w-100">
                                                Change or Update Profile Picture
                                            </Link>
                                        </div>

                                    </div>
                                    <h5 class="text-truncate mb-1">{{ profile.full_name }}</h5>
                                    <p class="text-secondary mb-3">{{ profile.email }}</p>
                                    <span class="tag bg-yellow">Referral Code : {{
                                            $page.props.auth.user.username
                                        }}</span>

                                </div>
                                <div class="col-12 col-md-6 col-lg-6 col-xl-7 border-left-dashed">
                                    <div class="row align-items-center mb-2">
                                        <div class="col">
                                            <h6 class="title">Account Details:</h6>
                                        </div>
                                        <div class="col-auto">
                                            <div class="dropdown d-inline-block">
                                                <a aria-expanded="false" class="btn btn-link dd-arrow-none"
                                                   data-bs-display="static" data-bs-toggle="dropdown" role="button">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <Link :href="route('account.change.password')"
                                                              class="dropdown-item">Change Password
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <Link :href="route('account.setting')" class="dropdown-item">
                                                            Update Profile
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class="list-group-item text-secondary">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div class="col-auto ps-0">
                                                    {{ profile.full_name }}
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item text-secondary">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                                <div class="col-auto ps-0">
                                                    {{ profile.email }}
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item text-secondary">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                                <div class="col-auto ps-0">
                                                    {{ profile.mobile }}
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item text-secondary">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <div class="col-auto ps-0">
                                                    {{ $page.props.auth.user.username }}
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item text-secondary">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <i class="bi bi-globe"></i>
                                                </div>
                                                <div class="col-auto ps-0">
                                                    {{ profile.country.name }}
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="card border-0 mb-4 theme-blue bg-radial-gradient text-white">
                                                <div class="card-body bg-none">
                                                    <div class="row gx-2 align-items-center">
                                                        <div class="col-auto">
                                                            <div
                                                                class="avatar avatar-40 h5 bg-light-white rounded-circle">
                                                                <i class="bi bi-calendar2-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <p class="text-muted small mb-1">Joining</p>
                                                            <h6><small>{{ $page.props.auth.user.created_at }}</small>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card border-0 mb-4 theme-green bg-radial-gradient text-white">
                                                <div class="card-body bg-none">
                                                    <div class="row gx-2 align-items-center">
                                                        <div class="col-auto">
                                                            <div
                                                                class="avatar avatar-40 h5 bg-light-white text-white rounded-circle">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <p class="text-muted small mb-1">Sponsor</p>
                                                            <h6>
                                                                <small>{{
                                                                        profile.sponsor_name ? profile.sponsor_name : 'Top ID'
                                                                    }}</small>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div v-else class="container">
            <div id="walletForm" class="row mb-100">

                <div class="col-12 col-md-6 col-lg-6 col-xxl-6 mb-4 ">
                    <!-- Inventory card -->
                    <div class="card border-0 bg-gradient-theme-light theme-red h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="fw-medium">
                                        <i class="bi bi-lock-fill h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                                        Update Profile
                                    </h6>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12  mb-4 mb-md-0">
                                <!-- Form elements -->
                                <form @submit.prevent="form.post(route('account.update.profile'))">
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-person"></i></span>
                                                <div class="form-floating">
                                                    <input v-model="form.full_name"
                                                           v-input-error="form.errors.full_name"
                                                           class="form-control border-start-0"
                                                           required type="text">
                                                    <label>Full Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">
                                            <input-error :error="form.errors.full_name"></input-error>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-phone"></i></span>
                                                <div class="form-floating">
                                                    <input v-model="form.mobile"
                                                           v-input-error="form.errors.mobile"
                                                           class="form-control border-start-0"
                                                           required type="text">
                                                    <label>Mobile Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">
                                            <input-error :error="form.errors.mobile"></input-error>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="form-group mb-3 position-relative check-valid">
                                            <div class="input-group input-group-lg">
                                            <span class="input-group-text text-theme border-end-0"><i
                                                class="bi bi-envelope"></i></span>
                                                <div class="form-floating">
                                                    <input v-model="form.email"
                                                           v-input-error="form.errors.email"
                                                           class="form-control border-start-0"
                                                           required type="text">
                                                    <label>Email ID</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback mb-3">
                                            <input-error :error="form.errors.email"></input-error>
                                        </div>
                                    </div>


                                    <div class="mb-2">
                                        <button :disabled="form.processing" class="btn btn-theme w-100" type="submit">
                                            Update Profile
                                            <font-awesome-icon v-if="form.processing" class="ml-1" icon="spinner" spin/>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import InputError from "@/components/InputError.vue";

export default {
    name: "Setting",
    components: {InputError, FontAwesomeIcon},

    layout: UserLayout,
    props: {
        profile: Object,
        subscription: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                full_name: this.profile.full_name,
                mobile: this.profile.mobile,
                email: this.profile.email
            })
        }
    }
}
</script>

<style scoped>

</style>
