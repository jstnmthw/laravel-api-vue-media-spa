<template>
  <div class="video-listing">
    <div class="row skeleton-row no-gutters" :class="'row-cols-'+cols" v-show="loading">
      <div
        class="col px-2 mb-md-3 position-relative" 
        v-for="card in cards" 
        :key="card.index"
        >
        <skeleton-card-video></skeleton-card-video>
      </div>
    </div>
    <div class="row video-list no-gutters" :class="'row-cols-'+cols" v-show="!loading">
      <div
        class="col px-2 mb-md-3 position-relative"
        v-for="video in videos" 
        :key="video.index"
        >
        <video-list-item :data="video"></video-list-item>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'videos', 
    'loading',
    'cards',
    'cols'
  ]
}
</script>
