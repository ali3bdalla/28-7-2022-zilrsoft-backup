const currency = require('./Currency');
const Vue = require('vue');
import Echo from 'laravel-echo';
import VModal from 'vue-js-modal'
window.Pusher = require('pusher-js');
import 'vue-spinners/dist/vue-spinners.css'
import VueSpinners from 'vue-spinners/dist/vue-spinners.common'

Vue.prototype.$currency = currency;
if (window._translations) Vue.prototype.$translator = JSON.parse(window._translations);


Vue.prototype.$asset = (url ) => {
	return '/' + url;
};
// Vue.prototype.$echo
Vue.prototype.$sound = {
	play: (name) => {
		let audio = new Audio(`./../../../sounds/${name}`);
		audio.play();
	}
};
Vue.use(VModal);
Vue.use(VueSpinners)


window.Echo = new Echo({
	broadcaster: 'pusher',
	key: '09fe6f5a7f92075a7063',
	cluster: 'ap2',
	forceTLS: true
});
