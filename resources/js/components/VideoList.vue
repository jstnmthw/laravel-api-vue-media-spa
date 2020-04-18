<template>
  <div class="video-listing mb-5">
    <p class="mb-2" v-show="!loading">Showing: {{ videos.current_page }} of {{ videos.total_pages }}</p>
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
        <div 
          class="video"
          v-bind:class="{ loading: loading }"
          >
          <div class="video-data">
            <div class="video-poster">
              <img :src="video.thumbnail" class="card-img-top" :alt="video.title">
            </div>
            <div class="video-info px-0">
              <h5 class="mt-2 mb-1">{{ video.title }}</h5>
              <span style="opacity: .5;" class="pr-2">
                <ion-icon name="eye" ></ion-icon> {{ video.views.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') }}
              </span>
              <span class="likes">
                <ion-icon name="thumbs-up" style="top: 1px;"></ion-icon> 
                {{ Math.trunc(((video.likes - video.dislikes) / video.likes) * 100) }}%
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import imagesLoaded from 'vue-images-loaded'

export default {
  mounted() {
    this.$parent.$on('paginate', function () {
      console.log('Emite recieved.');
      // $('.video-list img').attr('src', '')
      // document.getElementsByClassName('.video-list img').src='';
    });
  },
  props: [
    'videos', 
    'loading',
    'cards'
  ],
  directives: {
    // imagesLoaded
  },
  methods: {
    // imageProgress(instance, image) {
    //   const result = image.isLoaded ? 'loaded' : 'broken';
    //   console.log( 'image is ' + result + ' for ' + image.img.src );
    // },
    // loaded(instance) {
    //   console.log('All images loaded.');
    // }
  }
}
</script>

<style lang="scss" scoped>
  img {
    opacity: 0.05;
  }
  h5 {
    font-size: 14px;
    font-weight: 700;
  }
  .row {
    margin-left: -8px;
    margin-right: -8px;
  }
</style>
