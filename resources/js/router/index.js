import Vue from "vue"
import VueRouter from "vue-router"

// Pages
import Categories from "@/views/Categories.vue"
import Homepage from "@/views/Homepage.vue"
import NotFound from "@/views/NotFound.vue"
import Video from "@/views/Video.vue"

Vue.use(VueRouter)

export default new VueRouter({
  base: process.env.BASE_URL,
  mode: "history",
  routes: [
    {
      path: "/",
      component: Homepage,
      name: "home",
    },
    {
      path: "/categories/:category",
      component: Categories,
      name: "categories",
    },
    {
      path: "/videos/:id",
      component: Video,
      name: "video",
    },
    {
      path: "*",
      component: NotFound,
      name: "404",
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
})
