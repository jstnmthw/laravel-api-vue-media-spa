<template>
  <div class="container bg-purple">
    <div class="row">
      <main class="col-sm-12 col-md-9">
        <h3>{{ data.title }}</h3>
        <div class="label-wrap">
          <ul class="list-inline">
            <li class="list-inline-item" v-for="label in data.categories" :key="label.index">
              <video-category-labels :label="label"></video-category-labels>
            </li>
          </ul>
        </div>
        <div class="video-frame mb-3">
          <iframe id="video" frameborder="0" height="100%" width="100%" scrolling="no"></iframe>
        </div>
        <div class="video-actions d-flex justify-content-between align-items-top mb-5">
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
        <h5>Related Videos</h5>
        <video-list :videos="data.related" :loading="loading" :cards="10"></video-list>
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
      data: [],
      loading: false,
    }
  },
  mounted() {
    this.getVideo();
  },
  computed: {
    rating: function() {
      if(this.data.likes >= 1) {
        return Math.trunc((this.data.likes / (this.data.likes + this.data.dislikes)) * 100);
      }else {
        return 0;
      }
    }
  },
  methods: {
    getVideo() {
      // Start loading on frontend
      this.$Progress.start();

      // Loading
      this.loading = true;

      // Clear iframe src
      $('#video').attr('src', '');

      // Make the call
      axios.get('/api/videos/' + this.$route.params.id).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.data = response.data;

        // Loading
        this.loading = false;

        // Use replace to not effect the browser history
        $('#video')[0].contentWindow.location.replace(this.data.embed);

      }).catch(error => {
        // Console log API error.
        console.log('Error calling API.');

        // Loading
        this.loading = false;

        // Failed frontend progress bar
        this.$Progress.fail();
      });
    }
  },
  props: [
    'categories'
  ],
  watch: {
    // When route changes, call API
    $route(to, from) {

      // Clear data
      this.data = [];

      // Get new data
      this.getVideo();
    }
  }
}
</script>
