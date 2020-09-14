<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>

        <div v-if="error" class="error">
          <h4 class="text-center mt-lg-3">
            An error has occured, please try again later.
          </h4>
        </div>

        <div v-else>
          <div class="d-flex mb-3">
            <div class="flex-grow-1 mr-5">
              <page-header
                :title="'Hot Videos'"
                icon="flame"
                v-if="!isLoading"
              ></page-header>
              <div v-else class="skeleton-header w-100"></div>
              <div v-if="!isLoading" class="text-sage">
                Showing: {{ uf_num(videos.current_page) }} of
                {{ uf_num(videos.last_page) }}
              </div>
              <div v-else class="skeleton-text w-75"></div>
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
            v-if="!isLoading"
            :videos="videos.data"
            :cards="50"
            :cols="5"
          ></video-list>

          <skeleton-video-card
            class="mb-5"
            v-if="isLoading"
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

// Getters
import { mapState, mapGetters } from 'vuex'

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
  computed: { ...mapGetters(['isLoading']), ...mapState(['loading']) },
  mounted() {},
  methods: {
    sortyBy() {
      return null
    }
  },
  mixins: [getVideosMixin],
  props: ['categories'],
  watch: {
    // Watch route changes
    $route(to, from) {
      this.videos = []
      this.getVideos()
    }
  }
}
</script>
