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
            Showing: {{ current_page }} of
            {{ last_page }}
          </div>
          <div v-show="loaded">
            <select
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
          :cards="5"
          :cols="5"
        >
        </video-list>

        <skeleton-video-card class="mb-5" v-if="!loaded" :cards="5" :cols="5">
        </skeleton-video-card>

        <paginate
          class="mb-5"
          :pagination="videos"
          :loading="!loaded"
          @paginate="getVideos()"
        ></paginate>
      </main>
    </div>
  </div>
</template>

<script>
import SkeletonVideoCard from "../components/skeleton/VideoCard"

export default {
  components: {
    SkeletonVideoCard: SkeletonVideoCard,
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
      // Set error
      this.error = false

      // Start loading on frontend
      this.$Progress.start()

      // Set loading
      this.loading = true

      // Stop unfinished images loading
      $(".video-poster img").attr("src", "")

      // Make the call
      await axios
        .get("/api/videos", {
          params: { ...this.$route.params, ...this.$route.query },
        })
        .then(response => {
          // Finish loading on frontend
          this.$Progress.finish()

          // Set video object
          this.videos = response.data

          // Disable loading
          this.loading = false
          this.loaded = true
        })
        .catch(error => {
          // Console log API error.
          console.log("Error calling API.")

          // Failed frontend progress bar
          this.$Progress.fail()
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
  computed: {
    last_page: function() {
      return this.uf_num(this.videos.last_page)
    },
    current_page: function() {
      return this.uf_num(this.videos.current_page)
    },
  },
  props: ["categories"],
  watch: {
    // When route changes, call API
    $route(to, from) {
      // Clear data
      this.videos = []

      // Get new data
      this.getVideos()
    },
  },
}
</script>
