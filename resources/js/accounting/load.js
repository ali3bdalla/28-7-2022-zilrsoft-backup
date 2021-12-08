import { db } from './db'
const helpers = require('./helpers')
window.metaHelper = helpers.metaHelper
window.trans = helpers.trans
window.inputHelper = helpers.inputHelper
window.messages = trans('messages')
window.db = db
require('./components')
