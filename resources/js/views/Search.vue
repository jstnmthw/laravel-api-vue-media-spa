<template>
  <div class="container">
    <div class="row bg-purple">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>

        <div v-if="error" class="text-center m-4">
          There was an error, please try again later.
        </div>

        <div v-else>
          <div
            v-if="loaded"
            class="d-flex justify-content-between align-items-center mb-3"
          >
            <div>
              <page-header
                :title="'Results for ' + this.$route.query.q"
                icon=""
              ></page-header>
              <div class="text-sage">
                Showing: {{ videos.current_page }} of
                {{ videos.last_page }}
              </div>
            </div>
            <div>
              <select
                v-model="sort"
                name="sortby"
                class="custom-select"
                @change="sortBy()"
              >
                <option selected value="relevant">Relevant</option>
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
import PageHeader from '@/components/PageHeader'
import Paginate from '@/components/Paginate'
import MainSidebar from '@/components/MainSidebar'
import TopAdBanner from '@/components/TopAdBanner'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'
import VideoList from '@/components/VideoList'

// Mixins
import getVideosMixin from '@/mixins/getVideosMixin'

export default {
  data() {
    return {
      videos: [],
      pagination: [],
      error: false,
      loaded: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'relevant'
    }
  },
  components: {
    pageHeader: PageHeader,
    paginate: Paginate,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard,
    TopAdBanner: TopAdBanner,
    VideoList: VideoList
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
    sortBy() {
      this.$router.push({
        query: Object.assign({}, this.$route.query, {
          sortby: this.sort
        })
      })
    }
  },
  mixins: [getVideosMixin],
  props: ['categories'],
  watch: {
    $route(to, from) {
      this.videos = []
      this.getVideos()
    }
  }
}
</script>
