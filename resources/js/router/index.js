import Vue from 'vue'
import VueRouter from 'vue-router'

// Pages
import Watched from '@/views/Watched'
import NotFound from '@/views/NotFound'
import Video from '@/views/Video'
import Homepage from '@/views/Homepage'
import Categories from '@/views/Categories'
import Search from '@/views/Search'
import Best from '@/views/Best'
import MostViewed from '@/views/MostViewed'
import Recommended from '@/views/Recommended'

Vue.use(VueRouter)

export default new VueRouter({
  base: process.env.BASE_URL,
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
      path: '/videos/:slug',
      component: Video,
      name: 'media'
    },
    {
      path: '/search',
      component: Search,
      name: 'search'
    },
    {
      path: '/my/watch-history',
      component: Watched,
      name: 'watched'
    },
    {
      path: '/best',
      component: Best,
      name: 'best'
    },
    {
      path: '/most-viewed',
      component: MostViewed,
      name: 'most-viewed'
    },
    {
      path: '/recommended',
      component: Recommended,
      name: 'recommended'
    },
    {
      path: '*',
      component: NotFound,
      name: '404'
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})
