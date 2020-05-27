// Laravel's bootstrap
require("./bootstrap")

// Library Imports
import Vue from "vue"
import Vuex from "vuex"
import VueRouter from "vue-router"
import VueProgressBar from "vue-progressbar"

// Import Pages
import Homepage from "./pages/Homepage"
import Categories from "./pages/Categories"
import Video from "./pages/Video"
import NotFound from "./pages/NotFound"

// Import global components
import Navbar from "./components/Navbar"
import MainFooter from "./components/MainFooter"

// Register global components
Vue.component("Navbar", Navbar)
Vue.component("MainFooter", MainFooter)

// Plugin Settings
const pbSettings = {
  color: "rgba(217, 128, 250,1.0)",
  failedColor: "red",
  height: "2px",
}

// Register Instances
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueProgressBar, pbSettings)

// Ignore Ion's Font Icons custom elements
Vue.config.ignoredElements = ["ion-icon"]

// Register Routes
const router = new VueRouter({
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

router.beforeEach((to, from, next) => {
  // ...
  next()
})

// Initiate instance
const app = new Vue({
  el: "#app",
  router,
  data: {
    categories: [],
  },
  mounted() {
    this.getCategories()
  },
  methods: {
    async getCategories() {
      await axios.get("/api/categories").then(response => {
        this.categories = response.data
        console.log("Getting categories.")
      })
    },
  },
})
