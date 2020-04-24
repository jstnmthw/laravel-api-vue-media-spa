<template>
  <div class="video-listing">
    <div class="row row-cols-6 skeleton-row no-gutters" :class="{ loading: loading }">
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
        v-for="video in videos" 
        :key="video.index"
        >
        <video-list-item :data="video" :loading="loading"></video-list-item>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'videos', 
    'loading',
    'cards'
  ]
}
</script>
