const currency = require('./Currency')
const Vue = require('vue')
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

// const pusher = require('pusher-js');


//
// Pusher.logToConsole = true;
//
// const Pusher = new Pusher('09fe6f5a7f92075a7063', {
//     cluster: 'ap2'
// });
// //
// var channel = pusher.subscribe('my-channel');
// channel.bind('my-event', function (data) {
//     alert('message');
//     // app.messages.push(JSON.stringify(data));
// });


const echo = new Echo({
    broadcaster: 'pusher',
    key: '09fe6f5a7f92075a7063',
    cluster: 'ap2',
    forceTLS: true
});
Vue.prototype.$currency = currency;
Vue.prototype.$echo = echo;
Vue.prototype.$translator = JSON.parse(window._translations);


