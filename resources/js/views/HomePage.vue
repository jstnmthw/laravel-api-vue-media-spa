<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <div v-if="error" class="error">
          <h4 class="text-center mt-lg-3">
            An error has occurred, please try again later.
          </h4>
        </div>
        <div v-else>
          <div class="d-flex mb-3">
            <div class="d-flex flex-grow-1 flex-column mr-5">
              <page-header
                :title="'Videos'"
                icon="flame"
                v-if="loaded"
              ></page-header>
              <div v-else class="skeleton-header w-25 mb-2"></div>
              <div v-if="loaded" class="text-sage">
                Showing: {{ first_page }} of {{ last_page }}
              </div>
              <div v-else class="skeleton-text w-25"></div>
            </div>
            <div class="d-flex align-items-center sort-by">
              <label for="sort_by" class="text-sage mb-0 mr-2">Sort By: </label>
              <select
                id="sort_by"
                v-model="sort"
                name="sort_by"
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
            class="mb-5 video-listing"
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
        </div>
      </main>
    </div>
  </div>
</template>

<script>
// Components
import PageHeader from '@/components/PageHeader'
import Paginate from '@/components/Paginate'
import MainSidebar from '@/components/MainSidebar'
import TopAdBanner from '@/components/TopAdBanner'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'
import VideoList from '@/components/VideoList'

// Mixins
import getVideosMixin from '@/mixins/getVideosMixin.js'

export default {
  components: {
    PageHeader: PageHeader,
    Paginate: Paginate,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList
  },
  data() {
    return {
      videos: [],
      error: false,
      loaded: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'most_views'
    }
  },
  computed: {
    first_page() {
      return this.comma_delimiter(this.videos.current_page)
    },
    last_page() {
      return this.comma_delimiter(this.videos.last_page)
    }
  },
  mounted() {
    this.getVideos()
  },
  methods: {
    sort_by() {
      return null
    }
  },
  mixins: [getVideosMixin],
  watch: {
    $route(to, from) {
      this.videos = []
      this.getVideos()
    }
  }
}
</script>
