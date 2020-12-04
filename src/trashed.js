/*

    util.js

*/

window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
window.formSerialize = require('form-serialize');

window.serializeById = (id, hash = true) => {
  let form = document.getElementById(id)
  return formSerialize(form, {hash})
}

window.serialize = (form, hash = true) => formSerialize(form, {hash})








/*

            app.js

*/

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

window.jamIonic = () => {
    let ionicFrame = document.getElementsByTagName('iframe')[0]
    l(ionicFrame)
    ionicFrame.contentWindow.console.log = function() { /* nop */ }
}

let setIonicFrame = (jam = true, src = 'http://localhost:8100/') => {
    let iframe = document.createElement('iframe')
    iframe.src = src
    iframe.className = 'app-preview'
    let container = document.getElementsByClassName('phone-block')[0]
    container.appendChild(iframe)
    let iframeWindow = iframe.contentWindow;
    iframeWindow.console.log = function() { /* nop */ };
}
setIonicFrame();


// document.addEventListener('click', function(e) {
//     e = e || window.event;
//     var target = e.target || e.srcElement,
//         text = target.textContent || target.innerText;

//     console.log(target, text)
// }, false);

// function setUserAgent(window, userAgent) {
//     if (window.navigator.userAgent != userAgent) {
//         var userAgentProp = { get: function () { return userAgent; } };
//         try {
//             Object.defineProperty(window.navigator, 'userAgent', userAgentProp);
//         } catch (e) {
//             window.navigator = Object.create(navigator, {
//                 userAgent: userAgentProp
//             });
//         }
//     }
// }

// let agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1';
// axios('localhost:8100', {
//     headers: {
//         'User-Agent': agent,
//     }
// })


// setUserAgent(document.querySelector('iframe').contentWindow, agent);
// setTimeout(() => document.querySelector('iframe').src = 'http://localhost:4200?mode=ios', 200);

randomColor = () => {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}