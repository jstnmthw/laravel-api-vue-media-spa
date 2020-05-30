import Vue from "vue"
import VueRouter from "vue-router"

// Pages
import Categories from "@/views/Categories"
import Homepage from "@/views/Homepage"
import NotFound from "@/views/NotFound"
import Search from "@/views/Search"
import Video from "@/views/Video"

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
      path: "/search",
      component: Search,
      name: "search",
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
