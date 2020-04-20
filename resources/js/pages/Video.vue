<template>
  <div class="container bg-purple">
    <div class="row">
      <main class="col-sm-12 col-md-9">
        <h3>{{ data.title }}</h3>
        <video-category-labels :categories="data.categories"></video-category-labels>
        <div class="video-frame mb-3">
          <iframe id="video" frameborder="0" height="100%" width="100%" scrolling="no"></iframe>
        </div>
        <div class="video-actions d-flex justify-content-between align-items-top mb-3">
          <button type="button" class="btn btn-primary  mr-3">
            <ion-icon name="thumbs-up"></ion-icon> 
          </button>
          <div class="mr-3 video-rating">
            {{ data.likes }} Likes / {{ data.dislikes }} Dislikes
            <div class="video-rating-bar mt-1">
              <div class="video-rating-likes" :style="{ width: rating + '%' }"></div>
            </div>
          </div>
          <button type="button" class="btn btn-primary">
            <ion-icon name="thumbs-down"></ion-icon>
          </button>
          <button class="btn btn-primary ml-auto">
            <ion-icon name="flag"></ion-icon>
          </button>
        </div>
      </main>
      <aside class="col-sm-12 col-md-3">
        <div class="sidead-placeholder"></div>
      </aside>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: []
    }
  },
  computed: {
    rating: function() {
      if(this.data.likes >= 1) {
        return Math.trunc((this.data.likes / (this.data.likes + this.data.dislikes)) * 100);
      }else {
        return 0;
      }
    },
  },
  props: ['categories'],
  mounted() {
    this.getVideo();
  },
  methods: {
    getVideo() {
      // Start loading on frontend
      this.$Progress.start();

      // Make the call
      axios.get('/api/videos/' + this.$route.params.id).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.data = response.data;

        // Set iframe src
        $('#video').attr('src', this.data.embed);

      }).catch(error => {
        // Console log API error.
        console.log('Error calling API.');

        // Failed frontend progress bar
        this.$Progress.fail();
      });
    }
  }
}
</script>
