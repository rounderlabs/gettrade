 window._ = require('lodash');
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.$ = window.jQuery = require('jquery');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
//import '../../resources/assets/js/helper';
// import '../../resources/assets/js/chart';
// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
//

//require('../js/app-theme')
// require('../assets/plugins/bootstrap/js/bootstrap.bundle')


 // 3rd Parties
 // import '../../resources/assets/js/chart'
 // import '../../resources/assets/js/highlight'
 // import '../../resources/assets/js/feather'
 // import '../../resources/assets/js/tiny-slider'
 // import '../../resources/assets/js/tippy'

 // import '../../resources/assets/js/datepicker'
 // import '../../resources/assets/js/tail-select'
 // import '../../resources/assets/js/dropzone'

 // import '../../resources/assets/js/ckeditor'
 // import '../../resources/assets/js/validation'
 // import '../../resources/assets/js/zoom'
 // import '../../resources/assets/js/svg-loader'
 // import '../../resources/assets/js/toast'
 // import '../../resources/assets/js/tabulator'

 // Components
 // import '../../resources/assets/js/maps'
 // // import '../../resources/assets/js/chat'
 // import '../../resources/assets/js/dropdown'
 // import '../../resources/assets/js/modal'
 // import '../../resources/assets/js/show-modal'
 // import '../../resources/assets/js/show-dropdown'
 // import '../../resources/assets/js/tab'
 // import '../../resources/assets/js/accordion'
 // import '../../resources/assets/js/search'
 // import '../../resources/assets/js/copy-code'
 // import '../../resources/assets/js/show-code'
 // import '../../resources/assets/js/side-menu'
 // import '../../resources/assets/js/mobile-menu'
 // import '../../resources/assets/js/side-menu-tooltip'

 // Dark mode switcher
 // import '../../resources/assets/js/dark-mode-switcher'
