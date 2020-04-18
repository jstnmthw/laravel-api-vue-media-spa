<template>
  <main class="col-md-10">
    <top-ad-banner></top-ad-banner>
    <div v-if="!error">
      <page-header :title="this.$route.params.category +' Video Category'" icon="" v-show="!loading"></page-header>
      <video-list :videos="videos" :loading="loading" :cards="40"></video-list>
      <paginate :pagination="videos" @paginate="getVideos()" :loading="loading"></paginate>
    </div>
    <div v-else class="text-center m-4">
      {{ error }}
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      videos: [],
      pagination: [],
      loading: false,
      error: false,
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

          // Set Vue data if present
          if(response.data.error) {
            this.error = response.data.error;
          }else {
            this.videos = response.data;
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
      this.error = false;

      // Get new videos
      this.getVideos();
    }
  }
}
</script>
