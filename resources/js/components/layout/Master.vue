<template>
  <div class="content container bg-purple">
    <div class="row">
      <sidebar classes="d-lg-block"></sidebar>
      <main class="col-12 col-lg-9 col-xl-10">
        <top-ad-banner></top-ad-banner>
        <div v-if="error.status && !loading" class="error">
          <h4 class="text-center mt-lg-3">
            {{ error.msg }}
          </h4>
        </div>
        <template v-else>
          <div class="d-flex mb-3" v-if="loading">
            <div class="d-flex flex-grow-1 flex-column mr-0 mr-lg-5">
              <div class="skeleton-header w-50 mb-2"></div>
              <div class="skeleton-text w-50 mb-4"></div>
              <skeleton-video-card
                class="mb-5 media-listing"
                :cards="50"
                :cols="2"
              ></skeleton-video-card>
            </div>
          </div>
          <div v-else>
            <div class="d-block d-md-flex mb-3 mb-md-3">
              <div
                class="d-flex flex-grow-1 justify-content-between flex-row flex-md-column"
              >
                <slot>
                  <page-header :title="'Videos'" icon="flame"></page-header>
                </slot>
                <div class="d-none d-md-block text-sage">
                  Showing: {{ first_page }} of {{ last_page }}
                </div>
              </div>
              <div class="d-flex align-items-center sort-by mb-0 mb-md-0">
                <label for="sort_by" class="text-sage mb-0 mr-2 d-none"
                  >Sort By:
                </label>
                <select
                  id="sort_by"
                  v-model="sort"
                  name="sort_by"
                  class="custom-select d-none"
                  @change="sortBy()"
                >
                  <option selected value="most_views">Most Views</option>
                  <option value="top_rated">Top Rated</option>
                  <option value="duration">Duration</option>
                  <option value="most_recent">Most Recent</option>
                </select>
              </div>
            </div>
            <media-list
              class="mb-5"
              :media="media.data"
              :cards="50"
              :cols="5"
            ></media-list>
            <paginate
              class="mb-5"
              :pagination="media"
              :loading="loading"
              @paginate="callAPI()"
            ></paginate>
          </div>
        </template>
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
import MediaList from '@/components/media/List'
import { comma_delimiter } from '@/helpers/numbers'

// State
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  components: {
    PageHeader,
    Paginate,
    Sidebar,
    SkeletonVideoCard,
    TopAdBanner,
    MediaList
  },
  data() {
    return {
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'most_views'
    }
  },
  computed: {
    ...mapGetters('api', ['loading']),
    ...mapState('api', {
      media: (state) => state.data,
      error: (state) => state.error
    }),
    first_page() {
      return comma_delimiter(this.media.current_page)
    },
    last_page() {
      return comma_delimiter(this.media.last_page)
    }
  },
  mounted() {
    this.callAPI()
  },
  methods: {
    ...mapActions('api', ['api']),
    sortBy() {
      return null
    },
    callAPI() {
      return this.api({
        url: '/api/media' + this.$route.path,
        query: this.$route.query
      })
    }
  },
  watch: {
    $route() {
      this.callAPI()
    }
  }
}
</script>
