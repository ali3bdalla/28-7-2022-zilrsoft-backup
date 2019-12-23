const helpers = require('./helpers');
import VueSpinners from 'vue-spinners'
Vue.use(VueSpinners);

window.metaHelper = helpers.metaHelper;
window.trans = helpers.trans;
window.route = helpers.route;
require('./components');