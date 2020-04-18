<template>
  <div>
    <div class="row mb-2">
        <div class="col-6" v-show="!loading">Showing: {{ videos.current_page }} of {{ videos.last_page }}</div>
      </div>
    <div class="video-listing mb-5">
      <div class="row row-cols-5 skeleton-row no-gutters" v-bind:class="{ loading: loading }">
        <div
          class="col px-2 mb-md-3 position-relative" 
          v-for="card in cards" 
          :key="card.index"
          >
          <div class="skeleton-card">
            <div class="skeleton-card-image"></div>
            <div class="skeleton-card-line"></div>
            <div class="skeleton-card-line2"></div>
            <div class="skeleton-card-views"></div>
            <div class="skeleton-card-likes"></div>
          </div>
        </div>
      </div>
      <div class="row row-cols-5 video-list no-gutters">
        <div
          class="col px-2 mb-md-3 position-relative"
          v-for="video in videos.data" 
          :key="video.index"
          >
          <video-list-item :data="video" :loading="loading"></video-list-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    views: function() {
      return this.videos.views.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
  },
  props: [
    'videos', 
    'loading',
    'cards'
  ]
}
</script>
