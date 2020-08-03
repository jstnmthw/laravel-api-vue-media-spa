<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <page-header
          v-if="loaded"
          :title="'Recently Viewed'"
          icon="sync-circle"
          class="mb-3"
        ></page-header>
        <video-list
          v-if="loaded"
          class="mb-5"
          :videos="videos"
          :cards="20"
          :cols="5"
        ></video-list>
        <skeleton-video-card
          class="video-listing mb-5"
          :cards="50"
          :cols="5"
          v-if="!loaded"
        ></skeleton-video-card>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import TopAdBanner from '@/components/TopAdBanner'
import PageHeader from '@/components/PageHeader'
import MainSidebar from '@/components/MainSidebar'
import VideoList from '@/components/VideoList'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'

export default {
  data() {
    return {
      loaded: false,
      videos: false,
      watchedVideos: localStorage.getItem('watched_ids')
        ? JSON.parse(localStorage.getItem('watched_ids'))
        : false
    }
  },
  mounted() {
    this.getCollection()
  },
  components: {
    TopAdBanner: TopAdBanner,
    PageHeader: PageHeader,
    MainSidebar: MainSidebar,
    VideoList: VideoList,
    SkeletonVideoCard: SkeletonVideoCard
  },
  props: ['categories'],
  methods: {
    async getCollection() {
      await axios
        .get('/api/videos', {
          params: {
            collection: this.watchedVideos.join()
          }
        })
        .then((res) => {
          this.videos = res.data
          this.loaded = true
        })
    }
  }
}
</script>
