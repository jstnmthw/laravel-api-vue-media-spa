<template>
  <div 
    class="video"
    v-bind:class="{ loading: loading }"
    >
    <div class="video-data">
      <div class="video-poster">
        <img :src="data.thumbnail" class="card-img-top" :alt="data.title">
        <div class="duration">
          {{ duration }}
        </div>
      </div>
      <div class="data-info px-0">
        <h5 class="mt-2 mb-1">{{ data.title }}</h5>
        <span style="opacity: .5;" class="pr-2">
          <ion-icon name="eye" ></ion-icon> {{ views }}
        </span>
        <span class="likes">
          <ion-icon name="thumbs-up" style="top: 1px;"></ion-icon> 
          {{ rating }}%
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['data', 'loading'],
  computed: {
    views: function() {
      return this.data.views.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    },
    rating: function() {
      return Math.trunc((this.data.likes / (this.data.likes + this.data.dislikes)) * 100);
    },
    duration: function() {
      return new Date(this.data.duration * 1000).toISOString().substr(14, 5);
    }
  }
}
</script>
