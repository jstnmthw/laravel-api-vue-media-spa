<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>

        <page-header
          :title="'Most Viewed Videos'"
          icon="eye"
          v-show="loaded"
        ></page-header>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="text-center" v-show="!loaded">Loading API</h3>
          <div v-if="loaded">
            Showing: {{ uf_num(videos.current_page) }} of
            {{ uf_num(videos.last_page) }}
          </div>
          <div>
            <select
              id="sortby"
              v-model="sort"
              name="sortby"
              class="custom-select"
              @change="sortBy()"
            >
              <option selected value="most_views">Most Views</option>
              <option value="top_rated">Top Rated</option>
              <option value="duration">Duration</option>
              <option value="most_recent">Most Recent</option>
            </select>
          </div>
        </div>

        <video-list
          class="mb-5"
          v-if="loaded"
          :videos="videos.data"
          :cards="50"
          :cols="5"
        ></video-list>

        <skeleton-video-card
          class="mb-5"
          v-if="!loaded"
          :cards="50"
          :cols="5"
        ></skeleton-video-card>

        <paginate
          class="mb-5"
          v-show="loaded"
          :pagination="videos"
          :loaded="loaded"
          @paginate="getVideos()"
        ></paginate>
      </main>
    </div>
  </div>
</template>

<script>
import axios from "axios"
import PageHeader from "@/components/PageHeader"
import Paginate from "@/components/Paginate"
import MainSidebar from "@/components/MainSidebar"
import TopAdBanner from "@/components/TopAdBanner"
import SkeletonVideoCard from "@/components/skeleton/VideoCard"
import VideoList from "@/components/VideoList"

export default {
  components: {
    pageHeader: PageHeader,
    paginate: Paginate,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList,
  },
  data() {
    return {
      videos: [],
      pagination: [],
      error: false,
      loaded: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : "most_views",
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos()
  },
  methods: {
    // Axios Call
    async getVideos() {
      this.$Progress.start()
      this.error = false
      this.loaded = false

      // Stop unfinished images loading
      $(".video-poster img").attr("src", "")

      // Check for sort query string
      let sort = !this.$route.query.sortby ? { sortby: "most_views" } : ""

      // Make the call
      await axios
        .get("/api/videos", {
          params: {
            ...this.$route.params,
            ...this.$route.query,
            ...sort,
          },
        })
        .then(response => {
          this.$Progress.finish()
          this.videos = response.data
          this.loaded = true
        })
        .catch(error => {
          this.$Progress.fail()
          if (axios.isCancel(error)) {
            console.log("API Request canceled by user.")
          } else {
            console.log("Error calling API.")
          }
        })
    },

    // Set default or user sortby
    sortBy() {
      this.$router.push({
        query: Object.assign({}, this.$route.query, { sortby: this.sort }),
      })
    },

    // Format to user friendly number
    uf_num(int) {
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    },
  },
  props: ["categories"],
  watch: {
    // When route changes, call API
    $route(to, from) {
      this.videos = []
      this.getVideos()
    },
  },
}
</script>
