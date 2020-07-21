const helpers = require('./helpers');
import {db} from './db';
import VueSpinners from 'vue-spinners'
Vue.use(VueSpinners);
window.metaHelper = helpers.metaHelper;
window.trans = helpers.trans;
window.route = helpers.route;
window.inputHelper = helpers.inputHelper;
window.db = db;
require('./components');