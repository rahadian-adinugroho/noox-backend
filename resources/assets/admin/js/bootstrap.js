
// window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
    
    require('fastclick');
    Noty = require('noty');
    NProgress = require('nprogress');

    var dataTable   = require('datatables.net-bs');
    var responsive  = require('datatables.net-responsive-bs');
    
    $.fn.DataTable  = dataTable;
    $.fn.responsive = responsive;
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// let csrf_token = document.head.querySelector('meta[name="csrf-token"]');

// if (csrf_token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf_token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

let jwt = document.head.querySelector('meta[name="jwt"]');

if (jwt && (jwt.content.length > 0)) {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + jwt.content;
} else {
    console.error('JWT not found, please navigate to: ' + window.location.origin + '/cms/login');
}

require('./helper');
