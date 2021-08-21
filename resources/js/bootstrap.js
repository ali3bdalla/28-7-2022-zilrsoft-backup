
window._ = require('lodash')

try {
  window.Popper = require('popper.js').default
  window.$ = window.jQuery = require('jquery')

  require('bootstrap')
} catch (e) {
}

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

const validate = require('validate.js')
const helpers = require('./mass/helpers').helpers
window.validate = validate
window.helpers = helpers
window.config = '{}'
