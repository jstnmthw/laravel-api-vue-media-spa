// Laravel's bootstrap
require('./bootstrap');

// Imports
import Vue            from 'vue'
import VueRouter      from 'vue-router'
import VueProgressBar from 'vue-progressbar';

// Pages
import Homepage       from './pages/Homepage'
import Categories     from './pages/Categories'

// Components
import Navbar         from './components/Navbar'
import NotFound       from './components/NotFound'
import MainSidebar    from './components/MainSidebar'
import VideoList      from './components/VideoList'
import TopAdBanner    from './components/TopAdBanner'
import Paginate       from './components/Paginate'
import PageHeader     from './components/PageHeader'

// Register Router
Vue.use(VueRouter)

// Vue Config
Vue.config.ignoredElements = ['ion-icon'];

// Register Components
Vue.component('Navbar', Navbar);
Vue.component('NotFound', NotFound);
Vue.component('MainSidebar', MainSidebar);
Vue.component('VideoList', VideoList);
Vue.component('TopAdBanner', TopAdBanner);
Vue.component('PageHeader', PageHeader);
Vue.component('Paginate', Paginate);

/**
 * Vue Plugins
 */

// Progress bar config
Vue.use(VueProgressBar, {
  color: 'rgba(217, 128, 250,1.0)',
  failedColor: 'red',
  height: '2px'
});

// Register Routes
const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Homepage,
      name: 'home'
    },
    {
      path: '/categories/:category',
      component: Categories,
      name: 'categories'
    },
    { 
      path: '*', 
      component: NotFound,
      name: '404'
    }
  ],
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }    
})

// Initiate instance
const app = new Vue({
  el: '#app',
  router,
  data: {
    categories: []
  },
  created() {
    var vm = this
    axios.get('/api/categories').then(function(response) {
      vm.categories = response.data
    })
  }
});
