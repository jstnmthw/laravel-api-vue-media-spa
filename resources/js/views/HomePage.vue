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
          <div class="d-flex justify-content-between align-items-top mb-3">
            <div>
              <page-header
                :title="'Hot Videos'"
                icon="flame"
                v-show="loaded"
              ></page-header>
              <h3 class="text-center" v-show="!loaded">Loading API</h3>
              <div v-if="loaded" class="text-sage">
                Showing: {{ uf_num(videos.current_page) }} of
                {{ uf_num(videos.last_page) }}
              </div>
            </div>
            <!-- <div>
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
            </div> -->
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
      loaded: false
    }
  },
  mounted() {},
  methods: {
    // Format to user friendly number
    uf_num(int) {
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
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
