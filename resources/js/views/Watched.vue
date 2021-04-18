<template>
  <div class="content container bg-purple">
    <div class="row">
      <sidebar classes="d-lg-block"></sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <page-header v-if="!loading" icon="sync-circle" class="mb-3"
          >✨ Recently Viewed</page-header
        >
        <media-list
          v-if="!loading"
          class="mb-5"
          :media="media.data"
          :cards="20"
          :cols="5"
        ></media-list>
        <paginate
          class="mb-5"
          :pagination="media"
          :loading="!loading"
          @paginate="callAPI()"
        ></paginate>
        <skeleton-video-card
          class="video-listing mb-5"
          :cards="50"
          :cols="5"
          v-if="loading"
        ></skeleton-video-card>
      </main>
    </div>
  </div>
</template>

<script>
import TopAdBanner from '@/components/TopAdBanner'
import PageHeader from '@/components/PageHeader'
import Paginate from '@/components/Paginate'
import Sidebar from '@/components/layout/Sidebar'
import MediaList from '@/components/media/List'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  props: ['categories'],
  components: {
    TopAdBanner,
    PageHeader,
    Paginate,
    Sidebar,
    MediaList,
    SkeletonVideoCard
  },
  data() {
    return {
      watchedVideos: localStorage.getItem('watched_ids')
        ? JSON.parse(localStorage.getItem('watched_ids'))
        : false
    }
  },
  computed: {
    ...mapGetters('api', ['loading']),
    ...mapState('api', {
      media: (state) => state.data,
      error: (state) => state.error
    })
  },
  mounted() {
    this.callAPI()
  },
  methods: {
    ...mapActions('api', ['api']),
    async callAPI() {
      return this.api({
        url: '/api/media/collect?ids=' + this.watchedVideos
      })
    }
  }
}
</script>
