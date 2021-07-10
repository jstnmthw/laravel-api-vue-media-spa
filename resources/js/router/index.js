import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
  base: process.env.BASE_URL,
  mode: 'history',
  routes: [
    {
      path: '/',
      component: () =>
        import(
          /* webpackChunkName: "homepage.vue" */
          '@/views/Homepage.vue'
        ),
      name: 'home'
    },
    {
      path: '/categories/:category',
      component: () =>
        import(
          /* webpackChunkName: "category.vue" */
          '@/views/Categories.vue'
        ),
      name: 'categories'
    },
    {
      path: '/videos/:slug',
      component: () =>
        import(
          /* webpackChunkName: "video.vue" */
          '@/views/Video.vue'
        ),
      name: 'media'
    },
    {
      path: '/search',
      component: () =>
        import(
          /* webpackChunkName: "search.vue" */
          '@/views/Search.vue'
        ),
      name: 'search'
    },
    {
      path: '/my/watch-history',
      component: () =>
        import(
          /* webpackChunkName: "watch-history.vue" */
          '@/views/Watched.vue'
        ),
      name: 'watched'
    },
    {
      path: '/best',
      component: () =>
        import(
          /* webpackChunkName: "best.vue" */
          '@/views/Best.vue'
        ),
      name: 'best'
    },
    {
      path: '/most-viewed',
      component: () =>
        import(
          /* webpackChunkName: "most-viewed.vue" */
          '@/views/MostViewed.vue'
        ),
      name: 'most-viewed'
    },
    {
      path: '/recommended',
      component: () =>
        import(
          /* webpackChunkName: "recommended.vue" */
          '@/views/Recommended.vue'
        ),
      name: 'recommended'
    },
    {
      path: '*',
      component: () =>
        import(
          /* webpackChunkName: "404.vue" */
          '@/views/NotFound.vue'
        ),
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
