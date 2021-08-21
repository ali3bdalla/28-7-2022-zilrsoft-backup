import Echo from 'laravel-echo'
window.axios = require('axios')
window._ = require('lodash')
window.Pusher = require('pusher-js')
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '09fe6f5a7f92075a7063',
  cluster: 'ap2',
  forceTLS: true
})
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf
  console.error(
    'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
  )
}
