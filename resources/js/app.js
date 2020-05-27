// Laravel's bootstrap
require("./bootstrap")

// Imports
import Vue from "vue"
import Vuex from "vuex"
import VueRouter from "vue-router"
import VueProgressBar from "vue-progressbar"

// Pages
import Homepage from "./pages/Homepage"
import Categories from "./pages/Categories"
import Video from "./pages/Video"
import NotFound from "./pages/NotFound"

// Components
import Navbar from "./components/Navbar"
import MainFooter from "./components/MainFooter"
import MainSidebar from "./components/MainSidebar"
import VideoLabels from "./components/VideoLabels"
import VideoList from "./components/VideoList"
import VideoListItem from "./components/VideoListItem"
import TopAdBanner from "./components/TopAdBanner"
import Paginate from "./components/Paginate"
import PageHeader from "./components/PageHeader"

// Register
Vue.use(VueRouter)
Vue.use(Vuex)

// Vue Config
Vue.config.ignoredElements = ["ion-icon"]

// Register Components
Vue.component("Navbar", Navbar)
Vue.component("NotFound", NotFound)
Vue.component("MainFooter", MainFooter)
Vue.component("MainSidebar", MainSidebar)
Vue.component("VideoCategoryLabels", VideoLabels)
Vue.component("VideoList", VideoList)
Vue.component("VideoListItem", VideoListItem)
Vue.component("PageHeader", PageHeader)
Vue.component("Paginate", Paginate)
Vue.component("TopAdBanner", TopAdBanner)

// Vue Plugins
Vue.use(VueProgressBar, {
  color: "rgba(217, 128, 250,1.0)",
  failedColor: "red",
  height: "2px",
})

// Vue.mixin({
//   created: function() {
//     const CancelToken = axios.CancelToken
//     const source = CancelToken.source()
//     // console.log(source)
//   },
// })

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
      })
    },
  },
})
