import Vue from 'vue'
import VueRouter from 'vue-router'

// Pages
import Master from '@/views/Master'
import Watched from '@/views/Watched'
import NotFound from '@/views/NotFound'
import Video from '@/views/Video'

Vue.use(VueRouter)

export default new VueRouter({
  base: process.env.BASE_URL,
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Master,
      name: 'home'
    },
    {
      path: '/categories/:category',
      component: Master,
      name: 'categories'
    },
    {
      path: '/videos/:slug',
      component: Video,
      name: 'media'
    },
    {
      path: '/search',
      component: Master,
      name: 'search'
    },
    {
      path: '/my/watch-history',
      component: Watched,
      name: 'watched'
    },
    {
      path: '/best',
      component: Master,
      name: 'best'
    },
    {
      path: '/:most_viewed',
      component: Master,
      name: 'most-viewed'
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
