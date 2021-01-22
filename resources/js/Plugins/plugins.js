import Echo from 'laravel-echo'
import VModal from 'vue-js-modal'
import 'vue-spinners/dist/vue-spinners.css'
import VueSpinners from 'vue-spinners/dist/vue-spinners.common'
import VueCountdownTimer from 'vuejs-countdown-timer'

import InfiniteLoading from 'vue-infinite-loading'
import VueProgressBar from 'vue-progressbar'

import {
  Button,
  TableColumn,
  Table,
  TabPane,
  Tabs,
  Image,
  Radio,
  Card,
  Option,
  Select
} from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
const currency = require('./Currency')
const Vue = require('vue')
window.Pusher = require('pusher-js')

Vue.use(Image)
Vue.use(Radio)
Vue.use(Card)
Vue.use(Button)
Vue.use(Tabs)
Vue.use(TabPane)
Vue.use(TableColumn)
Vue.use(Table)
Vue.use(Option)
Vue.use(Select)
Vue.use(VueCountdownTimer)
Vue.use(InfiniteLoading, { /* options */ })

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})
Vue.prototype.$currency = currency
if (window._translations) Vue.prototype.$translator = JSON.parse(window._translations)

Vue.prototype.$asset = (url) => {
  return '/' + url
}

Vue.prototype.$sound = {
  play: (name) => {
    const audio = new Audio(`./../../../sounds/${name}`)
    audio.play()
  }
}
Vue.use(VModal)
Vue.use(VueSpinners)

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '09fe6f5a7f92075a7063',
  cluster: 'ap2',
  forceTLS: true
})
