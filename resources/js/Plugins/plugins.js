const currency = require('./Currency');
const Vue = require('vue');
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

Vue.prototype.$currency = currency;
if (window._translations) Vue.prototype.$translator = JSON.parse(window._translations);

// Vue.prototype.$echo
Vue.prototype.$sound = {
	play: (name) => {
		let audio = new Audio(`./../../../sounds/${name}`);
		audio.play();
	}
};

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: '09fe6f5a7f92075a7063',
	cluster: 'ap2',
	forceTLS: true
});
