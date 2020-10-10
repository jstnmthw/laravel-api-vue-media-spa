import Vue from 'vue'
import VueRouter from 'vue-router'

// Pages
import Homepage from '@/views/Homepage'
import Watched from '@/views/Watched'
import NotFound from '@/views/NotFound'
import Search from '@/views/Search'
import Video from '@/views/Video'
import Best from '@/views/Best'

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
      component: Homepage,
      name: 'categories'
    },
    {
      path: '/videos/:id',
      component: Video,
      name: 'video'
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
      component: Homepage,
      name: 'best'
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
