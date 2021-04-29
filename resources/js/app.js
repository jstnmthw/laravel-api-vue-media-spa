// Laravel bootstrap
require('./bootstrap')

// Libraries
import axios from 'axios'
import Vue from 'vue'
import router from './router'
import store from './store'
import VueMeta from 'vue-meta'
import VueProgressBar from 'vue-progressbar'
import VueLazyload from 'vue-lazyload'

// Global components
import Navbar from '@/components/layout/Navbar'
import Footer from '@/components/layout/Footer'

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
  color: '#ffaf81',
  failedColor: 'red',
  height: '2px'
}

// Register Instances
Vue.use(VueMeta)
Vue.use(VueProgressBar, vpbSettings)
Vue.use(VueLazyload)

// Initiate instance
const app = new Vue({
  el: '#app',
  router,
  store,
  data: {},
  metaInfo: {
    title: 'App',
    titleTemplate: '%s - Media'
  },
  mounted() {
    store.dispatch('getCategories')
    this.$Progress.finish()
  },
  created() {
    this.$Progress.start()
    this.$router.beforeEach((to, from, next) => {
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress
        this.$Progress.parseMeta(meta)
      }
      this.$Progress.start()
      next()
    })
    this.$router.afterEach((to, from) => {
      this.$Progress.finish()
    })
  }
})

// Before creation callback
router.beforeEach((to, from, next) => {
  store.dispatch('requestToken/CANCEL_PENDING_REQUESTS').then(() => next())
})
