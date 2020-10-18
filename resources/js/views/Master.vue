<template>
  <div class="content container bg-purple">
    <div class="row">
      <sidebar></sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <div v-if="error.status" class="error">
          <h4 class="text-center mt-lg-3">
            An error has occurred, please try again later.
          </h4>
        </div>
        <div v-else>
          <div class="d-flex mb-3" v-if="loading">
            <div class="d-flex flex-grow-1 flex-column mr-5">
              <div class="skeleton-header w-25 mb-2"></div>
              <div class="skeleton-text w-25 mb-4"></div>
              <skeleton-video-card
                class="mb-5 video-listing"
                :cards="50"
                :cols="5"
              ></skeleton-video-card>
            </div>
          </div>
          <div v-else>
            <div class="d-flex mb-3">
              <div class="d-flex flex-grow-1 flex-column mr-5">
              <page-header
                :title="'Videos'"
                icon="flame"
              ></page-header>
              <div class="text-sage">
                Showing: {{ first_page }} of {{ last_page }}
              </div>
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
              :videos="videos.data"
              :cards="50"
              :cols="5"
            ></video-list>
            <paginate
              class="mb-5"
              :pagination="videos"
              :loading="loading"
              @paginate="callAPI()"
            ></paginate>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
// Components
import PageHeader from '@/components/PageHeader'
import Paginate from '@/components/Paginate'
import Sidebar from '@/components/layout/Sidebar'
import TopAdBanner from '@/components/TopAdBanner'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'
import VideoList from '@/components/media/List'

// State
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  components: {
    PageHeader: PageHeader,
    Paginate: Paginate,
    Sidebar: Sidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList
  },
  data() {
    return {
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'most_views'
    }
  },
  computed: {
    ...mapGetters('api', [
      'loading',
    ]),
    ...mapState('api', {
      videos: (state) => state.data,
      error: (state) => state.error,
    }),
    first_page() {
      return this.comma_delimiter(this.videos.current_page)
    },
    last_page() {
      return this.comma_delimiter(this.videos.last_page)
    }
  },
  mounted() {
    this.callAPI();
  },
  methods: {
    ...mapActions('api', [
      'api'
    ]),
    sortBy() {
      return null
    },
    callAPI() {
      return this.api({
        url: '/api/media',
        params: { ...this.$route.params, ...this.$route.query }
      });
    }
  },
  watch: {
    $route(to, from) {
      this.callAPI();
    }
  }
}
</script>
