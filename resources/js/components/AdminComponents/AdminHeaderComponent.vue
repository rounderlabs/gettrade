<template>

    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-white border-bottom shadow-sm">
        <!--begin::Container-->
        <div class="container-fluid px-4">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-2">
                    <!-- use @click.prevent so it works without relying on AdminLTE -->
                    <button
                        class="btn btn-sidebar-toggle border-0 rounded-circle"
                        @click="toggleSidebar"
                    >
                        <i class="bi bi-list fs-4"></i>
                    </button>
                </li>
                <li class="nav-item d-none d-md-block px-2">
                    <a class="nav-link fw-semibold text-secondary hover-primary" href="#">Home</a>
                </li>
                <li class="nav-item d-none d-md-block px-2">
                    <a class="nav-link fw-semibold text-secondary hover-primary" href="#">Contact</a>
                </li>
            </ul>
            <!--end::Start Navbar Links-->
            <!--begin::End Navbar Links-->
            <ul class="navbar-nav ms-auto align-items-center">

                <!--begin::Fullscreen Toggle-->
                <li class="nav-item me-2">
                    <a class="nav-link rounded-circle btn-icon" data-lte-toggle="fullscreen" href="#">
                        <i class="bi bi-arrows-fullscreen text-dark fs-5" data-lte-icon="maximize"></i>
                        <i class="bi bi-fullscreen-exit text-dark fs-5" data-lte-icon="minimize" style="display: none"></i>
                    </a>
                </li>
                <!--end::Fullscreen Toggle-->
                <!--begin::User Menu Dropdown-->
                <li class="nav-item dropdown user-menu">
                    <a class="nav-link dropdown-toggle d-flex align-items-center px-2 py-1 rounded-pill bg-light border" data-bs-toggle="dropdown" href="#">
                        <img
                            alt="User Image"
                            class="user-image rounded-circle border border-2 border-white shadow-sm"
                            src="/assets/img/user2-160x160.jpg"
                            style="width: 32px; height: 32px; object-fit: cover;"
                        />
                        <span class="d-none d-md-inline fw-semibold text-dark ms-2 me-1">{{ $page.props.admin?.name ?? 'Admin' }}</span>
                        <i class="bi bi-chevron-down text-muted fs-7 ms-1 me-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end border-0 shadow-lg dropdown-animated mt-2">
                        <!--begin::User Header-->
                        <li class="user-header bg-gradient-dark text-white p-4 rounded-top d-flex flex-column align-items-center">
                            <img
                                alt="User Image"
                                class="rounded-circle shadow border border-3 border-white-50 mb-3"
                                src="/assets/img/user2-160x160.jpg"
                                style="width: 80px; height: 80px; object-fit: cover;"
                            />
                            <p class="mb-0 fw-bold fs-5">
                                {{ $page.props.admin?.name ?? 'Admin' }}
                            </p>
                            <small class="text-white-50">Administrator</small>
                        </li>
                        <!--end::User Header-->
                        <!--begin::Menu Footer-->
                        <li class="user-footer p-3 bg-light rounded-bottom border-top">
                            <div class="d-grid gap-2">
                                <Link :href="route('account.profile')" class="btn btn-white text-start shadow-sm border py-2 px-3 rounded-3 hover-bg-light">
                                    <i class="bi bi-person me-2 text-primary fs-5"></i> Profile
                                </Link>
                                <Link :href="route('account.change.password')" class="btn btn-white text-start shadow-sm border py-2 px-3 rounded-3 hover-bg-light">
                                    <i class="bi bi-shield-lock me-2 text-warning fs-5"></i> Reset Password
                                </Link>
                                <Link :href="route('account.setting')" class="btn btn-white text-start shadow-sm border py-2 px-3 rounded-3 hover-bg-light">
                                    <i class="bi bi-gear me-2 text-info fs-5"></i> Settings
                                </Link>
                                <hr class="my-2 border-secondary-subtle">
                                <a class="btn btn-danger py-2 rounded-3 text-white fw-semibold hover-shadow" href="/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </div>
                        </li>
                        <!--end::Menu Footer-->
                    </ul>
                </li>
                <!--end::User Menu Dropdown-->
            </ul>
            <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
    </nav>
    <!--end::Header-->

</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "AdminHeaderComponent",
    components: {Link},
    methods: {
        toggleSidebar() {
            this.$emit('toggle-sidebar');
        }

    },
};
</script>

<style scoped>
.app-header {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    height: 70px;
    display: flex;
    align-items: center;
}

.btn-sidebar-toggle {
    background-color: transparent;
    color: #475569;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.btn-sidebar-toggle:hover {
    background-color: #f1f5f9;
    color: #0d6efd;
}

.btn-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.btn-icon:hover {
    background-color: #f1f5f9;
}

.hover-primary {
    transition: color 0.2s ease;
}
.hover-primary:hover {
    color: #0d6efd !important;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
}

.btn-white {
    background-color: #ffffff;
    color: #475569;
    border-color: #e2e8f0 !important;
    font-weight: 500;
}
.btn-white:hover {
    background-color: #f8fafc;
    color: #0f172a;
}

.dropdown-animated {
    animation: slideIn 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: top right;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.sidebar-overlay {
    transition: opacity 0.3s ease;
}
</style>
