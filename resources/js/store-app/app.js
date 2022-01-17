import { createInertiaApp,Link as inertiaLink} from '@inertiajs/inertia-vue'

import Vue from 'vue'
import Vuex from 'vuex'
import VueLogger from 'vuejs-logger'
import VueSimpleAlert from 'vue-simple-alert'
import vClickOutside from 'v-click-outside'
import Dialog from 'vue-dialog-loading'
import VModal from 'vue-js-modal'
import 'vue-spinners/dist/vue-spinners.css'
import VueSpinners from 'vue-spinners/dist/vue-spinners.common'
import VueCountdownTimer from 'vuejs-countdown-timer'
import ToggleButton from 'vue-js-toggle-button'
import InfiniteLoading from 'vue-infinite-loading'
import VueProgressBar from 'vue-progressbar'
import InstantSearch from 'vue-instantsearch'
import {
  Button,
  TableColumn,
  Table,
  TabPane,
  InputNumber,
  Tabs,
  Image,
  Radio,
  Card,
  Option,
  Select,
  Checkbox,
  Slider,
  Switch,
  Tag,
  Dropdown,
  DropdownItem,
  DropdownMenu,
  Steps,
  Step
} from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

const isProduction = process.env.NODE_ENV === 'production'
const app = document.getElementById('app')

require('./bootstrap')
require('./func')
Vue.use(ToggleButton)
Vue.use(Image)
Vue.use(Radio)
Vue.use(Card)
Vue.use(Button)
Vue.use(Tabs)
Vue.use(TabPane)
Vue.use(TableColumn)
Vue.use(Dropdown)
Vue.use(DropdownItem)
Vue.use(DropdownMenu)
Vue.use(Table)
Vue.use(Option)
Vue.use(Step)
Vue.use(Steps)
Vue.use(Select)
Vue.use(InputNumber)
Vue.use(Slider)
Vue.use(VueCountdownTimer)
Vue.use(InfiniteLoading, { /* options */ })
Vue.use(Checkbox)
Vue.use(InstantSearch)
Vue.use(Tag)
Vue.use(Switch)
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})
Vue.use(VModal)
Vue.use(VueSpinners)
Vue.use(VueLogger, {
  isEnabled: true,
  logLevel: isProduction ? 'error' : 'info',
  stringifyArguments: false,
  showLogLevel: true,
  showMethodName: true,
  separator: '|',
  showConsoleColors: true
})
Vue.use(Vuex)
Vue.use(VueSimpleAlert)
Vue.use(Dialog, {
  dialogBtnColor: '#0f0',
  background: 'rgba(0, 0, 0, 0.5)'
})
Vue.use(vClickOutside)
Vue.component('InertiaLink', inertiaLink)
createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props }) {
    new Vue({
      store: new Vuex.Store(require('./state')),
      render: h => h(App, props),
    }).$mount(el)
  },
})

