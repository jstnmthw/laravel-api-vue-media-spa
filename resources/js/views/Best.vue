<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <page-header
          :title="'Weekly Best Videos'"
          icon="trophy"
          v-show="loaded"
          class="mb-4"
        ></page-header>
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
      </main>
    </div>
  </div>
</template>

<script>
// Libraries
import axios from 'axios'

// Components
import TopAdBanner from '@/components/TopAdBanner'
import PageHeader from '@/components/PageHeader'
import MainSidebar from '@/components/MainSidebar'
import VideoList from '@/components/VideoList'
import SkeletonVideoCard from '@/components/skeleton/VideoCard'

export default {
  data() {
    return {
      loaded: false,
      videos: []
    }
  },
  mounted() {
    this.getBest()
  },
  components: {
    VideoList: VideoList,
    PageHeader: PageHeader,
    TopAdBanner: TopAdBanner,
    MainSidebar: MainSidebar,
    SkeletonVideoCard: SkeletonVideoCard
  },
  props: ['categories'],
  methods: {
    async getBest() {
      await axios.get('/api/videos/best').then((res) => {
        this.videos = res.data
        this.loaded = true
      })
    }
  }
}
</script>
