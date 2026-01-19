/**
 * TradeMiles Admin Panel JS (Laravel Mix + Inertia + Vue3)
 */
require('./bootstrap');

import { createApp, h } from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import store from './store';
import VueFeather from 'vue-feather';
import globalComponents from './global-components';
import utils from './utils';

// Core UI
import 'bootstrap';
import 'overlayscrollbars';
import 'overlayscrollbars/styles/overlayscrollbars.css';
import 'admin-lte/dist/css/adminlte.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

// AdminLTE JS core
import 'admin-lte/dist/js/adminlte.min.js';

// ✅ Initialize OverlayScrollbars for sidebar or any scrollable areas
document.addEventListener('DOMContentLoaded', () => {
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');
    if (sidebarWrapper && window.OverlayScrollbarsGlobal) {
        const { OverlayScrollbars } = window.OverlayScrollbarsGlobal;
        OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: 'os-theme-light',
                autoHide: 'leave',
                clickScroll: true,
            },
        });
    }

    console.log('✅ AdminLTE + Inertia loaded successfully');
});

// ✅ Mount Inertia/Vue app
// const el = document.querySelector('[data-page]');
// if (el) {
//     createApp({
//         render: () =>
//             h(InertiaApp, {
//                 initialPage: JSON.parse(el.dataset.page),
//                 resolveComponent: name =>
//                     import(`@/views/${name}.vue`).then(module => module.default),
//             }),
//     })
//         .use(store)
//         .component('vue-feather', VueFeather)
//         .mixin({ methods: { route } })
//         .mount(el);
// }

createInertiaApp({
    resolve: (name) =>
        import(`./views/${name}.vue`).then((module) => module.default),

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin) // 🔥 REQUIRED
            .use(store)
            .component("vue-feather", VueFeather)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

// ✅ Inertia progress bar
InertiaProgress.init({ color: '#2563eb', showSpinner: true });
