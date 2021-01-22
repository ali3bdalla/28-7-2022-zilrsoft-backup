import { db } from './db'
const helpers = require('./helpers')
// import VueSpinners from 'vue-spinners'
// Vue.use(VueSpinners);
window.metaHelper = helpers.metaHelper
window.trans = helpers.trans
window.route = helpers.route
window.inputHelper = helpers.inputHelper
window.messages = trans('messages')
window.db = db
require('./components')
