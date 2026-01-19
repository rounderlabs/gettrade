require('./bootstrap');

// Import modules...
import {createApp, h} from 'vue';
import {App as InertiaApp} from '@inertiajs/vue3';
import {InertiaProgress} from '@inertiajs/progress';
import store from './store';
import InputErrorDirective from './directives/inputErrorDirective';
import globalComponents from './global-components';
import utils from './utils';
import VueFeather from 'vue-feather';

window.$ = window.jQuery = require('jquery');
const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => import(`./views/${name}`).then(module => module.default),
        }),
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/serviceworker.js')
            .then(reg => console.log('✅ Service Worker Registered', reg))
            .catch(err => console.error('SW registration failed:', err));
    });
}

app.component('vue-feather', VueFeather);
app.mixin({methods: {route}});
app.use(store);
app.directive('input-error', InputErrorDirective);
globalComponents(app);
utils(app);
app.mount(el);

InertiaProgress.init({color: '#4B5563'});
