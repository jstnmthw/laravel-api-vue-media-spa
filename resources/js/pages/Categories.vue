<template>
  <main class="col-md-10">
    <top-ad-banner></top-ad-banner>
    <page-header :title="this.$route.params.category +' Videos'" icon="" v-show="!loading"></page-header>
    <video-list :videos="videos" :loading="loading" :cards="40"></video-list>
    <paginate :pagination="videos" @paginate="getVideos()" :loading="loading"></paginate>
  </main>
</template>

<script>
export default {
  data() {
    return {
      videos: [],
      pagination: [],
      loading: false,
      error: [],
      title: '',
    }
  },
  computed: {},
  methods: {
    getVideos() {
      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '');

      // Make the call
      axios.get('/api/videos', { params: {...this.$route.params, ...this.$route.query } }).then((response) => {


          // Finish frontend progress bar
          this.$Progress.finish();

          // Set Vue Data
          if(!response['error']) {
            this.videos = response.data;
          }else {
            this.error = response.error
          }

          // Disable loading
          this.loading = false;

        }).catch(error => {

          // Console log API error.
          console.log('Error calling API.');
          
          // Failed frontend progress bar
          this.$Progress.fail();

      });
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos();
  },
  watch: {
    // When route changes, call API
    $route(to, from) {
      
      // Clear data
      this.videos = [];

      // Get new videos
      this.getVideos();
    }
  }
}
</script>
