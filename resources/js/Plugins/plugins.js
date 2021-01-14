const currency = require('./Currency');
const Vue = require('vue');
import Echo from 'laravel-echo';
import VModal from 'vue-js-modal'
window.Pusher = require('pusher-js');
import 'vue-spinners/dist/vue-spinners.css'
import VueSpinners from 'vue-spinners/dist/vue-spinners.common'



import {
    
    Image,
    Radio,
    Card,
    Option,
    Select
  } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';


Vue.use(Image)
Vue.use(Radio)
Vue.use(Card)
Vue.use(Option)
Vue.use(Select)


Vue.prototype.$currency = currency;
if (window._translations) Vue.prototype.$translator = JSON.parse(window._translations);


Vue.prototype.$asset = (url ) => {
	return '/' + url;
};

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
