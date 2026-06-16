require('./bootstrap');

// Import modules...
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {InertiaProgress} from '@inertiajs/progress';
import store from './store';
import InputErrorDirective from './directives/inputErrorDirective';
import globalComponents from './global-components';
import utils from './utils';
import VueFeather from 'vue-feather';

window.$ = window.jQuery = require('jquery');

if (process.env.NODE_ENV === 'production' && 'serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/serviceworker.js')
            .then(reg => console.log('✅ Service Worker Registered', reg))
            .catch(err => console.error('SW registration failed:', err));
    });
} else if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for(let registration of registrations) {
            registration.unregister();
        }
    });
}

createInertiaApp({
    resolve: name => import(`./views/${name}.vue`).then(module => module.default),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
            .use(store)
            .component('vue-feather', VueFeather)
            .mixin({methods: {route}})
            .directive('input-error', InputErrorDirective);

        globalComponents(app);
        utils(app);
        app.mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});
