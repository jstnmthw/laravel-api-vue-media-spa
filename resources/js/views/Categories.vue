<template>
  <div class="container">
    <div class="row bg-purple">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>

        <div v-if="error" class="text-center m-4">
          {{ error }}
        </div>

        <div v-else>
          <page-header
            :title="category_title"
            icon=""
            v-show="loaded"
          ></page-header>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div v-show="loaded">
              Showing: {{ videos.current_page }} of
              {{ videos.last_page }}
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
            :loaded="loaded"
            :cards="50"
            :cols="5"
          ></video-list>

          <skeleton-video-card
            class="mb-5"
            v-if="!loaded"
            :cards="50"
            :cols="5"
          >
          </skeleton-video-card>

          <paginate
            class="mb-5"
            v-show="loaded"
            :pagination="videos"
            :loaded="loaded"
            @paginate="getVideos()"
          ></paginate>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import PageHeader from '@/components/PageHeader'
import Paginate from '@/components/Paginate'
import MainSidebar from '@/components/MainSidebar'
import TopAdBanner from '@/components/TopAdBanner'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'
import VideoList from '@/components/VideoList'

export default {
  components: {
    pageHeader: PageHeader,
    paginate: Paginate,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList
  },
  data() {
    return {
      videos: [],
      pagination: [],
      error: false,
      loaded: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'most_views'
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos()

    // cookie for cats
    document.cookie =
      'category=' +
      this.$route.params.category +
      '; expires=Sun, 25 December 2022 00:00:00 UTC; path=/'
  },
  computed: {
    category_title: function () {
      const categories = this.categories.map((el) => el.slug)
      const key = categories.indexOf(this.$route.params.category)
      if (key >= 0) {
        return this.categories[key]['name'] + ' Video Category'
      }
    }
  },
  methods: {
    getVideos() {
      this.error = false
      this.$Progress.start()
      this.loaded = false

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '')

      // Check for sort query string
      let sort = !this.$route.query.sortby ? { sortby: 'most_views' } : ''

      // Make the call
      axios
        .get('/api/videos', {
          params: {
            ...this.$route.params,
            ...this.$route.query,
            ...sort
          }
        })
        .then((response) => {
          this.$Progress.finish()
          if (response.data.error) {
            this.error = response.data.error
          } else {
            this.videos = response.data
          }
          this.loaded = true
        })
        .catch((error) => {
          this.$Progress.fail()
          if (axios.isCancel(error)) {
            console.log('API Request canceled by user.')
          } else {
            console.log('Error calling API.')
          }
        })
    },
    sortBy() {
      this.$router.push({
        query: Object.assign({}, this.$route.query, {
          sortby: this.sort
        })
      })
    }
  },
  props: ['categories'],
  watch: {
    $route(to, from) {
      this.videos = []
      this.getVideos()
      document.cookie =
        'category=' +
        this.$route.params.category +
        '; expires=Sun, 25 December 2022 00:00:00 UTC; path=/'
    }
  }
}
</script>
