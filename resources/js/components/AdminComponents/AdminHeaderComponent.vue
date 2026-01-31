<template>

    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <!-- use @click.prevent so it works without relying on AdminLTE -->
                    <button
                        class="btn btn-sidebar-toggle"
                        @click="$emit('toggle-sidebar')"
                    >
                        <i class="bi bi-list"></i>
                    </button>
                </li>
                <li class="nav-item d-none d-md-block"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item d-none d-md-block"><a class="nav-link" href="#">Contact</a></li>
            </ul>
            <!--end::Start Navbar Links-->
            <!--begin::End Navbar Links-->
            <ul class="navbar-nav ms-auto">


                <!--begin::Fullscreen Toggle-->
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="fullscreen" href="#">
                        <i class="bi bi-arrows-fullscreen" data-lte-icon="maximize"></i>
                        <i class="bi bi-fullscreen-exit" data-lte-icon="minimize" style="display: none"></i>
                    </a>
                </li>
                <!--end::Fullscreen Toggle-->
                <!--begin::User Menu Dropdown-->
                <li class="nav-item dropdown user-menu">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        <img
                            alt="User Image"
                            class="user-image rounded-circle shadow"
                            src="/assets/img/user2-160x160.jpg"
                        />
                        <span class="d-none d-md-inline">{{ $page.props.admin?.name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <!--begin::User Image-->
                        <li class="user-header text-bg-primary">
                            <img
                                alt="User Image"
                                class="rounded-circle shadow"
                                src="/assets/img/user2-160x160.jpg"
                            />
                            <p>{{ $page.props.admin?.name ?? 'Admin' }}

                            </p>
                        </li>
                        <!--end::User Image-->
                        <!--begin::Menu Body-->
                        <li class="user-body">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-4 text-center"><a href="#">Followers</a></div>
                                <div class="col-4 text-center"><a href="#">Sales</a></div>
                                <div class="col-4 text-center"><a href="#">Friends</a></div>
                            </div>
                            <!--end::Row-->
                        </li>
                        <!--end::Menu Body-->
                        <!--begin::Menu Footer-->
                        <li class="user-footer">

                            <Link :href="route('account.profile')" class="btn btn-default btn-flat"><i
                                class="w-4 h-4 mr-2" data-feather="user"></i> Profile
                            </Link>
                            <Link :href="route('account.change.password')" class="btn btn-default btn-flat"><i
                                class="w-4 h-4 mr-2" data-feather="lock"></i> Reset Password
                            </Link>
                            <Link :href="route('account.setting')" class="btn btn-default btn-flat"><i
                                class="w-4 h-4 mr-2" data-feather="help-circle"></i> Update Profile
                            </Link>

                            <a class="btn btn-default btn-flat float-end" href="/logout"> <i class="w-4 h-4 mr-2"
                                                                                             data-feather="toggle-right"></i>
                                Logout </a>

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
            const body = document.body;

            // Mobile viewport
            if (window.innerWidth < 992) {
                // Toggle mobile "sidebar-open"
                if (body.classList.contains('sidebar-open')) {
                    body.classList.remove('sidebar-open');
                    body.classList.add('sidebar-closed');
                    // Remove overlay if exists
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) overlay.remove();
                } else {
                    body.classList.add('sidebar-open');
                    body.classList.remove('sidebar-closed');

                    // Add overlay for mobile background
                    const overlay = document.createElement('div');
                    overlay.classList.add('sidebar-overlay');
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.background = 'rgba(0, 0, 0, 0.5)';
                    overlay.style.zIndex = '1038';
                    overlay.addEventListener('click', () => {
                        body.classList.remove('sidebar-open');
                        overlay.remove();
                    });
                    document.body.appendChild(overlay);
                }
            }
            // Desktop viewport
            else {
                body.classList.toggle('sidebar-collapse');
            }

            // Trigger resize event so charts/layouts adjust
            window.dispatchEvent(new Event('resize'));
        }

    },
};
</script>

<style scoped>
/* Optional fallback if AdminLTE styles aren't loaded */
body.sidebar-open .app-sidebar {
    transform: translateX(0);
}
body.sidebar-closed .app-sidebar {
    transform: translateX(-100%);
}
.sidebar-overlay {
    transition: opacity 0.3s ease;
}
</style>
