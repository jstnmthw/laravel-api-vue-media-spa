// Laravel's bootstrap
require('./bootstrap')

// Libraries
import axios from 'axios'
import Vue from 'vue'
import router from '@/router'
import store from '@/store'
import VueProgressBar from 'vue-progressbar'

// Global components
import Navbar from '@/components/layout/Navbar'
import Footer from '@/components/layout/Footer'

// Mixins
import Helpers from '@/mixins/Helpers.js'

// Register global components
Vue.component('Navbar', Navbar)
Vue.component('MainFooter', Footer)

// Axios settings
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.withCredentials = true

// Axios cancel token request interceptor
axios.interceptors.request.use(
  function (config) {
    //  Generate cancel token source
    let source = axios.CancelToken.source()

    // Set cancel token on this request
    config.cancelToken = source.token

    // Add to vuex to make cancellation available from anywhere
    store.commit('requestToken/ADD_CANCEL_TOKEN', source)

    return config
  },
  function (error) {
    return Promise.reject(error)
  }
)

// Vue Progressbar
const vpbSettings = {
  color: 'rgba(217, 128, 250,1.0)',
  failedColor: 'red',
  height: '2px'
}

// Ignore Ion's Font Icons custom elements
Vue.config.ignoredElements = ['ion-icon']

// Register Instances
Vue.use(VueProgressBar, vpbSettings)

// Register Global Mixin
Vue.mixin(Helpers)

// Initiate instance
const app = new Vue({
  el: '#app',
  router,
  store,
  data: {},
  mounted() {
    store.dispatch('getCategories')
  },
  methods: {}
})

// Before creation callback
router.beforeEach((to, from, next) => {
  store.dispatch('requestToken/CANCEL_PENDING_REQUESTS')
  next()
})
