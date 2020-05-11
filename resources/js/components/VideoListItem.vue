<template>
  <div class="video">
    <div class="video-data">
      <div class="video-poster">
        <img :src="data.thumbnail" class="card-img-top" :alt="data.title">
        <div class="carousel">
          <img v-for="image in data.album" :key="image.index" :data-src="image" alt="">
        </div>
        <div class="duration">
          {{ duration }}
        </div>
      </div>
      <div class="video-info px-0">
        <h5 class="video-title mt-2 mb-1">
          <router-link :to="'/videos/' + data.id">{{ data.title }}</router-link>
        </h5>
        <span style="opacity: .5;" class="pr-2 position-relative">
          <ion-icon name="eye" style="top: 3px;"></ion-icon> 
          {{ views }}
        </span>
        <span :class="rating > 50 ? 'liked' : 'disliked'">
          <ion-icon name="thumbs-up"></ion-icon> 
          <ion-icon name="thumbs-down"></ion-icon> 
          {{ rating }}%
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'data'
  ],
  computed: {
    views: function() {
      return this.data.views.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    },
    rating: function() {
      if(this.data.likes >= 1) {
        return Math.trunc((this.data.likes / (this.data.likes + this.data.dislikes)) * 100);
      }else {
        return 0;
      }
    },
    duration: function() {
      return new Date(this.data.duration * 1000).toISOString().substr(14, 5);
    }
  }
}
</script>