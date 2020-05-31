<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        {{ query }}
        <top-ad-banner></top-ad-banner>
        <page-header :title="page_title" v-show="loaded"></page-header>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="text-center" v-show="!loaded">Loading API</h3>
          <div v-if="loaded">
            Showing: {{ number_seperator(videos.current_page) }} of
            {{ number_seperator(videos.last_page) }}
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
  data() {
    return {
      videos: [],
      loaded: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : "most_views",
      query: this.$route.query.q,
    }
  },
  mounted() {
    this.getVideos()
  },
  components: {
    pageHeader: PageHeader,
    paginate: Paginate,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList,
  },
  props: ["categories"],
  methods: {
    // Get Video Listing
    async getVideos() {
      this.$Progress.start()
      this.loaded = false
      await axios
        .get("/api/videos/search", {
          params: this.$route.query,
        })
        .then(response => {
          this.$Progress.finish()
          this.loaded = true
          this.videos = response.data
        })
        .catch(error => {
          this.$Progress.fail()
          this.error = true
          console.log(error)
        })
    },

    // Set default or user sortby
    sortBy() {
      this.$router.push({
        query: Object.assign({}, this.$route.query, { sortby: this.sort }),
      })
    },

    // Thousands number seperator
    number_seperator(int) {
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    },
  },
  computed: {
    page_title: function() {
      if (this.loaded) {
        return "Search Result: " + this.query
      }
    },
  },
  watch: {
    // When route changes, call API
    $route(to, from) {
      $(".video-poster img").attr("src", "")
      this.videos = []
      this.getVideos()
    },
  },
}
</script>

<style></style>
